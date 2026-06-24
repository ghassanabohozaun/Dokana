<div class="modal modal-pop" id="updateStoreWithdrawalModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreWithdrawalModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_withdrawal_form' data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="updateStoreWithdrawalModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_withdrawals.update_store_withdrawal') !!}
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
                        @if(isset($stores))
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_id_dept_edit">{!! __('stores.store') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_id_dept_edit' name="store_id">
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text store_id_error"></span>
                            </div>
                        </div>
                        @endif

                        <!-- Bank Account -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_bank_account_id_edit">{!! __('bank_accounts.bank_account') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_bank_account_id_edit' name="store_bank_account_id" style="width: 100%;">
                                    <option value="" data-balance="0" selected>{!! __('general.select_from_list') !!}</option>
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
                                <div id="bank_account_balance_info_edit" class="mt-3 d-none w-100">
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2 shadow-sm" style="border: 2px dashed #b1b1b1; background-color: #f8f9fa; border-radius: 8px;">
                                        <div class="text-success font-weight-bold" style="font-size: 14px;">
                                            <i class="fas fa-wallet mr-1"></i> {!! __('general.balance') !!}: <span class="balance-amount text-success font-weight-bolder" style="font-size: 15px;">0.00</span>
                                        </div>
                                        <div class="remaining-balance-container text-primary font-weight-bold" style="font-size: 14px;">
                                            <i class="fas fa-money-check-alt mr-1"></i> {!! __('general.remaining_balance') !!}: <span class="remaining-balance-amount text-primary font-weight-bolder" style="font-size: 15px;">0.00</span>
                                        </div>
                                    </div>
                                    <div class="exceeded-balance-warning text-danger mt-2 mb-0 d-none font-weight-bold" style="font-size: 13px;">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> {!! __('store_withdrawals.balance_exceeded_warning') !!}
                                    </div>
                                </div>
                                <span class="text-danger error-text store_bank_account_id_error"></span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="amount_edit">{!! __('store_withdrawals.amount') !!} <span class="text-danger">*</span></label>
                                <input type="number" id="amount_edit" name="amount" step="0.01" min="0"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>

                        <!-- Withdrawal Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="withdrawal_date_edit">{!! __('store_withdrawals.date') !!} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="withdrawal_date_edit" name="withdrawal_date"
                                        class="form-control premium-input shadow-none ptc-datepicker" style="padding-left: 35px;" autocomplete="off">
                                </div>
                                <span class="text-danger error-text withdrawal_date_error"></span>
                            </div>
                        </div>

                        <!-- Reason -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="reason_edit">{!! __('store_withdrawals.reason') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="reason_edit" name="reason"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text reason_error"></span>
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

                    <button type="button" class="btn btn-premium-secondary font-weight-bold"
                        data-dismiss="modal">
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
            $('body').on('click', '.edit_store_withdrawal_button', function(e) {
                e.preventDefault();
                
                let store_withdrawal_id = $(this).attr('store_withdrawal-id');
                let store_withdrawal_amount = $(this).attr('store_withdrawal-amount');
                let store_withdrawal_reason = $(this).attr('store_withdrawal-reason');
                let store_withdrawal_store_id = $(this).attr('store_withdrawal-store-id');
                let store_withdrawal_date = $(this).attr('store_withdrawal-date');
                let store_withdrawal_bank_account_id = $(this).attr('store_withdrawal-store-bank-account-id');
                let store_withdrawal_bank_account_name = $(this).attr('store_withdrawal-bank-account-name');
                let store_withdrawal_bank_account_balance = $(this).attr('store_withdrawal-bank-account-balance');

                // Populate form fields
                $('#id_edit').val(store_withdrawal_id);
                $('#amount_edit').val(store_withdrawal_amount);
                $('#amount_edit').attr('data-original-amount', store_withdrawal_amount);
                $('#amount_edit').attr('data-original-bank-account-id', store_withdrawal_bank_account_id);
                $('#reason_edit').val(store_withdrawal_reason);
                $('#withdrawal_date_edit').val(store_withdrawal_date);

                // Populate Select2 for Bank Account
                if ($('#store_bank_account_id_edit').length) {
                    if (store_withdrawal_bank_account_id) {
                        if ($('#store_bank_account_id_edit').find("option[value='" + store_withdrawal_bank_account_id + "']").length == 0) {
                            let newOpt = new Option(store_withdrawal_bank_account_name, store_withdrawal_bank_account_id, true, true);
                            $(newOpt).attr('data-balance', store_withdrawal_bank_account_balance);
                            $('#store_bank_account_id_edit').append(newOpt);
                        } else {
                            $('#store_bank_account_id_edit').find("option[value='" + store_withdrawal_bank_account_id + "']").attr('data-balance', store_withdrawal_bank_account_balance);
                        }
                        $('#store_bank_account_id_edit').val(store_withdrawal_bank_account_id).trigger('change');
                    } else {
                        $('#store_bank_account_id_edit').val(null).trigger('change');
                    }
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_withdrawal_store_id) {
                        $('#store_id_dept_edit').val(store_withdrawal_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-withdrawals.update', 'id') !!}".replace('id', store_withdrawal_id);
                $('#update_store_withdrawal_form').attr('action', url);
                
                // Show modal
                $('#updateStoreWithdrawalModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreWithdrawalModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#store_bank_account_id_edit').length) {
                $('#store_bank_account_id_edit').select2({
                    dropdownParent: $('#updateStoreWithdrawalModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Fetch bank accounts by store on change
            $('#store_id_dept_edit').on('change', function(e) {
                if (!e.isTrigger || e.type !== 'change') {
                    let store_id = $(this).val();
                    let bankAccountSelect = $('#store_bank_account_id_edit');
                    
                    bankAccountSelect.empty().append('<option value="" data-balance="0" selected>{!! __('general.select_from_list') !!}</option>');
                    
                    if (store_id) {
                        $.ajax({
                            url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, account) {
                                    let entityName = account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar;
                                    let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                    let newOption = new Option(accountName, account.id, false, false);
                                    $(newOption).attr('data-balance', account.current_balance);
                                    bankAccountSelect.append(newOption);
                                });
                            }
                        });
                    }
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
                        data: { bank_account_id: bank_account_id },
                        success: function(response) {
                            let balance = parseFloat(response.balance);
                            $('#store_bank_account_id_edit').find('option:selected').attr('data-balance', balance);
                            infoDiv.find('.balance-amount').text(balance.toFixed(2));
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
                let withdrawalAmount = parseFloat($('#amount_edit').val()) || 0;
                
                // For edit, we must add back the original withdrawal amount to find the true available balance
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                let originalBankAccountId = $('#amount_edit').attr('data-original-bank-account-id');
                
                let trueAvailableBalance = currentBalance;
                if (originalBankAccountId == $('#store_bank_account_id_edit').val()) {
                    trueAvailableBalance += originalAmount;
                }
                
                let remaining = trueAvailableBalance - withdrawalAmount;
                let infoDiv = $('#bank_account_balance_info_edit');
                let remainingContainer = infoDiv.find('.remaining-balance-container');
                let remainingSpan = infoDiv.find('.remaining-balance-amount');
                let warningMsg = infoDiv.find('.exceeded-balance-warning');

                remainingSpan.text(remaining.toFixed(2));
                
                if (remaining < 0) {
                    remainingContainer.removeClass('text-primary').addClass('text-danger');
                    remainingSpan.removeClass('text-primary').addClass('text-danger');
                    warningMsg.removeClass('d-none').hide().fadeIn(200);
                } else {
                    remainingContainer.removeClass('text-danger').addClass('text-primary');
                    remainingSpan.removeClass('text-danger').addClass('text-primary');
                    warningMsg.addClass('d-none');
                }
            }

            // Refresh balance when modal is shown
            $('#updateStoreWithdrawalModal').on('shown.bs.modal', function () {
                if ($('#store_bank_account_id_edit').val()) {
                    updateEditBalance();
                }
            });
        });
    </script>
@endpush
