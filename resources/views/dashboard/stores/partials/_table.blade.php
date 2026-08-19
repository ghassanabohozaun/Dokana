<input type="hidden" id="stores-total-count" value="{{ $stores->total() }}">

<div class="overflow-x-auto">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th class="w-16 text-center">{{ __('stores.logo') }}</th>
                <th>{{ __('stores.store_name') }}</th>
                <th class="hidden sm:table-cell">{{ __('stores.email') }} / {{ __('stores.phone') }}</th>
                <th class="text-center">{{ __('stores.subscription_plan') }}</th>
                <th class="text-center">{{ __('stores.status') }}</th>
                @can('stores_update')
                <th class="text-center">{{ __('stores.manage_status') }}</th>
                @endcan
                <th class="text-center w-24">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($stores as $store)
                <tr id="row{{ $store->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                            {{ $loop->iteration + ($stores->currentPage() - 1) * $stores->perPage() }}
                        </span>
                    </td>

                    <!-- Logo -->
                    <td class="text-center">
                        @include('dashboard.stores.parts.logo')
                    </td>

                    <!-- Store Name & Creator -->
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-white">
                                {{ $store->name }}
                            </span>
                        </div>
                        @if($store->creator)
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1 mt-0.5">
                                <i class="fas fa-user-tie text-[9px]"></i> {{ $store->creator->name }}
                            </span>
                        @endif
                    </td>

                    <!-- Email & Phone -->
                    <td class="hidden sm:table-cell">
                        <div class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                            {{ $store->email ?? '—' }}
                        </div>
                        @if($store->phone)
                            <div class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5" dir="ltr">
                                <i class="fas fa-phone text-[9px] me-1"></i>{{ $store->phone }}
                            </div>
                        @endif
                    </td>

                    <!-- Subscription Plan -->
                    <td class="text-center">
                        @php
                            $plan = strtolower($store->subscription_plan);
                            $planBadge = match($plan) {
                                'enterprise' => 'badge-pill-warning',
                                'premium' => 'badge-pill-success',
                                default => 'badge-pill-info',
                            };
                        @endphp
                        <span class="badge-pill {{ $planBadge }}">
                            {{ __('stores.plan_' . $plan) }}
                        </span>
                    </td>

                    <!-- Status Badge -->
                    <td class="text-center">
                        @include('dashboard.stores.parts.status')
                    </td>

                    <!-- Manage Status Toggle Switch -->
                    @can('stores_update')
                    <td class="text-center">
                        @include('dashboard.stores.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Row Action Buttons -->
                    <td class="text-center">
                        @include('dashboard.stores.parts.actions')
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-store-slash text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {{ __('stores.no_stores_found') }}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            لم يتم تسجيل أي دكاكين في النظام حتى الآن. يمكنك إضافة دكانة جديدة من الزر أعلاه.
                        </p>
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($stores->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $stores->links() !!}
    </div>
@endif
