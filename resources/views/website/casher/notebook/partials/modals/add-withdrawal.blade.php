<!-- Add Withdrawal Drawer -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'withdrawalModal') show = true"
     x-on:close-modal.window="if ($event.detail.id === 'withdrawalModal') show = false"
     style="display: none;"
     class="fixed inset-0 z-50 flex" x-cloak>
     
    <!-- Backdrop -->
    <div x-show="show" 
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="drawer-backdrop" 
         x-on:click="show = false"></div>
         
    <!-- Drawer Panel -->
    <div x-show="show" 
         x-transition:enter="transform transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="drawer-panel p-6 overflow-y-auto">
         
        <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 md:hidden"></div>
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-black mb-1 flex items-center gap-2">
                <i class="ph-bold ph-hand-coins text-red-500"></i>
                <span x-text="isEditingWithdrawal ? '{{ __('notebook.edit_withdrawal') ?? 'تعديل السحب' }}' : '{{ __('notebook.add_withdrawal') ?? 'إضافة سحب جديد' }}'"></span>
            </h2>
            <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>
        
        <p class="text-xs text-gray-500 mb-4 -mt-4">
            {{ __('notebook.withdrawal_subtitle') ?? 'سجل مصروفات ومسحوبات اليوم' }}
        </p>
        
        <form @submit.prevent="submitWithdrawal()" novalidate class="space-y-4 flex-1 flex flex-col">
            <!-- Bank Account Dropdown -->
            @if(count($storeBankAccounts) > 0)
            <div x-data="{ openBankSelect: false }">
                <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                    {{ __('notebook.bank_account') ?? 'حساب الدفع' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <!-- Icon -->
                    <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-gray-400 text-lg {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                        <i class="ph-bold ph-caret-down transition-transform duration-75" :class="openBankSelect ? 'rotate-180 text-red-500' : ''"></i>
                    </div>
                    
                    <!-- Custom Select Trigger -->
                    <button type="button" 
                            @click="openBankSelect = !openBankSelect" 
                            @click.away="openBankSelect = false" 
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 outline-none text-gray-900 dark:text-white font-medium flex items-center transition-all hover:border-red-500/50 {{ app()->getLocale() == 'ar' ? 'pr-4 pl-12 text-right' : 'pl-4 pr-12 text-left' }}">
                        
                        <!-- Display Name -->
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-bank text-red-500/70 text-lg"></i>
                            <span x-text="withdrawalBankAccountId ? casherConfig.storeAccounts.find(a => a.id == withdrawalBankAccountId)?.name : '{{ __('general.select_from_list') }}'"></span>
                        </div>
                    </button>
                    
                    <!-- Hidden Select (For validation/model binding) -->
                    <select x-model="withdrawalBankAccountId" class="sr-only">
                        <option value="">{{ __('general.select_from_list') }}</option>
                        @foreach($storeBankAccounts as $account)
                            <option value="{{ $account->id }}"></option>
                        @endforeach
                    </select>

                    <!-- Custom Dropdown Menu -->
                    <div x-show="openBankSelect" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-2"
                         class="absolute z-[120] w-full mt-2 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 py-2 max-h-60 overflow-y-auto custom-scrollbar" x-cloak>
                         
                         <button type="button" @click="withdrawalBankAccountId = ''; openBankSelect = false;" class="w-full text-start px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors text-gray-500 font-bold border-b dark:border-gray-100 dark:border-opacity-5">
                             {{ __('general.select_from_list') }}
                         </button>
                         
                         @foreach($storeBankAccounts as $account)
                             @php
                                 $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                 $isDefault = $account->is_default ? " <span class='text-[10px] bg-red-500/10 text-red-500 px-2 py-0.5 rounded-full mx-1'>الأساسي</span>" : "";
                                 $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                             @endphp
                             <button type="button" 
                                     @click="withdrawalBankAccountId = '{{ $account->id }}'; openBankSelect = false;" 
                                     class="w-full text-start px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex items-center justify-between border-b dark:border-gray-100 dark:border-opacity-5 last:border-0"
                                     :class="withdrawalBankAccountId == '{{ $account->id }}' ? 'bg-red-500/5 text-red-600' : 'text-gray-700 dark:text-gray-300'">
                                 
                                 <div class="flex items-center gap-3">
                                     <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0 transition-colors" :class="withdrawalBankAccountId == '{{ $account->id }}' ? 'bg-red-500 text-white shadow-red-500/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'">
                                        @if($account->account_type === 'cash')
                                            <i class="ph-bold ph-money text-lg"></i>
                                        @else
                                            <i class="ph-bold ph-bank text-lg"></i>
                                        @endif
                                     </div>
                                     <div class="flex flex-col">
                                        <span class="font-bold text-sm">{!! addslashes($accountName) !!}</span>
                                        @if($account->is_default)
                                            <span class="text-[10px] text-red-500 font-bold mt-0.5">{{ __('bank_accounts.is_default') ?? 'الأساسي' }}</span>
                                        @endif
                                     </div>
                                 </div>
                                 
                                 <div x-show="withdrawalBankAccountId == '{{ $account->id }}'" class="w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center shadow-sm" x-transition.scale>
                                    <i class="ph-bold ph-check text-xs"></i>
                                 </div>
                             </button>
                         @endforeach
                    </div>
                </div>
                
                <!-- Live Balance Reactivity Box -->
                <div x-show="selectedBankBalance !== null" x-transition.opacity style="display: none;" class="mt-3 p-3 rounded-xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700 transition-all duration-75">
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
            <div class="pt-4 mt-auto">
                <button type="submit" 
                    :disabled="isWithdrawalExceeding || isSavingWithdrawal" 
                    :class="isWithdrawalExceeding ? 'opacity-50 cursor-not-allowed from-gray-400 to-gray-500' : 'from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 shadow-[0_8px_20px_rgba(239,68,68,0.3)] disabled:opacity-70 disabled:cursor-not-allowed'"
                    class="w-full bg-gradient-to-r text-white font-bold rounded-xl py-3.5 flex items-center justify-center gap-2 transition-all">
                    <i x-show="isSavingWithdrawal" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                    <i x-show="!isSavingWithdrawal" class="ph-bold ph-check-circle text-lg"></i> 
                    <span x-show="!isSavingWithdrawal">{{ __('notebook.save') ?? 'حفظ السحب' }}</span>
                    <span x-show="isSavingWithdrawal">{{ __('notebook.saving') ?? 'جاري الحفظ...' }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
