@extends('layouts.website.app')

@section('content')
<div x-data="casherNotebook(window.casherConfig)" x-init="init()" class="notebook-container transition-colors duration-300 font-sans shadow-xl">
    
    <div class="notebook-main min-h-screen relative pb-24">
        @include('website.casher.notebook.partials.header')
    
    <template x-if="activeTab === 'customers'">
        <div class="animate-fade-in-up">
            @include('website.casher.notebook.partials.metrics')
            @include('website.casher.notebook.partials.search-filters')
            @include('website.casher.notebook.partials.customer-list')
        </div>
    </template>
    
    <template x-if="activeTab === 'withdrawals'">
        <div>
            @include('website.casher.notebook.partials.withdrawals-list')
        </div>
    </template>

    @include('website.casher.notebook.partials.bottom-nav')
    </div> <!-- End notebook-main -->

    <!-- AI Voice Command Floating Action Button -->
    <div class="fixed {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} bottom-24 z-40">
        <button type="button" @click.prevent="startAIVoiceCommand()" 
            class="w-14 h-14 flex items-center justify-center rounded-full bg-gradient-to-tr from-purple-600 to-blue-500 text-white shadow-lg shadow-purple-500/30 hover:scale-105 transition-all focus:outline-none focus:ring-4 focus:ring-purple-300"
            :class="isAIListening ? 'animate-pulse shadow-[0_0_0_8px_rgba(168,85,247,0.4)]' : ''"
            title="تسجيل حركة بالذكاء الاصطناعي">
            <template x-if="isAIListening">
                <i class="ph-bold ph-spinner animate-spin text-2xl"></i>
            </template>
            <template x-if="!isAIListening">
                <i class="ph-bold ph-microphone-stage text-2xl"></i>
            </template>
        </button>
    </div>

    <!-- Drawers, Bottom Sheets, and Overlays -->
    @include('website.casher.notebook.partials.modals.add-customer')
    @include('website.casher.notebook.partials.modals.edit-customer')
    @include('website.casher.notebook.partials.modals.add-withdrawal')
    @include('website.casher.notebook.partials.modals.ledger')
    @include('website.casher.notebook.partials.modals.transaction')
    @include('website.casher.notebook.partials.modals.accounts-sheet')
    @include('website.casher.notebook.partials.modals.financial-summary')
    @include('website.casher.notebook.partials.modals.today-collections')
    @include('website.casher.notebook.partials.modals.today-debts')
    @include('website.casher.notebook.partials.modals.today-direct-sales')

</div>

@push('css')
    <!-- jQuery in Head so it is available -->
    <script src="{{ asset('assets/dashbaord/vendors/js/tables/jquery-1.12.3.js') }}"></script>
    
    <!-- Flatpickr Datepicker CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
    @endif
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    @if (app()->getLocale() == 'ar')
        <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    @endif
    
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/store.css') }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/website/css/casher-notebook.css') }}?v={{ time() }}">
@endpush

@push('scripts')
<script>
    window.casherConfig = {
        apiBase: '{{ url(app()->getLocale() . "/casher/api") }}',
        locale: '{{ app()->getLocale() }}',
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        todayDate: '{{ \Carbon\Carbon::today()->format("Y-m-d") }}',
        translations: {
            success: '{{ __("notebook.success") }}',
            warning: '{{ __("notebook.warning") }}',
            areYouSure: '{{ __("notebook.are_you_sure") }}',
            confirmDeleteTx: '{{ __("notebook.confirm_delete_transaction") }}',
            confirmDeleteWithdrawal: '{{ __("notebook.confirm_delete_withdrawal") }}',
            yesDelete: '{{ __("notebook.yes_delete") }}',
            cancel: '{{ __("notebook.cancel") }}',
            selectAccount: '{{ __("notebook.please_select_bank_account") }}',
            pleaseEnterCustomerName: '{{ __("notebook.please_enter_customer_name") }}',
            pleaseEnterAmount: '{{ __("notebook.please_enter_amount") }}',
            pleaseSelectDate: '{{ __("notebook.please_select_date") }}',
            pleaseEnterReason: '{{ __("notebook.please_enter_reason") }}'
        },
        bankBalances: {
            @foreach($storeBankAccounts as $account)
            "{{ $account->id }}": {{ $account->current_balance ?? 0 }},
            @endforeach
        },
        storeAccounts: [
            @foreach($storeBankAccounts as $account)
            @php
                $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
            @endphp
            {
                id: {{ $account->id }},
                name: "{!! addslashes($accountName) !!}"
            },
            @endforeach
        ]
    };
</script>
<script src="{{ asset('assets/website/js/casher-notebook.js') }}?v={{ time() }}"></script>
@endpush
@endsection
