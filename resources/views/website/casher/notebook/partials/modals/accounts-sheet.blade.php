<!-- Accounts Bottom Sheet -->
<div x-show="showAccountsSheet" class="fixed inset-0 z-50 flex items-end justify-center pointer-events-none" style="display: none;">
    <!-- Backdrop -->
    <div x-show="showAccountsSheet" 
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200" 
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" 
         @click="showAccountsSheet = false" 
         class="absolute inset-0 bg-black/60 backdrop-blur-sm pointer-events-auto">
    </div>

    <!-- Sheet Panel -->
    <div x-show="showAccountsSheet" 
         x-transition:enter="transition-transform ease-out duration-300"
         x-transition:enter-start="translate-y-full" 
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition-transform ease-in duration-200" 
         x-transition:leave-start="translate-y-0" 
         x-transition:leave-end="translate-y-full"
         class="w-full max-w-md bg-gray-50 dark:bg-darkCard rounded-t-3xl shadow-2xl relative z-10 pointer-events-auto flex flex-col max-h-[85vh]">
         
        <!-- Handle -->
        <div class="w-full flex justify-center pt-3 pb-2 cursor-pointer" @click="showAccountsSheet = false">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="px-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ Lang::has('notebook.store_accounts') ? __('notebook.store_accounts') : 'حسابات المتجر' }}</h3>
            <button @click="showAccountsSheet = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 text-gray-500 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x"></i>
            </button>
        </div>

        <!-- Body: Accounts List -->
        <div class="p-6 overflow-y-auto hide-scrollbar flex-1">
            <div class="space-y-4">
                <template x-for="account in storeAccounts" :key="account.id">
                    <div class="bg-white dark:bg-[#1a2235] p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between group transition-all hover:shadow-md">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center text-xl shrink-0">
                                <i class="ph-bold ph-wallet"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 dark:text-white text-md" x-text="account.name"></h4>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <div class="text-xs text-gray-500 mb-1">{{ Lang::has('notebook.current_balance') ? __('notebook.current_balance') : 'الرصيد الحالي' }}</div>
                            <span class="font-bold text-blue-500 text-xl block">
                                <span x-text="Number(bankBalances[account.id] || 0).toFixed(2)"></span> <span class="text-sm">₪</span>
                            </span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
