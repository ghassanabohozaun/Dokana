<div class="modal fade" id="updateStoreTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_transaction_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
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
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreTransactionModalLabel">
                            {!! __('store_transactions.update_store_transaction') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" id="id_edit" name="id">
                    <input type="hidden" name="store_id" id="hidden_store_id_edit">
                    <input type="hidden" name="store_customer_id" id="hidden_store_customer_id_edit">

                    @if(isset($stores))
                    <!-- Store Select (for admin - disabled visual) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_edit">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select id="store_id_dept_edit" class="form-input-modern select2" disabled>
                            <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Customer & Type Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="store_customer_id_edit">
                                {!! __('store_customers.store_customer') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select id="store_customer_id_edit" class="form-input-modern select2" disabled>
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                @if(isset($customers))
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_customer_id_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="type_edit">
                                {!! __('store_transactions.type') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="type" id="type_edit" class="form-input-modern">
                                <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                <option value="debt">{!! __('store_transactions.debt') !!} (دين عليه)</option>
                                <option value="payment">{!! __('store_transactions.payment') !!} (سداد منه)</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text type_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bank Account (Conditional for Payment) -->
                    <div class="hidden" id="bank_account_container_edit">
                        <label class="form-label-modern" for="store_bank_account_id_edit">
                            {!! __('bank_accounts.bank_account') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_bank_account_id" id="store_bank_account_id_edit" class="form-input-modern select2">
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
                            @if(isset($bankAccounts))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = (optional($account->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}">{{ $accountName }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Transaction Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="amount_edit">
                                {!! __('store_transactions.amount') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="amount_edit" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="transaction_date_edit">
                                {!! __('store_transactions.date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="transaction_date_edit" name="transaction_date"
                                class="form-input-modern flatpickr-date" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text transaction_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="form-label-modern" for="description_edit">
                            {!! __('store_transactions.description') !!}
                        </label>
                        <textarea id="description_edit" name="description" rows="2"
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation
            $(document).on('click', '.editStoreTransactionBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let transaction_id = $btn.data('id');
                let customer_id = $btn.data('store_customer_id');
                let customer_name = $btn.data('customer_name');
                let store_id = $btn.data('store_id');
                let type = $btn.data('type');
                let amount = $btn.data('amount');
                let description = $btn.data('description');
                let bank_account_id = $btn.data('bank_account_id');
                let bank_account_name = $btn.data('bank_account_name');
                let date = $btn.data('date');

                // Populate form fields
                $('#id_edit').val(transaction_id);
                $('#hidden_store_id_edit').val(store_id);
                $('#hidden_store_customer_id_edit').val(customer_id);
                $('#amount_edit').val(amount);
                $('#description_edit').val(description);
                $('#type_edit').val(type);

                // Set Flatpickr date or input value
                let dateInput = document.querySelector('#transaction_date_edit');
                if (dateInput && dateInput._flatpickr) {
                    dateInput._flatpickr.setDate(date, true);
                } else {
                    $('#transaction_date_edit').val(date);
                }

                // Handle Bank Account Container
                if (type === 'payment') {
                    $('#bank_account_container_edit').removeClass('hidden');
                } else {
                    $('#bank_account_container_edit').addClass('hidden');
                }

                // Populate Customer Select2
                if ($('#store_customer_id_edit').length) {
                    if (customer_id) {
                        if ($('#store_customer_id_edit').find("option[value='" + customer_id + "']").length == 0) {
                            let newOpt = new Option(customer_name, customer_id, true, true);
                            $('#store_customer_id_edit').append(newOpt);
                        }
                        $('#store_customer_id_edit').val(customer_id).trigger('change.select2');
                    }
                }

                // Populate Bank Account Select2
                if ($('#store_bank_account_id_edit').length) {
                    if (bank_account_id) {
                        if ($('#store_bank_account_id_edit').find("option[value='" + bank_account_id + "']").length == 0) {
                            let newOpt = new Option(bank_account_name, bank_account_id, true, true);
                            $('#store_bank_account_id_edit').append(newOpt);
                        }
                        $('#store_bank_account_id_edit').val(bank_account_id).trigger('change.select2');
                    } else {
                        $('#store_bank_account_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Populate Store Select2
                if ($('#store_id_dept_edit').length) {
                    if (store_id) {
                        $('#store_id_dept_edit').val(store_id).trigger('change.select2');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-transactions.update', ':id') !!}".replace(':id', transaction_id);
                $('#update_store_transaction_form').attr('action', url);
                
                $('#update_store_transaction_form').find('.error-text').text('');
                $('#update_store_transaction_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreTransactionModal').modal('show');
            });

            // Toggle Bank Account visibility based on Type in edit modal
            $('#type_edit').on('change', function() {
                if ($(this).val() === 'payment') {
                    $('#bank_account_container_edit').removeClass('hidden');
                } else {
                    $('#bank_account_container_edit').addClass('hidden');
                    $('#store_bank_account_id_edit').val('').trigger('change.select2');
                }
            });
        });
    </script>
@endpush
