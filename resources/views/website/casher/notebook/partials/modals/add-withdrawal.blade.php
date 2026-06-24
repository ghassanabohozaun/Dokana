<!-- Add Withdrawal Modal (Alpine.js Style) -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'withdrawalModal') show = true"
     x-on:close-modal.window="if ($event.detail.id === 'withdrawalModal') show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex items-end sm:items-center justify-center">
     
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
    
    <div x-show="show" x-transition.translate.y.bottom class="relative bg-white dark:bg-darkCard w-full max-w-md rounded-t-[2rem] sm:rounded-3xl p-6 shadow-2xl border border-white/10 z-10">
        <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 sm:hidden"></div>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black mb-1 flex items-center gap-2">
            <i class="ph-bold ph-hand-coins text-red-500"></i>
            <span x-text="isEditingWithdrawal ? 'تعديل السحب' : '{{ __('notebook.add_withdrawal') ?? 'إضافة سحب جديد' }}'"></span>
        </h2>
            <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        
        <p class="text-xs text-gray-500 mb-4 -mt-4">
            {{ __('notebook.withdrawal_subtitle') ?? 'سجل مصروفات ومسحوبات اليوم' }}
        </p>
        
        <form @submit.prevent="submitWithdrawal()" class="space-y-4">
            <!-- Bank Account Dropdown -->
            @if(count($storeBankAccounts) > 0)
            <div>
                <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                    {{ __('notebook.bank_account') ?? 'حساب الدفع' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-red-500 text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                        <i class="ph-bold ph-bank"></i>
                    </div>
                    <select x-model="withdrawalBankAccountId" 
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none text-gray-900 dark:text-white font-medium appearance-none {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}" required>
                        <option value="">-- {{ __('notebook.select_account') ?? 'إختر حساب الدفع' }} --</option>
                        @foreach($storeBankAccounts as $account)
                            @php
                                $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                $isDefault = $account->is_default ? "(" . __('bank_accounts.is_default') . ")" : "";
                                $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                            @endphp
                            <option value="{{ $account->id }}" {{ $account->is_default ? 'selected' : '' }}>
                                {{ $accountName }} {{ $isDefault }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Live Balance Reactivity Box -->
                <div x-show="selectedBankBalance !== null" x-transition.opacity style="display: none;" class="mt-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700 transition-all duration-300">
                    <div class="flex justify-between items-center text-sm mb-2">
                        <span class="text-gray-500 font-medium">{{ __('notebook.available_balance') ?? 'الرصيد المتوفر:' }}</span>
                        <span class="font-bold text-gray-900 dark:text-white" x-text="Number(selectedBankBalance || 0).toFixed(2) + ' ₪'"></span>
                    </div>
                    <div class="flex justify-between items-center text-sm border-t border-gray-200 dark:border-gray-700 pt-2">
                        <span class="text-gray-500 font-medium">{{ __('notebook.remaining_balance') ?? 'الرصيد المتبقي بعد السحب:' }}</span>
                        <span class="font-bold" 
                              :class="isWithdrawalExceeding ? 'text-red-500' : 'text-green-500'" 
                              x-text="Number(remainingBalance || 0).toFixed(2) + ' ₪'"></span>
                    </div>
                    <p x-show="isWithdrawalExceeding" style="display: none;" class="text-xs text-red-500 font-bold mt-2 flex items-center gap-1 bg-red-50 dark:bg-red-500/10 p-2 rounded-lg">
                        <i class="ph-fill ph-warning"></i> {{ __('notebook.amount_exceeds_balance') ?? 'عذراً، المبلغ المطلوب سحبه أكبر من الرصيد المتوفر!' }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Amount Field -->
            <div>
                <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                    {{ __('notebook.amount') ?? 'المبلغ' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center pointer-events-none text-red-500">
                        <i class="ph-bold ph-hand-coins text-xl"></i>
                    </div>
                    <input type="number" step="0.01" x-model="withdrawalAmount" 
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }} py-3.5 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all font-bold text-lg text-gray-900 dark:text-white" 
                        placeholder="0.00" required>
                </div>
            </div>

            <!-- Reason Field -->
            <div>
                <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                    {{ __('notebook.withdrawal_reason') ?? 'سبب السحب / المستفيد' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center pointer-events-none text-gray-400">
                        <i class="ph-bold ph-wallet text-xl"></i>
                    </div>
                    <input type="text" x-model="withdrawalReason" 
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }} py-3.5 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white" 
                        placeholder="{{ __('notebook.withdrawal_reason_placeholder') ?? 'مثال: مندوب كوكاكولا...' }}" required>
                </div>
            </div>

            <!-- Date Field -->
            <div>
                <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                    {{ __('notebook.date') ?? 'التاريخ' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center pointer-events-none text-red-500">
                        <i class="ph-bold ph-calendar text-xl"></i>
                    </div>
                    <input type="text" x-model="withdrawalDate" 
                        x-init="
                            flatpickr($el, {
                                dateFormat: 'Y-m-d',
                                locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
                                disableMobile: true,
                                onChange: function(selectedDates, dateStr, instance) {
                                    withdrawalDate = dateStr;
                                    $el.dispatchEvent(new Event('input'));
                                }
                            });
                        "
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }} py-3.5 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none font-medium text-gray-900 dark:text-white cursor-pointer" 
                        required>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                :disabled="isWithdrawalExceeding || isLoading" 
                :class="isWithdrawalExceeding ? 'opacity-50 cursor-not-allowed from-gray-400 to-gray-500' : 'from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 shadow-[0_8px_20px_rgba(239,68,68,0.3)]'"
                class="w-full bg-gradient-to-r text-white font-bold rounded-xl py-3.5 mt-4 flex items-center justify-center gap-2 transition-all">
                <i class="ph-bold ph-check-circle text-lg"></i> <span x-text="isLoading ? '{{ __('notebook.saving') ?? 'جاري الحفظ...' }}' : '{{ __('notebook.save') ?? 'حفظ السحب' }}'"></span>
            </button>
        </form>
    </div>
</div>
