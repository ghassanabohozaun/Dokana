<div class="px-6 py-4 animate-fade-in-up">
    <!-- General Summary Card -->
    <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-5 text-white shadow-[0_10px_30px_rgba(239,68,68,0.3)] mb-6 relative overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
        <div class="absolute -left-4 -bottom-4 w-24 h-24 bg-black/10 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class="ph-bold ph-hand-coins text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg opacity-90">{{ __('notebook.total_withdrawals_today') ?? 'سحوبات اليوم' }}</h3>
            </div>
            <div class="text-3xl font-black mt-2 tracking-tight">
                <span x-text="Number(totalTodayWithdrawals).toFixed(2)"></span> <span class="text-sm font-medium opacity-80">₪</span>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex gap-2 flex-wrap mb-6 pb-1">
        <button @click="withdrawalFilter = 'all'" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold transition-all" :class="withdrawalFilter === 'all' ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard hover:bg-gray-50'">{{ __('notebook.filter_all') ?? 'الكل' }}</button>
        <template x-for="account in storeAccounts" :key="'filter-'+account.id">
            <button @click="withdrawalFilter = account.id" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold transition-all" :class="withdrawalFilter == account.id ? 'bg-primary text-white shadow-md' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard hover:bg-gray-50'" x-text="account.name"></button>
        </template>
    </div>

    <!-- Specific Account Stats -->
    <template x-if="selectedAccountData">
        <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl p-5 text-white shadow-lg mb-6 relative overflow-hidden animate-fade-in-up">
            <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-4 -bottom-4 w-32 h-32 bg-black/10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                        <i class="ph-bold ph-wallet text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-lg opacity-90" x-text="selectedAccountData.name"></h3>
                </div>
                
                <div class="grid grid-cols-2 gap-4 divide-x divide-x-reverse divide-white/20">
                    <div>
                        <div class="text-xs text-indigo-100 mb-1">{{ __('notebook.current_balance') ?? 'الرصيد الحالي' }}</div>
                        <div class="font-bold text-xl">
                            <span x-text="Number(bankBalances[selectedAccountData.id] || 0).toFixed(2)"></span> <span class="text-sm">₪</span>
                        </div>
                    </div>
                    <div class="pl-4">
                        <div class="text-xs text-indigo-100 mb-1">مسحوبات الحساب اليوم</div>
                        <div class="font-bold text-xl text-red-100">
                            <span x-text="Number(selectedAccountTotalWithdrawals).toFixed(2)"></span> <span class="text-sm">₪</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Withdrawals List -->
    <div class="space-y-4 pb-20">
        <h4 class="font-bold text-gray-800 dark:text-gray-200 text-lg mb-3">
            {{ __('notebook.recent_withdrawals') ?? 'سجل السحوبات' }}
        </h4>

        <template x-for="withdrawal in filteredWithdrawals" :key="withdrawal.id">
            <div x-data="{ expanded: false }" class="bg-white dark:bg-[#1a2235] rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 transition-all hover:shadow-md mb-3 overflow-hidden">
                <!-- Main Visible Item -->
                <div @click="expanded = !expanded" class="p-4 flex items-center justify-between cursor-pointer group select-none">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-900/20 text-red-500 flex items-center justify-center text-xl shrink-0 transition-transform" :class="expanded ? 'rotate-90' : ''">
                            <i class="ph-bold ph-arrow-down-left"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 dark:text-white text-md" x-text="withdrawal.reason"></h4>
                            <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                <template x-if="withdrawal.bank_account_name">
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 rounded-full text-[10px]" x-text="withdrawal.bank_account_name"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="font-bold text-red-500 text-lg block" x-text="Number(withdrawal.amount).toFixed(2)"></span>
                        <div class="text-[10px] text-gray-400 mt-0.5" x-text="new Date(withdrawal.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})"></div>
                    </div>
                </div>
                
                <!-- Expanded Details & Actions -->
                <div x-show="expanded" x-collapse x-cloak>
                    <div class="px-4 pb-4 pt-1 flex items-center justify-between border-t border-gray-50 dark:border-gray-800/50 mt-1">
                        <div class="text-xs text-gray-400">
                            <!-- Show who added it if available, else date -->
                            <span x-text="new Date(withdrawal.created_at).toLocaleDateString()"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if (auth('casher')->user()->hasAbility('notebook_delete'))
                                <button @click.stop="deleteWithdrawal(withdrawal.id)"
                                    class="text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors px-3 py-1.5 rounded-lg flex items-center gap-1 text-sm font-bold">
                                    <i class="ph-bold ph-trash"></i>
                                    <span>{{ __('notebook.delete') ?? 'حذف' }}</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="filteredWithdrawals.length === 0">
            <div class="text-center py-10">
                <div
                    class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-check-circle text-4xl text-gray-300 dark:text-gray-600"></i>
                </div>
                <h4 class="font-bold text-gray-500">{{ __('notebook.no_withdrawals') ?? 'لا توجد مسحوبات اليوم' }}</h4>
            </div>
        </template>
    </div>
</div>
