<div class="modal fade" id="updateStoreSupplierPaymentModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierPaymentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_supplier_payment_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
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
                    <input type="hidden" id="id_edit" name="id">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_edit">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_edit" class="form-input-modern select2">
                            <option value="" disabled>{!! __('general.select_from_list') !!}</option>
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
                            <label class="form-label-modern" for="store_supplier_id_edit">
                                {!! __('store_supplier_payments.supplier') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_supplier_id" id="store_supplier_id_edit" class="form-input-modern select2">
                                <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_id_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="store_supplier_invoice_id_edit">
                                {!! __('store_supplier_payments.invoice') !!}
                            </label>
                            <select name="store_supplier_invoice_id" id="store_supplier_invoice_id_edit" class="form-input-modern select2">
                                <option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_invoice_id_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Payment Bank Account / Wallet -->
                    <div>
                        <label class="form-label-modern" for="store_bank_account_id_edit">
                            {!! __('store_supplier_payments.bank_account') !!} <span class="text-rose-500">*</span>
                        </label>
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

                        <!-- Balance & Remaining Balance Card -->
                        <div id="bank_account_balance_info_edit" class="hidden mt-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <div class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5">
                                    <i class="fas fa-wallet text-xs"></i>
                                    <span>{!! __('general.balance') !!}:</span>
                                    <span class="balance-amount font-black" dir="ltr">0.00</span>
                                </div>
                                <div class="remaining-balance-container text-indigo-600 dark:text-indigo-400 flex items-center gap-1.5">
                                    <i class="fas fa-money-check-alt text-xs"></i>
                                    <span>{!! __('general.remaining_balance') !!}:</span>
                                    <span class="remaining-balance-amount font-black" dir="ltr">0.00</span>
                                </div>
                            </div>
                            <div class="exceeded-balance-warning hidden text-xs font-bold text-rose-600 dark:text-rose-400 mt-2 flex items-center gap-1.5">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>{!! __('store_supplier_payments.balance_exceeded_warning') !!}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Amount & Payment Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="amount_edit">
                                {!! __('store_supplier_payments.amount') !!} <span class="text-rose-500">*</span>
                            </label>
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation (0ms lag)
            $(document).on('click', '.editStoreSupplierPaymentBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let payment_id = $btn.data('id');
                let store_id = $btn.data('store_id');
                let supplier_id = $btn.data('supplier_id');
                let supplier_name = $btn.data('supplier_name');
                let invoice_id = $btn.data('invoice_id');
                let bank_account_id = $btn.data('store_bank_account_id');
                let bank_account_name = $btn.data('bank_account_name');
                let bank_account_balance = $btn.data('bank_account_balance');
                let amount = $btn.data('amount');
                let notes = $btn.data('notes');
                let payment_date = $btn.data('date');

                // Populate form fields
                $('#id_edit').val(payment_id);
                $('#amount_edit').val(amount);
                $('#amount_edit').attr('data-original-amount', amount);
                $('#amount_edit').attr('data-original-bank-account-id', bank_account_id);
                $('#notes_edit').val(notes);
                
                // Set Flatpickr date or input value
                let dateInput = document.querySelector('#payment_date_edit');
                if (dateInput && dateInput._flatpickr) {
                    dateInput._flatpickr.setDate(payment_date, true);
                } else {
                    $('#payment_date_edit').val(payment_date);
                }

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
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                    }
                }

                // Load invoices for supplier and select current invoice
                loadInvoicesForSupplierEdit(supplier_id, invoice_id);

                // Immediately calculate and render balance and remaining balance (0ms lag)
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

            // Fetch suppliers and bank accounts by store on change in edit modal
            $('#store_id_dept_edit').on('change', function(e) {
                if (!e.isTrigger || e.type !== 'change') {
                    let store_id = $(this).val();
                    let supplierSelect = $('#store_supplier_id_edit');
                    let bankAccountSelect = $('#store_bank_account_id_edit');
                    let invoiceSelect = $('#store_supplier_invoice_id_edit');
                    
                    supplierSelect.empty().append('<option value="" disabled selected>{!! __('general.select_from_list') !!}</option>');
                    bankAccountSelect.empty().append('<option value="" data-balance="0" disabled selected>{!! __('general.select_from_list') !!}</option>');
                    invoiceSelect.empty().append('<option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>');
                    
                    if (store_id) {
                        $.ajax({
                            url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, supplier) {
                                    let newOption = new Option(supplier.name + ' - ' + (supplier.mobile || ''), supplier.id, false, false);
                                    supplierSelect.append(newOption);
                                });
                                supplierSelect.trigger('change.select2');
                            }
                        });

                        $.ajax({
                            url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, account) {
                                    let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar || account.payment_entity.name) : '';
                                    let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                    let newOption = new Option(accountName, account.id, false, false);
                                    $(newOption).attr('data-balance', account.current_balance);
                                    bankAccountSelect.append(newOption);
                                });
                                bankAccountSelect.trigger('change.select2');
                            }
                        });
                    }
                }
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
                invoiceSelect.empty().append('<option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>');
                
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
                                    invoiceSelect.append(newOption);
                                });
                                invoiceSelect.val(selected_invoice_id || '').trigger('change.select2');
                            }
                        }
                    });
                }
            }

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
                }
            }

            function updateEditRemainingBalance() {
                let selectedOption = $('#store_bank_account_id_edit').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                let paymentAmount = parseFloat($('#amount_edit').val()) || 0;
                
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                let originalBankAccountId = $('#amount_edit').attr('data-original-bank-account-id');
                
                let trueAvailableBalance = currentBalance;
                if (originalBankAccountId == $('#store_bank_account_id_edit').val()) {
                    trueAvailableBalance += originalAmount;
                }
                
                let remaining = trueAvailableBalance - paymentAmount;
                let infoDiv = $('#bank_account_balance_info_edit');
                let remainingContainer = infoDiv.find('.remaining-balance-container');
                let remainingSpan = infoDiv.find('.remaining-balance-amount');
                let warningMsg = infoDiv.find('.exceeded-balance-warning');

                remainingSpan.text(remaining.toFixed(2));
                
                if (remaining < 0) {
                    remainingContainer.removeClass('text-indigo-600 dark:text-indigo-400').addClass('text-rose-600 dark:text-rose-400');
                    warningMsg.removeClass('hidden');
                } else {
                    remainingContainer.removeClass('text-rose-600 dark:text-rose-400').addClass('text-indigo-600 dark:text-indigo-400');
                    warningMsg.addClass('hidden');
                }
            }
        });
    </script>
@endpush
