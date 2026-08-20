<input type="hidden" id="store_withdrawals-total-count" value="{{ $withdrawals->total() }}">

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
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
                <th class="w-24 text-center sticky end-0 bg-slate-50 dark:bg-slate-800 z-10 border-s border-slate-200/80 dark:border-slate-700/80 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($withdrawals as $withdrawal)
                <tr id="row{{ $withdrawal->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
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
                    <td class="text-center sticky end-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-10 border-s border-slate-100 dark:border-slate-800 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">
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

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-3">
    @forelse ($withdrawals as $withdrawal)
        @php
            $isWallet = optional($withdrawal->bankAccount)->account_type === 'wallet';
            $entityName = optional(optional($withdrawal->bankAccount)->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional(optional($withdrawal->bankAccount)->paymentEntity)->getTranslation('name', 'ar');
        @endphp
        <div id="mobile-row{{ $withdrawal->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Account & Amount -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-base font-bold shadow-xs">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                            {{ $entityName ?: 'حساب بنكي' }}
                        </h3>
                        @if($withdrawal->bankAccount && $withdrawal->bankAccount->account_type !== 'cash')
                            <span class="text-[11px] font-mono text-slate-400 dark:text-slate-500 block mt-0.5" dir="ltr">
                                {{ $withdrawal->bankAccount->account_number }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Amount Badge -->
                <div class="shrink-0 text-end">
                    <span class="block font-mono text-sm font-black text-rose-600 dark:text-rose-400" dir="ltr">
                        {{ number_format($withdrawal->amount, 2) }}
                    </span>
                    <span class="text-[10px] text-slate-400">مسحوبات</span>
                </div>
            </div>

            <!-- Reason Description Box -->
            @if($withdrawal->reason)
                <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 text-xs">
                    <span class="text-[11px] text-slate-400 font-medium block mb-0.5">سبب السحب / البيان:</span>
                    <p class="text-slate-700 dark:text-slate-200 font-medium">{{ $withdrawal->reason }}</p>
                </div>
            @endif

            <!-- Footer: Creator, Date & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <div class="flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 font-medium">
                    @if (isset($stores) && $withdrawal->store)
                        <span class="inline-flex items-center gap-1">
                            <i class="fas fa-store text-[9px]"></i>
                            <span>{{ $withdrawal->store->name }}</span>
                        </span>
                        <span>•</span>
                    @endif
                    <span dir="ltr">
                        <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                        {{ $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('Y-m-d') : $withdrawal->created_at->format('Y-m-d') }}
                    </span>
                </div>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.store_withdrawals.parts.actions')
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('store_withdrawals.no_store_withdrawals_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('store_withdrawals.no_withdrawals_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($withdrawals->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $withdrawals->links('dashboard.includes.pagination') !!}
    </div>
@endif
