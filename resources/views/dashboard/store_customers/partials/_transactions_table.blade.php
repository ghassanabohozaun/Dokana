<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full">
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
                        <span class="font-mono font-black text-xs {{ $transaction->type === 'debt' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
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

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-2.5">
    @forelse($transactions as $index => $transaction)
        @php
            $isDebt = $transaction->type === 'debt';
        @endphp
        <div class="p-3.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs space-y-2">
            <!-- Header: Type & Amount -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-xl {{ $isDebt ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/60 dark:text-rose-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400' }} text-xs">
                        <i class="fas {{ $isDebt ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                    </div>
                    @if($isDebt)
                        <span class="badge-pill badge-pill-danger text-[10px]">
                            {!! __('store_transactions.debt') !!}
                        </span>
                    @else
                        <span class="badge-pill badge-pill-success text-[10px]">
                            {!! __('store_transactions.payment') !!}
                        </span>
                    @endif
                </div>

                <span class="font-mono text-sm font-black {{ $isDebt ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}" dir="ltr">
                    {!! number_format($transaction->amount, 2) !!}
                </span>
            </div>

            <!-- Description & Bank details -->
            @if($transaction->description || ($transaction->type == 'payment' && $transaction->store_bank_account_id))
                <div class="text-xs text-slate-700 dark:text-slate-300 pt-1 border-t border-slate-100 dark:border-slate-800">
                    @if($transaction->description)
                        <p class="font-medium mb-1">{{ $transaction->description }}</p>
                    @endif
                    @if($transaction->type == 'payment' && $transaction->store_bank_account_id && $transaction->bankAccount)
                        @php
                            $entityName = optional($transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                            $accountName = $transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $transaction->bankAccount->account_number;
                        @endphp
                        <span class="inline-flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 font-semibold bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                            <i class="fas fa-wallet text-emerald-500"></i> {{ $accountName }}
                        </span>
                    @endif
                </div>
            @endif

            <!-- Footer: Date -->
            <div class="flex items-center justify-between text-[11px] text-slate-400 dark:text-slate-500 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="font-mono text-[10px]">#{{ $index + 1 + ($transactions->currentPage() - 1) * $transactions->perPage() }}</span>
                <span dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {!! $transaction->transaction_date ? \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d') : $transaction->created_at->format('Y-m-d') !!}
                </span>
            </div>
        </div>
    @empty
        <div class="text-center py-8 text-slate-400 text-xs">
            <i class="fas fa-folder-open text-2xl mb-2 block opacity-40"></i>
            {!! __('general.no_data') ?? 'لا توجد حركات مالية مسجلة' !!}
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if($transactions->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $transactions->links('dashboard.includes.pagination') !!}
    </div>
@endif
