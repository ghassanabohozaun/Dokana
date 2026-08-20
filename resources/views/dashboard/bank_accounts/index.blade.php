@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    <!-- 1. Header & Actions Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <i class="fas fa-home text-xs"></i>
                    <span>{!! __('dashboard.home') !!}</span>
                </a>
                <span>/</span>
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('bank_accounts.bank_accounts') !!}</span>
            </nav>

            <!-- Page Title & Counter Badge -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                    <i class="fas fa-university"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('bank_accounts.bank_accounts') !!}
                        </h1>
                        <span id="bank_accountsCountBadge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $bankAccounts->total() !!} {!! __('general.records') ?? 'سجل' !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('bank_accounts_create')
            <button type="button" data-toggle="modal" data-target="#createBankAccountModal"
                class="btn-primary-gradient">
                <i class="fas fa-plus text-xs"></i>
                <span>{!! __('bank_accounts.create_new_bank_account') !!}</span>
            </button>
            @endcan
        </div>
    </div>

    <!-- 2. Search Filters Bar -->
    @include('dashboard.bank_accounts.partials._search')

    <!-- 3. Main Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.bank_accounts.partials._table')
        </div>
    </div>
</div>

<!-- Modals -->
@can('bank_accounts_create')
@include('dashboard.bank_accounts.modals.create')
@endcan

@can('bank_accounts_update')
@include('dashboard.bank_accounts.modals.edit')
@include('dashboard.bank_accounts.modals.adjustment')
@endcan

@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Global Select2 Initialization for Modals ONLY
            $('.modal .select2').each(function() {
                var $el = $(this);
                var parentModal = $el.closest('.modal');
                $el.select2({
                    dropdownParent: parentModal.length ? parentModal : $(document.body),
                    width: '100%',
                    placeholder: $el.attr('placeholder') || "{!! __('general.choose') !!}",
                });
            });

            // Filter Store Select2
            if ($('#filter_store_id').length) {
                $('#filter_store_id').select2({
                    width: '100%'
                });
            }

            // Handle Adjust Balance Button Click
            $(document).on('click', '.adjustBankAccountBtn', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let current_balance = parseFloat($(this).data('current_balance')).toFixed(2);
                
                $('#adjust_store_bank_account_id').val(id);
                $('#current_system_balance').text(current_balance);
                $('#actual_balance').val('');
                $('#adjust_notes').val('');
                
                $('#adjust_bank_account_form').find('.error-text').text('');
                $('#adjust_bank_account_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#adjustBankAccountModal').modal('show');
            });
        });

        // Dynamically refresh Store Select Filter Options without full page refresh
        function refreshStoreFilterOptions() {
            let $select = $('#filter_store_id');
            if (!$select.length) return;

            let currentVal = $select.val();

            $.ajax({
                url: "{{ route('dashboard.stores.options') }}",
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.status && response.data) {
                        let optionsHtml = '<option value="">{!! __('general.all_stores') !!}</option>';
                        response.data.forEach(function(store) {
                            let selectedAttr = (store.id == currentVal) ? 'selected' : '';
                            optionsHtml += `<option value="${store.id}" ${selectedAttr}>${store.name}</option>`;
                        });
                        $select.html(optionsHtml).trigger('change.select2');
                    }
                }
            });
        }

        // Hook into AJAX form success & deletion events
        $(document).on('ajax-form-success record-deleted', function() {
            refreshStoreFilterOptions();
        });
    </script>
@endpush
