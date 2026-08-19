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
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-hand-holding-usd"></i>
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
                            <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
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
                            <label class="form-label-modern" for="store_supplier_invoice_id_create">
                                {!! __('store_supplier_payments.invoice') !!}
                            </label>
                            <select name="store_supplier_invoice_id" id="store_supplier_invoice_id_create" class="form-input-modern select2" disabled>
                                <option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_invoice_id_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Payment Bank Account / Wallet -->
                    <div>
                        <label class="form-label-modern" for="store_bank_account_id_create">
                            {!! __('store_supplier_payments.bank_account') !!} <span class="text-rose-500">*</span>
                        </label>
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

                        <!-- Balance & Remaining Balance Card -->
                        <div id="bank_account_balance_info_create" class="hidden mt-3 p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
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
                            <label class="form-label-modern" for="amount_create">
                                {!! __('store_supplier_payments.amount') !!} <span class="text-rose-500">*</span>
                            </label>
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
                invoiceSelect.empty().append('<option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>').prop('disabled', true).trigger('change.select2');
                
                if (store_id) {
                    // Fetch Suppliers
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, supplier) {
                                let newOption = new Option(supplier.name + ' - ' + (supplier.mobile || ''), supplier.id, false, false);
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
                
                invoiceSelect.empty().append('<option value="" selected>{!! __('general.general_payment') ?? 'دفعة عامة (بدون فاتورة محددة)' !!}</option>');
                
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
                }
            }

            function updateCreateRemainingBalance() {
                let selectedOption = $('#store_bank_account_id_create').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                let paymentAmount = parseFloat($('#amount_create').val()) || 0;
                
                let remaining = currentBalance - paymentAmount;
                let infoDiv = $('#bank_account_balance_info_create');
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

            // Instant balance rendering on modal open
            $('#createStoreSupplierPaymentModal').on('show.bs.modal', function () {
                updateCreateBalance();
            });
        });
    </script>
@endpush
