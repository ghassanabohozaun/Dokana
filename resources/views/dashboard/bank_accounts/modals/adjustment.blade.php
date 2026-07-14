<div class="modal modal-pop" id="adjustBankAccountModal" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="adjustBankAccountModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.bank-accounts.adjust') !!}" method="POST"
            id='adjust_bank_account_form' novalidate data-success-msg="{!! __('bank_accounts.reconciliation_successful') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg premium-modal-content">
                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center"
                        id="adjustBankAccountModalLabel">
                        <i class="fas fa-balance-scale text-primary mr-2 icon-size-18"></i> {!! __('bank_accounts.adjust_balance') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <input type="hidden" name="store_bank_account_id" id="adjust_store_bank_account_id">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            <h5 class="text-muted mb-1">{!! __('bank_accounts.system_balance') !!}</h5>
                            <h3 class="font-weight-bold text-primary" dir="ltr" id="current_system_balance">0.00</h3>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Actual Balance -->
                        <div class="col-md-12 mb-3">
                            <div class="premium-form-group">
                                <label class="premium-label" for="actual_balance">{!! __('bank_accounts.actual_balance') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" id="actual_balance" name="actual_balance"
                                    class="form-control premium-input shadow-none" autocomplete="off" required
                                    placeholder="{!! __('bank_accounts.enter_actual_balance') !!}">
                                <span class="text-danger error-text actual_balance_error"></span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="adjust_notes">{!! __('general.notes') !!}</label>
                                <textarea id="adjust_notes" name="notes" class="form-control premium-input shadow-none"
                                    rows="3" placeholder="{!! __('bank_accounts.enter_adjustment_notes') !!}"></textarea>
                                <span class="text-danger error-text notes_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer mt-3">
                    <button type="submit" id="saveAdjustBtn" class="btn btn-premium-save font-weight-bold">
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
