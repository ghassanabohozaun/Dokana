    <!-- Edit Customer Drawer -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'editCustomerModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'editCustomerModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-[110] flex" x-cloak>
         
        <!-- Backdrop -->
        <div x-show="show" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="drawer-backdrop" 
             x-on:click="show = false"></div>

        <!-- Drawer Panel -->
        <div x-show="show" 
             x-transition:enter="transition ease-in-out duration-300 transform"
             x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full rtl:md:-translate-x-full"
             x-transition:enter-end="translate-y-0 md:translate-x-0"
             x-transition:leave="transition ease-in-out duration-300 transform"
             x-transition:leave-start="translate-y-0 md:translate-x-0"
             x-transition:leave-end="translate-y-full md:translate-y-0 md:translate-x-full rtl:md:-translate-x-full"
             class="drawer-panel p-6 overflow-y-auto">
             
            <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto mb-6 md:hidden"></div>
            
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="ph-fill ph-pencil-simple text-primary text-2xl"></i>
                    {{ __('notebook.edit_customer') ?? 'تعديل بيانات الزبون' }}
                </h2>
                <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            
            <form @submit.prevent="submitEditCustomer()" novalidate class="space-y-4 flex-1">
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.name') }} <span class="text-red-500">*</span></label>
                    <input x-model="editCustomerName" type="text" required placeholder="{{ __('notebook.enter_customer_name') ?? 'أدخل اسم الزبون' }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.phone_optional') }}</label>
                    <input x-model="editCustomerPhone" type="tel" maxlength="10" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400" dir="ltr" placeholder="05...">
                </div>
                <div class="pt-4 mt-auto">
                    <button type="submit" 
                            x-bind:disabled="isSavingCustomer"
                            class="w-full btn-gradient-primary font-bold rounded-xl py-3.5 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed transition-all">
                        <i x-show="isSavingCustomer" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                        <span x-show="!isSavingCustomer">{{ __('notebook.save_changes') ?? 'حفظ التعديلات' }}</span>
                        <span x-show="isSavingCustomer">{{ __('notebook.saving') ?? 'جاري الحفظ...' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
