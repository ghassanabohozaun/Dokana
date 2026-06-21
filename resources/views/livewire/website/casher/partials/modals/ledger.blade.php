    <!-- Customer Ledger Modal -->
    <div x-data="{ show: false }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail.id === 'ledgerModal') show = true"
         x-on:close-modal.window="if ($event.detail.id === 'ledgerModal') show = false"
         style="display: none;"
         class="fixed inset-0 z-40 flex flex-col items-center justify-end sm:justify-center">
        <div x-show="show" x-transition.opacity class="fixed inset-0 bg-gray-900/75 dark:bg-black/85" x-on:click="show = false"></div>
        <div x-show="show" x-transition.translate.y.bottom class="relative bg-gray-50 dark:bg-dark w-full max-w-md h-[92vh] sm:h-[85vh] rounded-t-[2rem] sm:rounded-3xl flex flex-col shadow-2xl overflow-hidden border border-white/10 z-10">
            @if($activeCustomer)
            <!-- Header -->
            <div class="p-5 border-b dark:border-gray-800 flex justify-between items-center bg-white dark:bg-darkCard z-10 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-xl">
                        <span>{{ mb_substr($activeCustomer->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h2 class="font-bold text-lg text-gray-900 dark:text-white">{{ $activeCustomer->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5 font-medium">{!! $activeCustomer->phone ? '<i class="ph-fill ph-phone text-xs"></i> ' . $activeCustomer->phone : '-' !!}</p>
                    </div>
                </div>
                <button x-on:click="show = false" class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors text-gray-600 dark:text-gray-300">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <!-- Balance & Actions -->
            <div class="p-5 bg-white dark:bg-darkCard shadow-sm z-10 relative shrink-0">
                <div class="flex justify-between items-end mb-5">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">{{ __('notebook.current_balance') }}</p>
                        <h3 class="text-3xl font-black tracking-tight {{ $activeCustomer->balance > 0 ? 'text-red-500' : ($activeCustomer->balance < 0 ? 'text-emerald-500' : 'text-gray-800 dark:text-white') }}">
                            {{ number_format(abs($activeCustomer->balance), 1) }} <span class="text-base font-normal opacity-80">{{ __('notebook.currency') }}</span>
                        </h3>
                    </div>
                    <div class="text-xs font-bold px-3 py-1.5 rounded-full {{ $activeCustomer->balance > 0 ? 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400' : ($activeCustomer->balance < 0 ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400') }}">
                        {{ $activeCustomer->balance > 0 ? __('notebook.owes_debt') : ($activeCustomer->balance < 0 ? __('notebook.has_credit') : __('notebook.paid')) }}
                    </div>
                </div>
                @if(auth('casher')->user()->hasAbility('notebook_create'))
                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="openTxModal('debt')" class="flex flex-col items-center justify-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 dark:text-red-400 py-3 rounded-[1rem] font-bold transition-all active:scale-95 border border-red-100 dark:border-red-900/30 group">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-red-900/40 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-minus text-xl"></i>
                        </div>
                        {{ __('notebook.new_debt') }}
                    </button>
                    <button wire:click="openTxModal('payment')" class="flex flex-col items-center justify-center gap-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:hover:bg-emerald-900/40 dark:text-emerald-400 py-3 rounded-[1rem] font-bold transition-all active:scale-95 border border-emerald-100 dark:border-emerald-900/30 group">
                        <div class="w-10 h-10 rounded-full bg-white dark:bg-emerald-900/40 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-plus text-xl"></i>
                        </div>
                        {{ __('notebook.payment_transfer') }}
                    </button>
                </div>
                @endif
            </div>

            <!-- Transaction List -->
            <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50/50 dark:bg-[#0b1121] custom-scrollbar relative">
                <div wire:loading.flex wire:target="loadMoreLedger" class="absolute inset-0 bg-white/50 dark:bg-black/50 z-10 flex items-center justify-center rounded-xl backdrop-blur-sm">
                    <i class="ph-bold ph-spinner-gap animate-spin text-4xl text-primary"></i>
                </div>

                @if(count($ledgerTransactions) === 0)
                <div class="flex flex-col items-center justify-center py-16 text-gray-400">
                    <i class="ph-fill ph-receipt text-5xl mb-3 text-gray-300 dark:text-gray-600 opacity-50"></i>
                    <p class="text-sm font-bold">{{ __('notebook.no_registered_transactions') }}</p>
                </div>
                @else
                    @foreach($ledgerTransactions as $tx)
                        @php
                            $isDebt = $tx->type === 'debt';
                        @endphp
                        <div class="bg-white dark:bg-darkCard p-4 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 {{ $isDebt ? 'bg-red-50 text-red-500 dark:bg-red-900/20' : 'bg-emerald-50 text-emerald-500 dark:bg-emerald-900/20' }}">
                                        <i class="ph-bold {{ $isDebt ? 'ph-arrow-up-right' : 'ph-arrow-down-left' }} text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $tx->description }}</p>
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 font-medium flex items-center gap-1">
                                            <i class="ph-fill ph-calendar text-xs"></i> {{ $tx->transaction_date ? $tx->transaction_date->format('Y-m-d') : $tx->created_at->format('Y-m-d') }} - {{ $tx->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-left font-black shrink-0 text-lg {{ $isDebt ? 'text-red-500' : 'text-emerald-500' }}">
                                    {{ $isDebt ? '+' : '-' }}{{ number_format($tx->amount, 1) }} <span class="text-[10px] font-normal">₪</span>
                                </div>
                            </div>
                            @if(auth('casher')->user()->hasAbility('notebook_update') || auth('casher')->user()->hasAbility('notebook_delete'))
                            <div class="flex items-center gap-2 border-t dark:border-gray-800 pt-3 mt-1">
                                @if(auth('casher')->user()->hasAbility('notebook_update'))
                                <button wire:click="editTransaction({{ $tx->id }})" class="flex-1 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                    <i class="ph-bold ph-pencil-simple"></i> {{ __('notebook.edit') }}
                                </button>
                                @endif
                                @if(auth('casher')->user()->hasAbility('notebook_delete'))
                                <button type="button" x-data x-on:click="
                                    Swal.fire({
                                        html: `
                                            <div class='flex flex-col items-center'>
                                                <div class='w-16 h-16 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-full flex items-center justify-center mb-4'>
                                                    <i class='ph-fill ph-warning text-4xl animate-pulse'></i>
                                                </div>
                                                <h3 class='text-xl font-black text-gray-800 dark:text-gray-100 mb-2'>{{ __('notebook.are_you_sure') }}</h3>
                                                <p class='text-sm font-medium text-gray-500 dark:text-gray-400'>{{ __('notebook.confirm_delete_transaction') }}</p>
                                            </div>
                                        `,
                                        showCancelButton: true,
                                        showClass: {
                                            popup: 'animate-warningPop',
                                            backdrop: 'swal2-backdrop-show'
                                        },
                                        hideClass: {
                                            popup: 'animate-swalHide',
                                            backdrop: 'swal2-backdrop-hide'
                                        },
                                        buttonsStyling: false,
                                        customClass: {
                                            popup: 'w-[90%] max-w-[20rem] rounded-[1.5rem] bg-white dark:bg-[#1e293b] !pt-6',
                                            htmlContainer: '!m-0 !p-0',
                                            actions: 'gap-3 mt-6 flex w-full px-6 pb-2',
                                            confirmButton: 'flex-1 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 font-bold rounded-xl px-4 py-3 transition-colors',
                                            cancelButton: 'flex-1 bg-gray-100 text-gray-600 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 font-bold rounded-xl px-4 py-3 transition-colors'
                                        },
                                        confirmButtonText: '{{ __('notebook.yes_delete') }}',
                                        cancelButtonText: '{{ __('notebook.cancel') }}'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.deleteTransaction({{ $tx->id }});
                                        }
                                    })
                                " class="flex-1 py-1.5 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 rounded-lg transition-colors flex items-center justify-center gap-1">
                                    <i class="ph-bold ph-trash"></i> {{ __('notebook.delete') }}
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                    @endforeach

                    @if($totalLedgerTransactions > count($ledgerTransactions))
                    <div class="mt-6 flex justify-center pb-2">
                        <button wire:click="loadMoreLedger" class="group relative px-5 py-2.5 text-xs font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-full transition-all duration-300 flex items-center justify-center gap-2 active:scale-95">
                            <span wire:loading.remove wire:target="loadMoreLedger" class="flex items-center gap-2">
                                {{ __('notebook.show_older_transactions') }} <i class="ph-bold ph-caret-down group-hover:translate-y-0.5 transition-transform"></i>
                            </span>
                            <span wire:loading wire:target="loadMoreLedger" class="flex items-center gap-2">
                                <i class="ph-bold ph-spinner animate-spin"></i> {{ __('notebook.updating') }}
                            </span>
                        </button>
                    </div>
                    @endif
                @endif
            </div>
            @endif
        </div>
    </div>
