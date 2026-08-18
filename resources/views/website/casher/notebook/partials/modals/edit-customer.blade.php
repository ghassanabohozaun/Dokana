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
