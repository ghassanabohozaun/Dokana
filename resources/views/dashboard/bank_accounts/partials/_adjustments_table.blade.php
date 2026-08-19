<div class="overflow-x-auto">
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
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                            {{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                        </span>
                    </td>

                    <!-- Date -->
                    <td>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300" dir="ltr">
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

@if ($transactions->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center custom-pagination">
        {!! $transactions->links() !!}
    </div>
@endif
