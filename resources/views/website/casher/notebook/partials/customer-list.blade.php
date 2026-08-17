    <!-- Customer List -->
    <div class="px-4 pb-4 relative">
        <div x-show="isLoading" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center rounded-xl backdrop-blur-sm" x-cloak>
            <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
        </div>

        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-3 px-1">{{ __('notebook.customers_list') }}</h3>
        
        <!-- Empty State -->
        <template x-if="customers.length === 0 && !search && filter === 'all' && !isLoading">
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <div class="w-24 h-24 mb-4 opacity-50">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-gray-300 dark:text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <p class="font-bold text-gray-600 dark:text-gray-300 text-lg mb-1">{{ __('notebook.no_customers_added') }}</p>
                <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('notebook.click_to_add_first_customer') }}</p>
            </div>
        </template>

        <!-- No Results -->
        <template x-if="customers.length === 0 && (search || filter !== 'all') && !isLoading">
            <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                <i class="ph-fill ph-magnifying-glass-minus text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
                <p class="font-bold text-gray-500 dark:text-gray-400">{{ __('notebook.no_results') }}</p>
            </div>
        </template>

        <!-- List -->
        <div class="space-y-3" x-show="customers.length > 0">
            <template x-for="customer in customers" :key="customer.id">
                <div @click="openLedger(customer.id)" class="card-hover p-4 rounded-[1.25rem] border flex justify-between items-center cursor-pointer"
                     :class="customer.status == 0 ? 'bg-gray-50 dark:bg-[#0b1121] border-gray-200 dark:border-gray-800 opacity-60 grayscale-[50%]' : 'bg-white dark:bg-darkCard border-gray-100 dark:border-gray-800'">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 shadow-sm relative overflow-hidden"
                             :class="customer.status == 0 ? 'bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400'">
                            <span x-show="loadingCustomerId !== customer.id" x-text="customer.name ? customer.name.substring(0, 1) : '-'"></span>
                            <div x-show="loadingCustomerId === customer.id" class="absolute inset-0 bg-white/50 dark:bg-black/50 flex items-center justify-center" x-cloak>
                                <i class="ph-bold ph-spinner-gap animate-spin"></i>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight flex items-center gap-1.5">
                                    <span x-text="customer.name"></span>

                                </h4>
                                <template x-if="customer.status == 0">
                                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">{{ __('notebook.disabled') ?? 'معطل' }}</span>
                                </template>
                            </div>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-phone text-xs"></i> <span x-text="customer.phone || '{{ __('notebook.no_phone') }}'"></span>
                            </p>
                            <template x-if="customer.max_debt_limit !== null">
                                <div class="mt-2 w-full pr-1">
                                    <div class="flex justify-between items-center text-[10px] mb-1.5 gap-2">
                                        <span class="text-gray-500 font-semibold whitespace-nowrap">{{ __('notebook.limit') }}: <span x-text="customer.max_debt_limit"></span></span>
                                        <span x-show="customer.balance > customer.max_debt_limit" 
                                              class="font-bold text-[9px] px-1.5 py-0.5 rounded bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 whitespace-nowrap" 
                                              x-text="'{{ __('notebook.exceeded_by') }} ' + (customer.balance - customer.max_debt_limit).toFixed(1)">
                                        </span>
                                        <span x-show="customer.balance <= customer.max_debt_limit" 
                                              class="font-bold text-[9px] px-1.5 py-0.5 rounded bg-blue-50 text-primary dark:bg-blue-900/30 dark:text-blue-400 whitespace-nowrap" 
                                              x-text="'{{ __('notebook.remaining') }}: ' + Math.max(0, customer.max_debt_limit - Math.max(0, customer.balance)).toFixed(1)">
                                        </span>
                                    </div>
                                    <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                                        <div class="h-full transition-all duration-300 rounded-full" 
                                             :class="(customer.balance >= customer.max_debt_limit) ? 'bg-red-500' : ((Math.max(0, customer.balance) / customer.max_debt_limit) > 0.8 ? 'bg-orange-400' : 'bg-primary')"
                                             :style="`width: ${Math.min(100, Math.max(0, (Math.max(0, customer.balance) / customer.max_debt_limit) * 100))}%`">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="text-left flex flex-col items-end">
                        <span class="font-black text-lg leading-tight" 
                              :class="customer.balance > 0 ? 'text-red-500' : (customer.balance < 0 ? 'text-emerald-500' : 'text-gray-500 dark:text-gray-400')"
                              x-text="Math.abs(customer.balance).toFixed(1)">
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full mt-1"
                              :class="customer.balance > 0 ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : (customer.balance < 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400')"
                              x-text="customer.balance > 0 ? '{{ __('notebook.owes_debt') }}' : (customer.balance < 0 ? '{{ __('notebook.has_credit') }}' : '{{ __('notebook.paid') }}')">
                        </span>
                    </div>
                </div>
            </template>
            
            <!-- Load More -->
            <template x-if="totalCustomers > customers.length">
                <div class="mt-8 mb-12 flex justify-center pb-10 px-2">
                    <button @click="loadMoreCustomers" class="w-full max-w-xs py-3.5 px-6 text-sm font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white dark:bg-emerald-500/10 dark:hover:bg-emerald-500 dark:text-emerald-400 dark:hover:text-white rounded-2xl transition-all duration-150 flex items-center justify-center gap-2 overflow-hidden shadow-sm hover:shadow-md hover:shadow-primary/20 active:scale-95 touch-manipulation cursor-pointer">
                        <span class="flex items-center gap-2">
                            <span>{{ __('notebook.load_more_customers') }}</span>
                            <i class="ph-bold ph-caret-down text-base"></i>
                        </span>
                    </button>
                </div>
            </template>
        </div>
    </div>
