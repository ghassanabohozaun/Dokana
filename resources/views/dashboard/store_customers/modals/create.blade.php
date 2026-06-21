<div class="modal modal-pop" id="createStoreCustomerModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreCustomerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.store-customers.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_store_customer_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createStoreCustomerModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('store_customers.create_new_store_customer') !!}
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
                                <label class="premium-label" for="store_id_dept_create">{!! __('stores.store') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_id_dept_create' name="store_id">
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
                        <!-- Name -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_create">{!! __('store_customers.name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_create" name="name"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('store_customers.enter_name') !!}">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="phone_create">{!! __('store_customers.phone') !!}</label>
                                <input type="text" id="phone_create" name="phone"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('store_customers.enter_phone') !!}">
                                <span class="text-danger error-text phone_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtn" class="btn btn-premium-save font-weight-bold">
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
    <script>
        $(document).ready(function() {
            if ($('#store_id_dept_create').length) {
                $('#store_id_dept_create').select2({
                    dropdownParent: $('#createStoreCustomerModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush




