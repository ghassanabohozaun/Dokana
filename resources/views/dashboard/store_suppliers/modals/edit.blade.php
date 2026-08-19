<div class="modal fade" id="updateStoreSupplierModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_supplier_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreSupplierModalLabel">
                            {!! __('store_suppliers.update_store_supplier') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" id="id_edit" name="id">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_edit">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_edit" class="form-input-modern select2">
                            <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Name & Mobile Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_edit">
                                {!! __('store_suppliers.name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_edit" name="name"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="mobile_edit">
                                {!! __('store_suppliers.mobile') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="mobile_edit" name="mobile"
                                class="form-input-modern" placeholder="0599000000" autocomplete="off"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" dir="ltr">
                            <span class="text-xs text-rose-500 error-text mobile_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bank Name & Account Number Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="bank_name_edit">
                                {!! __('store_suppliers.bank_name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="bank_name_edit" name="bank_name"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.bank_name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text bank_name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="account_number_edit">
                                {!! __('store_suppliers.account_number') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="account_number_edit" name="account_number"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.account_number') !!}" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text account_number_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Email & Address Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="email_edit">
                                {!! __('store_suppliers.email') !!}
                            </label>
                            <input type="email" id="email_edit" name="email"
                                class="form-input-modern" placeholder="supplier@example.com" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text email_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="address_edit">
                                {!! __('store_suppliers.address') !!}
                            </label>
                            <input type="text" id="address_edit" name="address"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.address') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text address_error block mt-1"></span>
                        </div>
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation
            $(document).on('click', '.editStoreSupplierBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let store_supplier_id = $btn.data('id');
                let store_supplier_name = $btn.data('name');
                let store_supplier_mobile = $btn.data('mobile');
                let store_supplier_bank_name = $btn.data('bank_name');
                let store_supplier_account_number = $btn.data('account_number');
                let store_supplier_email = $btn.data('email');
                let store_supplier_address = $btn.data('address');
                let store_supplier_store_id = $btn.data('store_id');

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
                        $('#store_id_dept_edit').val(store_supplier_store_id).trigger('change.select2');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-suppliers.update', ':id') !!}".replace(':id', store_supplier_id);
                $('#update_store_supplier_form').attr('action', url);
                
                $('#update_store_supplier_form').find('.error-text').text('');
                $('#update_store_supplier_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreSupplierModal').modal('show');
            });
        });
    </script>
@endpush
