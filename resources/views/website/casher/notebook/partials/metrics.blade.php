<!-- Dashboard Metrics -->
    <div class="p-4">
        <!-- Top Stats (Today) - Grid of 3 -->
        <div class="grid grid-cols-3 gap-2 mb-3">
            <!-- Today Collections -->
            <div x-on:click="openTodayCollections()" class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-emerald-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center shadow-sm border border-emerald-100 dark:border-emerald-900/50 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i x-show="!isCollectionsCardLoading" class="ph-bold ph-trend-up"></i>
                    <i x-show="isCollectionsCardLoading" class="ph-bold ph-spinner-gap animate-spin" x-cloak></i>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.collections') ?? 'تحصيلات اليوم' }}</p>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(todayCollections).toFixed(1)"></h4>
            </div>

            <!-- Today Direct Sales -->
            <div x-on:click="openTodayDirectSales()" class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-blue-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shadow-sm border border-blue-100 dark:border-blue-900/50 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                    <i x-show="!isDirectSalesCardLoading" class="ph-bold ph-shopping-cart"></i>
                    <i x-show="isDirectSalesCardLoading" class="ph-bold ph-spinner-gap animate-spin" x-cloak></i>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.direct_sales_summary') ?? 'مبيعات فورية' }}</p>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(todayDirectSales).toFixed(1)"></h4>
            </div>

            <!-- Today Debts -->
            <div x-on:click="openTodayDebts()" class="bg-white dark:bg-darkCard rounded-[1rem] p-3 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col items-center justify-center text-center gap-1.5 transition-colors hover:border-red-300/50 cursor-pointer active:scale-95 group relative">
                <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center shadow-sm border border-red-100 dark:border-red-900/50 group-hover:bg-red-500 group-hover:text-white transition-colors">
                    <i x-show="!isDebtsCardLoading" class="ph-bold ph-trend-down"></i>
                    <i x-show="isDebtsCardLoading" class="ph-bold ph-spinner-gap animate-spin" x-cloak></i>
                </div>
                <p class="text-[10px] text-gray-500 dark:text-gray-400 font-bold leading-tight mt-0.5">{{ __('notebook.today_debts') ?? 'ديون اليوم' }}</p>
                <h4 class="text-sm sm:text-base font-black text-gray-800 dark:text-gray-100 leading-none" dir="ltr" x-text="Number(todayDebts).toFixed(1)"></h4>
            </div>
        </div>

        <!-- Bottom Stats (Totals) - Grid of 2 -->
        <div class="grid grid-cols-2 gap-3 mb-2">
            <!-- Total Debt Card -->
            <div class="col-span-2 sm:col-span-1 bg-gradient-to-br from-rose-500 to-red-700 rounded-[1.25rem] p-4 text-white shadow-lg shadow-rose-500/20 relative overflow-hidden transition-all duration-75 border border-rose-400/20">
                <div class="absolute top-0 end-0 p-3 opacity-10 pointer-events-none transform rtl:-scale-x-100">
                    <i class="ph-fill ph-wallet text-6xl -mt-2 -me-2 -rotate-12"></i>
                </div>
                <div class="relative z-10 flex flex-col items-start">
                    <div class="flex items-center gap-1.5 mb-1.5 opacity-90">
                        <i class="ph-fill ph-coins"></i>
                        <p class="text-xs font-bold">{{ __('notebook.total_market_debts') }}</p>
                    </div>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <h2 class="text-2xl font-black tracking-tight drop-shadow-sm" dir="ltr" x-text="Number(totalDebt).toFixed(1)"></h2>
                        <span class="text-xs font-bold opacity-80">{{ __('notebook.currency') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Customers Card -->
            <div class="col-span-2 sm:col-span-1 bg-white dark:bg-darkCard rounded-[1.25rem] p-4 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-3 transition-colors hover:border-primary/30">
                <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 flex items-center justify-center shrink-0 shadow-sm border border-indigo-100 dark:border-indigo-900/50">
                    <i class="ph-fill ph-users text-xl"></i>
                </div>
                <div class="flex flex-col items-start">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap">{{ __('notebook.active_customers') }}</p>
                    <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 leading-tight mt-0.5" dir="ltr" x-text="totalCustomers"></h4>
                </div>
            </div>
        </div>
    </div>
