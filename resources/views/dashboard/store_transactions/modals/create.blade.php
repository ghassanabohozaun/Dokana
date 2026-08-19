<div class="modal fade" id="createStoreTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-transactions.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_transaction_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreTransactionModalLabel">
                            {!! __('store_transactions.create_new_store_transaction') !!}
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

                    <!-- Customer & Type Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="store_customer_id_create">
                                {!! __('store_customers.store_customer') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_customer_id" id="store_customer_id_create" class="form-input-modern select2" @if(isset($stores)) disabled @endif>
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                @if(isset($customers) && !isset($stores))
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_customer_id_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="type_create">
                                {!! __('store_transactions.type') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="type" id="type_create" class="form-input-modern">
                                <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                <option value="debt">{!! __('store_transactions.debt') !!} (دين عليه)</option>
                                <option value="payment">{!! __('store_transactions.payment') !!} (سداد منه)</option>
                            </select>
                            <span class="text-xs text-rose-500 error-text type_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Bank Account (Conditional for Payment) -->
                    <div class="hidden" id="bank_account_container_create">
                        <label class="form-label-modern" for="store_bank_account_id_create">
                            {!! __('bank_accounts.bank_account') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_bank_account_id" id="store_bank_account_id_create" class="form-input-modern select2">
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
                            @if(isset($bankAccounts) && !isset($stores))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = (optional($account->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $isDefault = $account->is_default ? "(" . __('general.default') . ")" : "";
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}" @if($account->is_default) selected @endif>{{ $accountName }} {{ $isDefault }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Transaction Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="amount_create">
                                {!! __('store_transactions.amount') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="amount_create" name="amount" step="0.01" min="0"
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
                        <label class="form-label-modern" for="description_create">
                            {!! __('store_transactions.description') !!}
                        </label>
                        <textarea id="description_create" name="description" rows="2"
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
            // Fetch customers and bank accounts by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let customerSelect = $('#store_customer_id_create');
                let bankAccountSelect = $('#store_bank_account_id_create');
                
                customerSelect.empty().append('<option value="" disabled selected>{!! __('general.select_from_list') !!}</option>');
                bankAccountSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                
                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-customers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, customer) {
                                let newOption = new Option(customer.name + ' - ' + (customer.phone || ''), customer.id, false, false);
                                customerSelect.append(newOption);
                            });
                            customerSelect.prop('disabled', false).trigger('change.select2');
                        },
                        error: function() {
                            customerSelect.prop('disabled', false).trigger('change.select2');
                        }
                    });

                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, account) {
                                let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar) : '';
                                let isDefault = account.is_default ? " ({!! __('general.default') !!})" : "";
                                let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                
                                let newOption = new Option(accountName + isDefault, account.id, account.is_default, account.is_default);
                                bankAccountSelect.append(newOption);
                            });
                            bankAccountSelect.trigger('change.select2');
                        }
                    });
                } else {
                    customerSelect.prop('disabled', true).trigger('change.select2');
                }
            });

            // Toggle Bank Account visibility based on Type
            $('#type_create').on('change', function() {
                if ($(this).val() === 'payment') {
                    $('#bank_account_container_create').removeClass('hidden');
                } else {
                    $('#bank_account_container_create').addClass('hidden');
                    $('#store_bank_account_id_create').val('').trigger('change.select2');
                }
            });
        });
    </script>
@endpush
