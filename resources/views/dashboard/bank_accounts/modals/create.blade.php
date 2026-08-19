<div class="modal fade" id="createBankAccountModal" tabindex="-1" role="dialog" aria-labelledby="createBankAccountModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.bank-accounts.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_bank_account_form" novalidate data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-university"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createBankAccountModalLabel">
                            {!! __('bank_accounts.create_new_bank_account') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    @if (isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_bank_create">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_bank_create" class="form-input-modern select2">
                            <option value="">{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Account Type (Full Width) -->
                    <div>
                        <label class="form-label-modern" for="account_type_create">
                            {!! __('bank_accounts.account_type') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="account_type" id="account_type_create" class="form-input-modern select2">
                            <option value="">{!! __('bank_accounts.select_account_type') !!}</option>
                            <option value="bank">{!! __('bank_accounts.type_bank') !!}</option>
                            <option value="wallet">{!! __('bank_accounts.type_wallet') !!}</option>
                        </select>
                        <span class="text-xs text-rose-500 error-text account_type_error block mt-1"></span>
                    </div>

                    <!-- Bank / Wallet Name Select (Full Width when visible) -->
                    <div id="bank_name_container_create" style="display: none;">
                        <label class="form-label-modern" id="bank_name_label_create" for="payment_entity_id_create">
                            {!! __('bank_accounts.bank_name') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="payment_entity_id" id="payment_entity_id_create" class="form-input-modern select2">
                            <option value="">{!! __('bank_accounts.select_bank_name') !!}</option>
                        </select>
                        <span class="text-xs text-rose-500 error-text payment_entity_id_error bank_name_ar_error bank_name_en_error block mt-1"></span>
                    </div>

                    <!-- Account Holder AR & EN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="account_holder_name_ar_create">
                                {!! __('bank_accounts.account_holder_name_ar') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="account_holder_name_ar_create" name="account_holder_name[ar]"
                                class="form-input-modern" placeholder="{!! __('bank_accounts.enter_account_holder_name_ar') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text account_holder_name_ar_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="account_holder_name_en_create">
                                {!! __('bank_accounts.account_holder_name_en') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="account_holder_name_en_create" name="account_holder_name[en]"
                                class="form-input-modern" placeholder="{!! __('bank_accounts.enter_account_holder_name_en') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text account_holder_name_en_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Account Number & IBAN -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" id="account_number_label_create" for="account_number_create">
                                {!! __('bank_accounts.account_number') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="account_number_create" name="account_number"
                                class="form-input-modern" placeholder="{!! __('bank_accounts.enter_account_number') !!}"
                                maxlength="20" oninput="this.value = this.value.replace(/[^0-9]/g, '')" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text account_number_error block mt-1"></span>
                        </div>

                        <div id="iban_container_create">
                            <label class="form-label-modern" for="iban_create">
                                {!! __('bank_accounts.iban') !!}
                            </label>
                            <input type="text" id="iban_create" name="iban"
                                class="form-input-modern" placeholder="{!! __('bank_accounts.enter_iban') !!}" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text iban_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Opening Balance -->
                    <div>
                        <label class="form-label-modern" for="opening_balance_create">
                            {!! __('bank_accounts.opening_balance') !!}
                        </label>
                        <input type="number" step="0.01" id="opening_balance_create" name="opening_balance"
                            class="form-input-modern" value="0" placeholder="{!! __('bank_accounts.enter_opening_balance') !!}" autocomplete="off">
                        <span class="text-xs text-rose-500 error-text opening_balance_error block mt-1"></span>
                    </div>

                    <!-- Is Default Switch -->
                    <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-500 text-sm">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h6 class="text-xs font-bold text-slate-800 dark:text-white">{!! __('bank_accounts.is_default') !!}</h6>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">{!! __('bank_accounts.set_as_default') !!}</span>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer select-none">
                            <input type="checkbox" class="sr-only peer" id="is_default_create" name="is_default">
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
    <script>
        $(document).ready(function() {
            var banksList = @json($banks ?? []);
            var walletsList = @json($wallets ?? []);

            $('#account_type_create').on('change', function() {
                var type = $(this).val();
                var $select = $('#payment_entity_id_create');
                var lang = $('html').attr('lang') || 'ar';
                
                if (!type) {
                    $('#bank_name_container_create').hide();
                    $select.empty().append('<option value="">{!! __("general.select_from_list") !!}</option>').trigger('change.select2');
                    return;
                }

                $select.empty().append('<option value="">' + (type === 'bank' ? '{!! __("bank_accounts.select_bank_name") !!}' : '{!! __("bank_accounts.select_wallet_name") !!}') + '</option>');
                
                var list = type === 'bank' ? banksList : walletsList;
                list.forEach(function(item) {
                    var text = lang === 'ar' ? item.ar : item.en;
                    $select.append('<option value="' + item.id + '">' + text + '</option>');
                });
                
                $('#bank_name_container_create').show();
                $select.trigger('change.select2');

                if (type === 'bank') {
                    $('#bank_name_label_create').html('{!! __("bank_accounts.bank_name") !!} <span class="text-rose-500">*</span>');
                    $('#account_number_label_create').html('{!! __("bank_accounts.account_number") !!} <span class="text-rose-500">*</span>');
                    $('#account_number_create').attr('placeholder', '{!! __("bank_accounts.enter_account_number") !!}');
                    $('#iban_container_create').show();
                } else {
                    $('#bank_name_label_create').html('{!! __("bank_accounts.wallet_name") !!} <span class="text-rose-500">*</span>');
                    $('#account_number_label_create').html('{!! __("bank_accounts.wallet_number") !!} <span class="text-rose-500">*</span>');
                    $('#account_number_create').attr('placeholder', '{!! __("bank_accounts.enter_wallet_number") !!}');
                    $('#iban_container_create').hide();
                    $('#iban_create').val('');
                }
            });
        });
    </script>
@endpush
