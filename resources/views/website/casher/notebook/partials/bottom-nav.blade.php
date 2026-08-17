<div class="fixed bottom-4 sm:bottom-6 left-1/2 transform -translate-x-1/2 w-[96%] sm:w-max min-w-[340px] z-40 pb-safe pointer-events-none transition-all duration-300">
    <div class="bg-white/90 dark:bg-[#151e32]/90 backdrop-blur-2xl border border-white/50 dark:border-gray-700/50 shadow-2xl rounded-full p-2 flex justify-between items-center relative pointer-events-auto gap-1 sm:gap-2">
        
        <!-- Customers Tab -->
        <button @click="switchTab('customers')"
            class="flex items-center justify-center gap-1.5 py-3 px-3.5 sm:px-4 rounded-full transition-all duration-300 group"
            :class="activeTab === 'customers' ? 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-xl transition-all duration-300 ph-users" :class="activeTab === 'customers' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'customers' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.customers') ?? 'العملاء' }}
            </span>
        </button>

        <!-- Suppliers Tab -->
        <button @click="switchTab('suppliers')"
            class="flex items-center justify-center gap-1.5 py-3 px-3.5 sm:px-4 rounded-full transition-all duration-300 group"
            :class="activeTab === 'suppliers' ? 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-xl transition-all duration-300 ph-truck" :class="activeTab === 'suppliers' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'suppliers' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.suppliers') ?? 'الموردين' }}
            </span>
        </button>

        <!-- Withdrawals Tab -->
        <button @click="switchTab('withdrawals')"
            class="flex items-center justify-center gap-1.5 py-3 px-3.5 sm:px-4 rounded-full transition-all duration-300 group"
            :class="activeTab === 'withdrawals' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 shadow-[inset_0_1px_2px_rgba(255,255,255,0.5)] dark:shadow-none' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-800/50'">
            <i class="text-xl transition-all duration-300 ph-wallet" :class="activeTab === 'withdrawals' ? 'ph-fill drop-shadow-md' : 'ph-bold group-hover:scale-110'"></i>
            <span class="text-xs font-bold whitespace-nowrap overflow-hidden transition-all duration-300"
                  :class="activeTab === 'withdrawals' ? 'w-auto opacity-100 ml-1 rtl:mr-1 rtl:ml-0' : 'w-0 opacity-0'">
                {{ __('notebook.withdrawals') ?? 'المسحوبات' }}
            </span>
        </button>

        <!-- Divider -->
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mx-0.5 shrink-0"></div>

        <!-- Dynamic Action Button (Add) -->
        <button @click="activeTab === 'customers' ? openAddCustomer() : (activeTab === 'suppliers' ? openAddSupplier() : openWithdrawalModal())"
            class="w-11 h-11 shrink-0 bg-gradient-to-tr rounded-full flex items-center justify-center text-white shadow-xl transition-all duration-300 hover:scale-105 active:scale-95 group relative"
            :class="activeTab === 'customers' ? 'from-cyan-500 to-blue-500 shadow-cyan-500/40' : (activeTab === 'suppliers' ? 'from-amber-500 to-orange-500 shadow-amber-500/40' : 'from-rose-500 to-red-500 shadow-rose-500/40')"
            title="إضافة">
            <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
            <i class="ph-bold text-xl drop-shadow-md transition-all duration-300" 
               :class="activeTab === 'customers' ? 'ph-user-plus' : (activeTab === 'suppliers' ? 'ph-truck' : 'ph-money')"></i>
        </button>

        <!-- Divider -->
        <div class="h-8 w-px bg-gray-200 dark:bg-gray-700 mx-1 shrink-0"></div>

        <!-- Profile -->
        <div class="flex items-center gap-2 px-1.5 py-1.5 h-11 shrink-0 rounded-full transition-all duration-300 group cursor-default hover:bg-gray-50 dark:hover:bg-gray-800/50"
             title="{{ auth('casher')->user() && auth('casher')->user()->name ? explode(' ', auth('casher')->user()->name)[0] : 'المستخدم' }}">
            @if (auth('casher')->user() && auth('casher')->user()->userPhoto())
                <div class="w-8 h-8 border-[2px] border-white dark:border-gray-700 rounded-full overflow-hidden shadow-sm shrink-0">
                    <img src="{{ auth('casher')->user()->userPhoto() }}" class="w-full h-full object-cover" alt="User">
                </div>
            @else
                <div class="w-8 h-8 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full border border-white dark:border-gray-600 shadow-sm text-gray-400 dark:text-gray-300 shrink-0">
                    <i class="ph-fill ph-user text-base"></i>
                </div>
            @endif
            <span class="text-xs font-bold text-gray-600 dark:text-gray-300 whitespace-nowrap max-w-[70px] truncate mr-0.5 rtl:ml-0.5 rtl:mr-0">
                {{ auth('casher')->user() && auth('casher')->user()->name ? explode(' ', auth('casher')->user()->name)[0] : 'المستخدم' }}
            </span>
        </div>

        <!-- Logout Button -->
        <a href="{{ route('website.casher.logout') }}" 
            class="w-11 h-11 shrink-0 flex items-center justify-center rounded-full transition-all duration-300 text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 group"
            title="{{ __('notebook.logout') ?? 'خروج' }}">
            <i class="ph-bold ph-sign-out text-xl group-hover:scale-110 transition-transform group-hover:drop-shadow-sm"></i>
        </a>
    </div>
</div>
