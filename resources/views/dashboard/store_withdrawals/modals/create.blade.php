<div class="modal fade" id="createStoreWithdrawalModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreWithdrawalModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-withdrawals.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_withdrawal_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-sm">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreWithdrawalModalLabel">
                            {!! __('store_withdrawals.create_new_store_withdrawal') !!}
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
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Bank Account / Wallet Select -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                            <label class="form-label-modern mb-0" for="store_bank_account_id_create">
                                {!! __('bank_accounts.bank_account') !!} <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Inline Balance Header Pill -->
                            <div id="bank_account_balance_info_create" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60 shadow-2xs transition-all">
                                <i class="fas fa-wallet text-[10px]"></i>
                                <span>{!! __('general.balance') !!}:</span>
                                <span class="balance-amount font-black font-mono" dir="ltr">0.00</span>
                            </div>
                        </div>

                        <select name="store_bank_account_id" id="store_bank_account_id_create" class="form-input-modern select2" @if(isset($stores)) disabled @endif>
                            <option value="" data-balance="0" disabled selected>@if(isset($stores)) {{ __('store_withdrawals.select_store_first') ?? 'يرجى اختيار الدكانة أولاً...' }} @else {!! __('general.select_from_list') !!} @endif</option>
                            @if(isset($bankAccounts) && !isset($stores))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $isDefault = $account->is_default ? "(" . __('general.default') . ")" : "";
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}" data-balance="{{ $account->current_balance }}" @if($account->is_default) selected @endif>{{ $accountName }} {{ $isDefault }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Withdrawal Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="amount_create">
                                    {!! __('store_withdrawals.amount') !!} <span class="text-rose-500">*</span>
                                </label>

                                <!-- Inline Remaining Balance Pill -->
                                <div id="remaining_balance_info_create" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs transition-all">
                                    <i class="fas fa-calculator text-[10px]"></i>
                                    <span>{!! __('general.remaining_balance') !!}:</span>
                                    <span class="remaining-balance-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>

                            <input type="number" id="amount_create" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="withdrawal_date_create">
                                {!! __('store_withdrawals.date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="withdrawal_date_create" name="withdrawal_date"
                                class="form-input-modern flatpickr-date" value="{{ date('Y-m-d') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text withdrawal_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="form-label-modern" for="reason_create">
                            {!! __('store_withdrawals.reason') !!} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="reason_create" name="reason"
                            class="form-input-modern" placeholder="{!! __('store_withdrawals.enter_withdrawal_reason') !!}" autocomplete="off">
                        <span class="text-xs text-rose-500 error-text reason_error block mt-1"></span>
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
            // Fetch bank accounts by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let bankAccountSelect = $('#store_bank_account_id_create');
                let infoDiv = $('#bank_account_balance_info_create');
                
                if (store_id) {
                    bankAccountSelect.empty().append('<option value="" data-balance="0" disabled selected>{!! __('general.select_from_list') !!}</option>');
                    
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, account) {
                                let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar || account.payment_entity.name) : '';
                                let isDefault = account.is_default ? " ({!! __('general.default') !!})" : "";
                                let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                
                                // Do not auto-select default (keep unselected)
                                let newOption = new Option(accountName + isDefault, account.id, false, false);
                                $(newOption).attr('data-balance', account.current_balance);
                                bankAccountSelect.append(newOption);
                            });
                            bankAccountSelect.val('').prop('disabled', false).trigger('change.select2');
                            infoDiv.addClass('hidden');
                        },
                        error: function() {
                            bankAccountSelect.val('').prop('disabled', false).trigger('change.select2');
                            infoDiv.addClass('hidden');
                        }
                    });
                } else {
                    // Reset bank account dropdown when store is cleared
                    bankAccountSelect.empty().append('<option value="" data-balance="0" disabled selected>{{ __('store_withdrawals.select_store_first') ?? 'يرجى اختيار الدكانة أولاً...' }}</option>');
                    bankAccountSelect.val('').prop('disabled', true).trigger('change.select2');
                    infoDiv.addClass('hidden');
                }
            });


            // Show balance on account change
            $('#store_bank_account_id_create').on('change', function() {
                updateCreateBalance();
            });

            // Update remaining balance on amount input
            $('#amount_create').on('input keyup', function() {
                updateCreateRemainingBalance();
            });

            function updateCreateBalance() {
                let bank_account_id = $('#store_bank_account_id_create').val();
                let infoDiv = $('#bank_account_balance_info_create');
                
                if (bank_account_id) {
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.get-balance') !!}",
                        type: 'GET',
                        data: { bank_account_id: bank_account_id },
                        success: function(response) {
                            let balance = parseFloat(response.balance);
                            $('#store_bank_account_id_create').find('option:selected').attr('data-balance', balance);
                            infoDiv.find('.balance-amount').text(balance.toFixed(2));
                            infoDiv.removeClass('hidden d-none');
                            updateCreateRemainingBalance();
                        }
                    });
                } else {
                    infoDiv.addClass('hidden');
                }
            }

            function updateCreateRemainingBalance() {
                let bank_account_id = $('#store_bank_account_id_create').val();
                let remainingPill = $('#remaining_balance_info_create');
                let amountInput = $('#amount_create');
                let withdrawalAmount = parseFloat(amountInput.val()) || 0;

                if (!bank_account_id || amountInput.val() === '') {
                    remainingPill.addClass('hidden');
                    return;
                }

                let selectedOption = $('#store_bank_account_id_create').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                let remaining = currentBalance - withdrawalAmount;
                let remainingSpan = remainingPill.find('.remaining-balance-amount');

                remainingSpan.text(remaining.toFixed(2));
                remainingPill.removeClass('hidden d-none');
                
                if (remaining < 0) {
                    remainingPill
                        .removeClass('bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border-indigo-200/80 dark:border-indigo-800/60')
                        .addClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-300 dark:border-rose-800/60 animate-pulse');
                    remainingPill.find('i').attr('class', 'fas fa-triangle-exclamation text-[10px]');
                } else {
                    remainingPill
                        .removeClass('bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border-rose-300 dark:border-rose-800/60 animate-pulse')
                        .addClass('bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border-indigo-200/80 dark:border-indigo-800/60');
                    remainingPill.find('i').attr('class', 'fas fa-calculator text-[10px]');
                }
            }

            // Instant balance rendering on modal open
            $('#createStoreWithdrawalModal').on('show.bs.modal', function () {
                let opt = $('#store_bank_account_id_create').find('option:selected');
                if (opt.length && opt.val()) {
                    let bal = parseFloat(opt.attr('data-balance')) || 0;
                    let infoDiv = $('#bank_account_balance_info_create');
                    infoDiv.find('.balance-amount').text(bal.toFixed(2));
                    infoDiv.removeClass('hidden d-none');
                    updateCreateRemainingBalance();
                }
            });
        });
    </script>
@endpush

