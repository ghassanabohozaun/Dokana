<div class="modal modal-pop" id="updateStoreTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_transaction_form' data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="updateStoreTransactionModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_transactions.update_store_transaction') !!}
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

                        <!-- Customer -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_customer_id_edit">{!! __('store_customers.store_customer') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_customer_id_edit' name="store_customer_id">
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if(isset($customers))
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text store_customer_id_error"></span>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="type_edit">{!! __('store_transactions.type') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none" id='type_edit' name="type">
                                    <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                    <option value="debt">{!! __('store_transactions.debt') !!}</option>
                                    <option value="payment">{!! __('store_transactions.payment') !!}</option>
                                </select>
                                <span class="text-danger error-text type_error"></span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="amount_edit">{!! __('store_transactions.amount') !!} <span class="text-danger">*</span></label>
                                <input type="number" id="amount_edit" name="amount" step="0.01" min="0"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>

                        <!-- Transaction Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_date_edit">{!! __('store_transactions.date') !!} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="transaction_date_edit" name="transaction_date"
                                        class="form-control premium-input shadow-none ptc-datepicker" style="padding-left: 35px;" autocomplete="off">
                                </div>
                                <span class="text-danger error-text transaction_date_error"></span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="description_edit">{!! __('store_transactions.description') !!}</label>
                                <input type="text" id="description_edit" name="description"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text description_error"></span>
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
            $('body').on('click', '.edit_store_transaction_button', function(e) {
                e.preventDefault();
                
                let store_transaction_id = $(this).attr('store_transaction-id');
                let store_transaction_store_customer_id = $(this).attr('store_transaction-store-customer-id');
                let store_transaction_type = $(this).attr('store_transaction-type');
                let store_transaction_amount = $(this).attr('store_transaction-amount');
                let store_transaction_description = $(this).attr('store_transaction-description');
                let store_transaction_store_id = $(this).attr('store_transaction-store-id');
                let store_transaction_date = $(this).attr('store_transaction-date');
                let store_transaction_customer_name = $(this).attr('store_transaction-customer-name');

                // Populate form fields
                $('#id_edit').val(store_transaction_id);
                $('#type_edit').val(store_transaction_type);
                $('#amount_edit').val(store_transaction_amount);
                $('#description_edit').val(store_transaction_description);
                $('#transaction_date_edit').val(store_transaction_date);

                // Populate Select2 for Customer
                if ($('#store_customer_id_edit').length) {
                    if (store_transaction_store_customer_id) {
                        // Ensure option exists
                        if ($('#store_customer_id_edit').find("option[value='" + store_transaction_store_customer_id + "']").length == 0) {
                            $('#store_customer_id_edit').append(new Option(store_transaction_customer_name, store_transaction_store_customer_id, true, true));
                        }
                        $('#store_customer_id_edit').val(store_transaction_store_customer_id).trigger('change');
                    } else {
                        $('#store_customer_id_edit').val(null).trigger('change');
                    }
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_transaction_store_id) {
                        $('#store_id_dept_edit').val(store_transaction_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-transactions.update', 'id') !!}".replace('id', store_transaction_id);
                $('#update_store_transaction_form').attr('action', url);
                
                // Show modal
                $('#updateStoreTransactionModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreTransactionModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#store_customer_id_edit').length) {
                $('#store_customer_id_edit').select2({
                    dropdownParent: $('#updateStoreTransactionModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Fetch customers by store on change
            $('#store_id_dept_edit').on('change', function(e) {
                // Ignore if triggered by programmatic update during modal open, 
                // unless user actually clicked (isTrusted or triggered manually without modal data)
                if (!e.isTrigger || e.type !== 'change') {
                    let store_id = $(this).val();
                    let customerSelect = $('#store_customer_id_edit');
                    
                    customerSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                    
                    if (store_id) {
                        $.ajax({
                            url: "{!! route('dashboard.store-customers.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, customer) {
                                    customerSelect.append('<option value="' + customer.id + '">' + customer.name + ' - ' + (customer.phone || '') + '</option>');
                                });
                            }
                        });
                    }
                }
            });
        });
    </script>
@endpush


