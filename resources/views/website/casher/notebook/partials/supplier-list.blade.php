<!-- Supplier List Section -->
<div class="animate-fade-in-up">
    <!-- Supplier Metrics Summary Bar (Overall Totals & Financial Position) -->
    <div class="p-4">
        <!-- Top Stats (Major Overall Totals) - Grid of 3 Clickable Cards -->
        <div class="grid grid-cols-3 gap-2 mb-3">
            <!-- 1. Total Purchases (Clickable: Opens All Invoices Modal) -->
            <div x-on:click="openAllSupplierInvoices('all')" 
                 class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-blue-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shadow-sm border border-blue-100 dark:border-blue-900/50 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i x-show="!isAllSupplierInvoicesCardLoading" class="ph-bold ph-receipt"></i>
                    <i x-show="isAllSupplierInvoicesCardLoading" class="ph-bold ph-spinner-gap animate-spin" x-cloak></i>
                </div>
                <div class="flex items-center gap-1">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.all_purchases') }}</p>
                    <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 opacity-80" x-text="'(' + (totalInvoicesCount || 0) + ')'"></span>
                </div>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(totalPurchases || 0).toFixed(1)"></h4>
            </div>

            <!-- 2. Total Paid to Suppliers (Clickable: Opens All Payments Modal) -->
            <div x-on:click="openAllSupplierPayments()" 
                 class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-amber-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center shadow-sm border border-amber-100 dark:border-amber-900/50 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                    <i x-show="!isAllSupplierPaymentsCardLoading" class="ph-bold ph-hand-coins"></i>
                    <i x-show="isAllSupplierPaymentsCardLoading" class="ph-bold ph-spinner-gap animate-spin" x-cloak></i>
                </div>
                <div class="flex items-center gap-1">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.all_supplier_payments') }}</p>
                    <span class="text-[9px] font-black text-amber-600 dark:text-amber-400 opacity-80" x-text="'(' + (totalPaymentsCount || 0) + ')'"></span>
                </div>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(totalPaid || 0).toFixed(1)"></h4>
            </div>

            <!-- 3. Pending / Unpaid Invoices (Clickable: Opens Invoices Modal filtered to Pending) -->
            <div x-on:click="openAllSupplierInvoices('pending')" 
                 class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-rose-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-900/20 text-rose-500 flex items-center justify-center shadow-sm border border-rose-100 dark:border-rose-900/50 group-hover:bg-rose-500 group-hover:text-white transition-colors">
                    <i class="ph-bold ph-hourglass-high"></i>
                </div>
                <div class="flex items-center gap-1">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.pending_invoices') }}</p>
                    <span class="text-[9px] font-black text-rose-600 dark:text-rose-400 opacity-80" x-text="'(' + (pendingInvoicesCount || 0) + ')'"></span>
                </div>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(totalPendingDues || 0).toFixed(1)"></h4>
            </div>
        </div>

        <!-- Bottom Stats (Totals & Overview) - Grid of 2 -->
        <div class="grid grid-cols-2 gap-3 mb-1">
            <!-- Total Supplier Due Card (Hero Gradient Card) -->
            <div class="col-span-2 sm:col-span-1 bg-gradient-to-br from-amber-500 via-orange-500 to-rose-600 rounded-[1.25rem] p-4 text-white shadow-lg shadow-amber-500/20 relative overflow-hidden transition-all duration-75 border border-amber-400/20">
                <div class="absolute top-0 end-0 p-3 opacity-15 pointer-events-none transform rtl:-scale-x-100">
                    <i class="ph-fill ph-wallet text-6xl -mt-2 -me-2 -rotate-12"></i>
                </div>
                <div class="relative z-10 flex flex-col items-start">
                    <div class="flex items-center gap-1.5 mb-1.5 opacity-90">
                        <i class="ph-fill ph-coins"></i>
                        <p class="text-xs font-bold">{{ __('notebook.total_supplier_due') }}</p>
                    </div>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <h2 class="text-2xl font-black tracking-tight drop-shadow-sm" dir="ltr" x-text="Number(totalSupplierDue || 0).toFixed(1)"></h2>
                        <span class="text-xs font-bold opacity-80">{{ __('notebook.currency') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Suppliers Summary Card -->
            <div class="col-span-2 sm:col-span-1 bg-white dark:bg-darkCard rounded-[1.25rem] p-4 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between gap-3 transition-colors hover:border-amber-500/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 shadow-sm border border-amber-100 dark:border-amber-900/50">
                        <i class="ph-fill ph-truck text-xl"></i>
                    </div>
                    <div class="flex flex-col items-start">
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap">{{ __('notebook.suppliers') }}</p>
                        <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 leading-tight mt-0.5" dir="ltr" x-text="totalActiveSuppliers || suppliers.length || 0"></h4>
                    </div>
                </div>
                
                <div class="text-left">
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40" x-text="(suppliersWithDueCount || 0) + ' ' + '{{ __('notebook.suppliers_with_due') }}'">
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="px-4 py-2">
        <!-- Search input -->
        <div class="relative mb-3">
            <input type="text"
                   x-model="supplierSearch"
                   @input.debounce.300ms="fetchSuppliers()"
                   placeholder="{{ __('notebook.search_supplier') }}"
                   class="w-full h-12 {{ app()->getLocale() == 'ar' ? 'pr-11 pl-10' : 'pl-11 pr-10' }} rounded-2xl bg-white dark:bg-darkCard border border-gray-200 dark:border-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 text-gray-800 dark:text-gray-100 shadow-sm transition-all placeholder:text-gray-400">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none text-gray-400">
                <i class="ph-bold ph-magnifying-glass text-lg"></i>
            </div>
            <button x-show="supplierSearch" 
                    @click="supplierSearch = ''; fetchSuppliers()" 
                    class="absolute top-1/2 -translate-y-1/2 {{ app()->getLocale() == 'ar' ? 'left-3' : 'right-3' }} w-6 h-6 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-500 hover:text-gray-700 dark:text-gray-300 transition-colors"
                    x-cloak>
                <i class="ph-bold ph-x text-xs"></i>
            </button>
        </div>

        <!-- Filter Pills -->
        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1">
            <button @click="setSupplierFilter('all')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all duration-200"
                :class="supplierFilter === 'all' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'bg-white dark:bg-darkCard text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800'">
                {{ __('notebook.filter_all') }}
            </button>

            <button @click="setSupplierFilter('due')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all duration-200 flex items-center gap-1"
                :class="supplierFilter === 'due' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'bg-white dark:bg-darkCard text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40 hover:bg-amber-50/50 dark:hover:bg-amber-900/10'">
                <span>{{ __('notebook.filter_has_due') }}</span>
                <span class="text-[10px] font-black opacity-80" x-text="'(' + (suppliersWithDueCount || 0) + ')'"></span>
            </button>

            <button @click="setSupplierFilter('cleared')" 
                class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all duration-200"
                :class="supplierFilter === 'cleared' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'bg-white dark:bg-darkCard text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800'">
                {{ __('notebook.filter_cleared') }}
            </button>
        </div>
    </div>

    <!-- Supplier List Container -->
    <div class="px-4 pb-6 relative">
        <div x-show="isSuppliersLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center rounded-xl backdrop-blur-sm" x-cloak>
            <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-amber-500"></i>
        </div>

        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-3 px-1">{{ __('notebook.suppliers_list') }}</h3>

        <!-- Empty State -->
        <template x-if="suppliers.length === 0 && !supplierSearch && supplierFilter === 'all' && !isSuppliersLoading">
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <div class="w-20 h-20 mb-4 rounded-3xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center">
                    <i class="ph-duotone ph-truck text-4xl"></i>
                </div>
                <p class="font-bold text-gray-600 dark:text-gray-300 text-lg mb-1">{{ __('notebook.no_suppliers_added') }}</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('notebook.click_to_add_first_supplier') }}</p>
                <button @click="openAddSupplier()" class="mt-4 px-5 py-2.5 rounded-full bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold shadow-lg shadow-amber-500/30 transition-all flex items-center gap-1.5 active:scale-95">
                    <i class="ph-bold ph-plus"></i>
                    <span>{{ __('notebook.add_new_supplier') }}</span>
                </button>
            </div>
        </template>

        <!-- No Results Search -->
        <template x-if="suppliers.length === 0 && (supplierSearch || supplierFilter !== 'all') && !isSuppliersLoading">
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="ph-fill ph-magnifying-glass-minus text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="font-bold text-gray-500 dark:text-gray-400">{{ __('notebook.no_results') }}</p>
            </div>
        </template>

        <!-- Suppliers Cards -->
        <div class="space-y-3" x-show="suppliers.length > 0">
            <template x-for="supplier in suppliers" :key="supplier.id">
                <div @click="openSupplierLedger(supplier)" 
                     class="card-hover p-4 rounded-[1.25rem] border bg-white dark:bg-darkCard border-gray-100 dark:border-gray-800 flex justify-between items-center cursor-pointer transition-all duration-200 hover:border-amber-200 dark:hover:border-amber-900/50">
                    <div class="flex items-center gap-3.5 min-w-0">
                        <!-- Avatar / Icon -->
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-amber-500/10 to-orange-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-xl shrink-0 shadow-sm border border-amber-200/40 dark:border-amber-700/30 relative overflow-hidden">
                            <span x-show="loadingSupplierId !== supplier.id" x-text="supplier.name ? supplier.name.substring(0, 1) : '-'"></span>
                            <div x-show="loadingSupplierId === supplier.id" class="absolute inset-0 bg-white/50 dark:bg-black/50 flex items-center justify-center" x-cloak>
                                <i class="ph-bold ph-spinner-gap animate-spin text-amber-500"></i>
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="min-w-0">
                            <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight truncate" x-text="supplier.name"></h4>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[11px] text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1 font-medium">
                                    <i class="ph-fill ph-phone text-xs text-amber-500"></i>
                                    <span x-text="supplier.mobile || '-'"></span>
                                </span>

                                <template x-if="supplier.bank_name && supplier.bank_name !== '-'">
                                    <span class="flex items-center gap-1 font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-1.5 py-0.5 rounded text-[10px]">
                                        <i class="ph-fill ph-bank text-xs"></i>
                                        <span x-text="supplier.bank_name"></span>
                                    </span>
                                </template>
                                
                                <template x-if="supplier.address">
                                    <span class="flex items-center gap-1 font-medium text-gray-400 truncate max-w-[150px]">
                                        <i class="ph-fill ph-map-pin text-xs"></i>
                                        <span x-text="supplier.address"></span>
                                    </span>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Due & Status -->
                    <div class="text-left flex flex-col items-end shrink-0 {{ app()->getLocale() == 'ar' ? 'mr-2' : 'ml-2' }}">
                        <span class="font-black text-lg leading-tight" dir="ltr"
                              :class="supplier.balance_due > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-gray-500'"
                              x-text="Number(supplier.balance_due || 0).toFixed(1)">
                        </span>
                        
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full mt-1"
                              :class="supplier.balance_due > 0 ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/40' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400'"
                              x-text="supplier.balance_due > 0 ? '{{ __('notebook.remaining_to_supplier') }}' : '{{ __('notebook.filter_cleared') }}'">
                        </span>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
