<div class="modal modal-pop" id="updateStoreSupplierModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_supplier_form' data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="updateStoreSupplierModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_suppliers.update_store_supplier') !!}
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

                        <!-- Name -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_edit">{!! __('store_suppliers.name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_edit" name="name"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text name_error"></span>
                            </div>
                        </div>

                        <!-- Mobile -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="mobile_edit">{!! __('store_suppliers.mobile') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="mobile_edit" name="mobile"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);">
                                <span class="text-danger error-text mobile_error"></span>
                            </div>
                        </div>

                        <!-- Bank/Wallet Name -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="bank_name_edit">{!! __('store_suppliers.bank_name') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="bank_name_edit" name="bank_name"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text bank_name_error"></span>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="account_number_edit">{!! __('store_suppliers.account_number') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="account_number_edit" name="account_number"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text account_number_error"></span>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="email_edit">{!! __('store_suppliers.email') !!}</label>
                                <input type="email" id="email_edit" name="email"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text email_error"></span>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="address_edit">{!! __('store_suppliers.address') !!}</label>
                                <input type="text" id="address_edit" name="address"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text address_error"></span>
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
            $('body').on('click', '.edit_store_supplier_button', function(e) {
                e.preventDefault();
                
                let store_supplier_id = $(this).attr('store_supplier-id');
                let store_supplier_name = $(this).attr('store_supplier-name');
                let store_supplier_mobile = $(this).attr('store_supplier-mobile');
                let store_supplier_bank_name = $(this).attr('store_supplier-bank_name');
                let store_supplier_account_number = $(this).attr('store_supplier-account_number');
                let store_supplier_email = $(this).attr('store_supplier-email');
                let store_supplier_address = $(this).attr('store_supplier-address');
                let store_supplier_store_id = $(this).attr('store_supplier-store-id');

                // Populate form fields
                $('#id_edit').val(store_supplier_id);
                $('#name_edit').val(store_supplier_name);
                $('#mobile_edit').val(store_supplier_mobile);
                $('#bank_name_edit').val(store_supplier_bank_name);
                $('#account_number_edit').val(store_supplier_account_number);
                $('#email_edit').val(store_supplier_email);
                $('#address_edit').val(store_supplier_address);

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_supplier_store_id) {
                        $('#store_id_dept_edit').val(store_supplier_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-suppliers.update', 'id') !!}".replace('id', store_supplier_id);
                $('#update_store_supplier_form').attr('action', url);
                
                // Show modal
                $('#updateStoreSupplierModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreSupplierModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush
