<input type="hidden" id="bank_accounts-total-count" value="{{ $bankAccounts->total() }}">

<div class="overflow-x-auto">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{{ __('stores.store') }}</th>
                @endif
                <th>{{ __('bank_accounts.account_details') }}</th>
                <th>{{ __('bank_accounts.account_info') }}</th>
                <th class="text-center">{{ __('bank_accounts.total_deposits') }}</th>
                <th class="text-center">{{ __('bank_accounts.total_withdrawals') }}</th>
                <th class="text-center">{{ __('general.balance') }}</th>
                <th class="text-center">{{ __('bank_accounts.is_default') }}</th>
                <th class="hidden sm:table-cell">{{ __('departments.created_by') }}</th>
                <th class="text-center w-28">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse ($bankAccounts as $account)
                <tr id="row{{ $account->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                            {{ $loop->iteration + ($bankAccounts->currentPage() - 1) * $bankAccounts->perPage() }}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                    <td>
                        @if($account->store)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                {{ $account->store->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    @endif

                    <!-- Account Details & Type -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $account->account_type == 'wallet' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400' : ($account->account_type == 'cash' ? 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400') }} text-xs">
                                <i class="fas {{ $account->account_type == 'wallet' ? 'fa-wallet' : ($account->account_type == 'cash' ? 'fa-money-bill-wave' : 'fa-university') }}"></i>
                            </div>
                            <div>
                                <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-white block">
                                    {{ $account->paymentEntity->name ?? '---' }}
                                </span>
                                <span class="text-[10px] text-slate-400">
                                    @if ($account->account_type == 'wallet')
                                        {{ __('bank_accounts.type_wallet') }}
                                    @elseif ($account->account_type == 'cash')
                                        {{ __('bank_accounts.type_cash') ?? 'نقدي' }}
                                    @else
                                        {{ __('bank_accounts.type_bank') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- Account Info (Number & Holder) -->
                    <td>
                        <div class="font-bold text-xs text-slate-800 dark:text-slate-200" dir="ltr">
                            {{ $account->account_number }}
                        </div>
                        <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                            {{ $account->account_holder_name }}
                        </div>
                    </td>

                    <!-- Total Deposits -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <i class="fas fa-arrow-down text-[10px] me-1"></i>{{ number_format($account->total_deposits, 2) }}
                        </span>
                    </td>

                    <!-- Total Withdrawals -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-rose-600 dark:text-rose-400">
                            <i class="fas fa-arrow-up text-[10px] me-1"></i>{{ number_format($account->total_withdrawals, 2) }}
                        </span>
                    </td>

                    <!-- Balance -->
                    <td class="text-center" dir="ltr">
                        <span class="text-xs font-black {{ $account->current_balance > 0 ? 'text-emerald-600 dark:text-emerald-400' : ($account->current_balance < 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-600 dark:text-slate-400') }}">
                            <i class="fas fa-coins text-[10px] me-1"></i>{{ number_format($account->current_balance, 2) }}
                        </span>
                    </td>

                    <!-- Is Default Star -->
                    <td class="text-center">
                        @if ($account->is_default)
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-500" title="{{ __('bank_accounts.is_default') }}">
                                <i class="fas fa-star text-xs"></i>
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-lg text-slate-300 dark:text-slate-700">
                                <i class="far fa-star text-xs"></i>
                            </span>
                        @endif
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($account->creator)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i> {{ $account->creator->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.bank_accounts.parts.actions')
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 10 : 9 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-university text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {{ __('bank_accounts.no_bank_accounts_found') }}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            لم يتم تسجيل أي بنوك أو محافظ دفع في النظام حتى الآن. يمكنك إضافة حساب جديد من الزر أعلاه.
                        </p>
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($bankAccounts->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $bankAccounts->links() !!}
    </div>
@endif
