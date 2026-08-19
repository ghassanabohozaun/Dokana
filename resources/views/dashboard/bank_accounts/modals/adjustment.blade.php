<div class="modal fade" id="adjustBankAccountModal" tabindex="-1" role="dialog" aria-labelledby="adjustBankAccountModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered max-w-md mx-auto" role="document" style="max-width: 480px;">
        <form class="ajax-form w-full" action="{!! route('dashboard.bank-accounts.adjust') !!}" method="POST"
            id="adjust_bank_account_form" novalidate data-success-msg="{!! __('bank_accounts.reconciliation_successful') !!}"
            data-success-action="reload-table" data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
                
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-sm">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="adjustBankAccountModalLabel">
                                {!! __('bank_accounts.adjust_balance') !!}
                            </h4>
                            <p class="text-[11px] text-slate-400 dark:text-slate-500">تسوية الفارق بين رصيد النظام والرصيد الفعلي</p>
                        </div>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <input type="hidden" name="store_bank_account_id" id="adjust_store_bank_account_id">
                    
                    <!-- System Current Balance Card -->
                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block mb-0.5">
                                {!! __('bank_accounts.system_balance') !!}
                            </span>
                            <span class="text-[11px] text-slate-400">الرصيد الدفتري المسجل</span>
                        </div>
                        <div class="text-lg font-black text-indigo-600 dark:text-indigo-400" dir="ltr" id="current_system_balance">
                            0.00
                        </div>
                    </div>

                    <!-- Actual Balance Input -->
                    <div>
                        <label class="form-label-modern" for="actual_balance">
                            {!! __('bank_accounts.actual_balance') !!} <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" id="actual_balance" name="actual_balance"
                            class="form-input-modern" required placeholder="{!! __('bank_accounts.enter_actual_balance') !!}" autocomplete="off">
                        <span class="text-xs text-rose-500 error-text actual_balance_error block mt-1"></span>
                    </div>

                    <!-- Live Difference Indicator Badge (Appears when typed) -->
                    <div id="diff_indicator_container" class="hidden p-3 rounded-xl border text-xs font-semibold flex items-center justify-between transition-all">
                        <span id="diff_label">الفارق المتوقع:</span>
                        <span id="diff_value" class="font-black" dir="ltr">0.00</span>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label-modern" for="adjust_notes">{!! __('general.notes') !!}</label>
                        <textarea id="adjust_notes" name="notes" rows="2" class="form-input-modern resize-none"
                            placeholder="{!! __('bank_accounts.enter_adjustment_notes') !!}"></textarea>
                        <span class="text-xs text-rose-500 error-text notes_error block mt-1"></span>
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
        $('#actual_balance').on('input', function() {
            let actual = parseFloat($(this).val());
            let current = parseFloat($('#current_system_balance').text());
            let $diffBox = $('#diff_indicator_container');
            let $diffLabel = $('#diff_label');
            let $diffValue = $('#diff_value');

            if (isNaN(actual)) {
                $diffBox.addClass('hidden');
                return;
            }

            let diff = actual - current;
            $diffBox.removeClass('hidden bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-400 bg-rose-50 border-rose-200 text-rose-700 dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-400 bg-slate-50 border-slate-200 text-slate-700');

            if (diff > 0) {
                $diffBox.addClass('bg-emerald-50 border-emerald-200 text-emerald-700 dark:bg-emerald-950/40 dark:border-emerald-800 dark:text-emerald-400');
                $diffLabel.text("فائض (زيادة بالرصيد):");
                $diffValue.text("+" + diff.toFixed(2));
            } else if (diff < 0) {
                $diffBox.addClass('bg-rose-50 border-rose-200 text-rose-700 dark:bg-rose-950/40 dark:border-rose-800 dark:text-rose-400');
                $diffLabel.text("عجز (نقص بالرصيد):");
                $diffValue.text(diff.toFixed(2));
            } else {
                $diffBox.addClass('bg-slate-50 border-slate-200 text-slate-700');
                $diffLabel.text("الرصيد مطابق تماماً:");
                $diffValue.text("0.00");
            }
        });
    });
</script>
@endpush
