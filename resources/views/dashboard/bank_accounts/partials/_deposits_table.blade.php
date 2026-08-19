<div class="overflow-x-auto">
    <table class="table-modern w-full">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('general.date') !!}</th>
                <th class="text-center">{!! __('store_transactions.amount') !!}</th>
                <th>{!! __('store_customers.store_customer') !!}</th>
                <th class="hidden sm:table-cell">{!! __('departments.created_by') !!}</th>
                <th>{!! __('store_transactions.description') !!}</th>
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
                            {{ $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d h:i A') : '—' }}
                        </span>
                    </td>

                    <!-- Amount -->
                    <td class="text-center">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/60" dir="ltr">
                            <i class="fas fa-arrow-down text-[10px]"></i>
                            {{ number_format($transaction->amount, 2) }}
                        </span>
                    </td>

                    <!-- Customer -->
                    <td>
                        @if($transaction->customer)
                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ $transaction->customer->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($transaction->createdBy)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i>
                                {{ $transaction->createdBy->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Description -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400">
                            {{ $transaction->description ?: __('general.no_description') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-12">
                        <div class="flex h-12 w-12 mx-auto items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-600 text-lg mb-2">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <h6 class="text-xs font-bold text-slate-600 dark:text-slate-400">{!! __('bank_accounts.no_deposits_yet') !!}</h6>
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
