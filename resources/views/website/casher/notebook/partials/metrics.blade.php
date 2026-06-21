    <!-- Dashboard Metrics -->
    <div class="p-4">
        <div class="grid grid-cols-2 gap-3 mb-2">
            <!-- Total Debt Card -->
            <div class="col-span-2 bg-gradient-to-br from-emerald-500 to-teal-700 rounded-[1.5rem] p-6 text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden transition-all duration-300 hover:shadow-emerald-500/40 border border-emerald-400/20">
                <!-- Decorative Elements -->
                <div class="absolute -end-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -start-4 -bottom-4 w-24 h-24 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="absolute top-0 end-0 p-4 opacity-10 pointer-events-none transform rtl:-scale-x-100">
                    <i class="ph-fill ph-wallet text-8xl -mt-4 -me-4 -rotate-12"></i>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 flex flex-col items-start">
                    <div class="flex items-center gap-2 mb-2 opacity-90 bg-black/10 px-3 py-1.5 rounded-full backdrop-blur-sm border border-white/10">
                        <i class="ph-fill ph-coins text-white"></i>
                        <p class="text-sm font-bold">{{ __('notebook.total_market_debts') }}</p>
                    </div>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h2 class="text-4xl font-black tracking-tight drop-shadow-sm" dir="ltr" x-text="Number(totalDebt).toFixed(1)"></h2>
                        <span class="text-lg font-bold opacity-80">{{ __('notebook.currency') }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Customers Card -->
            <div class="bg-white dark:bg-darkCard rounded-[1.25rem] p-4 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-3 transition-colors hover:border-primary/30">
                <div class="w-11 h-11 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shrink-0 shadow-sm border border-blue-100 dark:border-blue-900/50">
                    <i class="ph-fill ph-users text-xl"></i>
                </div>
                <div class="flex flex-col items-start">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap">{{ __('notebook.active_customers') }}</p>
                    <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 leading-tight mt-0.5" dir="ltr" x-text="totalCustomers"></h4>
                </div>
            </div>
            
            <!-- Today Collections Card -->
            <div class="bg-white dark:bg-darkCard rounded-[1.25rem] p-4 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center gap-3 transition-colors hover:border-primary/30">
                <div class="w-11 h-11 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center shrink-0 shadow-sm border border-emerald-100 dark:border-emerald-900/50">
                    <i class="ph-fill ph-trend-up text-xl"></i>
                </div>
                <div class="flex flex-col items-start">
                    <p class="text-xs text-gray-500 dark:text-gray-400 font-bold whitespace-nowrap">{{ __('notebook.today_collections') }}</p>
                    <div class="flex items-baseline gap-1 mt-0.5">
                        <h4 class="text-xl font-black text-gray-800 dark:text-gray-100 leading-tight" dir="ltr" x-text="Number(todayCollections).toFixed(1)"></h4>
                        <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
