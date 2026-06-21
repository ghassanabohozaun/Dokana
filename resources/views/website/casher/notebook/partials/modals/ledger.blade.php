    <!-- Customer Ledger Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'ledgerModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'ledgerModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-40 flex flex-col items-center justify-end sm:justify-center">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
        <div x-show="show" x-transition.translate.y.bottom class="relative bg-gray-50 dark:bg-dark w-full max-w-md h-[92vh] sm:h-[85vh] rounded-t-[2rem] sm:rounded-3xl flex flex-col shadow-2xl overflow-hidden border border-white/10 z-10">
            <template x-if="activeCustomer">
                <div class="flex flex-col h-full">
                    <!-- Header -->
                    <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xl">
                                <span x-text="activeCustomer.name.substring(0, 1)"></span>
                            </div>
                            <div>
                                <h2 class="font-bold text-lg text-gray-900 dark:text-white" x-text="activeCustomer.name"></h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium" x-html="activeCustomer.phone ? '<i class=\'ph-fill ph-phone text-xs\'></i> ' + activeCustomer.phone : '-'"></p>
                            </div>
                        </div>
                        <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Balance & Actions -->
                    <div class="p-5 bg-white dark:bg-darkCard shadow-sm z-10 relative shrink-0">
                        <div class="flex justify-between items-end mb-5">
                            <div>
                                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('notebook.current_balance') }}</p>
                                <h3 class="text-3xl font-black tracking-tight" :class="activeCustomer.balance > 0 ? 'text-red-500' : (activeCustomer.balance < 0 ? 'text-emerald-500' : 'text-gray-800 dark:text-white')">
                                    <span x-text="Math.abs(activeCustomer.balance).toFixed(1)"></span> <span class="text-base font-normal opacity-80">{{ __('notebook.currency') }}</span>
                                </h3>
                            </div>
                            <div class="text-xs font-bold px-3 py-1.5 rounded-full" :class="activeCustomer.balance > 0 ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : (activeCustomer.balance < 0 ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400')">
                                <span x-text="activeCustomer.balance > 0 ? '{{ __('notebook.owes_debt') }}' : (activeCustomer.balance < 0 ? '{{ __('notebook.has_credit') }}' : '{{ __('notebook.paid') }}')"></span>
                            </div>
                        </div>
                        @if(auth('casher')->user()->hasAbility('notebook_create'))
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="openTxModal('debt')" class="flex flex-col items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 py-3 rounded-[1rem] font-bold transition-all active:scale-95 border border-red-100 dark:border-red-900/30 group">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-red-900/40 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-minus text-xl"></i>
                                </div>
                                {{ __('notebook.new_debt') }}
                            </button>
                            <button @click="openTxModal('payment')" class="flex flex-col items-center justify-center gap-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40 dark:text-emerald-400 py-3 rounded-[1rem] font-bold transition-all active:scale-95 border border-emerald-100 dark:border-emerald-900/30 group">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-emerald-900/40 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-xl"></i>
                                </div>
                                {{ __('notebook.payment_transfer') }}
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Transaction List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                        <div x-show="isLedgerLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center rounded-xl backdrop-blur-sm" x-cloak>
                            <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
                        </div>

                        <template x-if="ledgerTransactions.length === 0">
                            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                                <i class="ph-fill ph-receipt text-5xl mb-3 text-gray-300 dark:text-gray-600 opacity-50"></i>
                                <p class="text-sm font-bold">{{ __('notebook.no_registered_transactions') }}</p>
                            </div>
                        </template>

                        <template x-if="ledgerTransactions.length > 0">
                            <div>
                                <template x-for="tx in ledgerTransactions" :key="tx.id">
                                    <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 mb-3">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" :class="tx.type === 'debt' ? 'bg-red-50 text-red-500 dark:bg-red-900/20' : 'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20'">
                                                    <i class="ph-bold text-lg" :class="tx.type === 'debt' ? 'ph-arrow-up-right' : 'ph-arrow-down-left'"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="tx.description"></p>
                                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 font-medium flex items-center gap-1">
                                                        <i class="ph-fill ph-calendar text-xs"></i> 
                                                        <span x-text="(tx.transaction_date || tx.created_at || '').substring(0,10)"></span>
                                                    </p>
                                                    <!-- Cashier Name -->
                                                    <template x-if="tx.cashier_name">
                                                        <p class="text-[10px] text-primary/80 dark:text-primary/60 mt-0.5 font-bold flex items-center gap-1">
                                                            <i class="ph-fill ph-user text-[10px]"></i> {{ __('notebook.added_by') }}: <span x-text="tx.cashier_name"></span>
                                                        </p>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-left font-black shrink-0 text-lg" :class="tx.type === 'debt' ? 'text-red-500' : 'text-emerald-500'">
                                                <span x-text="(tx.type === 'debt' ? '+' : '-') + Number(tx.amount).toFixed(1)"></span> <span class="text-[10px] font-normal">₪</span>
                                            </div>
                                        </div>
                                        @if(auth('casher')->user()->hasAbility('notebook_update') || auth('casher')->user()->hasAbility('notebook_delete'))
                                        <div class="flex items-center gap-2 border-t dark:border-gray-800 pt-3 mt-1">
                                            @if(auth('casher')->user()->hasAbility('notebook_update'))
                                            <button @click="editTransaction(tx)" class="flex-1 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                                <i class="ph-bold ph-pencil-simple"></i> {{ __('notebook.edit') }}
                                            </button>
                                            @endif
                                            @if(auth('casher')->user()->hasAbility('notebook_delete'))
                                            <button @click="deleteTransaction(tx.id)" class="flex-1 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                                <i class="ph-bold ph-trash"></i> {{ __('notebook.delete') }}
                                            </button>
                                            @endif
                                        </div>
                                        @endif
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="totalLedgerTransactions > ledgerTransactions.length">
                            <div class="mt-6 flex justify-center pb-2">
                                <button @click="loadMoreLedger" class="group relative px-5 py-2.5 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full transition-all duration-300 flex items-center justify-center gap-2 active:scale-95">
                                    <span class="flex items-center gap-2">
                                        {{ __('notebook.show_older_transactions') }} <i class="ph-bold ph-caret-down group-hover:translate-y-0.5 transition-transform"></i>
                                    </span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
