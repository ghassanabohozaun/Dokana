<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('general.date') !!}</th>
                <th class="hidden sm:table-cell">{!! __('departments.created_by') !!}</th>
                <th class="text-center">{!! __('bank_accounts.previous_balance') !!}</th>
                <th class="text-center">{!! __('bank_accounts.actual_balance_title') !!}</th>
                <th class="text-center">{!! __('bank_accounts.difference_adjustment') !!}</th>
                <th>{!! __('bank_accounts.notes') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($transactions as $transaction)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300" dir="ltr">
                            {{ $transaction->created_at ? $transaction->created_at->format('Y-m-d h:i A') : '—' }}
                        </span>
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($transaction->creator)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i>
                                {{ $transaction->creator->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Old Balance -->
                    <td class="text-center" dir="ltr">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">
                            {{ number_format($transaction->old_balance, 2) }}
                        </span>
                    </td>

                    <!-- New Actual Balance -->
                    <td class="text-center" dir="ltr">
                        <span class="text-xs text-slate-900 dark:text-white font-bold">
                            {{ number_format($transaction->new_balance, 2) }}
                        </span>
                    </td>

                    <!-- Difference Adjustment Badge -->
                    <td class="text-center">
                        @if($transaction->amount > 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-black bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400" dir="ltr">
                                <i class="fas fa-arrow-down text-[9px]"></i> +{{ number_format(abs($transaction->amount), 2) }}
                            </span>
                            <span class="block text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">{!! __('bank_accounts.surplus') !!}</span>
                        @elseif($transaction->amount < 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-black bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400" dir="ltr">
                                <i class="fas fa-arrow-up text-[9px]"></i> -{{ number_format(abs($transaction->amount), 2) }}
                            </span>
                            <span class="block text-[10px] text-rose-600 dark:text-rose-400 mt-0.5">{!! __('bank_accounts.deficit') !!}</span>
                        @else
                            <span class="text-xs text-slate-400 font-bold">0.00</span>
                        @endif
                    </td>

                    <!-- Notes -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400">
                            {{ $transaction->notes ?: __('general.no_description') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-12">
                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-lg mb-2">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h6 class="text-xs font-bold text-slate-600 dark:text-slate-400">{!! __('bank_accounts.no_adjustments_yet') !!}</h6>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-2.5">
    @forelse ($transactions as $transaction)
        <div class="p-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs space-y-2">
            <!-- Header: Status & Difference -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/60 dark:text-amber-400 text-xs">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">تسوية جردية</span>
                </div>

                @if($transaction->amount > 0)
                    <span class="font-mono text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/60 px-2 py-0.5 rounded-lg" dir="ltr">
                        +{{ number_format(abs($transaction->amount), 2) }} (فائض)
                    </span>
                @elseif($transaction->amount < 0)
                    <span class="font-mono text-xs font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/60 px-2 py-0.5 rounded-lg" dir="ltr">
                        -{{ number_format(abs($transaction->amount), 2) }} (عجز)
                    </span>
                @else
                    <span class="text-xs text-slate-400 font-bold">0.00</span>
                @endif
            </div>

            <!-- Balances Transition Matrix -->
            <div class="grid grid-cols-2 gap-2 p-2 rounded-xl bg-slate-50 dark:bg-slate-800/60 text-xs">
                <div>
                    <span class="text-[10px] text-slate-400 block">الرصيد الدفتري:</span>
                    <span class="font-mono font-bold text-slate-600 dark:text-slate-300" dir="ltr">{{ number_format($transaction->old_balance, 2) }}</span>
                </div>
                <div>
                    <span class="text-[10px] text-slate-400 block">الرصيد الفعلي:</span>
                    <span class="font-mono font-bold text-slate-900 dark:text-white" dir="ltr">{{ number_format($transaction->new_balance, 2) }}</span>
                </div>
            </div>

            <!-- Notes -->
            @if($transaction->notes)
                <p class="text-xs text-slate-600 dark:text-slate-300 font-medium">
                    {{ $transaction->notes }}
                </p>
            @endif

            <!-- Footer: Date -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="font-mono text-[10px]">#{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</span>
                <span dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $transaction->created_at ? $transaction->created_at->format('Y-m-d h:i A') : '—' }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400 text-xs">
            <i class="fas fa-inbox text-2xl mb-2 block opacity-40"></i>
            {!! __('bank_accounts.no_adjustments_yet') !!}
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($transactions->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $transactions->links('dashboard.includes.pagination') !!}
    </div>
@endif
