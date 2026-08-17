    <!-- Today Collections Overlay -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'todayCollectionsModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'todayCollectionsModal') show = false"
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
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20 flex items-center justify-center font-bold text-xl shrink-0">
                            <i class="ph-bold ph-trend-up"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                                <span>{{ __('notebook.collections') ?? 'تحصيلات اليوم' }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                    <span x-text="totalTodayCollectionsCount"></span> {{ __('notebook.transactions_count') ?? 'عملية' }}
                                </span>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-clock-counter-clockwise text-xs"></i> 
                                <span>{{ __('notebook.collections_history') ?? 'سجل جميع الدفعات والمبيعات' }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Transaction List -->
                <div class="flex-1 overflow-y-auto p-4 pb-28 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                    <div x-show="isCollectionsLoading && todayCollectionsList.length === 0" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                        <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
                    </div>

                    <template x-if="todayCollectionsList.length === 0 && !isCollectionsLoading">
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <i class="ph-fill ph-receipt text-6xl mb-4 text-gray-300 dark:text-gray-600 opacity-50"></i>
                            <p class="text-sm font-bold">{{ __('notebook.no_registered_transactions') }}</p>
                        </div>
                    </template>

                    <template x-if="todayCollectionsList.length > 0">
                        <div>
                            <template x-for="tx in todayCollectionsList" :key="tx.id">
                                <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 mb-3 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20">
                                                <i class="ph-bold text-lg ph-arrow-down-left"></i>
                                            </div>
                                            <div>
                                                <!-- Customer Name -->
                                                <p @click="if(tx.customer) { activeCustomer = tx.customer; openLedger(tx.customer.id); }" 
                                                   class="font-black text-sm text-gray-900 dark:text-gray-100 flex items-center gap-1.5"
                                                   :class="tx.customer ? 'cursor-pointer hover:text-primary transition-colors' : ''">
                                                    <i class="ph-fill ph-user text-xs text-primary/70"></i> 
                                                    <span x-text="tx.customer ? tx.customer.name : '{{ __('notebook.customer_deleted') ?? 'زبون محذوف' }}'"></span>
                                                    <template x-if="tx.type === 'direct_sale'">
                                                        <span class="text-[9px] bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 px-2 py-0.5 rounded-md font-bold">{{ __('notebook.direct_sale') ?? 'شراء فوري' }}</span>
                                                    </template>
                                                </p>
                                                
                                                <!-- Description -->
                                                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 font-bold" x-show="tx.description" x-text="tx.description"></p>
                                                
                                                <!-- Date / Time -->
                                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 font-medium flex items-center gap-1">
                                                    <i class="ph-fill ph-clock text-xs"></i> 
                                                    <span x-text="formatDateTime(tx.transaction_date || tx.created_at)"></span>
                                                </p>

                                                <!-- Bank Account Badge -->
                                                <template x-if="tx.bank_account_name">
                                                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 font-medium flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-md inline-flex w-fit">
                                                        <i class="ph-fill ph-bank text-xs"></i> <span x-text="tx.bank_account_name"></span>
                                                    </p>
                                                </template>

                                                <!-- Cashier Name -->
                                                <template x-if="tx.cashier_name">
                                                    <p class="text-[10px] text-primary/80 dark:text-primary/60 mt-0.5 font-bold flex items-center gap-1">
                                                        <i class="ph-fill ph-user text-[10px]"></i> {{ __('notebook.added_by') }}: <span x-text="tx.cashier_name"></span>
                                                    </p>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Amount -->
                                        <div class="text-left font-black shrink-0 text-xl text-emerald-500 flex flex-col items-end">
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
                                            <button @click="activeCustomer = tx.customer; deleteTransaction(tx.id);" class="flex-1 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
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

                    <template x-if="totalTodayCollectionsCount > todayCollectionsList.length">
                        <div class="mt-6 mb-10 flex justify-center pb-14 px-2">
                            <button @click="loadMoreCollections" :disabled="isCollectionsLoading" 
                                    class="w-full max-w-sm py-3.5 px-6 text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 shadow-md hover:shadow-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-gray-200 rounded-2xl transition-all duration-150 flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 touch-manipulation cursor-pointer">
                                <span class="flex items-center gap-2" x-show="!isCollectionsLoading">
                                    <span>{{ __('notebook.show_older_transactions') ?? 'عرض المزيد من العمليات' }}</span>
                                    <i class="ph-bold ph-caret-down text-base"></i>
                                </span>
                                <span class="flex items-center gap-2" x-show="isCollectionsLoading" style="display: none;">
                                    <i class="ph-bold ph-spinner-gap animate-spin text-lg text-primary"></i> 
                                    <span>{{ __('notebook.loading') ?? 'جاري التحميل...' }}</span>
                                </span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
