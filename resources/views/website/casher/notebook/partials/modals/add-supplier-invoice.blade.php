<!-- Add Supplier Invoice Overlay -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'addSupplierInvoiceModal') show = true"
     x-on:close-modal.window="if ($event.detail.id === 'addSupplierInvoiceModal') show = false"
     style="display: none;"
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
                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 mr-3 rtl:mr-0 rtl:ml-3 bg-blue-500/10 text-blue-600 dark:text-blue-400">
                    <i class="ph-bold ph-receipt text-2xl"></i>
                </div>
                <div>
                    <h2 class="font-bold text-lg text-gray-900 dark:text-white">
                        <span>{{ __('notebook.add_invoice') }}</span>
                    </h2>
                    <template x-if="activeSupplier">
                        <p class="text-xs text-gray-400 font-medium" x-text="'المورد: ' + activeSupplier.name"></p>
                    </template>
                </div>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-4 md:p-6 bg-white dark:bg-[#0b1121] custom-scrollbar relative">
                <form @submit.prevent="saveSupplierInvoice()" novalidate class="space-y-4">
                    <!-- Invoice Number -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.invoice_number') }} <span class="text-red-500">*</span></label>
                        <input x-model="newSupplierInvoiceNumber" 
                               @input="newSupplierInvoiceNumber = normalizeArabicNumbers(newSupplierInvoiceNumber)"
                               type="text" 
                               required 
                               placeholder="{{ __('notebook.invoice_number_placeholder') }}" 
                               class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400">
                    </div>

                    <!-- Total Amount -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.invoice_amount') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input x-model="newSupplierInvoiceAmount" 
                                   @input="newSupplierInvoiceAmount = sanitizeAmountInput(newSupplierInvoiceAmount)" 
                                   type="text" 
                                   inputmode="decimal" 
                                   required 
                                   placeholder="0.00" 
                                   class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all font-bold text-lg text-gray-900 dark:text-white placeholder-gray-400">
                            <span class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} text-xs font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                        </div>
                    </div>

                    <!-- Invoice Date -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.invoice_date') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-blue-500 text-xl {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }}">
                                <i class="ph-bold ph-calendar"></i>
                            </div>
                            <input type="text" 
                                   required 
                                   x-model="newSupplierInvoiceDate"
                                   x-init="
                                       flatpickr($el, {
                                           dateFormat: 'Y-m-d',
                                           locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
                                           disableMobile: true,
                                           onChange: function(selectedDates, dateStr, instance) {
                                               newSupplierInvoiceDate = dateStr;
                                               $el.dispatchEvent(new Event('input'));
                                           }
                                       });
                                   "
                                   class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl py-3.5 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none text-gray-900 dark:text-white font-medium cursor-pointer {{ app()->getLocale() == 'ar' ? 'pl-12 pr-4 text-right' : 'pr-12 pl-4 text-left' }}">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-bold mb-1.5 text-gray-700 dark:text-gray-300">{{ __('notebook.notes_optional') }}</label>
                        <textarea x-model="newSupplierInvoiceNotes" rows="3" placeholder="{{ __('notebook.example_notes') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all font-medium text-gray-900 dark:text-white placeholder-gray-400 resize-none"></textarea>
                    </div>
                    
                    <button type="submit" class="hidden"></button>
                </form>
            </div>
            
            <!-- Footer -->
            <div class="p-4 md:p-6 border-t dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <button type="button" 
                        @click="saveSupplierInvoice()"
                        x-bind:disabled="isSavingSupplierInvoice"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl py-4 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed transition-all shadow-lg shadow-blue-500/25 focus:ring-4 focus:outline-none ring-blue-500/30 active:scale-95">
                    <i x-show="isSavingSupplierInvoice" class="ph-bold ph-spinner-gap animate-spin text-xl relative z-10" x-cloak></i>
                    <span x-show="!isSavingSupplierInvoice">{{ __('notebook.register') }}</span>
                    <span x-show="isSavingSupplierInvoice">{{ __('notebook.registering') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
