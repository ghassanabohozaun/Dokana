    <!-- Customer Ledger Overlay -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'ledgerModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'ledgerModal') show = false"
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
            <template x-if="activeCustomer">
                <div class="flex flex-col h-screen">
                    <!-- Header -->
                    <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0 sticky top-0">
                        <div class="flex items-center gap-3">
                            <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300 mr-2 rtl:ml-2 rtl:mr-0 rtl:-scale-x-100">
                                <i class="ph-bold ph-arrow-left text-xl"></i>
                            </button>
                            <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xl shrink-0">
                                <span x-text="activeCustomer.name.substring(0, 1)"></span>
                            </div>
                            <div>
                                <h2 class="font-bold text-lg text-gray-900 dark:text-white flex flex-wrap items-center gap-2">
                                    <span x-text="activeCustomer.name"></span>

                                    <button @click="openEditCustomerModal()" class="text-blue-500 hover:text-blue-600 bg-blue-50 hover:bg-blue-100 dark:text-blue-400 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 transition-colors w-7 h-7 flex items-center justify-center rounded-full shadow-sm border border-blue-100 dark:border-blue-800/50 shrink-0">
                                        <i class="ph-bold ph-pencil-simple text-sm"></i>
                                    </button>
                                    <template x-if="activeCustomer.status == 0">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">{{ __('notebook.disabled') ?? 'معطل' }}</span>
                                    </template>
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium flex items-center gap-1" x-html="activeCustomer.phone ? '<i class=\'ph-fill ph-phone text-xs\'></i> ' + activeCustomer.phone : '-'"></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Balance & Actions -->
                    <div class="p-4 bg-white dark:bg-darkCard shadow-sm z-10 relative shrink-0">
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mb-0.5">{{ __('notebook.current_balance') }}</p>
                                <h3 class="text-2xl font-black tracking-tight" :class="activeCustomer.balance > 0 ? 'text-red-500' : (activeCustomer.balance < 0 ? 'text-emerald-500' : 'text-gray-800 dark:text-white')">
                                    <span x-text="Math.abs(activeCustomer.balance).toFixed(1)"></span> <span class="text-sm font-normal opacity-80">{{ __('notebook.currency') }}</span>
                                </h3>
                            </div>
                            <div class="flex flex-col items-end gap-1.5">
                                <div class="text-[11px] font-bold px-2.5 py-1 rounded-md" :class="activeCustomer.balance > 0 ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : (activeCustomer.balance < 0 ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400')">
                                    <span x-text="activeCustomer.balance > 0 ? '{{ __('notebook.owes_debt') }}' : (activeCustomer.balance < 0 ? '{{ __('notebook.has_credit') }}' : '{{ __('notebook.paid') }}')"></span>
                                </div>

                            </div>
                        </div>

                        <!-- Max Debt Limit Progress Bar -->
                        <template x-if="activeCustomer.max_debt_limit !== null && activeCustomer.max_debt_limit > 0">
                            <div class="mb-4">
                                <div class="flex justify-between items-center text-[11px] mb-2 gap-2">
                                    <span class="text-gray-500 font-bold whitespace-nowrap">{{ __('notebook.limit') }}: <span x-text="activeCustomer.max_debt_limit"></span></span>
                                    <span x-show="activeCustomer.balance > activeCustomer.max_debt_limit" 
                                          class="font-bold px-2 py-0.5 rounded bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 whitespace-nowrap" 
                                          x-text="'{{ __('notebook.exceeded_by') }} ' + (activeCustomer.balance - activeCustomer.max_debt_limit).toFixed(1)">
                                    </span>
                                    <span x-show="activeCustomer.balance <= activeCustomer.max_debt_limit" 
                                          class="font-bold px-2 py-0.5 rounded bg-blue-50 text-primary dark:bg-blue-900/30 dark:text-blue-400 whitespace-nowrap" 
                                          x-text="'{{ __('notebook.remaining') }}: ' + Math.max(0, activeCustomer.max_debt_limit - Math.max(0, activeCustomer.balance)).toFixed(1)">
                                    </span>
                                </div>
                                <div class="h-2 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full transition-all duration-300 rounded-full" 
                                         :class="(activeCustomer.balance >= activeCustomer.max_debt_limit) ? 'bg-gradient-to-r from-red-500 to-rose-500' : ((Math.max(0, activeCustomer.balance) / activeCustomer.max_debt_limit) > 0.8 ? 'bg-gradient-to-r from-orange-400 to-red-400' : 'bg-gradient-to-r from-blue-400 to-primary')"
                                         :style="`width: ${Math.min(100, Math.max(0, (Math.max(0, activeCustomer.balance) / activeCustomer.max_debt_limit) * 100))}%`">
                                    </div>
                                </div>
                            </div>
                        </template>

                        @if(auth('casher')->user()->hasAbility('notebook_create'))
                        <template x-if="activeCustomer.is_walk_in">
                            <div class="grid grid-cols-1 gap-2">
                                <button @click="openTxModal('direct_sale')"
                                    class="flex items-center justify-center gap-2 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 py-2.5 rounded-xl font-bold transition-all border border-emerald-100 dark:border-emerald-900/30 text-sm hover:bg-emerald-100 dark:hover:bg-emerald-900/40 active:scale-95 group">
                                    <i class="ph-bold ph-money text-lg"></i>
                                    {{ __('notebook.direct_payment') ?? 'دفع 💵' }}
                                </button>
                            </div>
                        </template>

                        <template x-if="!activeCustomer.is_walk_in">
                            <div class="grid grid-cols-3 gap-2">
                                <button @click="if(activeCustomer.status != 0) openTxModal('debt')"
                                    :disabled="activeCustomer.status == 0"
                                    :class="activeCustomer.status == 0 ? 'opacity-40 cursor-not-allowed grayscale' : 'hover:bg-red-100 dark:hover:bg-red-900/40 active:scale-95 group'"
                                    class="flex items-center justify-center gap-1 bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400 py-2.5 px-1 rounded-xl font-bold transition-all border border-red-100 dark:border-red-900/30 text-[12px] sm:text-[13px]">
                                    <i class="ph-bold ph-minus text-base"></i>
                                    <span class="truncate">{{ __('notebook.new_debt') }}</span>
                                </button>
                                <button @click="openTxModal('payment')"
                                    class="flex items-center justify-center gap-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400 py-2.5 px-1 rounded-xl font-bold transition-all border border-emerald-100 dark:border-emerald-900/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 active:scale-95 group text-[12px] sm:text-[13px]">
                                    <i class="ph-bold ph-plus text-base"></i>
                                    <span class="truncate">{{ __('notebook.payment_transfer') }}</span>
                                </button>
                                <button @click="openTxModal('direct_sale')"
                                    class="flex items-center justify-center gap-1 bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 py-2.5 px-1 rounded-xl font-bold transition-all border border-blue-100 dark:border-blue-900/30 text-[12px] sm:text-[13px] hover:bg-blue-100 dark:hover:bg-blue-900/40 active:scale-95 group">
                                    <i class="ph-bold ph-shopping-cart text-base"></i>
                                    <span class="truncate">{{ __('notebook.direct_sale') ?? 'شراء فوري' }}</span>
                                </button>
                            </div>
                        </template>
                        @endif
                        <template x-if="activeCustomer.status == 0">
                            <div class="mt-3 text-center text-xs font-bold text-red-500 dark:text-red-400 bg-red-50 dark:bg-red-900/10 py-1.5 rounded-lg border border-red-100 dark:border-red-900/20">
                                <i class="ph-fill ph-warning-circle"></i> {{ __('notebook.customer_disabled_msg') }}
                            </div>
                        </template>
                    </div>

                    <!-- Transaction List -->
                    <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                        <div x-show="isLedgerLoading && ledgerTransactions.length === 0" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center backdrop-blur-sm" x-cloak>
                            <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
                        </div>

                        <template x-if="ledgerTransactions.length === 0 && !isLedgerLoading">
                            <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                                <i class="ph-fill ph-receipt text-6xl mb-4 text-gray-300 dark:text-gray-600 opacity-50"></i>
                                <p class="text-sm font-bold">{{ __('notebook.no_registered_transactions') }}</p>
                            </div>
                        </template>

                        <template x-if="ledgerTransactions.length > 0">
                            <div>
                                <template x-for="tx in ledgerTransactions" :key="tx.id">
                                    <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3 mb-3 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" :class="tx.type === 'debt' ? 'bg-red-50 text-red-500 dark:bg-red-900/20' : 'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20'">
                                                    <i class="ph-bold text-lg" :class="tx.type === 'debt' ? 'ph-arrow-up-right' : 'ph-arrow-down-left'"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="tx.description"></p>
                                                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 font-medium flex items-center gap-1">
                                                        <i class="ph-fill ph-calendar text-xs"></i> 
                                                        <span x-text="formatDateTime(tx.transaction_date || tx.created_at)"></span>
                                                    </p>
                                                    <!-- Cashier Name -->
                                                    <template x-if="tx.cashier_name">
                                                        <p class="text-[10px] text-primary/80 dark:text-primary/60 mt-0.5 font-bold flex items-center gap-1">
                                                            <i class="ph-fill ph-user text-[10px]"></i> {{ __('notebook.added_by') }}: <span x-text="tx.cashier_name"></span>
                                                        </p>
                                                    </template>
                                                    <!-- Bank Account Name -->
                                                    <template x-if="tx.bank_account_name">
                                                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 font-medium flex items-center gap-1 bg-emerald-50 dark:bg-emerald-900/20 px-2 py-0.5 rounded-md inline-flex w-fit">
                                                            <i class="ph-fill ph-bank text-xs"></i> <span x-text="tx.bank_account_name"></span>
                                                        </p>
                                                    </template>
                                                </div>
                                            </div>
                                            <div class="text-left flex flex-col items-end shrink-0">
                                                <div class="font-black text-xl" :class="tx.type === 'debt' ? 'text-red-500' : 'text-emerald-500'">
                                                    <span x-text="(tx.type === 'debt' ? '+' : '-') + Number(tx.amount).toFixed(1)"></span> <span class="text-[11px] font-normal">₪</span>
                                                </div>
                                                <template x-if="tx.running_balance !== undefined && tx.running_balance !== null">
                                                    <div class="text-[10px] font-bold mt-1 px-2 py-0.5 rounded-md" 
                                                         :class="Number(tx.running_balance) > 0 ? 'bg-red-50 text-red-500 dark:bg-red-900/20 dark:text-red-400' : (Number(tx.running_balance) < 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400')">
                                                        {{ __('notebook.balance_after') ?? 'الرصيد بعدها:' }} <span x-text="Math.abs(Number(tx.running_balance)).toFixed(1)"></span> ₪
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                        @if(auth('casher')->user()->hasAbility('notebook_update') || auth('casher')->user()->hasAbility('notebook_delete'))
                                        <template x-if="!(tx.type === 'debt' && tx.linked_transaction_id !== null) && activeCustomer.status != 0">
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
                                        </template>
                                        @endif
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="totalLedgerTransactions > ledgerTransactions.length">
                            <div class="mt-6 flex justify-center pb-4">
                                <button @click="loadMoreLedger" :disabled="isLedgerLoading" class="group relative px-6 py-3 text-xs font-bold text-gray-600 bg-white hover:bg-gray-100 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 dark:text-gray-300 rounded-full transition-all duration-75 flex items-center justify-center gap-2 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span class="flex items-center gap-2" x-show="!isLedgerLoading">
                                        {{ __('notebook.show_older_transactions') }} <i class="ph-bold ph-caret-down group-hover:translate-y-0.5 transition-transform"></i>
                                    </span>
                                    <span class="flex items-center gap-2" x-show="isLedgerLoading" style="display: none;">
                                        <i class="ph-bold ph-spinner-gap animate-spin text-lg"></i> {{ __('notebook.loading') ?? 'جاري التحميل...' }}
                                    </span>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
