<div class="modal fade" id="createPaymentEntityModal" tabindex="-1" role="dialog" aria-labelledby="createPaymentEntityModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.payment-entities.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_payment_entity_form" novalidate data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createPaymentEntityModalLabel">
                            {!! __('payment_entities.create_new_payment_entity') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    <!-- Type Selection -->
                    <div>
                        <label class="form-label-modern" for="type_create">
                            {!! __('payment_entities.type') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="type" id="type_create" class="form-input-modern select2">
                            <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                            <option value="bank">{!! __('payment_entities.type_bank') !!}</option>
                            <option value="wallet">{!! __('payment_entities.type_wallet') !!}</option>
                        </select>
                        <span class="text-xs text-rose-500 error-text type_error block mt-1"></span>
                    </div>

                    <!-- Arabic Name & English Name -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="name_ar_create">
                                {!! __('payment_entities.name_ar') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_ar_create" name="name[ar]" class="form-input-modern"
                                placeholder="{!! __('payment_entities.enter_name_ar') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_ar_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="name_en_create">
                                {!! __('payment_entities.name_en') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name_en_create" name="name[en]" class="form-input-modern"
                                placeholder="{!! __('payment_entities.enter_name_en') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text name_en_error block mt-1"></span>
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
