<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('general.date') !!}</th>
                <th class="text-center">{!! __('store_transactions.amount') !!}</th>
                <th class="hidden sm:table-cell">{!! __('departments.created_by') !!}</th>
                <th>{!! __('store_withdrawals.reason') !!}</th>
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
                            {{ $transaction->withdrawal_date ? $transaction->withdrawal_date->format('Y-m-d h:i A') : '—' }}
                        </span>
                    </td>

                    <!-- Amount -->
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/60" dir="ltr">
                            <i class="fas fa-arrow-up text-[10px]"></i>
                            {{ number_format($transaction->amount, 2) }}
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

                    <!-- Reason -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 truncate max-w-[200px] block" title="{{ $transaction->reason }}">
                            {{ $transaction->reason ?: __('general.no_description') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-12">
                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-lg mb-2">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h6 class="text-xs font-bold text-slate-600 dark:text-slate-400">{!! __('bank_accounts.no_withdrawals_yet') !!}</h6>
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
            <!-- Header: Icon & Amount -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400 text-xs">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-800 dark:text-white">سحب / مصروف</span>
                </div>

                <span class="font-mono text-sm font-black text-rose-600 dark:text-rose-400 shrink-0" dir="ltr">
                    -{{ number_format($transaction->amount, 2) }}
                </span>
            </div>

            <!-- Reason -->
            @if($transaction->reason)
                <p class="text-xs text-slate-600 dark:text-slate-300 pt-1 border-t border-slate-100 dark:border-slate-800 font-medium">
                    {{ $transaction->reason }}
                </p>
            @endif

            <!-- Footer: Date -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="font-mono text-[10px]">#{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</span>
                <span dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $transaction->withdrawal_date ? $transaction->withdrawal_date->format('Y-m-d h:i A') : '—' }}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400 text-xs">
            <i class="fas fa-inbox text-2xl mb-2 block opacity-40"></i>
            {!! __('bank_accounts.no_withdrawals_yet') !!}
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
