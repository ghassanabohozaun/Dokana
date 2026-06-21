    <!-- Customer List -->
    <div class="px-4 pb-4 relative">
        <div wire:loading.flex wire:target="search, setFilter" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center rounded-xl backdrop-blur-sm">
            <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
        </div>

        <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-3 px-1">{{ __('notebook.customers_list') }}</h3>
        
        @if($customers->isEmpty() && !$search && $filter === 'all')
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <div class="w-24 h-24 mb-4 opacity-50">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="w-full h-full text-gray-300 dark:text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <p class="font-bold text-gray-600 dark:text-gray-300 text-lg mb-1">{{ __('notebook.no_customers_added') }}</p>
            <p class="text-sm text-gray-400 dark:text-gray-500">{{ __('notebook.click_to_add_first_customer') }}</p>
        </div>
        @elseif($customers->isEmpty())
        <!-- No Results -->
        <div class="flex flex-col items-center justify-center py-16 text-gray-400">
            <i class="ph-fill ph-magnifying-glass-minus text-5xl text-gray-300 dark:text-gray-600 mb-3"></i>
            <p class="font-bold text-gray-500 dark:text-gray-400">{{ __('notebook.no_results') }}</p>
        </div>
        @else
        <div class="space-y-3">
            @php
                $avatarColors = [
                    'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                    'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400',
                    'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
                    'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
                    'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                    'bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400',
                    'bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400',
                    'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
                    'bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400',
                    'bg-violet-100 text-violet-600 dark:bg-violet-900/30 dark:text-violet-400',
                    'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
                    'bg-fuchsia-100 text-fuchsia-600 dark:bg-fuchsia-900/30 dark:text-fuchsia-400',
                    'bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400',
                    'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400'
                ];
            @endphp
            @foreach($customers as $idx => $customer)
                @php
                    // Safe string to index hashing (prevents negative modulo & int overflow)
                    $hashIndex = hexdec(substr(md5($customer->name), 0, 6)) % count($avatarColors);
                    $colorClass = $avatarColors[$hashIndex];
                    $lastTx = $customer->transactions->first() ? $customer->transactions->first()->created_at->format('Y-m-d') : __('notebook.no_transactions');
                    $balance = $customer->balance;
                    $isDebt = $balance > 0;
                @endphp
                <div wire:click="openLedger({{ $customer->id }})" class="bg-white dark:bg-darkCard p-4 rounded-[1.25rem] border border-gray-100 dark:border-gray-800 flex justify-between items-center cursor-pointer hover:border-primary/50 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl shrink-0 shadow-sm {{ $colorClass }}">
                            {{ mb_substr($customer->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight">{{ $customer->name }}</h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-clock text-xs"></i> {{ $lastTx }}
                            </p>
                        </div>
                    </div>
                    <div class="text-left flex flex-col items-end">
                        <span class="font-black text-lg {{ $isDebt ? 'text-red-500' : ($balance < 0 ? 'text-emerald-500' : 'text-gray-500 dark:text-gray-400') }} leading-tight">
                            {{ number_format(abs($balance), 1) }}
                        </span>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full mt-1 {{ $isDebt ? 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400' : ($balance < 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400') }}">
                            {{ $isDebt ? __('notebook.owes_debt') : ($balance < 0 ? __('notebook.has_credit') : __('notebook.paid')) }}
                        </span>
                    </div>
                </div>
            @endforeach
            
            @if($totalCustomers > count($customers))
                <div class="mt-8 mb-4 flex justify-center">
                    <button wire:click="loadMoreCustomers" class="group relative px-6 py-3 text-sm font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white dark:bg-emerald-500/10 dark:hover:bg-emerald-500 dark:text-emerald-400 dark:hover:text-white rounded-full transition-all duration-300 flex items-center justify-center gap-2 overflow-hidden shadow-sm hover:shadow-md hover:shadow-primary/20 active:scale-95">
                        <span wire:loading.remove wire:target="loadMoreCustomers" class="flex items-center gap-2">
                            {{ __('notebook.load_more_customers') }} 
                            <i class="ph-bold ph-caret-down group-hover:translate-y-0.5 transition-transform"></i>
                        </span>
                        <span wire:loading wire:target="loadMoreCustomers" class="flex items-center gap-2">
                            <i class="ph-bold ph-spinner animate-spin text-lg"></i> {{ __('notebook.updating') }}
                        </span>
                    </button>
                </div>
            @endif
        </div>
        @endif
    </div>
