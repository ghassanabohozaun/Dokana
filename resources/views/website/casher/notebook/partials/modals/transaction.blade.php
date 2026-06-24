    <!-- Add Transaction Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'transactionModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'transactionModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
        <div x-show="show" x-transition.translate.y.bottom class="relative bg-white dark:bg-darkCard w-full max-w-md rounded-t-[2rem] sm:rounded-3xl p-6 shadow-2xl border border-white/10 z-10">
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 sm:hidden"></div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="ph-fill text-2xl" :class="txType === 'debt' ? 'ph-minus-circle text-red-500' : 'ph-plus-circle text-emerald-500'"></i>
                    <span x-text="editingTxId ? (txType === 'debt' ? '{{ __('notebook.edit_debt') }}' : '{{ __('notebook.edit_payment') }}') : (txType === 'debt' ? '{{ __('notebook.add_new_debt') }}' : '{{ __('notebook.add_new_payment') }}')"></span>
                </h2>
                <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <form @submit.prevent="saveTransaction()" class="space-y-5">
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.amount_currency') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input x-model="txAmount" type="number" required min="0.01" step="0.01" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl pe-12 ps-4 py-4 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-3xl font-black transition-all text-gray-900 dark:text-white text-start" placeholder="0.00">
                        <span class="absolute end-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-lg">₪</span>
                    </div>
                </div>
                
                <div x-show="txType === 'payment'">
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('bank_accounts.bank_account') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-primary text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                            <i class="ph-bold ph-bank"></i>
                        </div>
                        <select x-model="txBankAccountId" :required="txType === 'payment'" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-gray-900 dark:text-white font-medium appearance-none {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}">
                            <option value="">{{ __('general.select_from_list') }}</option>
                            @foreach($storeBankAccounts as $account)
                                @php
                                    $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                    $isDefault = $account->is_default ? "(" . __('bank_accounts.is_default') . ")" : "";
                                    $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                @endphp
                                <option value="{{ $account->id }}" {{ $account->is_default ? 'selected' : '' }}>{{ $accountName }} {{ $isDefault }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.transaction_date') }} <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <!-- Icon -->
                        <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-primary text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                            <i class="ph-bold ph-calendar"></i>
                        </div>
                        <!-- Input -->
                        <input type="text" 
                               required 
                               x-model="txDate"
                               x-init="
                                   flatpickr($el, {
                                       dateFormat: 'Y-m-d',
                                       locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
                                       disableMobile: true,
                                       onChange: function(selectedDates, dateStr, instance) {
                                           txDate = dateStr;
                                           $el.dispatchEvent(new Event('input'));
                                       }
                                   });
                               "
                               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-gray-900 dark:text-white font-medium cursor-pointer {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.notes_optional') }}</label>
                    <textarea x-model="txDescription" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white resize-none" placeholder="{{ __('notebook.example_notes') }}"></textarea>
                </div>
                <button type="submit" class="w-full text-white font-bold rounded-xl py-4 mt-4 transition-all active:scale-[0.98] shadow-lg flex items-center justify-center gap-2 focus:ring-4 focus:outline-none overflow-hidden relative group"
                        :class="txType === 'debt' ? 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 shadow-red-500/30 ring-red-500/50' : 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-emerald-500/30 ring-emerald-500/50'">
                    <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                    <span class="relative" x-text="editingTxId ? '{{ __('notebook.update') }}' : '{{ __('notebook.register') }}'"></span>
                </button>
            </form>
        </div>
    </div>
