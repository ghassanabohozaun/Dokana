<div class="modal modal-pop" id="updateStoreCustomerModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreCustomerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_customer_form' data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="updateStoreCustomerModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_customers.update_store_customer') !!}
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
                    </div>
                    @endif

                    <div class="row">
                        <input type="hidden" id="id_edit" name="id">

                        <!-- Name -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_edit">{!! __('store_customers.name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_edit" name="name"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('store_customers.enter_name') !!}">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="phone_edit">{!! __('store_customers.phone') !!}</label>
                                <input type="text" id="phone_edit" name="phone"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('store_customers.enter_phone') !!}">
                                <span class="text-danger error-text phone_error"></span>
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
            $('body').on('click', '.edit_store_customer_button', function(e) {
                e.preventDefault();
                
                let store_customer_id = $(this).attr('store_customer-id');
                let store_customer_name = $(this).attr('store_customer-name');
                let store_customer_phone = $(this).attr('store_customer-phone');
                let store_customer_store_id = $(this).attr('store_customer-store-id');
                let store_customer_store_name = $(this).attr('store_customer-store-name');

                // Populate form fields
                $('#id_edit').val(store_customer_id);
                $('#name_edit').val(store_customer_name);
                $('#phone_edit').val(store_customer_phone);

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_customer_store_id) {
                        $('#store_id_dept_edit').val(store_customer_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-customers.update', 'id') !!}".replace('id', store_customer_id);
                $('#update_store_customer_form').attr('action', url);
                
                // Show modal
                $('#updateStoreCustomerModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreCustomerModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush


