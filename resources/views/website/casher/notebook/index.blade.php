@extends('layouts.website.app')

@section('title', __('dashboard.casher') . ' - ' . (setting()->getTranslation('site_name', app()->getLocale()) ?: ''))

@section('content')
<div x-data="casherNotebook(window.casherConfig)" x-init="init()" class="notebook-container transition-colors duration-75 font-sans shadow-xl">
    
    <div class="notebook-main min-h-screen relative pb-36">
        @include('website.casher.notebook.partials.header')
    
        <div x-show="activeTab === 'customers'" class="animate-fade-in-up">
            @include('website.casher.notebook.partials.metrics')
            @include('website.casher.notebook.partials.search-filters')
            @include('website.casher.notebook.partials.customer-list')
        </div>

        <div x-show="activeTab === 'suppliers'" style="display: none;">
            @include('website.casher.notebook.partials.supplier-list')
        </div>
        
        <div x-show="activeTab === 'withdrawals'" style="display: none;">
            @include('website.casher.notebook.partials.withdrawals-list')
        </div>

        @include('website.casher.notebook.partials.bottom-nav')
    </div> <!-- End notebook-main -->

    <!-- AI Voice Command Floating Action Button -->
    <div class="fixed {{ app()->getLocale() == 'ar' ? 'left-6' : 'right-6' }} bottom-24 z-40">
        <button type="button" @click.prevent="startAIVoiceCommand()" 
            class="w-14 h-14 flex items-center justify-center rounded-full bg-gradient-to-tr from-purple-600 to-blue-500 text-white shadow-lg shadow-purple-500/30 hover:scale-105 transition-all focus:outline-none focus:ring-4 focus:ring-purple-300"
            :class="isAIListening ? 'animate-pulse shadow-[0_0_0_8px_rgba(168,85,247,0.4)]' : ''"
            title="تسجيل حركة بالذكاء الاصطناعي">
            <i x-show="isAIListening" class="ph-bold ph-spinner animate-spin text-2xl" x-cloak></i>
            <i x-show="!isAIListening" class="ph-bold ph-microphone-stage text-2xl"></i>
        </button>
    </div>

    <!-- Drawers, Bottom Sheets, and Overlays -->
    @include('website.casher.notebook.partials.modals.add-customer')
    @include('website.casher.notebook.partials.modals.edit-customer')
    @include('website.casher.notebook.partials.modals.add-supplier')
    @include('website.casher.notebook.partials.modals.edit-supplier')
    @include('website.casher.notebook.partials.modals.supplier-ledger')
    @include('website.casher.notebook.partials.modals.add-supplier-invoice')
    @include('website.casher.notebook.partials.modals.add-supplier-payment')
    @include('website.casher.notebook.partials.modals.add-withdrawal')
    @include('website.casher.notebook.partials.modals.ledger')
    @include('website.casher.notebook.partials.modals.transaction')
    @include('website.casher.notebook.partials.modals.accounts-sheet')
    @include('website.casher.notebook.partials.modals.financial-summary')
    @include('website.casher.notebook.partials.modals.today-collections')
    @include('website.casher.notebook.partials.modals.today-debts')
    @include('website.casher.notebook.partials.modals.today-direct-sales')
    @include('website.casher.notebook.partials.modals.today-supplier-payments')
    @include('website.casher.notebook.partials.modals.today-supplier-invoices')
    @include('website.casher.notebook.partials.modals.delete-confirmation')

</div>

@push('css')
    <!-- jQuery in Head so it is available -->
    <script src="{{ asset('assets/dashbaord/vendors/js/tables/jquery-1.12.3.js') }}"></script>
    
    <!-- Flatpickr Datepicker CSS & JS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/website/vendor/flatpickr/flatpickr.min.css') }}">
    @if (app()->getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('assets/website/vendor/flatpickr/airbnb.css') }}">
    @endif
    <script src="{{ asset('assets/website/vendor/flatpickr/flatpickr.min.js') }}"></script>
    @if (app()->getLocale() == 'ar')
        <script src="{{ asset('assets/website/vendor/flatpickr/ar.js') }}"></script>
    @endif
    
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/store.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/casher-notebook.css') }}?v={{ time() }}">

    <!-- Cashier Config and Component Script -->
    <script>
        window.casherConfig = {
            apiBase: '{{ url(app()->getLocale() . "/casher/api") }}',
            locale: '{{ app()->getLocale() }}',
            csrf: document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
            todayDate: '{{ \Carbon\Carbon::today()->format("Y-m-d") }}',
            translations: {
                success: '{{ __("notebook.success") }}',
                warning: '{{ __("notebook.warning") }}',
                areYouSure: '{{ __("notebook.are_you_sure") }}',
                confirmDeleteTx: '{{ __("notebook.confirm_delete_transaction") }}',
                confirmDeleteWithdrawal: '{{ __("notebook.confirm_delete_withdrawal") }}',
                confirmDeleteInvoice: '{{ __("notebook.confirm_delete_invoice") }}',
                confirmDeleteSupplierPayment: '{{ __("notebook.confirm_delete_payment") }}',
                yesDelete: '{{ __("notebook.yes_delete") }}',
                cancel: '{{ __("notebook.cancel") }}',
                selectAccount: '{{ __("notebook.please_select_bank_account") }}',
                pleaseEnterCustomerName: '{{ __("notebook.please_enter_customer_name") }}',
                pleaseEnterSupplierName: '{{ __("notebook.enter_supplier_name") }}',
                pleaseEnterInvoiceNumber: '{{ __("notebook.please_enter_invoice_number") }}',
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
    <!-- Alpine.js (Local) -->
    <script defer src="{{ asset('assets/website/vendor/alpine/alpine.min.js') }}"></script>
@endpush
@endsection
