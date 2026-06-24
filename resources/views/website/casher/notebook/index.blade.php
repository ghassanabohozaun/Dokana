@extends('layouts.website.app')

@section('content')
<div x-data="casherNotebook(window.casherConfig)" x-init="init()" class="max-w-md mx-auto min-h-screen relative pb-24 shadow-2xl bg-gray-50 dark:bg-[#0b1121] transition-colors duration-300 font-sans">
    
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

    <!-- Modals -->
    @include('website.casher.notebook.partials.modals.add-customer')
    @include('website.casher.notebook.partials.modals.add-withdrawal')
    @include('website.casher.notebook.partials.modals.ledger')
    @include('website.casher.notebook.partials.modals.transaction')
    @include('website.casher.notebook.partials.modals.accounts-sheet')

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
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        todayDate: '{{ \Carbon\Carbon::today()->format("Y-m-d") }}',
        translations: {
            success: '{{ __("notebook.success") }}',
            warning: '{{ __("notebook.warning") }}',
            areYouSure: '{{ __("notebook.are_you_sure") }}',
            confirmDeleteTx: '{{ __("notebook.confirm_delete_transaction") }}',
            confirmDeleteWithdrawal: '{{ __("notebook.confirm_delete_withdrawal") }}',
            yesDelete: '{{ __("notebook.yes_delete") }}',
            cancel: '{{ __("notebook.cancel") }}'
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
