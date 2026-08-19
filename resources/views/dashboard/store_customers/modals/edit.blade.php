<div class="modal fade" id="updateStoreCustomerModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreCustomerModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_customer_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreCustomerModalLabel">
                            {!! __('store_customers.update_store_customer') !!}
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

                    <!-- Name & Phone Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_edit">
                                {!! __('store_customers.name') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_edit" name="name"
                                class="form-input-modern" placeholder="{!! __('store_customers.enter_name') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="phone_edit">
                                {!! __('store_customers.phone') !!}
                            </label>
                            <input type="text" id="phone_edit" name="phone"
                                class="form-input-modern" placeholder="0599000000" autocomplete="off"
                                maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10);" dir="ltr">
                            <span class="text-xs text-rose-500 error-text phone_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Max Debt Limit -->
                    <div>
                        <label class="form-label-modern" for="max_debt_limit_edit">
                            {!! __('store_customers.max_debt_limit') !!}
                        </label>
                        <input type="number" step="0.01" min="0" id="max_debt_limit_edit" name="max_debt_limit"
                            class="form-input-modern" placeholder="{!! __('general.unlimited') ?? 'غير محدد' !!}" autocomplete="off">
                        <span class="text-xs text-rose-500 error-text max_debt_limit_error block mt-1"></span>
                    </div>

                    <!-- Bypass Debt Limit Toggle Card -->
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40" id="bypass_debt_limit_row_edit">
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
                            <input type="checkbox" id="bypass_debt_limit_edit" name="bypass_debt_limit" value="1" class="sr-only peer">
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

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation
            $(document).on('click', '.editStoreCustomerBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let store_customer_id = $btn.data('id');
                let store_customer_name = $btn.data('name');
                let store_customer_phone = $btn.data('phone');
                let store_customer_store_id = $btn.data('store_id');
                let store_customer_bypass_debt_limit = ($btn.data('bypass_debt_limit') == 1 || $btn.data('bypass_debt_limit') == '1');
                let store_customer_max_debt_limit = $btn.data('max_debt_limit');
                let store_customer_is_walk_in = ($btn.data('is_walk_in') == 1 || $btn.data('is_walk_in') == '1');

                // Populate form fields
                $('#id_edit').val(store_customer_id);
                $('#name_edit').val(store_customer_name);
                $('#phone_edit').val(store_customer_phone);
                $('#max_debt_limit_edit').val(store_customer_max_debt_limit);
                $('#bypass_debt_limit_edit').prop('checked', store_customer_bypass_debt_limit);

                if (store_customer_is_walk_in) {
                    $('#bypass_debt_limit_row_edit').hide();
                } else {
                    $('#bypass_debt_limit_row_edit').show();
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_customer_store_id) {
                        $('#store_id_dept_edit').val(store_customer_store_id).trigger('change.select2');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-customers.update', ':id') !!}".replace(':id', store_customer_id);
                $('#update_store_customer_form').attr('action', url);
                
                $('#update_store_customer_form').find('.error-text').text('');
                $('#update_store_customer_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreCustomerModal').modal('show');
            });
        });
    </script>
@endpush
