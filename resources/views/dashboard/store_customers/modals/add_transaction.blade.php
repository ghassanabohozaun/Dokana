<div class="modal modal-pop" id="addCustomerTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="addCustomerTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.store-transactions.store') !!}" method="POST" enctype="multipart/form-data"
            id='add_customer_transaction_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            
            <!-- Hidden inputs for submission since disabled fields don't submit -->
            <input type="hidden" name="store_id" id="hidden_store_id_create">
            <input type="hidden" name="store_customer_id" id="hidden_store_customer_id_create">

            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="addCustomerTransactionModalLabel">
                        <i class="fas fa-hand-holding-usd text-success mr-2 icon-size-18"></i> {!! __('store_transactions.create_new_store_transaction') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    
                    @if(isset($stores))
                    <div class="row">
                        <!-- Store (Readonly) -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="visible_store_id_create">{!! __('stores.store') !!}</label>
                                <input type="text" id="visible_store_id_create" class="form-control premium-input shadow-none bg-light" disabled>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Customer (Readonly) -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="visible_store_customer_id_create">{!! __('store_customers.store_customer') !!}</label>
                                <input type="text" id="visible_store_customer_id_create" class="form-control premium-input shadow-none bg-light" disabled>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_type_create">{!! __('store_transactions.type') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none" id='transaction_type_create' name="type">
                                    <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                    <option value="debt">{!! __('store_transactions.debt') !!}</option>
                                    <option value="payment">{!! __('store_transactions.payment') !!}</option>
                                </select>
                                <span class="text-danger error-text type_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Amount -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_amount_create">{!! __('store_transactions.amount') !!} <span class="text-danger">*</span></label>
                                <input type="number" id="transaction_amount_create" name="amount" step="0.01" min="0"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>

                        <!-- Transaction Date -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_date_create">{!! __('store_transactions.date') !!} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="transaction_date_create" name="transaction_date"
                                        class="form-control premium-input shadow-none ptc-datepicker" style="padding-left: 35px;" value="{{ date('Y-m-d') }}" autocomplete="off">
                                </div>
                                <span class="text-danger error-text transaction_date_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Description -->
                        <div class="col-md-12 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_description_create">{!! __('store_transactions.description') !!}</label>
                                <input type="text" id="transaction_description_create" name="description"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text description_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveTransactionBtn" class="btn btn-premium-save font-weight-bold">
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
