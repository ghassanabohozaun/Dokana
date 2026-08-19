<div class="modal fade" id="createStoreCustomerModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreCustomerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-customers.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_customer_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreCustomerModalLabel">
                            {!! __('store_customers.create_new_store_customer') !!}
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

                    <!-- Name & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_create">
                                {!! __('store_customers.name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_create" name="name"
                                class="form-input-modern" placeholder="{!! __('store_customers.enter_name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="phone_create">
                                {!! __('store_customers.phone') !!}
                            </label>
                            <input type="text" id="phone_create" name="phone"
                                class="form-input-modern" placeholder="0599000000" autocomplete="off"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" dir="ltr">
                            <span class="text-xs text-rose-500 error-text phone_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Opening Balance & Max Debt Limit Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="opening_balance_create">
                                {!! __('store_customers.opening_balance') ?? 'الرصيد الافتتاحي (ديون سابقة)' !!}
                            </label>
                            <input type="number" step="0.01" min="0" id="opening_balance_create" name="opening_balance"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text opening_balance_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="max_debt_limit_create">
                                {!! __('store_customers.max_debt_limit') !!}
                            </label>
                            <input type="number" step="0.01" min="0" id="max_debt_limit_create" name="max_debt_limit"
                                class="form-input-modern" placeholder="{!! __('general.unlimited') ?? 'غير محدد' !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text max_debt_limit_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bypass Debt Limit Toggle Card -->
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-sm">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-800 dark:text-white block">
                                    {!! __('store_customers.bypass_debt_limit') !!}
                                </span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 block">
                                    {!! __('store_customers.bypass_debt_limit_desc') !!}
                                </span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" id="bypass_debt_limit_create" name="bypass_debt_limit" value="1" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500 shadow-sm"></div>
                        </label>
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
