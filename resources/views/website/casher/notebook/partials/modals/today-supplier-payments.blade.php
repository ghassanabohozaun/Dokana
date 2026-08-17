<!-- Comprehensive Supplier Payments Overlay -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'allSupplierPaymentsModal' || $event.detail.id === 'todaySupplierPaymentsModal') show = true"
     x-on:close-modal.window="if ($event.detail.id === 'allSupplierPaymentsModal' || $event.detail.id === 'todaySupplierPaymentsModal') show = false"
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
            <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                <div class="flex items-center gap-3">
                    <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                        <i class="ph-bold ph-arrow-left text-xl"></i>
                    </button>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shrink-0 border border-amber-200/40">
                        <i class="ph-bold ph-hand-coins text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                            <span>{{ __('notebook.all_supplier_payments_title') }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                <span x-text="allSupplierPaymentsList.length"></span> {{ __('notebook.payments_count') ?? 'سند صرف' }}
                            </span>
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium flex items-center gap-1">
                            <i class="ph-fill ph-clock-counter-clockwise text-xs"></i> 
                            <span>{{ __('notebook.supplier_payments') }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Total Paid Badge -->
                <div class="text-left">
                    <span class="text-[10px] text-gray-400 font-bold block">{{ __('notebook.all_supplier_payments') }}</span>
                    <span class="text-base font-black text-amber-600 dark:text-amber-400" dir="ltr" x-text="Number(totalPaid || 0).toFixed(1) + ' ₪'"></span>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="p-4 bg-white dark:bg-darkCard border-b border-gray-100 dark:border-gray-800">
                <div class="relative">
                    <input type="text"
                           x-model="paymentsModalSearch"
                           @input.debounce.300ms="fetchAllSupplierPayments()"
                           placeholder="{{ __('notebook.search_payments_placeholder') }}"
                           class="w-full h-11 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-9' : 'pl-10 pr-9' }} rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 text-gray-800 dark:text-gray-100 shadow-sm transition-all placeholder:text-gray-400">
                    <i class="ph-bold ph-magnifying-glass absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'right-3.5' : 'left-3.5' }} text-sm text-gray-400"></i>
                    <button x-show="paymentsModalSearch" 
                            @click="paymentsModalSearch = ''; fetchAllSupplierPayments()" 
                            class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 text-xs">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
            </div>
            
            <!-- List Content -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                <div x-show="isAllSupplierPaymentsLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                    <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-amber-500"></i>
                </div>

                <template x-if="allSupplierPaymentsList.length === 0 && !isAllSupplierPaymentsLoading">
                    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <i class="ph-fill ph-receipt text-6xl mb-4 text-gray-300 dark:text-gray-600 opacity-50"></i>
                        <p class="text-sm font-bold">{{ __('notebook.no_supplier_payments_found') }}</p>
                    </div>
                </template>

                <template x-if="allSupplierPaymentsList.length > 0">
                    <div class="space-y-3">
                        <template x-for="p in allSupplierPaymentsList" :key="p.id">
                            <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold shrink-0">
                                            <i class="ph-bold ph-arrow-up-right text-lg"></i>
                                        </div>
                                        <div>
                                            <p @click="openSupplierLedgerById(p.supplier_id)" 
                                               class="font-black text-sm text-gray-900 dark:text-gray-100 flex items-center gap-1.5 cursor-pointer hover:text-amber-600 transition-colors">
                                                <i class="ph-fill ph-truck text-xs text-amber-500"></i>
                                                <span x-text="p.supplier_name"></span>
                                            </p>
                                            
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[11px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md" x-text="'فاتورة: ' + p.invoice_number"></span>
                                                <span class="text-[10px] text-gray-400" x-text="formatDateTime(p.payment_date || p.created_at)"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Amount -->
                                    <div class="text-left font-black shrink-0 text-lg text-amber-600 dark:text-amber-400" dir="ltr">
                                        <span x-text="'-' + Number(p.amount).toFixed(1)"></span> <span class="text-xs font-normal">₪</span>
                                    </div>
                                </div>

                                <!-- Bank & Cashier Details -->
                                <div class="flex flex-wrap items-center justify-between pt-2 border-t border-gray-50 dark:border-gray-800/60 text-xs gap-2">
                                    <div class="flex items-center gap-1.5 text-gray-600 dark:text-gray-300">
                                        <i class="ph-fill ph-bank text-amber-500"></i>
                                        <span x-text="p.bank_account_name || '-'"></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-3">
                                        <template x-if="p.cashier_name">
                                            <span class="text-[11px] text-gray-400" x-text="'{{ __('notebook.added_by') }}: ' + p.cashier_name"></span>
                                        </template>
                                        
                                        <button @click="confirmDeleteSupplierPayment(p.id)" class="w-7 h-7 rounded-full bg-gray-100 hover:bg-red-50 dark:bg-gray-800 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-600 flex items-center justify-center transition-colors" title="{{ __('notebook.delete') }}">
                                            <i class="ph-bold ph-trash text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
