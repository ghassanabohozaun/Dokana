<div class="modal modal-pop" id="createStoreTransactionModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreTransactionModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.store-transactions.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_store_transaction_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createStoreTransactionModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('store_transactions.create_new_store_transaction') !!}
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

                        <!-- Customer -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_customer_id_create">{!! __('store_customers.store_customer') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_customer_id_create' name="store_customer_id">
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if(isset($customers))
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }} - {{ $customer->phone }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text store_customer_id_error"></span>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="type_create">{!! __('store_transactions.type') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none" id='type_create' name="type">
                                    <option value="" selected>{!! __('store_transactions.choose_type') !!}</option>
                                    <option value="debt">{!! __('store_transactions.debt') !!}</option>
                                    <option value="payment">{!! __('store_transactions.payment') !!}</option>
                                </select>
                                <span class="text-danger error-text type_error"></span>
                            </div>
                        </div>

                        <!-- Bank Account (Conditional) -->
                        <div class="col-md-12 mb-1 d-none" id="bank_account_container_create">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_bank_account_id_create">{!! __('bank_accounts.bank_account') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_bank_account_id_create' name="store_bank_account_id" style="width: 100%;">
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if(isset($bankAccounts) && !isset($stores))
                                        @foreach($bankAccounts as $account)
                                            @php
                                                $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                                $isDefault = $account->is_default ? "(" . __('general.default') . ")" : "";
                                                $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                            @endphp
                                            <option value="{{ $account->id }}" @if($account->is_default) selected @endif>{{ $accountName }} {{ $isDefault }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text store_bank_account_id_error"></span>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="amount_create">{!! __('store_transactions.amount') !!} <span class="text-danger">*</span></label>
                                <input type="number" id="amount_create" name="amount" step="0.01" min="0"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text amount_error"></span>
                            </div>
                        </div>

                        <!-- Transaction Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="transaction_date_create">{!! __('store_transactions.date') !!} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="transaction_date_create" name="transaction_date"
                                        class="form-control premium-input shadow-none ptc-datepicker" style="padding-left: 35px;" value="{{ date('Y-m-d') }}" autocomplete="off">
                                </div>
                                <span class="text-danger error-text transaction_date_error"></span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="description_create">{!! __('store_transactions.description') !!}</label>
                                <input type="text" id="description_create" name="description"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text description_error"></span>
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
                    dropdownParent: $('#createStoreTransactionModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#store_customer_id_create').length) {
                $('#store_customer_id_create').select2({
                    dropdownParent: $('#createStoreTransactionModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            if ($('#store_bank_account_id_create').length) {
                $('#store_bank_account_id_create').select2({
                    dropdownParent: $('#createStoreTransactionModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Fetch customers and bank accounts by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let customerSelect = $('#store_customer_id_create');
                let bankAccountSelect = $('#store_bank_account_id_create');
                
                customerSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                bankAccountSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                
                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-customers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, customer) {
                                customerSelect.append('<option value="' + customer.id + '">' + customer.name + ' - ' + (customer.phone || '') + '</option>');
                            });
                        }
                    });

                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, account) {
                                let entityName = account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar;
                                let isDefault = account.is_default ? "({!! __('general.default') !!})" : "";
                                let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                
                                let newOption = new Option(accountName + ' ' + isDefault, account.id, account.is_default, account.is_default);
                                bankAccountSelect.append(newOption).trigger('change');
                            });
                        }
                    });
                }
            });

            // Toggle Bank Account visibility based on Type
            $('#type_create').on('change', function() {
                if ($(this).val() === 'payment') {
                    $('#bank_account_container_create').removeClass('d-none');
                } else {
                    $('#bank_account_container_create').addClass('d-none');
                    $('#store_bank_account_id_create').val('').trigger('change');
                }
            });
        });
    </script>
@endpush




