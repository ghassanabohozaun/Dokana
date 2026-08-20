<div class="modal fade" id="addCustomerTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="addCustomerTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-transactions.store') !!}" method="POST" enctype="multipart/form-data"
            id="add_customer_transaction_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            
            <!-- Hidden inputs for submission -->
            <input type="hidden" name="store_id" id="hidden_store_id_create">
            <input type="hidden" name="store_customer_id" id="hidden_store_customer_id_create">

            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-sm">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="addCustomerTransactionModalLabel">
                            {!! __('store_transactions.create_new_store_transaction') ?? 'إضافة حركة مالية للعميل' !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    <!-- Customer & Type Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="visible_store_customer_id_create">
                                {!! __('store_customers.store_customer') !!}
                            </label>
                            <input type="text" id="visible_store_customer_id_create" class="form-input-modern bg-slate-100 dark:bg-slate-800 text-slate-500 font-bold" disabled>
                        </div>

                        <div>
                            <label class="form-label-modern" for="transaction_type_create">
                                {!! __('store_transactions.type') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select class="form-input-modern select2" id="transaction_type_create" name="type">
                                <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                <option value="debt">{!! __('store_transactions.debt') !!} (دين عليه)</option>
                                <option value="payment">{!! __('store_transactions.payment') !!} (سداد منه)</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text type_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bank Account (Conditional for Payment) -->
                    <div class="hidden" id="transaction_bank_account_container_create">
                        <label class="form-label-modern" for="transaction_store_bank_account_id_create">
                            {!! __('bank_accounts.bank_account') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select class="form-input-modern select2" id="transaction_store_bank_account_id_create" name="store_bank_account_id">
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="transaction_amount_create">
                                {!! __('store_transactions.amount') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="transaction_amount_create" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="transaction_date_create">
                                {!! __('store_transactions.date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="transaction_date_create" name="transaction_date"
                                class="form-input-modern flatpickr-date" value="{{ date('Y-m-d') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text transaction_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="form-label-modern" for="transaction_description_create">
                            {!! __('store_transactions.description') !!}
                        </label>
                        <textarea id="transaction_description_create" name="description" rows="2"
                            class="form-input-modern" placeholder="{!! __('store_transactions.description') !!}" autocomplete="off"></textarea>
                        <span class="text-xs text-rose-500 error-text description_error block mt-1"></span>
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
    <script>
        $(document).ready(function() {
            // Toggle Bank Account visibility based on Type
            $('#transaction_type_create').on('change', function() {
                let type = $(this).val();
                let bankContainer = $('#transaction_bank_account_container_create');
                let bankSelect = $('#transaction_store_bank_account_id_create');

                if (type === 'payment') {
                    bankContainer.removeClass('hidden');
                } else {
                    bankContainer.addClass('hidden');
                    bankSelect.val('').trigger('change.select2');
                }
            });

            // Load Bank Accounts when modal is shown
            $('#addCustomerTransactionModal').on('show.bs.modal', function () {
                $('#transaction_bank_account_container_create').addClass('hidden');
                
                let bankSelect = $('#transaction_store_bank_account_id_create');
                bankSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                
                let storeId = $('#hidden_store_id_create').val();
                if (storeId) {
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: { store_id: storeId },
                        success: function(data) {
                            $.each(data, function(key, account) {
                                let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar) : '';
                                let isDefault = account.is_default ? " ({!! __('general.default') !!})" : "";
                                let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                
                                let newOption = new Option(accountName + isDefault, account.id, account.is_default, account.is_default);
                                bankSelect.append(newOption);
                            });
                            bankSelect.trigger('change.select2');
                        }
                    });
                }
            });
        });
    </script>
@endpush
