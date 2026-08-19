<div class="modal fade" id="createStoreSupplierModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreSupplierModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-suppliers.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_supplier_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreSupplierModalLabel">
                            {!! __('store_suppliers.create_new_store_supplier') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_create">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_create" class="form-input-modern select2">
                            <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
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
                            <label class="form-label-modern" for="name_create">
                                {!! __('store_suppliers.name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_create" name="name"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="mobile_create">
                                {!! __('store_suppliers.mobile') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="mobile_create" name="mobile"
                                class="form-input-modern" placeholder="0599000000" autocomplete="off"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" dir="ltr">
                            <span class="text-xs text-rose-500 error-text mobile_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bank Name & Account Number Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="bank_name_create">
                                {!! __('store_suppliers.bank_name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="bank_name_create" name="bank_name"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.bank_name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text bank_name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="account_number_create">
                                {!! __('store_suppliers.account_number') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="account_number_create" name="account_number"
                                class="form-input-modern" placeholder="{!! __('store_suppliers.account_number') !!}" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text account_number_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Email & Address Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="email_create">
                                {!! __('store_suppliers.email') !!}
                            </label>
                            <input type="email" id="email_create" name="email"
                                class="form-input-modern" placeholder="supplier@example.com" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text email_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="address_create">
                                {!! __('store_suppliers.address') !!}
                            </label>
                            <input type="text" id="address_create" name="address"
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
