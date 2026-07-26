<!-- Financial Summary Drawer -->
<div x-data="{ show: false }" x-show="show"
    x-on:open-modal.window="if ($event.detail.id === 'financialSummaryModal') show = true"
    x-on:close-modal.window="if ($event.detail.id === 'financialSummaryModal') show = false" style="display: none;"
    class="fixed inset-0 z-[60] flex" x-cloak>

    <!-- Backdrop -->
    <div x-show="show" x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="drawer-backdrop"
        x-on:click="show = false"></div>

    <!-- Drawer Panel -->
    <div x-show="show" x-transition:enter="transform transition ease-out duration-200"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-150" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full" class="drawer-panel flex flex-col overflow-hidden">

        <!-- Handle for Mobile -->
        <div class="w-12 h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full mx-auto my-3 md:hidden shrink-0"></div>

        <!-- Header -->
        <div class="px-6 pb-4 pt-2 md:pt-6 border-b dark:border-gray-800 shrink-0 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="ph-fill ph-chart-bar text-primary text-2xl"></i>
                <span>{{ __('notebook.financial_reports') ?? 'التقارير المالية' }}</span>
            </h2>
            <button x-on:click="show = false"
                class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-gray-500">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto relative p-5 custom-scrollbar">

            <div x-show="isSummaryLoading"
                class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-gray-50/80 dark:bg-darkCard/80 backdrop-blur-sm"
                x-cloak>
                <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary mb-3"></i>
                <p class="text-gray-500 font-bold text-sm">
                    {{ __('notebook.loading_reports') ?? 'جاري جلب التقارير...' }}</p>
            </div>

            <template x-if="summaryData">
                <div class="space-y-6 pb-4">

                    <!-- Tabs -->
                    <div class="flex p-1 bg-gray-200 dark:bg-gray-800 rounded-xl overflow-x-auto custom-scrollbar">
                        <button @click="summaryTab = 'today'"
                            :class="summaryTab === 'today' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' :
                                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="flex-1 py-2 px-3 text-sm font-bold rounded-lg transition-all whitespace-nowrap">{{ __('notebook.today') ?? 'اليوم' }}</button>
                        <button @click="summaryTab = 'week'"
                            :class="summaryTab === 'week' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' :
                                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="flex-1 py-2 px-3 text-sm font-bold rounded-lg transition-all whitespace-nowrap">{{ __('notebook.this_week') ?? 'هذا الأسبوع' }}</button>
                        <button @click="summaryTab = 'month'"
                            :class="summaryTab === 'month' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' :
                                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="flex-1 py-2 px-3 text-sm font-bold rounded-lg transition-all whitespace-nowrap">{{ __('notebook.this_month') ?? 'هذا الشهر' }}</button>
                        <button @click="summaryTab = 'custom'; if(!summaryData.custom) fetchCustomSummary();"
                            :class="summaryTab === 'custom' ? 'bg-white dark:bg-gray-700 shadow-sm text-primary' :
                                'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                            class="flex-1 py-2 px-3 text-sm font-bold rounded-lg transition-all whitespace-nowrap">{{ __('notebook.custom_date') ?? 'تاريخ محدد' }}</button>
                    </div>

                    <!-- Custom Date Picker -->
                    <div x-show="summaryTab === 'custom'" x-collapse>
                        <div
                            class="bg-gray-100 dark:bg-gray-800/50 p-3 rounded-xl flex items-center gap-3 mt-1 relative">
                            <div
                                class="absolute top-1/2 -translate-y-1/2 pointer-events-none flex items-center justify-center text-primary text-xl {{ app()->getLocale() == 'ar' ? 'left-5' : 'right-5' }}">
                                <i class="ph-bold ph-calendar"></i>
                            </div>
                            <label
                                class="text-xs font-bold text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ __('notebook.select_date') ?? 'اختر التاريخ:' }}</label>
                            <input type="text" x-model="summaryCustomDate" x-init="flatpickr($el, {
                                dateFormat: 'Y-m-d',
                                locale: '{{ app()->getLocale() == 'ar' ? 'ar' : 'en' }}',
                                disableMobile: true,
                                onChange: function(selectedDates, dateStr, instance) {
                                    summaryCustomDate = dateStr;
                                    fetchCustomSummary();
                                }
                            });"
                                class="flex-1 bg-white dark:bg-darkCard border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2">
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="grid gap-4">

                        <!-- Collections -->
                        <div
                            class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-trend-up text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">
                                        {{ __('notebook.collections') ?? 'التحصيلات (دفعات)' }}</p>
                                    <p class="text-xs text-emerald-500 font-bold mt-0.5">
                                        {{ __('notebook.collections_desc') ?? 'أموال دخلت الصندوق' }}</p>
                                </div>
                            </div>
                            <div class="text-end shrink-0 pl-2">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr"
                                    x-text="Number(summaryData[summaryTab].collections).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                        <!-- Direct Sales -->
                        <div
                            class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-shopping-cart text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">
                                        {{ __('notebook.direct_sales_summary') ?? 'المبيعات الفورية' }}</p>
                                    <p class="text-xs text-blue-500 font-bold mt-0.5">
                                        {{ __('notebook.direct_sales_desc') ?? 'كاش مباشر' }}</p>
                                </div>
                            </div>
                            <div class="text-end shrink-0 pl-2">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr"
                                    x-text="Number(summaryData[summaryTab].direct_sales).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                        <!-- Debts -->
                        <div
                            class="bg-white dark:bg-darkCard rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center shadow-sm">
                                    <i class="ph-bold ph-trend-down text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">
                                        {{ __('notebook.new_debts') ?? 'ديون جديدة' }}</p>
                                    <p class="text-xs text-red-500 font-bold mt-0.5">
                                        {{ __('notebook.new_debts_desc') ?? 'مبيعات بالآجل' }}</p>
                                </div>
                            </div>
                            <div class="text-end shrink-0 pl-2">
                                <h4 class="text-2xl font-black text-gray-800 dark:text-gray-100" dir="ltr"
                                    x-text="Number(summaryData[summaryTab].debts).toFixed(1)"></h4>
                                <span class="text-[11px] font-bold text-gray-400">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>

                    </div>

                    <!-- Total Cash Summary -->
                    <div
                        class="bg-gradient-to-br from-primary to-indigo-600 rounded-2xl p-5 text-white shadow-lg shadow-primary/30 relative overflow-hidden mt-4 hover:scale-[1.02] transition-transform">
                        <div class="absolute top-0 end-0 p-3 opacity-10 pointer-events-none transform rtl:-scale-x-100">
                            <i class="ph-fill ph-wallet text-6xl -mt-2 -me-2 -rotate-12"></i>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-bold opacity-90 mb-1 flex items-center gap-2">
                                <i class="ph-bold ph-info"></i>
                                {{ __('notebook.total_cash_summary') ?? 'إجمالي النقدية (تحصيلات + كاش)' }}
                            </p>
                            <div class="flex items-baseline gap-1 mt-1">
                                <h2 class="text-3xl font-black tracking-tight" dir="ltr"
                                    x-text="Number(Number(summaryData[summaryTab].collections) + Number(summaryData[summaryTab].direct_sales)).toFixed(1)">
                                </h2>
                                <span class="text-sm font-bold opacity-80">{{ __('notebook.currency') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </template>
        </div>
    </div>
</div>
