<!-- Edit Supplier Overlay -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'editSupplierModal') { show = true; $nextTick(() => { $el.scrollTop = 0; }); }"
     x-on:close-modal.window="if ($event.detail.id === 'editSupplierModal') show = false"
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
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 mr-3 rtl:mr-0 rtl:ml-3 bg-amber-500/10 text-amber-600 dark:text-amber-400">
                    <i class="ph-bold ph-pencil-simple text-2xl"></i>
                </div>
                <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                    <span>{{ __('notebook.edit_supplier') }}</span>
                </h2>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-[#0b1121] custom-scrollbar relative">
                <form @submit.prevent="updateSupplier()" novalidate class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.supplier_name') }} <span class="text-red-500">*</span></label>
                        <input x-model="editSupplierName" type="text" required placeholder="{{ __('notebook.enter_supplier_name') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.supplier_phone') }} <span class="text-red-500">*</span></label>
                        <input x-model="editSupplierPhone" 
                               @input="editSupplierPhone = normalizeArabicNumbers(editSupplierPhone)" 
                               type="tel" 
                               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400" 
                               dir="ltr" 
                               placeholder="05...">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.bank_or_wallet_name') }} <span class="text-red-500">*</span></label>
                            <input x-model="editSupplierBankName" type="text" required placeholder="{{ __('notebook.enter_bank_name') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.supplier_account_number') }}</label>
                            <input x-model="editSupplierAccountNumber" 
                                   @input="editSupplierAccountNumber = normalizeArabicNumbers(editSupplierAccountNumber)" 
                                   type="text" 
                                   placeholder="{{ __('notebook.enter_account_number') }}" 
                                   class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400" 
                                   dir="ltr">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.supplier_address') }}</label>
                        <input x-model="editSupplierAddress" type="text" placeholder="{{ __('notebook.supplier_address_placeholder') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                    </div>
                    
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="p-4 md:p-6 border-t dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" 
                        @click="updateSupplier()"
                        x-bind:disabled="isSavingSupplier"
                        class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold rounded-xl py-4 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed transition-all shadow-lg shadow-amber-500/25 focus:ring-4 focus:outline-none ring-amber-500/30 active:scale-95">
                    <i x-show="isSavingSupplier" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                    <span x-show="!isSavingSupplier">{{ __('notebook.save_changes') }}</span>
                    <span x-show="isSavingSupplier">{{ __('notebook.saving') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
