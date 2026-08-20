<div class="modal fade" id="createStoreSupplierPaymentModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreSupplierPaymentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-supplier-payments.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_supplier_payment_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-sm">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreSupplierPaymentModalLabel">
                            {!! __('store_supplier_payments.create_new_store_supplier_payment') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_create">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_create" class="form-input-modern select2">
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Supplier & Invoice Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="store_supplier_id_create">
                                {!! __('store_supplier_payments.supplier') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_supplier_id" id="store_supplier_id_create" class="form-input-modern select2" @if(isset($stores)) disabled @endif>
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                @if(isset($suppliers) && !isset($stores))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_id_error block mt-1"></span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="store_supplier_invoice_id_create">
                                    {!! __('store_supplier_payments.invoice') !!}
                                </label>

                                <!-- Inline Invoice Remaining Balance Pill -->
                                <div id="invoice_remaining_info_create" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/80 dark:border-amber-800/60 shadow-2xs transition-all cursor-pointer hover:bg-amber-100 dark:hover:bg-amber-900/60"
                                     title="{{ __("general.click_to_fill_remaining") }}">
                                    <i class="fas fa-file-invoice-dollar text-[10px]"></i>
                                    <span>{{ __("general.invoice_remaining") }}:</span>
                                    <span class="invoice-remaining-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>
                            <select name="store_supplier_invoice_id" id="store_supplier_invoice_id_create" class="form-input-modern select2" disabled>
                                <option value="" data-remaining="0" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_invoice_id_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Payment Bank Account / Wallet -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                            <label class="form-label-modern mb-0" for="store_bank_account_id_create">
                                {!! __('store_supplier_payments.bank_account') !!} <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Inline Balance Header Pill -->
                            <div id="bank_account_balance_info_create" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60 shadow-2xs transition-all">
                                <i class="fas fa-wallet text-[10px]"></i>
                                <span>{!! __('general.balance') !!}:</span>
                                <span class="balance-amount font-black font-mono" dir="ltr">0.00</span>
                            </div>
                        </div>

                        <select name="store_bank_account_id" id="store_bank_account_id_create" class="form-input-modern select2" @if(isset($stores)) disabled @endif>
                            <option value="" data-balance="0" disabled selected>{!! __('general.select_from_list') !!}</option>
                            @if(isset($bankAccounts) && !isset($stores))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $isDefault = $account->is_default ? "(" . __('general.default') . ")" : "";
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}" data-balance="{{ $account->current_balance }}" @if($account->is_default) selected @endif>{{ $accountName }} {{ $isDefault }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Payment Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="amount_create">
                                    {!! __('store_supplier_payments.amount') !!} <span class="text-rose-500">*</span>
                                </label>

                                <!-- Inline Remaining Balance Pill -->
                                <div id="remaining_balance_info_create" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs transition-all">
                                    <i class="fas fa-calculator text-[10px]"></i>
                                    <span>{!! __('general.remaining_balance') !!}:</span>
                                    <span class="remaining-balance-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>

                            <input type="number" id="amount_create" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="payment_date_create">
                                {!! __('store_supplier_payments.payment_date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="payment_date_create" name="payment_date"
                                class="form-input-modern flatpickr-date" value="{{ date('Y-m-d') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text payment_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label-modern" for="notes_create">
                            {!! __('store_supplier_payments.notes') !!}
                        </label>
                        <textarea id="notes_create" name="notes" rows="2"
                            class="form-input-modern" placeholder="{!! __('store_supplier_payments.notes') !!}" autocomplete="off"></textarea>
                        <span class="text-xs text-rose-500 error-text notes_error block mt-1"></span>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <button type="submit" class="btn-primary-gradient text-xs">
                        <i class="fas fa-save text-xs"></i>
                        <i class="fas fa-spinner fa-spin spinner_loading text-xs hidden d-none"></i>
                        <span>{!! __('general.save') !!}</span>
                    </button>
                    <button type="button" class="btn-secondary-modern text-xs" data-dismiss="modal">
                        {!! __('general.cancel') !!}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Fetch suppliers and bank accounts by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let supplierSelect = $('#store_supplier_id_create');
                let bankAccountSelect = $('#store_bank_account_id_create');
                let invoiceSelect = $('#store_supplier_invoice_id_create');
                
                supplierSelect.empty().append('<option value="" disabled selected>{!! __('general.select_from_list') !!}</option>');
                bankAccountSelect.empty().append('<option value="" data-balance="0" disabled selected>{!! __('general.select_from_list') !!}</option>');
                invoiceSelect.empty().append('<option value="" data-remaining="0" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>').prop('disabled', true).trigger('change.select2');
                $('#invoice_remaining_info_create').addClass('hidden');

                if (store_id) {
                    // Fetch Suppliers
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, supplier) {
                                let mobileText = supplier.mobile ? ' - ' + supplier.mobile : '';
                                let newOption = new Option(supplier.name + mobileText, supplier.id, false, false);
                                supplierSelect.append(newOption);
                            });
                            supplierSelect.prop('disabled', false).trigger('change.select2');
                        },
                        error: function() {
                            supplierSelect.prop('disabled', false).trigger('change.select2');
                        }
                    });

                    // Fetch Bank Accounts
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, account) {
                                let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar || account.payment_entity.name) : '';
                                let isDefault = account.is_default ? " ({!! __('general.default') !!})" : "";
                                let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                
                                let newOption = new Option(accountName + isDefault, account.id, account.is_default, account.is_default);
                                $(newOption).attr('data-balance', account.current_balance);
                                bankAccountSelect.append(newOption);
                            });
                            bankAccountSelect.prop('disabled', false).trigger('change.select2');
                            updateCreateBalance();
                        },
                        error: function() {
                            bankAccountSelect.prop('disabled', false).trigger('change.select2');
                        }
                    });
                } else {
                    supplierSelect.prop('disabled', true).trigger('change.select2');
                    bankAccountSelect.prop('disabled', true).trigger('change.select2');
                }
            });

            // Fetch Invoices when Supplier changes
            $('#store_supplier_id_create').on('change', function() {
                let supplier_id = $(this).val();
                let invoiceSelect = $('#store_supplier_invoice_id_create');
                
                invoiceSelect.empty().append('<option value="" data-remaining="0" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>');
                $('#invoice_remaining_info_create').addClass('hidden');

                if (supplier_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-supplier-invoices.by-supplier') !!}",
                        type: 'GET',
                        data: { supplier_id: supplier_id },
                        success: function(data) {
                            if (data && data.length > 0) {
                                $.each(data, function(key, inv) {
                                    let invText = '#' + inv.invoice_number + ' (المتبقي: ' + (inv.remaining_amount || 0) + ')';
                                    let newOption = new Option(invText, inv.id, false, false);
                                    $(newOption).attr('data-remaining', inv.remaining_amount || 0);
                                    invoiceSelect.append(newOption);
                                });
                                invoiceSelect.prop('disabled', false).trigger('change.select2');
                            } else {
                                invoiceSelect.prop('disabled', true).trigger('change.select2');
                            }
                        },
                        error: function() {
                            invoiceSelect.prop('disabled', true).trigger('change.select2');
                        }
                    });
                } else {
                    invoiceSelect.prop('disabled', true).trigger('change.select2');
                }
            });

            // Invoice selection change
            $('#store_supplier_invoice_id_create').on('change', function() {
                let opt = $(this).find('option:selected');
                let remaining = parseFloat(opt.attr('data-remaining')) || 0;
                let infoDiv = $('#invoice_remaining_info_create');

                if (opt.val() && remaining > 0) {
                    infoDiv.find('.invoice-remaining-amount').text(remaining.toFixed(2));
                    infoDiv.removeClass('hidden');
                } else {
                    infoDiv.addClass('hidden');
                }
                updateCreateRemainingBalance();
            });

            // Click invoice pill to auto-fill remaining amount
            $(document).on('click', '#invoice_remaining_info_create', function() {
                let opt = $('#store_supplier_invoice_id_create').find('option:selected');
                let remaining = parseFloat(opt.attr('data-remaining')) || 0;
                if (remaining > 0) {
                    $('#amount_create').val(remaining.toFixed(2)).trigger('input');
                }
            });

            // Show balance on account change
            $('#store_bank_account_id_create').on('change', function() {
                updateCreateBalance();
            });

            // Update remaining balance on amount input
            $('#amount_create').on('input keyup', function() {
                updateCreateRemainingBalance();
            });

            function updateCreateBalance() {
                let opt = $('#store_bank_account_id_create').find('option:selected');
                let balance = parseFloat(opt.attr('data-balance')) || 0;
                let infoDiv = $('#bank_account_balance_info_create');
                
                if (opt.val()) {
                    infoDiv.find('.balance-amount').text(balance.toFixed(2));
                    infoDiv.removeClass('hidden');
                    updateCreateRemainingBalance();
                } else {
                    infoDiv.addClass('hidden');
                    $('#remaining_balance_info_create').addClass('hidden');
                }
            }

            function updateCreateRemainingBalance() {
                let selectedBankOption = $('#store_bank_account_id_create').find('option:selected');
                let currentBankBalance = parseFloat(selectedBankOption.attr('data-balance')) || 0;
                let selectedInvoiceOption = $('#store_supplier_invoice_id_create').find('option:selected');
                let invoiceRemaining = parseFloat(selectedInvoiceOption.attr('data-remaining')) || 0;
                let paymentAmount = parseFloat($('#amount_create').val()) || 0;

                let remainingPill = $('#remaining_balance_info_create');
                let invoicePill = $('#invoice_remaining_info_create');

                // Bank Account balance pill
                if (selectedBankOption.val() && paymentAmount > 0) {
                    let remaining = currentBankBalance - paymentAmount;
                    let remainingSpan = remainingPill.find('.remaining-balance-amount');
                    remainingSpan.text(remaining.toFixed(2));
                    remainingPill.removeClass('hidden');

                    if (remaining < 0) {
                        remainingPill.removeClass('bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border-indigo-200/80 dark:border-indigo-800/60')
                            .addClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse');
                    } else {
                        remainingPill.removeClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse')
                            .addClass('bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border-indigo-200/80 dark:border-indigo-800/60');
                    }
                } else {
                    remainingPill.addClass('hidden');
                }

                // Invoice remaining check
                if (selectedInvoiceOption.val() && invoiceRemaining > 0) {
                    if (paymentAmount > invoiceRemaining) {
                        invoicePill.removeClass('bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200/80 dark:border-amber-800/60')
                            .addClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse');
                    } else {
                        invoicePill.removeClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse')
                            .addClass('bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200/80 dark:border-amber-800/60');
                    }
                }
            }

            // Instant balance rendering on modal open
            $('#createStoreSupplierPaymentModal').on('show.bs.modal', function () {
                updateCreateBalance();
            });
        });
    </script>
@endpush
