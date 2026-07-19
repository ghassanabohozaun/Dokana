<div class="fixed bottom-0 sm:bottom-4 left-0 right-0 w-full max-w-[1000px] mx-auto z-40 px-0 sm:px-4 pointer-events-none pb-safe">
    
    <!-- Central Smart FAB -->
    <div class="absolute left-1/2 -top-6 transform -translate-x-1/2 z-50 pointer-events-auto">
        <template x-if="activeTab === 'customers'">
            <button @click="openAddCustomer"
                class="w-14 h-14 bg-gradient-to-tr from-[#06b6d4] to-[#3b82f6] rounded-full flex items-center justify-center text-white shadow-lg shadow-cyan-500/40 hover:shadow-cyan-500/60 hover:-translate-y-1 transition-all duration-300 border-[3px] border-white/50 dark:border-gray-800/50 backdrop-blur-md relative group active:scale-95">
                <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                <i class="ph-bold ph-plus text-2xl drop-shadow-md relative z-10"></i>
            </button>
        </template>
        <template x-if="activeTab === 'withdrawals'">
            <button @click="openWithdrawalModal"
                class="w-14 h-14 bg-gradient-to-tr from-rose-500 to-red-600 rounded-full flex items-center justify-center text-white shadow-lg shadow-red-500/40 hover:shadow-red-500/60 hover:-translate-y-1 transition-all duration-300 border-[3px] border-white/50 dark:border-gray-800/50 backdrop-blur-md relative group active:scale-95">
                <span class="absolute inset-0 rounded-full bg-white opacity-0 group-hover:opacity-20 transition-opacity"></span>
                <i class="ph-bold ph-money text-2xl drop-shadow-md relative z-10"></i>
            </button>
        </template>
    </div>

    <!-- Glassmorphic Pill Container -->
    <div class="w-full bg-white/85 dark:bg-[#0f172a]/85 backdrop-blur-xl border-t sm:border border-white/40 dark:border-gray-700/50 shadow-[0_-8px_30px_rgb(0,0,0,0.12)] rounded-t-[1.5rem] sm:rounded-[2rem] px-2 py-2 sm:py-3 flex justify-between items-center relative pointer-events-auto overflow-hidden transition-all duration-300">
        
        <!-- Subtle gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/5 via-transparent to-rose-500/5 opacity-50 pointer-events-none"></div>

        <!-- Right Group -->
        <div class="flex flex-1 justify-around relative z-10">
            <!-- Customers Tab -->
            <button @click="switchTab('customers')"
                class="flex flex-col items-center justify-center py-1.5 transition-all duration-300 w-16 group relative"
                :class="activeTab === 'customers' ? 'text-cyan-500' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300'">
                <div class="relative transition-all duration-300 transform group-active:scale-95"
                    :class="activeTab === 'customers' ? '-translate-y-1' : ''">
                    <i class="text-2xl transition-all" :class="activeTab === 'customers' ? 'ph-fill drop-shadow-[0_2px_8px_rgba(6,182,212,0.5)]' : 'ph-bold'"></i>
                </div>
                <span class="text-xs font-bold mt-1 transition-all" :class="activeTab === 'customers' ? 'opacity-100' : 'opacity-70'">{{ __('notebook.customers') ?? 'العملاء' }}</span>
                <!-- Active Dot -->
                <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-cyan-500 transition-all duration-300" :class="activeTab === 'customers' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>

            <!-- Withdrawals Tab -->
            <button @click="switchTab('withdrawals')"
                class="flex flex-col items-center justify-center py-1.5 transition-all duration-300 w-16 group relative"
                :class="activeTab === 'withdrawals' ? 'text-rose-500' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300'">
                <div class="relative transition-all duration-300 transform group-active:scale-95"
                    :class="activeTab === 'withdrawals' ? '-translate-y-1' : ''">
                    <i class="text-2xl transition-all" :class="activeTab === 'withdrawals' ? 'ph-fill drop-shadow-[0_2px_8px_rgba(244,63,94,0.5)]' : 'ph-bold'"></i>
                </div>
                <span class="text-xs font-bold mt-1 transition-all" :class="activeTab === 'withdrawals' ? 'opacity-100' : 'opacity-70'">{{ __('notebook.withdrawals') ?? 'المسحوبات' }}</span>
                <!-- Active Dot -->
                <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-1.5 h-1.5 rounded-full bg-rose-500 transition-all duration-300" :class="activeTab === 'withdrawals' ? 'opacity-100 scale-100' : 'opacity-0 scale-0'"></div>
            </button>
        </div>

        <!-- Center Spacer for FAB -->
        <div class="w-[70px] shrink-0"></div>

        <!-- Left Group -->
        <div class="flex flex-1 justify-around relative z-10">
            <!-- User Profile Tab -->
            <div class="flex flex-col items-center justify-center py-1.5 text-gray-500 dark:text-gray-400 w-16 group cursor-default">
                <div class="relative transition-all duration-300 transform group-hover:-translate-y-1">
                    @if (auth('casher')->user() && auth('casher')->user()->userPhoto())
                        <div class="w-7 h-7 mb-0.5 border-[2px] border-white dark:border-gray-700 rounded-full overflow-hidden shadow-sm ring-2 ring-transparent group-hover:ring-gray-200 transition-all">
                            <img src="{{ auth('casher')->user()->userPhoto() }}" class="w-full h-full object-cover" alt="User">
                        </div>
                    @else
                        <div class="p-1 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full mb-0.5 border border-white dark:border-gray-600 shadow-sm text-gray-400 dark:text-gray-300">
                            <i class="ph-fill ph-user text-lg"></i>
                        </div>
                    @endif
                </div>
                <span class="text-xs font-bold mt-0.5 truncate w-full px-1 text-center opacity-70 group-hover:opacity-100 transition-opacity">
                    {{ auth('casher')->user() && auth('casher')->user()->name ? explode(' ', auth('casher')->user()->name)[0] : 'المستخدم' }}
                </span>
            </div>

            <!-- Logout Tab -->
            <a href="{{ route('website.casher.logout') }}" 
               class="flex flex-col items-center justify-center py-1.5 transition-all duration-300 text-gray-400 hover:text-red-500 w-16 group relative"
               title="{{ __('notebook.logout') ?? 'خروج' }}">
                <div class="relative transition-all duration-300 transform group-hover:-translate-y-1 group-active:scale-95">
                    <i class="ph-bold ph-sign-out text-2xl transition-all group-hover:drop-shadow-[0_2px_8px_rgba(239,68,68,0.5)]"></i>
                </div>
                <span class="text-xs font-bold mt-1 opacity-70 group-hover:opacity-100 transition-opacity">{{ app()->getLocale() == 'ar' ? 'خروج' : 'Exit' }}</span>
            </a>
        </div>

    </div>
</div>
