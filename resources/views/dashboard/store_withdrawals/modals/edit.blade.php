<div class="modal fade" id="updateStoreWithdrawalModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreWithdrawalModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_withdrawal_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
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
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreWithdrawalModalLabel">
                            {!! __('store_withdrawals.update_store_withdrawal') !!}
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

                    <!-- Bank Account / Wallet Select -->
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                            <label class="form-label-modern mb-0" for="store_bank_account_id_edit">
                                {!! __('bank_accounts.bank_account') !!} <span class="text-rose-500">*</span>
                            </label>
                            
                            <!-- Inline Balance Header Pill -->
                            <div id="bank_account_balance_info_edit" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200/80 dark:border-emerald-800/60 shadow-2xs transition-all">
                                <i class="fas fa-wallet text-[10px]"></i>
                                <span>{!! __('general.balance') !!}:</span>
                                <span class="balance-amount font-black font-mono" dir="ltr">0.00</span>
                            </div>
                        </div>

                        <select name="store_bank_account_id" id="store_bank_account_id_edit" class="form-input-modern select2">
                            <option value="" data-balance="0" disabled>{!! __('general.select_from_list') !!}</option>
                            @if(isset($bankAccounts))
                                @foreach($bankAccounts as $account)
                                    @php
                                        $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                    @endphp
                                    <option value="{{ $account->id }}" data-balance="{{ $account->current_balance }}">{{ $accountName }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="text-xs text-rose-500 error-text store_bank_account_id_error block mt-1"></span>
                    </div>

                    <!-- Amount & Withdrawal Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="amount_edit">
                                    {!! __('store_withdrawals.amount') !!} <span class="text-rose-500">*</span>
                                </label>

                                <!-- Inline Remaining Balance Pill -->
                                <div id="remaining_balance_info_edit" class="hidden inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200/80 dark:border-indigo-800/60 shadow-2xs transition-all">
                                    <i class="fas fa-calculator text-[10px]"></i>
                                    <span>{!! __('general.remaining_balance') !!}:</span>
                                    <span class="remaining-balance-amount font-black font-mono" dir="ltr">0.00</span>
                                </div>
                            </div>

                            <input type="number" id="amount_edit" name="amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="withdrawal_date_edit">
                                {!! __('store_withdrawals.date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="withdrawal_date_edit" name="withdrawal_date"
                                class="form-input-modern flatpickr-date" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text withdrawal_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="form-label-modern" for="reason_edit">
                            {!! __('store_withdrawals.reason') !!} <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" id="reason_edit" name="reason"
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
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation
            $(document).on('click', '.editStoreWithdrawalBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let store_withdrawal_id = $btn.data('id');
                let store_withdrawal_amount = $btn.data('amount');
                let store_withdrawal_reason = $btn.data('reason');
                let store_withdrawal_store_id = $btn.data('store_id');
                let store_withdrawal_date = $btn.data('withdrawal_date');
                let store_withdrawal_bank_account_id = $btn.data('store_bank_account_id');
                let store_withdrawal_bank_account_name = $btn.data('bank_account_name');
                let store_withdrawal_bank_account_balance = $btn.data('bank_account_balance');

                // Populate form fields
                $('#id_edit').val(store_withdrawal_id);
                $('#amount_edit').val(store_withdrawal_amount);
                $('#amount_edit').attr('data-original-amount', store_withdrawal_amount);
                $('#amount_edit').attr('data-original-bank-account-id', store_withdrawal_bank_account_id);
                $('#reason_edit').val(store_withdrawal_reason);
                
                // Set Flatpickr date or input value
                let dateInput = document.querySelector('#withdrawal_date_edit');
                if (dateInput && dateInput._flatpickr) {
                    dateInput._flatpickr.setDate(store_withdrawal_date, true);
                } else {
                    $('#withdrawal_date_edit').val(store_withdrawal_date);
                }

                // Populate Select2 for Bank Account
                if ($('#store_bank_account_id_edit').length) {
                    if (store_withdrawal_bank_account_id) {
                        if ($('#store_bank_account_id_edit').find("option[value='" + store_withdrawal_bank_account_id + "']").length == 0) {
                            let newOpt = new Option(store_withdrawal_bank_account_name, store_withdrawal_bank_account_id, true, true);
                            $(newOpt).attr('data-balance', store_withdrawal_bank_account_balance);
                            $('#store_bank_account_id_edit').append(newOpt);
                        } else {
                            $('#store_bank_account_id_edit').find("option[value='" + store_withdrawal_bank_account_id + "']").attr('data-balance', store_withdrawal_bank_account_balance);
                        }
                        $('#store_bank_account_id_edit').val(store_withdrawal_bank_account_id).trigger('change.select2');
                    } else {
                        $('#store_bank_account_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_withdrawal_store_id) {
                        $('#store_id_dept_edit').val(store_withdrawal_store_id).trigger('change.select2');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                    }
                }

                // Immediately calculate and render balance and remaining balance (0ms lag)
                let initialBalance = parseFloat(store_withdrawal_bank_account_balance) || 0;
                let infoDiv = $('#bank_account_balance_info_edit');
                infoDiv.find('.balance-amount').text(initialBalance.toFixed(2));
                infoDiv.removeClass('hidden');
                updateEditRemainingBalance();

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-withdrawals.update', ':id') !!}".replace(':id', store_withdrawal_id);
                $('#update_store_withdrawal_form').attr('action', url);
                
                $('#update_store_withdrawal_form').find('.error-text').text('');
                $('#update_store_withdrawal_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreWithdrawalModal').modal('show');

            });

            // Fetch bank accounts by store on change
            $('#store_id_dept_edit').on('change', function(e) {
                if (!e.isTrigger || e.type !== 'change') {
                    let store_id = $(this).val();
                    let bankAccountSelect = $('#store_bank_account_id_edit');
                    
                    bankAccountSelect.empty().append('<option value="" data-balance="0" disabled selected>{!! __('general.select_from_list') !!}</option>');
                    
                    if (store_id) {
                        $.ajax({
                            url: "{!! route('dashboard.bank-accounts.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, account) {
                                    let entityName = (account.payment_entity && account.payment_entity.name) ? (account.payment_entity.name["{!! app()->getLocale() !!}"] || account.payment_entity.name.ar || account.payment_entity.name) : '';
                                    let accountName = account.account_type === 'cash' ? entityName : entityName + ' - ' + account.account_number;
                                    let newOption = new Option(accountName, account.id, false, false);
                                    $(newOption).attr('data-balance', account.current_balance);
                                    bankAccountSelect.append(newOption);
                                });
                                bankAccountSelect.prop('disabled', false).trigger('change.select2');
                            },
                            error: function() {
                                bankAccountSelect.prop('disabled', false).trigger('change.select2');
                            }
                        });
                    }
                }
            });


            // Show balance on account change
            $('#store_bank_account_id_edit').on('change', function() {
                updateEditBalance();
            });

            // Update remaining balance on amount input
            $('#amount_edit').on('input keyup', function() {
                updateEditRemainingBalance();
            });

            function updateEditBalance() {
                let bank_account_id = $('#store_bank_account_id_edit').val();
                let infoDiv = $('#bank_account_balance_info_edit');
                
                if (bank_account_id) {
                    $.ajax({
                        url: "{!! route('dashboard.bank-accounts.get-balance') !!}",
                        type: 'GET',
                        data: { bank_account_id: bank_account_id },
                        success: function(response) {
                            let balance = parseFloat(response.balance);
                            $('#store_bank_account_id_edit').find('option:selected').attr('data-balance', balance);
                            infoDiv.find('.balance-amount').text(balance.toFixed(2));
                            infoDiv.removeClass('hidden d-none');
                            updateEditRemainingBalance();
                        }
                    });
                } else {
                    infoDiv.addClass('hidden');
                }
            }

            function updateEditRemainingBalance() {
                let bank_account_id = $('#store_bank_account_id_edit').val();
                let remainingPill = $('#remaining_balance_info_edit');
                let amountInput = $('#amount_edit');
                let withdrawalAmount = parseFloat(amountInput.val()) || 0;

                if (!bank_account_id || amountInput.val() === '') {
                    remainingPill.addClass('hidden');
                    return;
                }

                let selectedOption = $('#store_bank_account_id_edit').find('option:selected');
                let currentBalance = parseFloat(selectedOption.attr('data-balance')) || 0;
                
                let originalAmount = parseFloat($('#amount_edit').attr('data-original-amount')) || 0;
                let originalBankAccountId = $('#amount_edit').attr('data-original-bank-account-id');
                
                let trueAvailableBalance = currentBalance;
                if (originalBankAccountId == $('#store_bank_account_id_edit').val()) {
                    trueAvailableBalance += originalAmount;
                }
                
                let remaining = trueAvailableBalance - withdrawalAmount;
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

            // Refresh balance when modal is shown
            $('#updateStoreWithdrawalModal').on('shown.bs.modal', function () {
                if ($('#store_bank_account_id_edit').val()) {
                    updateEditBalance();
                }
            });
        });
    </script>
@endpush
