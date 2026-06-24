<div class="modal modal-pop" id="createBankAccountModal" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false" aria-labelledby="createBankAccountModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.bank-accounts.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_bank_account_form' novalidate data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg premium-modal-content">
                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center"
                        id="createBankAccountModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('bank_accounts.create_new_bank_account') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        @if (isset($stores))
                            <div class="col-md-12 mb-1">
                                <div class="premium-form-group">
                                    <label class="premium-label" for="store_id_bank_create">{!! __('stores.store') !!} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control premium-input shadow-none select2"
                                        id='store_id_bank_create' name="store_id">
                                        <option value="">{!! __('general.select_from_list') !!}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text store_id_error"></span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <!-- Account Type -->
                        <div class="col-md-12 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="account_type_create">{!! __('bank_accounts.account_type') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none select2" id="account_type_create" name="account_type">
                                    <option value="" selected>{!! __('bank_accounts.select_account_type') !!}</option>
                                    <option value="bank">{!! __('bank_accounts.type_bank') !!}</option>
                                    <option value="wallet">{!! __('bank_accounts.type_wallet') !!}</option>
                                </select>
                                <span class="text-danger error-text account_type_error"></span>
                            </div>
                        </div>

                        <!-- Bank / Wallet Name Select -->
                        <div class="col-md-12 mb-2" id="bank_name_container_create" style="display: none;">
                            <div class="premium-form-group">
                                <label class="premium-label" id="bank_name_label_create" for="bank_name_select_create">{!! __('bank_accounts.bank_name') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input shadow-none select2" id="payment_entity_id_create" name="payment_entity_id">
                                    <option value="">{!! __('bank_accounts.select_bank_name') !!}</option>
                                    <!-- Options populated by JS -->
                                </select>
                                <span class="text-danger error-text bank_name_ar_error bank_name_en_error"></span>
                            </div>
                        </div>

                        <!-- Account Holder Arabic -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="account_holder_name_ar_create">{!! __('bank_accounts.account_holder_name_ar') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="account_holder_name_ar_create"
                                    name="account_holder_name[ar]" class="form-control premium-input shadow-none"
                                    autocomplete="off" placeholder="{!! __('bank_accounts.enter_account_holder_name_ar') !!}">
                                <span class="text-danger error-text account_holder_name_ar_error"></span>
                            </div>
                        </div>

                        <!-- Account Holder English -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="account_holder_name_en_create">{!! __('bank_accounts.account_holder_name_en') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="account_holder_name_en_create"
                                    name="account_holder_name[en]" class="form-control premium-input shadow-none"
                                    autocomplete="off" placeholder="{!! __('bank_accounts.enter_account_holder_name_en') !!}">
                                <span class="text-danger error-text account_holder_name_en_error"></span>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" id="account_number_label_create" for="account_number_create">{!! __('bank_accounts.account_number') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="account_number_create" name="account_number"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    placeholder="{!! __('bank_accounts.enter_account_number') !!}">
                                <span class="text-danger error-text account_number_error"></span>
                            </div>
                        </div>

                        <!-- IBAN -->
                        <div class="col-md-6 mb-2" id="iban_container_create">
                            <div class="premium-form-group">
                                <label class="premium-label" for="iban_create">{!! __('bank_accounts.iban') !!}</label>
                                <input type="text" id="iban_create" name="iban"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('bank_accounts.enter_iban') !!}">
                                <span class="text-danger error-text iban_error"></span>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <label for="is_default_create" class="premium-switch-container"
                                style="display: flex !important; justify-content: space-between !important; align-items: center !important; flex-direction: row !important; width: 100% !important;">
                                <div class="premium-switch-content"
                                    style="display: flex !important; align-items: center !important; gap: 1rem !important;">
                                    <div class="premium-switch-icon-circle text-warning shadow-sm">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="premium-switch-texts">
                                        <h6 class="premium-switch-title mb-1">{!! __('bank_accounts.is_default') !!}</h6>
                                        <span class="premium-switch-subtitle">{!! __('bank_accounts.set_as_default') !!}</span>
                                    </div>
                                </div>
                                <label class="modern-switch" style="flex-shrink: 0 !important;">
                                    <input type="checkbox" id="is_default_create" name="is_default">
                                    <span class="modern-slider"></span>
                                </label>
                            </label>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer mt-3">
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
            var banksList = @json($banks ?? []);
            var walletsList = @json($wallets ?? []);

            if ($('#store_id_bank_create').length) {
                $('#store_id_bank_create').select2({
                    dropdownParent: $('#createBankAccountModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            $('#account_type_create').select2({
                dropdownParent: $('#createBankAccountModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'ltr'
            });

            $('#payment_entity_id_create').on('change', function() {
                // We no longer need to populate hidden fields
            });

            $('#account_type_create').on('change', function() {
                var type = $(this).val();
                var $select = $('#payment_entity_id_create');
                var lang = $('html').attr('lang') || 'ar';
                
                $select.empty().append('<option value="">' + (type === 'bank' ? '{!! __("bank_accounts.select_bank_name") !!}' : '{!! __("bank_accounts.select_wallet_name") !!}') + '</option>');
                
                var list = type === 'bank' ? banksList : walletsList;
                
                list.forEach(function(item) {
                    var text = lang === 'ar' ? item.ar : item.en;
                    $select.append('<option value="' + item.id + '">' + text + '</option>');
                });
                
                $('#bank_name_container_create').show();
                
                $select.select2({
                    dropdownParent: $('#createBankAccountModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
                
                $select.trigger('change');

                if (type === 'bank') {
                    $('#bank_name_label_create').html('{!! __("bank_accounts.bank_name") !!} <span class="text-danger">*</span>');
                    $('#account_number_label_create').html('{!! __("bank_accounts.account_number") !!} <span class="text-danger">*</span>');
                    $('#account_number_create').attr('placeholder', '{!! __("bank_accounts.enter_account_number") !!}');
                    $('#iban_container_create').show();
                } else {
                    $('#bank_name_label_create').html('{!! __("bank_accounts.wallet_name") !!} <span class="text-danger">*</span>');
                    $('#account_number_label_create').html('{!! __("bank_accounts.wallet_number") !!} <span class="text-danger">*</span>');
                    $('#account_number_create').attr('placeholder', '{!! __("bank_accounts.enter_wallet_number") !!}');
                    $('#iban_container_create').hide();
                    $('#iban_create').val(''); // Clear IBAN
                }
            });
        });
    </script>
@endpush
