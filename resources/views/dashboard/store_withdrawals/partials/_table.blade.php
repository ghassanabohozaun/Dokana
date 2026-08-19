<input type="hidden" id="store_withdrawals-total-count" value="{{ $withdrawals->total() }}">

<div class="overflow-x-auto">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{{ __('stores.store') }}</th>
                @endif
                <th>{{ __('bank_accounts.bank_account') }}</th>
                <th class="text-center">{{ __('store_withdrawals.amount') }}</th>
                <th>{{ __('store_withdrawals.reason') }}</th>
                <th>{{ __('store_withdrawals.date') }}</th>
                <th class="hidden sm:table-cell">{{ __('departments.created_by') }}</th>
                <th class="text-center w-24">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($withdrawals as $withdrawal)
                <tr id="row{{ $withdrawal->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                            {{ $loop->iteration + ($withdrawals->currentPage() - 1) * $withdrawals->perPage() }}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                    <td>
                        @if($withdrawal->store)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                {{ $withdrawal->store->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    @endif

                    <!-- Bank Account / Wallet Info -->
                    <td>
                        @if($withdrawal->bankAccount)
                            @php
                                $entityName = optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $withdrawal->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $withdrawal->bankAccount->account_number;
                                $isWallet = $withdrawal->bankAccount->account_type === 'wallet';
                            @endphp
                            <div class="flex items-center gap-2">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg {{ $isWallet ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400' }} text-[11px]">
                                    <i class="fas {{ $isWallet ? 'fa-wallet' : 'fa-university' }}"></i>
                                </div>
                                <span class="font-bold text-xs text-slate-800 dark:text-white">
                                    {{ $accountName }}
                                </span>
                            </div>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Amount -->
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/60" dir="ltr">
                            <i class="fas fa-arrow-up text-[10px]"></i>
                            {{ number_format($withdrawal->amount, 2) }}
                        </span>
                    </td>

                    <!-- Reason -->
                    <td>
                        <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                            {{ $withdrawal->reason ?? '—' }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400" dir="ltr">
                            {{ $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('Y-m-d') : $withdrawal->created_at->format('Y-m-d') }}
                        </span>
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($withdrawal->creator)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i>
                                {{ $withdrawal->creator->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.store_withdrawals.parts.actions')
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 8 : 7 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-hand-holding-usd text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('store_withdrawals.no_store_withdrawals_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('store_withdrawals.no_withdrawals_desc') !!}
                        </p>
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($withdrawals->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $withdrawals->links() !!}
    </div>
@endif
