<div class="modal modal-pop" id="updateStoreSupplierPaymentModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierPaymentModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_supplier_payment_form' data-success-msg="{!! __('general.update_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center"
                        id="updateStoreSupplierPaymentModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_supplier_payments.update_store_supplier_payment') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        <input type="hidden" id="id_edit" name="id">
                        @if (isset($stores))
                            <div class="col-md-12 mb-1">
                                <div class="premium-form-group">
                                    <label class="premium-label" for="store_id_dept_edit">{!! __('stores.store') !!} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control premium-input select2 shadow-none"
                                        id='store_id_dept_edit' name="store_id">
                                        <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text store_id_error"></span>
                                </div>
                            </div>
                        @endif

                        <!-- Supplier -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_supplier_id_edit">{!! __('store_supplier_payments.supplier') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none"
                                    id='store_supplier_id_edit' name="store_supplier_id" style="width: 100%;"
                                    @if (isset($stores)) disabled @endif>
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if (isset($suppliers) && !isset($stores))
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" data-mobile="{{ $supplier->mobile }}"
                                                data-bank-name="{{ $supplier->bank_name }}"
                                                data-account-number="{{ $supplier->account_number }}">
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text store_supplier_id_error"></span>
                            </div>
                        </div>



                        <!-- Supplier Invoice -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label"
                                    for="store_supplier_invoice_id_edit">{!! __('store_supplier_payments.invoice') !!}</label>
                                <select class="form-control premium-input select2 shadow-none"
                                    id='store_supplier_invoice_id_edit' name="store_supplier_invoice_id"
                                    style="width: 100%;" disabled>
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                </select>
                                <span class="text-danger error-text store_supplier_invoice_id_error"></span>
                            </div>
                        </div>

                        <!-- Bank Account -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_bank_account_id_edit">{!! __('bank_accounts.bank_account') !!}
                                    <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none"
                                    id='store_bank_account_id_edit' name="store_bank_account_id" style="width: 100%;"
                                    @if (isset($stores)) disabled @endif>
                                    <option value="" data-balance="0" selected>{!! __('general.select_from_list') !!}</option>
                                    @if (isset($bankAccounts) && !isset($stores))
                                        @foreach ($bankAccounts as $account)
                                            @php
                                                $entityName =
                                                    optional($account->paymentEntity)->getTranslation(
                                                        'name',
                                                        app()->getLocale(),
                                                    ) ?:
                                                    optional($account->paymentEntity)->getTranslation('name', 'ar');
                                                $accountName =
                                                    $account->account_type === 'cash'
                                                        ? $entityName
                                                        : $entityName . ' - ' . $account->account_number;
                                            @endphp
                                            <option value="{{ $account->id }}"
                                                data-balance="{{ $account->current_balance }}">{{ $accountName }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="bank_account_balance_info_edit" class="mt-3 d-none w-100">
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2 shadow-sm"
                                        style="border: 2px dashed #b1b1b1; background-color: #f8f9fa; border-radius: 8px;">
                                        <div class="text-success font-weight-bold" style="font-size: 14px;">
                                            <i class="fas fa-wallet mr-1"></i> {!! __('general.balance') !!}: <span
                                                class="balance-amount text-success font-weight-bolder"
                                                style="font-size: 15px;">0</span>
                                        </div>
                                        <div class="remaining-balance-container text-primary font-weight-bold"
                                            style="font-size: 14px;">
                                            <i class="fas fa-money-check-alt mr-1"></i> {!! __('general.remaining_balance') !!}: <span
                                                class="remaining-balance-amount text-primary font-weight-bolder"
                                                style="font-size: 15px;">0</span>
                                        </div>
                                    </div>
                                    <div class="exceeded-balance-warning text-danger mt-2 mb-0 d-none font-weight-bold"
                                        style="font-size: 13px;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {!! __('store_supplier_payments.balance_exceeded_warning') !!}
                                    </div>
                                </div>
                                <span class="text-danger error-text store_bank_account_id_error"></span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="amount_edit">{!! __('store_supplier_payments.amount') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="amount_edit" name="amount" step="1" min="1"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>

                        <!-- Withdrawal Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="payment_date_edit">{!! __('store_supplier_payments.date') !!} <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute"
                                        style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="payment_date_edit" name="payment_date"
                                        class="form-control premium-input shadow-none ptc-datepicker"
                                        style="padding-left: 35px;" autocomplete="off">
                                </div>
                                <span class="text-danger error-text payment_date_error"></span>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="notes_edit">{!! __('store_supplier_payments.notes') !!}</label>
                                <input type="text" id="notes_edit" name="notes"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text notes_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtnEdit" class="btn btn-premium-save font-weight-bold">
                        <i class="fas fa-save mr-2"></i>
                        <i class="fas fa-spinner fa-spin d-none spinner_loading mr-2"></i>
                        {{ __('general.save') }}
                    </button>

                    <button type="button" class="btn btn-premium-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('general.cancel') }}
                    </button>
                </div>
                <!--end::modal footer-->

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically
            $('body').on('click', '.edit_store_supplier_payment_button', function(e) {
                e.preventDefault();

                let store_supplier_payment_id = $(this).attr('store_supplier_payment-id');
                let store_supplier_payment_amount = $(this).attr('store_supplier_payment-amount');
                let store_supplier_payment_notes = $(this).attr('store_supplier_payment-notes');
                let store_supplier_payment_store_id = $(this).attr('store_supplier_payment-store-id');
                let store_supplier_payment_date = $(this).attr('store_supplier_payment-date');
                let store_supplier_payment_bank_account_id = $(this).attr(
                    'store_supplier_payment-store-bank-account-id');
                let store_supplier_payment_bank_account_name = $(this).attr(
                    'store_supplier_payment-bank-account-name');
                let store_supplier_payment_bank_account_balance = $(this).attr(
                    'store_supplier_payment-bank-account-balance');
                let store_supplier_payment_supplier_id = $(this).attr(
                    'store_supplier_payment-store-supplier-id');
                let store_supplier_payment_invoice_id = $(this).attr(
                    'store_supplier_payment-store-supplier-invoice-id');

                window.isStoreSupplierPaymentPopulating = true;

                // Populate form fields
                $('#id_edit').val(store_supplier_payment_id);
                $('#amount_edit').val(store_supplier_payment_amount);
                $('#amount_edit').attr('data-original-amount', store_supplier_payment_amount);
                $('#amount_edit').attr('data-original-bank-account-id',
                    store_supplier_payment_bank_account_id);
                $('#notes_edit').val(store_supplier_payment_notes);
                $('#payment_date_edit').val(store_supplier_payment_date);

                // Set pending invoice and supplier and bank account values
                $('#store_supplier_invoice_id_edit').attr('data-pending-val',
                    store_supplier_payment_invoice_id || '');
                $('#store_supplier_id_edit').attr('data-pending-val', store_supplier_payment_supplier_id ||
                    '');
                $('#store_bank_account_id_edit').attr('data-pending-val',
                    store_supplier_payment_bank_account_id || '');

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_supplier_payment_store_id) {
                        $('#store_id_dept_edit').val(store_supplier_payment_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                } else {
                    // For regular user, just trigger normal select population/change
                    if ($('#store_supplier_id_edit').length) {
                        if (store_supplier_payment_supplier_id) {
                            $('#store_supplier_id_edit').val(store_supplier_payment_supplier_id).trigger(
                                'change');
                        } else {
                            $('#store_supplier_id_edit').val(null).trigger('change');
                        }
                    }
                    if ($('#store_bank_account_id_edit').length) {
                        if (store_supplier_payment_bank_account_id) {
                            $('#store_bank_account_id_edit').val(store_supplier_payment_bank_account_id)
                                .trigger('change');
                        } else {
                            $('#store_bank_account_id_edit').val(null).trigger('change');
                        }
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-supplier-payments.update', 'id') !!}".replace('id', store_supplier_payment_id);
                $('#update_store_supplier_payment_form').attr('action', url);

                // Show modal
                $('#updateStoreSupplierPaymentModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreSupplierPaymentModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#store_bank_account_id_edit').length) {
                $('#store_bank_account_id_edit').select2({
                    dropdownParent: $('#updateStoreSupplierPaymentModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            if ($('#store_supplier_id_edit').length) {
                $('#store_supplier_id_edit').select2({
                    dropdownParent: $('#updateStoreSupplierPaymentModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            if ($('#store_supplier_invoice_id_edit').length) {
                $('#store_supplier_invoice_id_edit').select2({
                    dropdownParent: $('#updateStoreSupplierPaymentModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // On supplier change
            $('#store_supplier_id_edit').on('change', function() {
                let supplier_id = $(this).val();
                let selectedOption = $(this).find('option:selected');
                let invoiceSelect = $('#store_supplier_invoice_id_edit');

                invoiceSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');

                if (supplier_id) {
                    // Fetch supplier's invoices
                    $.ajax({
                        url: "{!! route('dashboard.store-supplier-invoices.by-supplier') !!}",
                        type: 'GET',
                        data: {
                            supplier_id: supplier_id
                        },
                        success: function(data) {
                            let pendingVal = invoiceSelect.attr('data-pending-val') || '';
                            let pendingValFound = false;

                            $.each(data, function(key, invoice) {
                                let label = invoice.invoice_number +
                                    ' ({!! __('store_supplier_invoices.total_amount') !!}: ' + Math.round(invoice
                                        .total_amount) +
                                    ' - {!! __('store_supplier_invoices.remaining_amount') !!}: ' + Math.round(invoice
                                        .remaining_amount) + ')';
                                let newOption = new Option(label, invoice.id, false,
                                    false);
                                $(newOption).attr('data-remaining', invoice
                                    .remaining_amount);
                                invoiceSelect.append(newOption);
                                if (invoice.id == pendingVal) {
                                    pendingValFound = true;
                                }
                            });

                            if (pendingVal && !pendingValFound) {
                                $.ajax({
                                    url: "{!! route('dashboard.store-supplier-invoices.show', 'id') !!}".replace('id',
                                        pendingVal),
                                    type: 'GET',
                                    success: function(invoice) {
                                        let label = invoice.invoice_number +
                                            ' ({!! __('store_supplier_invoices.total_amount') !!}: ' + Math
                                            .round(invoice.total_amount) +
                                            ' - {!! __('store_supplier_invoices.remaining_amount') !!}: ' + Math
                                            .round(invoice.remaining_amount) + ')';
                                        let newOption = new Option(label, invoice
                                            .id, true, true);
                                        $(newOption).attr('data-remaining', invoice
                                            .remaining_amount);
                                        invoiceSelect.append(newOption);
                                        invoiceSelect.prop('disabled', false).val(
                                            pendingVal).trigger(
                                            'change.select2');
                                        invoiceSelect.removeAttr(
                                            'data-pending-val');

                                        window.isStoreSupplierPaymentPopulating =
                                            false;
                                    }
                                });
                            } else {
                                invoiceSelect.prop('disabled', false);
                                if (pendingVal) {
                                    invoiceSelect.val(pendingVal).trigger('change.select2');
                                    invoiceSelect.removeAttr('data-pending-val');
                                } else {
                                    invoiceSelect.trigger('change.select2');
                                }

                                window.isStoreSupplierPaymentPopulating = false;
                            }
                        }
                    });
                } else {
                    invoiceSelect.prop('disabled', true).trigger('change.select2');
                    window.isStoreSupplierPaymentPopulating = false;
                }
            });



            // Fetch bank accounts and suppliers by store on change
            $('#store_id_dept_edit').on('change', function() {
                let store_id = $(this).val();
                let bankAccountSelect = $('#store_bank_account_id_edit');
                let supplierSelect = $('#store_supplier_id_edit');

                bankAccountSelect.empty().append(
                    '<option value="" data-balance="0" selected>{!! __('general.select_from_list') !!}</option>');
                supplierSelect.empty().append(
                    '<option value="" selected>{!! __('general.select_from_list') !!}</option>');

                if (store_id) {
                    // Fetch bank accounts
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: {
                            store_id: store_id
                        },
                        success: function(data) {
                            let pendingVal = bankAccountSelect.attr('data-pending-val') || '';
                            $.each(data, function(key, account) {
                                let entityName = account.payment_entity.name[
                                        "{!! app()->getLocale() !!}"] || account
                                    .payment_entity.name.ar;
                                let accountName = account.account_type === 'cash' ?
                                    entityName : entityName + ' - ' + account
                                    .account_number;
                                let newOption = new Option(accountName, account.id,
                                    false, false);
                                $(newOption).attr('data-balance', account
                                    .current_balance);
                                bankAccountSelect.append(newOption);
                            });

                            bankAccountSelect.prop('disabled', false);
                            if (pendingVal) {
                                bankAccountSelect.val(pendingVal).trigger('change.select2')
                                    .trigger('change');
                                bankAccountSelect.removeAttr('data-pending-val');
                            } else {
                                bankAccountSelect.trigger('change.select2').trigger('change');
                            }
                        }
                    });

                    // Fetch suppliers
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: {
                            store_id: store_id
                        },
                        success: function(data) {
                            let pendingVal = supplierSelect.attr('data-pending-val') || '';
                            $.each(data, function(key, supplier) {
                                let newOption = new Option(supplier.name, supplier.id,
                                    false, false);
                                $(newOption).attr('data-mobile', supplier.mobile);
                                $(newOption).attr('data-bank-name', supplier.bank_name);
                                $(newOption).attr('data-account-number', supplier
                                    .account_number);
                                supplierSelect.append(newOption);
                            });
                            supplierSelect.prop('disabled', false);
                            if (pendingVal) {
                                supplierSelect.val(pendingVal).trigger('change');
                                supplierSelect.removeAttr('data-pending-val');
                            } else {
                                supplierSelect.trigger('change.select2');
                            }
                        }
                    });
                } else {
                    bankAccountSelect.prop('disabled', true).trigger('change.select2');
                    supplierSelect.prop('disabled', true).trigger('change.select2');
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

            // Update balance dynamically via AJAX
            function updateEditBalance() {
                let bank_account_id = $('#store_bank_account_id_edit').val();
                let infoDiv = $('#bank_account_balance_info_edit');

                if (bank_account_id) {
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.get-balance') !!}",
                        type: 'GET',
                        data: {
                            bank_account_id: bank_account_id
                        },
                        success: function(response) {
                            let balance = parseFloat(response.balance);
                            $('#store_bank_account_id_edit').find('option:selected').attr(
                                'data-balance', balance);
                            infoDiv.find('.balance-amount').text(Math.round(balance));
                            infoDiv.removeClass('d-none');
                            updateEditRemainingBalance();
                        }
                    });
                } else {
                    infoDiv.addClass('d-none');
                }
            }

            function updateEditRemainingBalance() {
                let selectedOption = $('#store_bank_account_id_edit').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                let paymentAmount = parseFloat($('#amount_edit').val()) || 0;

                // For edit, we must add back the original payment amount to find the true available balance
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

                remainingSpan.text(Math.round(remaining));

                if (remaining < 0) {
                    remainingContainer.removeClass('text-primary').addClass('text-danger');
                    remainingSpan.removeClass('text-primary').addClass('text-danger');
                    if (warningMsg.hasClass('d-none')) {
                        warningMsg.removeClass('d-none').hide().fadeIn(200);
                    }
                } else {
                    remainingContainer.removeClass('text-danger').addClass('text-primary');
                    remainingSpan.removeClass('text-danger').addClass('text-primary');
                    warningMsg.addClass('d-none').hide();
                }
            }

            // Refresh balance when modal is shown
            $('#updateStoreSupplierPaymentModal').on('shown.bs.modal', function() {
                if ($('#store_bank_account_id_edit').val()) {
                    updateEditBalance();
                }
            });
        });
    </script>
@endpush
