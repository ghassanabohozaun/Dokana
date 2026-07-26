    <!-- Add Transaction Overlay -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'transactionModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'transactionModal') show = false"
         style="display: none; z-index: 110;"
         class="overlay-panel flex justify-center"
         x-transition:enter="transform transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         x-cloak>
         
        <div class="w-full md:max-w-3xl min-h-screen flex flex-col bg-gray-50 dark:bg-[#0b1121] shadow-2xl relative">
            <div class="flex flex-col h-screen">
                <!-- Header -->
                <div class="p-5 border-b dark:border-gray-800 flex items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                    <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                        <i class="ph-bold ph-arrow-left text-xl"></i>
                    </button>
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 mr-3 rtl:mr-0 rtl:ml-3" :class="(txType === 'debt') ? 'bg-red-50 text-red-500 dark:bg-red-900/20' : (txType === 'direct_sale' ? 'bg-blue-50 text-blue-500 dark:bg-blue-900/20' : 'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20')">
                        <i class="ph-bold" :class="(txType === 'debt') ? 'ph-minus-circle' : (txType === 'direct_sale' ? 'ph-shopping-cart' : 'ph-plus-circle')"></i>
                    </div>
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                        <span x-text="editingTxId ? (txType === 'debt' ? '{{ __('notebook.edit_debt') }}' : '{{ __('notebook.edit_payment') }}') : (txType === 'debt' ? '{{ __('notebook.add_new_debt') }}' : (txType === 'direct_sale' ? '{{ __('notebook.direct_sale_and_payment') ?? 'شراء ودفع فوري' }}' : '{{ __('notebook.add_new_payment') }}'))"></span>
                    </h2>
                </div>
                
                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-[#0b1121] custom-scrollbar relative">
                    <!-- Customer Info Badge -->
                    <div x-show="activeCustomer" class="mb-6 flex items-center gap-3 p-3.5 bg-gray-50 dark:bg-gray-800/80 rounded-2xl border border-gray-100 dark:border-gray-700/50" x-cloak>
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm transition-colors"
                             :class="(txType === 'debt') ? 'bg-red-50 text-red-500 dark:bg-red-500/10' : (txType === 'direct_sale' ? 'bg-blue-50 text-blue-500 dark:bg-blue-500/10' : 'bg-emerald-50 text-emerald-500 dark:bg-emerald-500/10')">
                            <i class="ph-bold ph-user text-xl"></i>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <div class="text-[11px] font-bold text-gray-500 mb-0.5">{{ __('notebook.customer') ?? 'الزبون' }}</div>
                            <div class="font-black text-gray-900 dark:text-white text-base truncate" x-text="activeCustomer?.name"></div>
                        </div>
                        <div x-show="activeCustomer?.phone" class="w-9 h-9 shrink-0 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center shadow-sm border border-gray-100 dark:border-gray-600">
                            <a :href="'tel:' + activeCustomer?.phone" class="text-gray-400 hover:text-primary transition-colors">
                                <i class="ph-fill ph-phone-call"></i>
                            </a>
                        </div>
                    </div>

                    <form @submit.prevent="saveTransaction()" novalidate class="space-y-5">
                        <div>
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.amount_currency') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input x-model="txAmount" type="number" required min="0.01" step="0.01" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl pe-12 ps-4 py-4 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-3xl font-black transition-all text-gray-900 dark:text-white text-start" placeholder="0.00">
                                <span class="absolute end-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-lg">₪</span>
                            </div>
                        </div>
                        
                        <div x-show="txType === 'payment' || txType === 'direct_sale'" x-data="{ openBankSelect: false }">
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('bank_accounts.bank_account') }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <!-- Icon -->
                                <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-gray-400 text-lg {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                                    <i class="ph-bold ph-caret-down transition-transform duration-75" :class="openBankSelect ? 'rotate-180 text-primary' : ''"></i>
                                </div>
                                
                                <!-- Custom Select Trigger -->
                                <button type="button" 
                                        @click="openBankSelect = !openBankSelect" 
                                        @click.away="openBankSelect = false" 
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none text-gray-900 dark:text-white font-medium flex items-center transition-all hover:border-primary/50 {{ app()->getLocale() == 'ar' ? 'pr-4 pl-12 text-right' : 'pl-4 pr-12 text-left' }}">
                                    
                                    <!-- Display Name -->
                                    <div class="flex items-center gap-2">
                                        <i class="ph-fill ph-bank text-primary/70 text-lg"></i>
                                        <span x-text="txBankAccountId ? casherConfig.storeAccounts.find(a => a.id == txBankAccountId)?.name : '{{ __('general.select_from_list') }}'"></span>
                                    </div>
                                </button>
                                
                                <!-- Hidden Select (For validation/model binding) -->
                                <select x-model="txBankAccountId" class="sr-only">
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
                                     
                                     <button type="button" @click="txBankAccountId = ''; openBankSelect = false;" class="w-full text-start px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors text-gray-500 font-bold border-b dark:border-gray-100 dark:border-opacity-5">
                                         {{ __('general.select_from_list') }}
                                     </button>
                                     
                                     @foreach($storeBankAccounts as $account)
                                         @php
                                             $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                                             $isDefault = $account->is_default ? " <span class='text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full mx-1'>الأساسي</span>" : "";
                                             $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                                         @endphp
                                         <button type="button" 
                                                 @click="txBankAccountId = '{{ $account->id }}'; openBankSelect = false;" 
                                                 class="w-full text-start px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors flex items-center justify-between border-b dark:border-gray-100 dark:border-opacity-5 last:border-0"
                                                 :class="txBankAccountId == '{{ $account->id }}' ? 'bg-primary/5 text-primary' : 'text-gray-700 dark:text-gray-300'">
                                             
                                             <div class="flex items-center gap-3">
                                                 <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0 transition-colors" :class="txBankAccountId == '{{ $account->id }}' ? 'bg-primary text-white shadow-primary/30' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'">
                                                    @if($account->account_type === 'cash')
                                                        <i class="ph-bold ph-money text-lg"></i>
                                                    @else
                                                        <i class="ph-bold ph-bank text-lg"></i>
                                                    @endif
                                                 </div>
                                                 <div class="flex flex-col">
                                                    <span class="font-bold text-sm">{!! addslashes($accountName) !!}</span>
                                                    @if($account->is_default)
                                                        <span class="text-[10px] text-primary font-bold mt-0.5">{{ __('bank_accounts.is_default') ?? 'الأساسي' }}</span>
                                                    @endif
                                                 </div>
                                             </div>
                                             
                                             <div x-show="txBankAccountId == '{{ $account->id }}'" class="w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center shadow-sm" x-transition.scale>
                                                <i class="ph-bold ph-check text-xs"></i>
                                             </div>
                                         </button>
                                     @endforeach
                                </div>
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
                        
                        <!-- Invisible submit button to allow form submission on enter -->
                        <button type="submit" class="hidden"></button>
                    </form>
                </div>
                
                <!-- Footer (Sticky) -->
                <div class="p-4 md:p-6 border-t dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    <button type="button" 
                            @click="saveTransaction()"
                            x-bind:disabled="isSavingTransaction"
                            class="w-full text-white font-bold rounded-xl py-4 transition-all shadow-lg flex items-center justify-center gap-2 focus:ring-4 focus:outline-none overflow-hidden relative group disabled:opacity-70 disabled:cursor-not-allowed disabled:active:scale-100"
                            :class="[
                                txType === 'debt' ? 'bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 shadow-red-500/30 ring-red-500/50' : (txType === 'direct_sale' ? 'bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 shadow-blue-500/30 ring-blue-500/50' : 'bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 shadow-emerald-500/30 ring-emerald-500/50'),
                                isSavingTransaction ? '' : 'active:scale-[0.98]'
                            ]">
                        <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                        <i x-show="isSavingTransaction" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                        <span class="relative z-10" x-show="!isSavingTransaction" x-text="editingTxId ? '{{ __('notebook.update') }}' : '{{ __('notebook.register') }}'"></span>
                        <span class="relative z-10" x-show="isSavingTransaction">{{ __('notebook.saving') ?? 'جاري الحفظ...' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
