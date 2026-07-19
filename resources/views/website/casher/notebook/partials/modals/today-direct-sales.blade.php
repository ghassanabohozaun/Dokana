    <!-- Today Debts Overlay -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'todayDirectSalesModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'todayDirectSalesModal') show = false"
         style="display: none;"
         class="overlay-panel flex justify-center"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         x-cloak>
         
        <div class="w-full md:max-w-3xl min-h-screen flex flex-col bg-gray-50 dark:bg-[#0b1121] shadow-2xl relative">
            <div class="flex flex-col h-screen">
                <!-- Header -->
                <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                    <div class="flex items-center gap-3">
                        <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                            <i class="ph-bold ph-arrow-left text-xl"></i>
                        </button>
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 dark:bg-blue-900/20 flex items-center justify-center font-bold text-xl shrink-0">
                            <i class="ph-bold ph-shopping-cart"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                                <span>{{ __('notebook.direct_sales_summary') ?? 'مبيعات فورية' }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                                    <span x-text="totalTodayDirectSalesCount"></span> {{ __('notebook.transaction') ?? 'حركة' }}
                                </span>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-calendar text-xs"></i> 
                                <span x-text="formatDateTime('{{ \Carbon\Carbon::today()->toDateString() }}')"></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction List -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                    <div x-show="isDebtsLoading && todayDirectSalesList.length === 0" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                        <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
                    </div>

                    <template x-if="todayDirectSalesList.length === 0 && !isDebtsLoading">
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="ph-fill ph-receipt text-6xl mb-4 text-gray-300 dark:text-gray-600 opacity-50"></i>
                            <p class="text-sm font-bold">{{ __('notebook.no_registered_transactions') }}</p>
                        </div>
                    </template>

                    <template x-if="todayDirectSalesList.length > 0">
                        <div>
                            <template x-for="tx in todayDirectSalesList" :key="tx.id">
                                <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 mb-3 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-blue-50 text-blue-500 dark:bg-blue-900/20">
                                                <i class="ph-bold text-lg ph-arrow-up-right"></i>
                                            </div>
                                            <div>
                                                <!-- Customer Name -->
                                                <p class="font-black text-sm text-gray-900 dark:text-gray-100 flex items-center gap-1">
                                                    <i class="ph-fill ph-user text-xs text-primary/70"></i> 
                                                    <span x-text="tx.customer ? tx.customer.name : '{{ __('notebook.customer_deleted') ?? 'زبون محذوف' }}'"></span>
                                                </p>
                                                
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 font-bold" x-text="tx.description"></p>
                                                
                                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-medium flex items-center gap-1">
                                                    <i class="ph-fill ph-clock text-xs"></i> 
                                                    <span x-text="formatDateTime(tx.transaction_date || tx.created_at)"></span>
                                                </p>
                                                <!-- Cashier Name -->
                                                <template x-if="tx.cashier_name">
                                                    <p class="text-[10px] text-primary/80 dark:text-primary/60 mt-0.5 font-bold flex items-center gap-1">
                                                        <i class="ph-fill ph-user text-[10px]"></i> {{ __('notebook.added_by') }}: <span x-text="tx.cashier_name"></span>
                                                    </p>
                                                </template>
                                            </div>
                                        </div>
                                        <div class="text-left font-black shrink-0 text-xl text-blue-500 flex flex-col items-end">
                                            <div>
                                                <span x-text="'+' + Number(tx.amount).toFixed(1)"></span> <span class="text-[11px] font-normal">₪</span>
                                            </div>
                                        </div>
                                    </div>
                                    @if(auth('casher')->user()->hasAbility('notebook_update') || auth('casher')->user()->hasAbility('notebook_delete'))
                                    <template x-if="tx.customer && tx.customer.status != 0">
                                        <div class="flex items-center gap-2 border-t dark:border-gray-800 pt-3 mt-1">
                                            @if(auth('casher')->user()->hasAbility('notebook_update'))
                                            <button @click="activeCustomer = tx.customer; editTransaction(tx);" class="flex-1 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                                <i class="ph-bold ph-pencil-simple"></i> {{ __('notebook.edit') }}
                                            </button>
                                            @endif
                                            @if(auth('casher')->user()->hasAbility('notebook_delete'))
                                            <button @click="activeCustomer = tx.customer; deleteTransaction(tx.id);" class="flex-1 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                                <i class="ph-bold ph-trash"></i> {{ __('notebook.delete') }}
                                            </button>
                                            @endif
                                        </div>
                                    </template>
                                    @endif
                                </div>
                            </template>
                        </div>
                    </template>

                    <template x-if="todayDirectSalesTotalCount > todayDirectSalesList.length">
                        <div class="mt-6 flex justify-center pb-4">
                            <button @click="loadMoreDirectSales" :disabled="isDebtsLoading" class="group relative px-6 py-3 text-xs font-bold text-gray-600 bg-white hover:bg-gray-100 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-gray-300 rounded-full transition-all duration-300 flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="flex items-center gap-2" x-show="!isDebtsLoading">
                                    {{ __('notebook.show_older_transactions') ?? 'عرض المزيد من العمليات' }} <i class="ph-bold ph-caret-down group-hover:translate-y-0.5 transition-transform"></i>
                                </span>
                                <span class="flex items-center gap-2" x-show="isDebtsLoading" style="display: none;">
                                    <i class="ph-bold ph-spinner-gap animate-spin text-lg"></i> {{ __('notebook.loading') ?? 'جاري التحميل...' }}
                                </span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
