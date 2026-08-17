<div class="fixed bottom-3 sm:bottom-6 left-1/2 transform -translate-x-1/2 w-max max-w-[96%] z-40 pb-safe pointer-events-none transition-all duration-300">
    <div class="bg-white/95 dark:bg-[#151e32]/95 backdrop-blur-2xl border border-white/60 dark:border-gray-700/60 shadow-2xl rounded-full p-1.5 sm:p-2 flex justify-center items-center relative pointer-events-auto gap-0.5 sm:gap-2">
        
        <!-- Customers Tab -->
        <button @click="switchTab('customers')"
            class="flex items-center justify-center gap-1 sm:gap-1.5 py-2 sm:py-2.5 px-2.5 sm:px-4 rounded-full transition-all duration-300 group shrink-0"
            :class="activeTab === 'customers' ? 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-lg sm:text-xl transition-all duration-300 ph-users" :class="activeTab === 'customers' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-[11px] sm:text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'customers' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.customers') ?? 'العملاء' }}
            </span>
        </button>

        <!-- Suppliers Tab -->
        <button @click="switchTab('suppliers')"
            class="flex items-center justify-center gap-1 sm:gap-1.5 py-2 sm:py-2.5 px-2.5 sm:px-4 rounded-full transition-all duration-300 group shrink-0"
            :class="activeTab === 'suppliers' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-lg sm:text-xl transition-all duration-300 ph-truck" :class="activeTab === 'suppliers' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-[11px] sm:text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'suppliers' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.suppliers') ?? 'الموردين' }}
            </span>
        </button>

        <!-- Withdrawals Tab -->
        <button @click="switchTab('withdrawals')"
            class="flex items-center justify-center gap-1 sm:gap-1.5 py-2 sm:py-2.5 px-2.5 sm:px-4 rounded-full transition-all duration-300 group shrink-0"
            :class="activeTab === 'withdrawals' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-lg sm:text-xl transition-all duration-300 ph-wallet" :class="activeTab === 'withdrawals' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-[11px] sm:text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'withdrawals' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.withdrawals') ?? 'المسحوبات' }}
            </span>
        </button>

        <!-- Divider -->
        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-gray-700 mx-0.5 shrink-0"></div>

        <!-- Dynamic Action Button (Add) -->
        <button @click="activeTab === 'customers' ? openAddCustomer() : (activeTab === 'suppliers' ? openAddSupplier() : openWithdrawalModal())"
            class="w-9 h-9 sm:w-11 sm:h-11 shrink-0 bg-gradient-to-tr rounded-full flex items-center justify-center text-white shadow-lg transition-all duration-300 hover:scale-105 active:scale-95 group relative"
            :class="activeTab === 'customers' ? 'from-cyan-500 to-blue-500 shadow-cyan-500/40' : (activeTab === 'suppliers' ? 'from-amber-500 to-orange-500 shadow-amber-500/40' : 'from-rose-500 to-red-500 shadow-rose-500/40')"
            title="إضافة">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
            <i class="ph-bold text-lg sm:text-xl drop-shadow-md transition-all duration-300" 
               :class="activeTab === 'customers' ? 'ph-user-plus' : (activeTab === 'suppliers' ? 'ph-truck' : 'ph-money')"></i>
        </button>

        <!-- Divider -->
        <div class="h-6 sm:h-8 w-px bg-gray-200 dark:bg-gray-700 mx-0.5 sm:mx-1 shrink-0"></div>

        <!-- Profile -->
        <div class="flex items-center gap-1.5 p-1 sm:px-1.5 sm:py-1.5 h-9 sm:h-11 shrink-0 rounded-full transition-all duration-300 group cursor-default hover:bg-gray-50 dark:hover:bg-gray-800/50"
             title="{{ auth('casher')->user() && auth('casher')->user()->name ? auth('casher')->user()->name : 'المستخدم' }}">
            @if (auth('casher')->user() && auth('casher')->user()->userPhoto())
                <div class="w-7 h-7 sm:w-8 sm:h-8 border-[1.5px] border-white dark:border-gray-700 rounded-full overflow-hidden shadow-sm shrink-0">
                    <img src="{{ auth('casher')->user()->userPhoto() }}" class="w-full h-full object-cover" alt="User">
                </div>
            @else
                <div class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full border border-white dark:border-gray-600 shadow-sm text-gray-400 dark:text-gray-300 shrink-0">
                    <i class="ph-fill ph-user text-sm sm:text-base"></i>
                </div>
            @endif
            <span class="hidden md:inline text-xs font-bold text-gray-600 dark:text-gray-300 whitespace-nowrap max-w-[70px] truncate mr-0.5 rtl:ml-0.5 rtl:mr-0">
                {{ auth('casher')->user() && auth('casher')->user()->name ? explode(' ', auth('casher')->user()->name)[0] : 'المستخدم' }}
            </span>
        </div>

        <!-- Logout Button -->
        <a href="{{ route('website.casher.logout') }}" 
            class="w-8 h-8 sm:w-11 sm:h-11 shrink-0 flex items-center justify-center rounded-full transition-all duration-300 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 group"
            title="{{ __('notebook.logout') ?? 'خروج' }}">
            <i class="ph-bold ph-sign-out text-lg sm:text-xl group-hover:scale-110 transition-transform group-hover:drop-shadow-sm"></i>
        </a>
    </div>
</div>
