<!-- Accounts Drawer -->
<div x-show="showAccountsSheet" 
     class="fixed inset-0 z-[60] flex" 
     style="display: none;" x-cloak>
     
    <!-- Backdrop -->
    <div x-show="showAccountsSheet" 
         x-transition:enter="transition-opacity ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0" 
         @click="showAccountsSheet = false" 
         class="drawer-backdrop pointer-events-auto">
    </div>

    <!-- Drawer Panel -->
    <div x-show="showAccountsSheet" 
         x-transition:enter="transform transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transform transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="drawer-panel flex flex-col pointer-events-auto">
         
        <!-- Handle for Mobile -->
        <div class="w-full flex justify-center pt-3 pb-2 cursor-pointer md:hidden" @click="showAccountsSheet = false">
            <div class="w-12 h-1.5 bg-gray-300 dark:bg-gray-700 rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="p-6 pb-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center shrink-0">
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
