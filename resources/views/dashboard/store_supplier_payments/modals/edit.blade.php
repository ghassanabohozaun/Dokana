<div class="modal fade" id="updateStoreSupplierPaymentModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierPaymentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_supplier_payment_form" novalidate
            data-success-msg="{!! __('general.update_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="id" id="store_supplier_payment_id_edit">

            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreSupplierPaymentModalLabel">
                            {!! __('store_supplier_payments.update_store_supplier_payment') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    @if(isset($stores))
                    <!-- Store Select (Disabled / Fixed in Edit) -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                            <label class="form-label-modern mb-0" for="store_id_dept_edit">
                                {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i class="fas fa-lock text-[9px]"></i>
                                <span>{{ __("general.immutable_field_store") }}</span>
                            </span>
                        </div>
                        <select id="store_id_dept_edit" class="form-input-modern select2" disabled>
                            <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name="store_id" id="hidden_store_id_edit">
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Supplier & Invoice Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="store_supplier_id_edit">
                                {!! __('store_supplier_payments.supplier') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_supplier_id" id="store_supplier_id_edit" class="form-input-modern select2">
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_id_error block mt-1"></span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="store_supplier_invoice_id_edit">
                                    {!! __('store_supplier_payments.invoice') !!}
                                </label>

                                <!-- Inline Invoice Remaining Balance Pill -->
                                <div id="invoice_remaining_info_edit" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/80 dark:border-amber-800/60 shadow-2xs transition-all cursor-pointer hover:bg-amber-100 dark:hover:bg-amber-900/60"
                                     title="{{ __("general.click_to_fill_remaining") }}">
                                    <i class="fas fa-file-invoice-dollar text-[10px]"></i>
                                    <span>{{ __("general.invoice_remaining") }}:</span>
                                    <span class="invoice-remaining-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>

                            <select name="store_supplier_invoice_id" id="store_supplier_invoice_id_edit" class="form-input-modern select2">
                                <option value="" data-remaining="0" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_invoice_id_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Payment Bank Account / Wallet -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                            <label class="form-label-modern mb-0" for="store_bank_account_id_edit">
                                {!! __('store_supplier_payments.bank_account') !!} <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Inline Balance Header Pill -->
                            <div id="bank_account_balance_info_edit" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60 shadow-2xs transition-all">
                                <i class="fas fa-wallet text-[10px]"></i>
                                <span>{!! __('general.balance') !!}:</span>
                                <span class="balance-amount font-black font-mono" dir="ltr">0.00</span>
                            </div>
                        </div>

                        <select name="store_bank_account_id" id="store_bank_account_id_edit" class="form-input-modern select2">
                            <option value="" data-balance="0" disabled>{!! __('general.select_from_list') !!}</option>
                            @if(isset($bankAccounts))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}" data-balance="{{ $account->current_balance }}">{{ $accountName }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Payment Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="amount_edit">
                                    {!! __('store_supplier_payments.amount') !!} <span class="text-rose-500">*</span>
                                </label>

                                <!-- Inline Remaining Balance Pill -->
                                <div id="remaining_balance_info_edit" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs transition-all">
                                    <i class="fas fa-calculator text-[10px]"></i>
                                    <span>{!! __('general.remaining_balance') !!}:</span>
                                    <span class="remaining-balance-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>

                            <input type="number" id="amount_edit" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="payment_date_edit">
                                {!! __('store_supplier_payments.payment_date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="payment_date_edit" name="payment_date"
                                class="form-input-modern flatpickr-date" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text payment_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label-modern" for="notes_edit">
                            {!! __('store_supplier_payments.notes') !!}
                        </label>
                        <textarea id="notes_edit" name="notes" rows="2"
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
            // Edit Button Click Handler
            $(document).on('click', '.editStoreSupplierPaymentBtn', function() {
                let $btn = $(this);
                let payment_id = $btn.data('id');
                let supplier_id = $btn.data('store_supplier_id') || $btn.data('supplier_id');
                let supplier_name = $btn.data('supplier_name');
                let invoice_id = $btn.data('store_supplier_invoice_id') || $btn.data('invoice_id');
                let bank_account_id = $btn.data('store_bank_account_id');
                let bank_account_name = $btn.data('bank_account_name');
                let bank_account_balance = $btn.data('bank_account_balance');
                let store_id = $btn.data('store_id');
                let amount = $btn.data('amount');
                let payment_date = $btn.data('payment_date') || $btn.data('date');
                let notes = $btn.data('notes');

                $('#store_supplier_payment_id_edit').val(payment_id);
                $('#amount_edit').val(amount).attr('data-original-amount', amount).attr('data-original-bank-account-id', bank_account_id).attr('data-original-invoice-id', invoice_id);
                
                let dateInput = document.querySelector('#payment_date_edit');
                if (dateInput && dateInput._flatpickr) {
                    dateInput._flatpickr.setDate(payment_date, true);
                } else {
                    $('#payment_date_edit').val(payment_date);
                }
                $('#notes_edit').val(notes);

                // Populate Select2 for Bank Account
                if ($('#store_bank_account_id_edit').length) {
                    if (bank_account_id) {
                        if ($('#store_bank_account_id_edit').find("option[value='" + bank_account_id + "']").length == 0) {
                            let newOpt = new Option(bank_account_name, bank_account_id, true, true);
                            $(newOpt).attr('data-balance', bank_account_balance);
                            $('#store_bank_account_id_edit').append(newOpt);
                        } else {
                            $('#store_bank_account_id_edit').find("option[value='" + bank_account_id + "']").attr('data-balance', bank_account_balance);
                        }
                        $('#store_bank_account_id_edit').val(bank_account_id).trigger('change.select2');
                    } else {
                        $('#store_bank_account_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Populate Select2 for Supplier
                if ($('#store_supplier_id_edit').length) {
                    if (supplier_id) {
                        if ($('#store_supplier_id_edit').find("option[value='" + supplier_id + "']").length == 0) {
                            let newOpt = new Option(supplier_name, supplier_id, true, true);
                            $('#store_supplier_id_edit').append(newOpt);
                        }
                        $('#store_supplier_id_edit').val(supplier_id).trigger('change.select2');
                    } else {
                        $('#store_supplier_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_id) {
                        $('#store_id_dept_edit').val(store_id).trigger('change.select2');
                        $('#hidden_store_id_edit').val(store_id);
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                        $('#hidden_store_id_edit').val('');
                    }
                }

                // Load invoices for supplier and select current invoice
                loadInvoicesForSupplierEdit(supplier_id, invoice_id);

                // Immediately calculate and render balance
                let initialBalance = parseFloat(bank_account_balance) || 0;
                let infoDiv = $('#bank_account_balance_info_edit');
                infoDiv.find('.balance-amount').text(initialBalance.toFixed(2));
                infoDiv.removeClass('hidden');
                updateEditRemainingBalance();

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-supplier-payments.update', ':id') !!}".replace(':id', payment_id);
                $('#update_store_supplier_payment_form').attr('action', url);
                
                $('#update_store_supplier_payment_form').find('.error-text').text('');
                $('#update_store_supplier_payment_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreSupplierPaymentModal').modal('show');
            });

            // Fetch Invoices when Supplier changes in edit modal
            $('#store_supplier_id_edit').on('change', function(e) {
                if (!e.isTrigger || e.type !== 'change') {
                    let supplier_id = $(this).val();
                    loadInvoicesForSupplierEdit(supplier_id, null);
                }
            });

            function loadInvoicesForSupplierEdit(supplier_id, selected_invoice_id) {
                let invoiceSelect = $('#store_supplier_invoice_id_edit');
                invoiceSelect.empty().append('<option value="" data-remaining="0" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>');
                $('#invoice_remaining_info_edit').addClass('hidden');

                if (supplier_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-supplier-invoices.by-supplier') !!}",
                        type: 'GET',
                        data: { supplier_id: supplier_id },
                        success: function(data) {
                            if (data && data.length > 0) {
                                $.each(data, function(key, inv) {
                                    let isSelected = (selected_invoice_id && inv.id == selected_invoice_id);
                                    let invText = '#' + inv.invoice_number + ' (المتبقي: ' + (inv.remaining_amount || 0) + ')';
                                    let newOption = new Option(invText, inv.id, isSelected, isSelected);
                                    $(newOption).attr('data-remaining', inv.remaining_amount || 0);
                                    invoiceSelect.append(newOption);
                                });
                                invoiceSelect.val(selected_invoice_id || '').trigger('change.select2');
                                updateEditInvoiceRemaining();
                            }
                        }
                    });
                }
            }

            // Invoice selection change
            $('#store_supplier_invoice_id_edit').on('change', function() {
                updateEditInvoiceRemaining();
            });

            function updateEditInvoiceRemaining() {
                let opt = $('#store_supplier_invoice_id_edit').find('option:selected');
                let remaining = parseFloat(opt.attr('data-remaining')) || 0;
                let infoDiv = $('#invoice_remaining_info_edit');

                // If editing and same invoice, add back original amount to remaining
                let originalInvoiceId = $('#amount_edit').attr('data-original-invoice-id');
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                if (originalInvoiceId && originalInvoiceId == opt.val()) {
                    remaining += originalAmount;
                }

                if (opt.val() && remaining > 0) {
                    infoDiv.find('.invoice-remaining-amount').text(remaining.toFixed(2));
                    infoDiv.removeClass('hidden');
                } else {
                    infoDiv.addClass('hidden');
                }
                updateEditRemainingBalance();
            }

            // Click invoice pill to auto-fill remaining amount
            $(document).on('click', '#invoice_remaining_info_edit', function() {
                let opt = $('#store_supplier_invoice_id_edit').find('option:selected');
                let remaining = parseFloat(opt.attr('data-remaining')) || 0;
                let originalInvoiceId = $('#amount_edit').attr('data-original-invoice-id');
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                if (originalInvoiceId && originalInvoiceId == opt.val()) {
                    remaining += originalAmount;
                }
                if (remaining > 0) {
                    $('#amount_edit').val(remaining.toFixed(2)).trigger('input');
                }
            });

            // Show balance on account change
            $('#store_bank_account_id_edit').on('change', function() {
                updateEditBalance();
            });

            // Update remaining balance on amount input
            $('#amount_edit').on('input keyup', function() {
                updateEditRemainingBalance();
            });

            function updateEditBalance() {
                let opt = $('#store_bank_account_id_edit').find('option:selected');
                let balance = parseFloat(opt.attr('data-balance')) || 0;
                let infoDiv = $('#bank_account_balance_info_edit');
                
                if (opt.val()) {
                    infoDiv.find('.balance-amount').text(balance.toFixed(2));
                    infoDiv.removeClass('hidden');
                    updateEditRemainingBalance();
                } else {
                    infoDiv.addClass('hidden');
                    $('#remaining_balance_info_edit').addClass('hidden');
                }
            }

            function updateEditRemainingBalance() {
                let selectedOption = $('#store_bank_account_id_edit').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                let selectedInvoiceOption = $('#store_supplier_invoice_id_edit').find('option:selected');
                let invoiceRemaining = parseFloat(selectedInvoiceOption.attr('data-remaining')) || 0;
                let paymentAmount = parseFloat($('#amount_edit').val()) || 0;
                
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                let originalBankAccountId = $('#amount_edit').attr('data-original-bank-account-id');
                let originalInvoiceId = $('#amount_edit').attr('data-original-invoice-id');
                
                let trueAvailableBalance = currentBalance;
                if (originalBankAccountId == $('#store_bank_account_id_edit').val()) {
                    trueAvailableBalance += originalAmount;
                }

                let trueInvoiceRemaining = invoiceRemaining;
                if (originalInvoiceId && originalInvoiceId == selectedInvoiceOption.val()) {
                    trueInvoiceRemaining += originalAmount;
                }
                
                let remainingPill = $('#remaining_balance_info_edit');
                let invoicePill = $('#invoice_remaining_info_edit');

                // Bank Account Balance Pill
                if (selectedOption.val() && paymentAmount > 0) {
                    let remaining = trueAvailableBalance - paymentAmount;
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

                // Invoice Remaining Check
                if (selectedInvoiceOption.val() && trueInvoiceRemaining > 0) {
                    if (paymentAmount > trueInvoiceRemaining) {
                        invoicePill.removeClass('bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200/80 dark:border-amber-800/60')
                            .addClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse');
                    } else {
                        invoicePill.removeClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-200/80 dark:border-rose-800/60 animate-pulse')
                            .addClass('bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border-amber-200/80 dark:border-amber-800/60');
                    }
                }
            }
        });
    </script>
@endpush
