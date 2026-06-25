<div class="modal modal-pop" id="createStoreSupplierModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreSupplierModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.store-suppliers.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_store_supplier_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createStoreSupplierModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('store_suppliers.create_new_store_supplier') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        @if(isset($stores))
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
                        @endif

                        <!-- Name -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_create">{!! __('store_suppliers.name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_create" name="name"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="mobile_create">{!! __('store_suppliers.mobile') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="mobile_create" name="mobile"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);">
                                <span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>

                        <!-- Bank/Wallet Name -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="bank_name_create">{!! __('store_suppliers.bank_name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="bank_name_create" name="bank_name"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text bank_name_error"></span>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="account_number_create">{!! __('store_suppliers.account_number') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="account_number_create" name="account_number"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text account_number_error"></span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="email_create">{!! __('store_suppliers.email') !!}</label>
                                <input type="email" id="email_create" name="email"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="address_create">{!! __('store_suppliers.address') !!}</label>
                                <input type="text" id="address_create" name="address"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text address_error"></span>
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
                    dropdownParent: $('#createStoreSupplierModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush
