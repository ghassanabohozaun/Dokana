<!-- Comprehensive Supplier Invoices Overlay -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'allSupplierInvoicesModal' || $event.detail.id === 'todaySupplierInvoicesModal') { show = true; $nextTick(() => { if($refs.supplierInvoicesScroll) $refs.supplierInvoicesScroll.scrollTop = 0; $el.scrollTop = 0; }); }"
     x-on:close-modal.window="if ($event.detail.id === 'allSupplierInvoicesModal' || $event.detail.id === 'todaySupplierInvoicesModal') show = false"
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
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-xl shrink-0 border border-blue-200/40">
                        <i class="ph-bold ph-receipt text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                            <span>{{ __('notebook.all_supplier_invoices_title') }}</span>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                <span x-text="allSupplierInvoicesList.length"></span> {{ __('notebook.invoices_count') }}
                            </span>
                        </h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-medium flex items-center gap-1">
                            <i class="ph-fill ph-files text-xs"></i> 
                            <span>{{ __('notebook.supplier_invoices') }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Total Purchases Badge -->
                <div class="text-left">
                    <span class="text-[10px] text-gray-400 font-bold block">{{ __('notebook.all_purchases') }}</span>
                    <span class="text-base font-black text-blue-600 dark:text-blue-400" dir="ltr" x-text="Number(totalPurchases || 0).toFixed(1) + ' ₪'"></span>
                </div>
            </div>

            <!-- Search & Status Filter Bar -->
            <div class="p-4 bg-white dark:bg-darkCard border-b border-gray-100 dark:border-gray-800 flex flex-col gap-2.5">
                <!-- Search Input -->
                <div class="relative">
                    <input type="text"
                           x-model="invoicesModalSearch"
                           @input.debounce.300ms="fetchAllSupplierInvoices()"
                           placeholder="{{ __('notebook.search_invoices_placeholder') }}"
                           class="w-full h-11 {{ app()->getLocale() == 'ar' ? 'pr-10 pl-9' : 'pl-10 pr-9' }} rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-gray-800 dark:text-gray-100 shadow-sm transition-all placeholder:text-gray-400">
                    <i class="ph-bold ph-magnifying-glass absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'right-3.5' : 'left-3.5' }} text-sm text-gray-400"></i>
                    <button x-show="invoicesModalSearch" 
                            @click="invoicesModalSearch = ''; fetchAllSupplierInvoices()" 
                            class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} w-5 h-5 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 text-xs">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>

                <!-- Filter Tabs -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
                    <button @click="invoicesModalFilter = 'all'; fetchAllSupplierInvoices()" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all"
                            :class="invoicesModalFilter === 'all' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200'">
                        {{ __('notebook.filter_all_invoices') }}
                    </button>

                    <button @click="invoicesModalFilter = 'pending'; fetchAllSupplierInvoices()" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all flex items-center gap-1"
                            :class="invoicesModalFilter === 'pending' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 hover:bg-rose-100'">
                        <span>{{ __('notebook.filter_pending_invoices') }}</span>
                    </button>

                    <button @click="invoicesModalFilter = 'paid'; fetchAllSupplierInvoices()" 
                            class="px-3 py-1 rounded-lg text-xs font-bold transition-all"
                            :class="invoicesModalFilter === 'paid' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100'">
                        {{ __('notebook.filter_paid_invoices') }}
                    </button>
                </div>
            </div>
            
            <!-- List Content -->
            <div x-ref="supplierInvoicesScroll" class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                <div x-show="isAllSupplierInvoicesLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                    <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-blue-500"></i>
                </div>

                <template x-if="allSupplierInvoicesList.length === 0 && !isAllSupplierInvoicesLoading">
                    <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                        <i class="ph-fill ph-receipt text-6xl mb-4 text-gray-300 dark:text-gray-600 opacity-50"></i>
                        <p class="text-sm font-bold">{{ __('notebook.no_supplier_invoices_found') }}</p>
                    </div>
                </template>

                <template x-if="allSupplierInvoicesList.length > 0">
                    <div class="space-y-3">
                        <template x-for="inv in allSupplierInvoicesList" :key="inv.id">
                            <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 hover:shadow-md transition-shadow">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shrink-0">
                                            <i class="ph-bold ph-file-text text-lg"></i>
                                        </div>
                                        <div>
                                            <p @click="openSupplierLedgerById(inv.supplier_id)" 
                                               class="font-black text-sm text-gray-900 dark:text-gray-100 flex items-center gap-1.5 cursor-pointer hover:text-blue-600 transition-colors">
                                                <i class="ph-fill ph-truck text-xs text-amber-500"></i>
                                                <span x-text="inv.supplier_name"></span>
                                            </p>
                                            
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[11px] font-bold text-gray-700 dark:text-gray-300" x-text="'فاتورة: ' + inv.invoice_number"></span>
                                                <span class="text-[10px] text-gray-400" x-text="formatDateTime(inv.invoice_date || inv.created_at)"></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Status & Total Amount -->
                                    <div class="text-left flex flex-col items-end">
                                        <div class="font-black text-lg text-gray-900 dark:text-white" dir="ltr">
                                            <span x-text="Number(inv.total_amount).toFixed(1)"></span> <span class="text-xs font-normal">₪</span>
                                        </div>
                                        
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full mt-1"
                                              :class="{
                                                  'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200': inv.status === 'paid',
                                                  'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 border border-amber-200': inv.status === 'partial' || inv.status === 'partially_paid',
                                                  'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200': inv.status === 'unpaid'
                                              }"
                                              x-text="inv.status === 'paid' ? '{{ __('notebook.status_paid') }}' : (inv.status === 'partial' || inv.status === 'partially_paid' ? '{{ __('notebook.status_partial') }}' : '{{ __('notebook.status_unpaid') }}')">
                                        </span>
                                    </div>
                                </div>

                                <!-- Remaining and Actions -->
                                <div class="flex flex-wrap items-center justify-between pt-2 border-t border-gray-50 dark:border-gray-800/60 text-xs gap-2">
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="text-gray-400">{{ __('notebook.remaining') }}:</span>
                                        <span class="font-bold" :class="Number(inv.remaining_amount) > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400'" dir="ltr" x-text="Number(inv.remaining_amount).toFixed(1) + ' ₪'"></span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <template x-if="inv.cashier_name">
                                            <span class="text-[11px] text-gray-400" x-text="'{{ __('notebook.added_by') }}: ' + inv.cashier_name"></span>
                                        </template>
                                        
                                        <template x-if="inv.status !== 'paid' && Number(inv.remaining_amount) > 0">
                                            <button @click="openDirectSupplierPayment(inv.id, inv.supplier_id)" 
                                                    class="px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-bold text-[11px] hover:bg-amber-100 flex items-center gap-1 transition-colors">
                                                <i class="ph-bold ph-hand-coins"></i>
                                                <span>{{ __('notebook.payout_to_supplier') }}</span>
                                            </button>
                                        </template>
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
