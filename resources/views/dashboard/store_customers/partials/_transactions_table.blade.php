<div class="overflow-x-auto custom-scrollbar">
    <table class="table-modern">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{!! __('store_transactions.date') !!}</th>
                <th class="text-center">{!! __('store_transactions.type') !!}</th>
                <th class="text-center">{!! __('store_transactions.amount') !!}</th>
                <th>{!! __('store_transactions.description') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse($transactions as $index => $transaction)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $index + 1 + ($transactions->currentPage() - 1) * $transactions->perPage() !!}
                        </span>
                    </td>
                    <td>
                        <span class="text-xs font-medium text-slate-700 dark:text-slate-300" dir="ltr">
                            {!! $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') : $transaction->created_at->format('Y-m-d') !!}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($transaction->type === 'debt')
                            <span class="badge-pill badge-pill-danger text-[11px]">
                                <i class="fas fa-arrow-down text-[10px]"></i> {!! __('store_transactions.debt') !!}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-success text-[11px]">
                                <i class="fas fa-arrow-up text-[10px]"></i> {!! __('store_transactions.payment') !!}
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="font-mono font-bold text-xs {{ $transaction->type === 'debt' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
                            {!! number_format($transaction->amount, 2) !!}
                        </span>
                    </td>
                    <td>
                        <span class="text-xs font-medium text-slate-800 dark:text-white block">
                            {!! $transaction->description ?: '—' !!}
                        </span>
                        @if($transaction->type == 'payment' && $transaction->store_bank_account_id && $transaction->bankAccount)
                            @php
                                $entityName = optional($transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $transaction->bankAccount->account_number;
                            @endphp
                            <div class="mt-1">
                                <span class="badge-pill badge-pill-info text-[10px] inline-flex items-center gap-1">
                                    <i class="fas fa-wallet"></i> {{ $accountName }}
                                </span>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-slate-400 text-xs">
                        <i class="fas fa-folder-open text-2xl mb-2 block opacity-40"></i>
                        {!! __('general.no_data') ?? 'لا توجد حركات مالية مسجلة' !!}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex justify-center">
        {!! $transactions->links() !!}
    </div>
@endif
