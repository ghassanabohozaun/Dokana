<div class="fixed bottom-0 left-0 right-0 max-w-md mx-auto z-40 pb-safe pointer-events-none">

    <!-- Central Smart FAB -->
    <div class="absolute left-1/2 -top-8 transform -translate-x-1/2 z-50 pointer-events-auto">
        <template x-if="activeTab === 'customers'">
            <button @click="openAddCustomer"
                class="w-16 h-16 bg-gradient-to-tr from-[#06b6d4] to-[#3b82f6] rounded-full flex items-center justify-center text-white shadow-[0_8px_25px_rgba(6,182,212,0.4)] hover:shadow-[0_10px_30px_rgba(6,182,212,0.6)] hover:-translate-y-1 transition-all duration-300 border-[4px] border-gray-50 dark:border-gray-900">
                <i class="ph-bold ph-plus text-3xl"></i>
            </button>
        </template>
        <template x-if="activeTab === 'withdrawals'">
            <button @click="openWithdrawalModal"
                class="w-16 h-16 bg-gradient-to-tr from-red-500 to-pink-600 rounded-full flex items-center justify-center text-white shadow-[0_8px_25px_rgba(239,68,68,0.4)] hover:shadow-[0_10px_30px_rgba(239,68,68,0.6)] hover:-translate-y-1 transition-all duration-300 border-[4px] border-gray-50 dark:border-gray-900">
                <i class="ph-bold ph-money text-3xl"></i>
            </button>
        </template>
    </div>

    <!-- The Nav Container (Background + Content) -->
    <div class="relative w-full pt-2 pb-2 pointer-events-auto">
        
        <!-- Background Layer with SVG Notch (2 Hills and Valley) -->
        <div class="absolute inset-0 pointer-events-none drop-shadow-[0_-5px_15px_rgba(0,0,0,0.08)] z-0 flex flex-col overflow-hidden rounded-t-[1.5rem]">
            <div class="flex w-full h-[45px]">
                <div class="flex-1 bg-white dark:bg-[#1a2235] ltr:mr-[-1px] rtl:ml-[-1px]"></div>
                <div class="w-[110px] h-[45px] text-white dark:text-[#1a2235] shrink-0">
                    <svg class="w-full h-full" viewBox="0 0 110 45" preserveAspectRatio="none">
                        <path fill="currentColor" d="M 0,0 C 15,0 20,10 25,26.4 A 40,40 0 0,0 85,26.4 C 90,10 95,0 110,0 L 110,45 L 0,45 Z" />
                    </svg>
                </div>
                <div class="flex-1 bg-white dark:bg-[#1a2235] ltr:ml-[-1px] rtl:mr-[-1px]"></div>
            </div>
            <div class="flex-1 w-full bg-white dark:bg-[#1a2235] mt-[-1px]"></div>
        </div>

        <!-- Foreground Content (Tabs) -->
        <div class="flex justify-between items-center w-full px-2 relative z-10">
            
            <!-- Right Group -->
            <div class="flex flex-1 justify-around">
                <!-- Customers Tab -->
                <button @click="switchTab('customers')"
                    class="flex flex-col items-center justify-center py-2 transition-all duration-300 w-16"
                    :class="activeTab === 'customers' ? 'text-[#06b6d4]' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500'">
                    <div class="relative p-1.5 transition-all duration-300"
                        :class="activeTab === 'customers' ? 'bg-cyan-50 dark:bg-cyan-900/20 rounded-2xl scale-110' : ''">
                        <i class="ph-fill ph-users text-2xl mb-1"></i>
                    </div>
                    <span class="text-[10px] font-bold mt-1">{{ __('notebook.customers') ?? 'العملاء' }}</span>
                </button>

                <!-- Withdrawals Tab -->
                <button @click="switchTab('withdrawals')"
                    class="flex flex-col items-center justify-center py-2 transition-all duration-300 w-16"
                    :class="activeTab === 'withdrawals' ? 'text-red-500' : 'text-gray-400 hover:text-gray-600 dark:text-gray-500'">
                    <div class="relative p-1.5 transition-all duration-300"
                        :class="activeTab === 'withdrawals' ? 'bg-red-50 dark:bg-red-900/20 rounded-2xl scale-110' : ''">
                        <i class="ph-fill ph-wallet text-2xl mb-1"></i>
                    </div>
                    <span class="text-[10px] font-bold mt-1">{{ __('notebook.withdrawals') ?? 'المسحوبات' }}</span>
                </button>
            </div>

            <!-- Notch Spacer (Matches cutout width) -->
            <div class="w-[110px] shrink-0"></div>

            <!-- Left Group -->
            <div class="flex flex-1 justify-around">
                <!-- User Profile Tab -->
                <div class="flex flex-col items-center justify-center py-2 text-gray-500 dark:text-gray-400 w-16">
                    @if (auth('casher')->user() && auth('casher')->user()->userPhoto())
                        <div class="relative w-8 h-8 mb-1 border-2 border-gray-100 dark:border-gray-700 rounded-full overflow-hidden shadow-sm">
                            <img src="{{ auth('casher')->user()->userPhoto() }}" class="w-full h-full object-cover" alt="User">
                        </div>
                    @else
                        <div class="relative p-1.5 bg-gray-50 dark:bg-gray-800 rounded-full mb-1 border border-gray-100 dark:border-gray-700 shadow-sm">
                            <i class="ph-fill ph-user text-xl text-gray-400"></i>
                        </div>
                    @endif
                    <span class="text-[10px] font-bold mt-1 truncate w-full px-1 text-center">
                        {{ auth('casher')->user() && auth('casher')->user()->name ? explode(' ', auth('casher')->user()->name)[0] : 'المستخدم' }}
                    </span>
                </div>

                <!-- Logout Tab -->
                <a href="{{ route('website.casher.logout') }}" 
                   class="flex flex-col items-center justify-center py-2 transition-all duration-300 text-gray-400 hover:text-red-500 w-16"
                   title="{{ __('notebook.logout') ?? 'خروج' }}">
                    <div class="relative p-1.5 transition-all duration-300">
                        <i class="ph-bold ph-sign-out text-2xl mb-1"></i>
                    </div>
                    <span class="text-[10px] font-bold mt-1">{{ app()->getLocale() == 'ar' ? 'خروج' : 'Exit' }}</span>
                </a>
            </div>

        </div>
    </div>
</div>
