    <!-- Edit Customer Overlay -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'editCustomerModal') { show = true; $nextTick(() => { $el.scrollTop = 0; }); }"
         x-on:close-modal.window="if ($event.detail.id === 'editCustomerModal') show = false"
         style="display: none;"
         class="overlay-panel overlay-panel-form flex justify-center"
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
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 mr-3 rtl:mr-0 rtl:ml-3 bg-primary/10 text-primary">
                        <i class="ph-bold ph-pencil-simple text-2xl"></i>
                    </div>
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                        <span>{{ __('notebook.edit_customer') ?? 'تعديل بيانات الزبون' }}</span>
                    </h2>
                </div>
                
                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-[#0b1121] custom-scrollbar relative">
                    <form @submit.prevent="submitEditCustomer()" novalidate class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.name') }} <span class="text-red-500">*</span></label>
                            <input x-model="editCustomerName" type="text" required placeholder="{{ __('notebook.enter_customer_name') ?? 'أدخل اسم الزبون' }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.phone_optional') }}</label>
                            <input x-model="editCustomerPhone" type="tel" maxlength="10" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400" dir="ltr" placeholder="05...">
                        </div>

                        <template x-if="activeCustomer && !activeCustomer.is_walk_in">
                            <div class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-800">
                                <!-- Max Debt Limit -->
                                <div>
                                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">
                                        {{ __('store_customers.max_debt_limit') ?? 'سقف الدين (الحد الأقصى)' }}
                                    </label>
                                    <div class="relative">
                                        <input x-model="editCustomerMaxDebtLimit" type="number" step="0.01" min="0" 
                                               placeholder="{{ __('general.unlimited') ?? 'غير محدد (بدون سقف)' }}" 
                                               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl pe-10 ps-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                                        <span class="absolute end-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">₪</span>
                                    </div>
                                </div>

                                <!-- Bypass Debt Limit Toggle Card -->
                                <div class="flex items-center justify-between p-4 rounded-2xl border border-gray-200 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-800/60 transition-colors"
                                     :class="editCustomerBypassDebtLimit ? 'border-amber-300 dark:border-amber-700 bg-amber-50/50 dark:bg-amber-950/20' : ''">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors shadow-sm"
                                             :class="editCustomerBypassDebtLimit ? 'bg-amber-500 text-white shadow-amber-500/20' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                            <i class="ph-bold text-xl" :class="editCustomerBypassDebtLimit ? 'ph-lock-key-open' : 'ph-lock-key'"></i>
                                        </div>
                                        <div>
                                            <span class="text-sm font-bold text-gray-800 dark:text-white block">
                                                {{ __('store_customers.bypass_debt_limit') ?? 'تجاوز سقف الدين' }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5" 
                                                  x-text="editCustomerBypassDebtLimit ? 'السقف مفتوح حالياً (يمكن إضافة ديون بدون قيود)' : 'الحساب مقيد بسقف الدين المحدد'">
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <label class="relative inline-flex items-center cursor-pointer select-none shrink-0">
                                        <input type="checkbox" x-model="editCustomerBypassDebtLimit" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-amber-500 shadow-sm"></div>
                                    </label>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Invisible submit button to allow form submission on enter -->
                        <button type="submit" class="hidden"></button>
                    </form>
                </div>
                
                <!-- Footer (Sticky) -->
                <div class="p-4 md:p-6 border-t dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    <button type="button" 
                            @click="submitEditCustomer()"
                            x-bind:disabled="isSavingCustomer"
                            class="w-full btn-gradient-primary font-bold rounded-xl py-4 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed transition-all shadow-lg focus:ring-4 focus:outline-none ring-primary/30">
                        <i x-show="isSavingCustomer" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                        <span x-show="!isSavingCustomer">{{ __('notebook.save_changes') ?? 'حفظ التعديلات' }}</span>
                        <span x-show="isSavingCustomer">{{ __('notebook.saving') ?? 'جاري الحفظ...' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
