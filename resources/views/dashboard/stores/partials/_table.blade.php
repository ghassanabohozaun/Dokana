<input type="hidden" id="stores-total-count" value="{{ $stores->total() }}">

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
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
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
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

<!-- ========================================== -->
<!-- 2. MOBILE RESPONSIVE CARDS (Below md:)     -->
<!-- ========================================== -->
<div class="block md:hidden p-3 space-y-3">
    @forelse($stores as $store)
        @php
            $cleanPhone = preg_replace('/[^0-9]/', '', (string)$store->phone);
            $plan = strtolower($store->subscription_plan);
            $planBadge = match($plan) {
                'enterprise' => 'badge-pill-warning',
                'premium' => 'badge-pill-success',
                default => 'badge-pill-info',
            };
        @endphp
        <div id="mobile-row{{ $store->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Card Header: Logo, Name, Plan & Status Switch -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0">
                        @include('dashboard.stores.parts.logo')
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                            {{ $store->name }}
                        </h3>
                        <div class="flex items-center gap-1.5 flex-wrap mt-0.5">
                            <span class="badge-pill {{ $planBadge }} text-[10px]">
                                {{ __('stores.plan_' . $plan) }}
                            </span>
                            @if($store->creator)
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-0.5">
                                    <i class="fas fa-user-tie text-[9px]"></i> {{ $store->creator->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Badge / Toggle -->
                <div class="shrink-0 flex items-center gap-2">
                    @can('stores_update')
                        @include('dashboard.stores.parts.manage_status')
                    @else
                        @include('dashboard.stores.parts.status')
                    @endcan
                </div>
            </div>

            <!-- Contact Information Bar -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-1.5 text-xs">
                @if($store->phone)
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">الهاتف:</span>
                        <div class="flex items-center gap-1.5">
                            <a href="tel:{{ $store->phone }}" class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-200 font-bold hover:text-indigo-600" dir="ltr">
                                <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                                <span>{{ $store->phone }}</span>
                            </a>
                            @if($cleanPhone)
                                <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 hover:bg-emerald-100 text-xs" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($store->email)
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">البريد:</span>
                        <a href="mailto:{{ $store->email }}" class="text-indigo-600 dark:text-indigo-400 font-medium truncate text-[11px] hover:underline" dir="ltr">
                            {{ $store->email }}
                        </a>
                    </div>
                @endif

                @if($store->address)
                    <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-200/50 dark:border-slate-700/40 text-[11px]">
                        <span class="text-slate-400 font-medium shrink-0">العنوان:</span>
                        <span class="text-slate-600 dark:text-slate-300 truncate">{{ $store->address }}</span>
                    </div>
                @endif
            </div>

            <!-- Footer: Actions Row -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800">
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $store->created_at ? $store->created_at->format('Y-m-d') : '—' }}
                </span>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.stores.parts.actions')
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-store-slash"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {{ __('stores.no_stores_found') }}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                لم يتم تسجيل أي دكاكين في النظام حتى الآن.
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($stores->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $stores->links('dashboard.includes.pagination') !!}
    </div>
@endif
