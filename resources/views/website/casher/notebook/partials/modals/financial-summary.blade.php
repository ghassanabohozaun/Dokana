<!-- Financial Summary Modal -->
<div x-data="{ show: false }" 
     x-show="show" 
     x-on:open-modal.window="if ($event.detail.id === 'financialSummaryModal') show = true"
     x-on:close-modal.window="if ($event.detail.id === 'financialSummaryModal') show = false"
     style="display: none;"
     class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center">
    
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
    
    <div x-show="show" x-transition.translate.y.bottom class="relative bg-gray-50 dark:bg-dark w-full max-w-md h-[85vh] sm:h-[80vh] rounded-t-[2rem] sm:rounded-3xl shadow-2xl border border-white/10 z-10 flex flex-col overflow-hidden">
        
        <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto my-3 sm:hidden shrink-0"></div>
        
        <!-- Header -->
        <div class="px-6 pb-4 pt-2 sm:pt-6 border-b dark:border-gray-800 bg-white dark:bg-darkCard shrink-0 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="ph-fill ph-chart-bar text-primary text-2xl"></i>
                <span>التقارير المالية</span>
            </h2>
            <button x-on:click="show = false" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto relative p-5 custom-scrollbar">
            
            <div x-show="isSummaryLoading" class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-gray-50/80 dark:bg-dark/80 backdrop-blur-sm">
                <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary mb-3"></i>
                <p class="text-gray-500 font-bold text-sm">جاري جلب التقارير...</p>
            </div>

            <template x-if="summaryData">
                <div class="space-y-6 pb-4">
                    
                    <!-- Tabs -->
                    <div class="flex p-1 bg-gray-200 dark:bg-gray-800 rounded-xl">
                        <button @click="summaryTab = 'today'" :class="summaryTab === 'today' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">اليوم</button>
                        <button @click="summaryTab = 'week'" :class="summaryTab === 'week' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">هذا الأسبوع</button>
                        <button @click="summaryTab = 'month'" :class="summaryTab === 'month' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'" class="flex-1 py-2 text-sm font-bold rounded-lg transition-all">هذا الشهر</button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid gap-4">
                        
                        <!-- Collections -->
                        <div class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-trend-up text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">التحصيلات (دفعات)</p>
                                    <p class="text-xs text-emerald-500 font-bold mt-0.5">أموال دخلت الصندوق</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr" x-text="Number(summaryData[summaryTab].collections).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                        <!-- Direct Sales -->
                        <div class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-shopping-cart text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">المبيعات الفورية</p>
                                    <p class="text-xs text-blue-500 font-bold mt-0.5">كاش مباشر</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr" x-text="Number(summaryData[summaryTab].direct_sales).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                        <!-- Debts -->
                        <div class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-trend-down text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">ديون جديدة</p>
                                    <p class="text-xs text-red-500 font-bold mt-0.5">مبيعات بالآجل</p>
                                </div>
                            </div>
                            <div class="text-end">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr" x-text="Number(summaryData[summaryTab].debts).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- Total Cash Summary -->
                    <div class="bg-gradient-to-br from-primary to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-primary/30 relative overflow-hidden mt-4">
                        <div class="absolute top-0 end-0 p-3 opacity-10 pointer-events-none transform rtl:-scale-x-100">
                            <i class="ph-fill ph-wallet text-6xl -mt-2 -me-2 -rotate-12"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-bold opacity-90 mb-1 flex items-center gap-2">
                                <i class="ph-bold ph-info"></i>
                                إجمالي النقدية (تحصيلات + كاش)
                            </p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <h2 class="text-3xl font-black tracking-tight" dir="ltr" x-text="Number(Number(summaryData[summaryTab].collections) + Number(summaryData[summaryTab].direct_sales)).toFixed(1)"></h2>
                                <span class="text-sm font-bold opacity-80">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>
</div>
