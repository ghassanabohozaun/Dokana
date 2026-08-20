<input type="hidden" id="payment_entities-total-count" value="{{ $entities->total() }}">

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-hidden">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{{ __('payment_entities.name') }}</th>
                <th class="text-center">{{ __('payment_entities.type') }}</th>
                <th class="hidden sm:table-cell">{{ __('departments.created_by') }}</th>
                <th class="text-center">{{ __('payment_entities.status') }}</th>
                @if(($isSuperAdmin ?? false) && auth()->user()->can('payment_entities_update'))
                <th class="text-center">{{ __('departments.manage_status') }}</th>
                @endif
                @if($isSuperAdmin ?? false)
                <th class="w-24 text-center sticky end-0 bg-slate-50 dark:bg-slate-800 z-10 border-s border-slate-200/80 dark:border-slate-700/80 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">{{ __('general.actions') ?? 'الإجراءات' }}</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($entities as $entity)
                <tr id="row{{ $entity->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {{ $loop->iteration + ($entities->currentPage() - 1) * $entities->perPage() }}
                        </span>
                    </td>

                    <!-- Entity Name with Icon -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $entity->type == 'wallet' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400' }} text-xs">
                                <i class="fas {{ $entity->type == 'wallet' ? 'fa-wallet' : 'fa-university' }}"></i>
                            </div>
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-white">
                                {{ $entity->name }}
                            </span>
                        </div>
                    </td>

                    <!-- Type Badge -->
                    <td class="text-center">
                        @if($entity->type == 'wallet')
                            <span class="badge-pill badge-pill-info text-[10px]">
                                <i class="fas fa-wallet text-[9px] me-1"></i> {{ __('payment_entities.type_wallet') }}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-primary text-[10px]">
                                <i class="fas fa-university text-[9px] me-1"></i> {{ __('payment_entities.type_bank') }}
                            </span>
                        @endif
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($entity->creator)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i> {{ $entity->creator->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Status Badge -->
                    <td class="text-center">
                        @include('dashboard.payment_entities.parts.status')
                    </td>

                    <!-- Manage Status Toggle Switch -->
                    @if(($isSuperAdmin ?? false) && auth()->user()->can('payment_entities_update'))
                    <td class="text-center">
                        @include('dashboard.payment_entities.parts.manage_status')
                    </td>
                    @endif

                    <!-- Actions -->
                    @if($isSuperAdmin ?? false)
                    <td class="text-center sticky end-0 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs z-10 border-s border-slate-100 dark:border-slate-800 shadow-[-3px_0_6px_-2px_rgba(0,0,0,0.06)]">
                        @include('dashboard.payment_entities.parts.actions')
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-landmark text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {{ __('payment_entities.no_payment_entities_found') }}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            لم يتم تسجيل أي جهات دفع في النظام حتى الآن. يمكنك إضافة جهة دفع جديدة من الزر أعلاه.
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
    @forelse($entities as $entity)
        <div id="mobile-row{{ $entity->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Icon, Name & Type -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $entity->type == 'wallet' ? 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400' : 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400' }} text-base font-bold shadow-xs">
                        <i class="fas {{ $entity->type == 'wallet' ? 'fa-wallet' : 'fa-university' }}"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                            {{ $entity->name }}
                        </h3>
                        <div class="mt-0.5">
                            @if($entity->type == 'wallet')
                                <span class="badge-pill badge-pill-info text-[9px] px-1.5 py-0.5">
                                    <i class="fas fa-wallet text-[8px] me-1"></i> {{ __('payment_entities.type_wallet') }}
                                </span>
                            @else
                                <span class="badge-pill badge-pill-primary text-[9px] px-1.5 py-0.5">
                                    <i class="fas fa-university text-[8px] me-1"></i> {{ __('payment_entities.type_bank') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="shrink-0 flex items-center gap-2">
                    @if(($isSuperAdmin ?? false) && auth()->user()->can('payment_entities_update'))
                        @include('dashboard.payment_entities.parts.manage_status')
                    @else
                        @include('dashboard.payment_entities.parts.status')
                    @endcan
                </div>
            </div>

            <!-- Footer: Creator & Actions -->
            <div class="flex items-center justify-between gap-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs">
                @if($entity->creator)
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center gap-1">
                        <i class="fas fa-user-tie text-[9px]"></i>
                        <span class="truncate max-w-[120px]">{{ $entity->creator->name }}</span>
                    </span>
                @else
                    <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                        <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                        {{ $entity->created_at ? $entity->created_at->format('Y-m-d') : '—' }}
                    </span>
                @endif

                @if($isSuperAdmin ?? false)
                    <div class="flex items-center gap-1.5">
                        @include('dashboard.payment_entities.parts.actions')
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-landmark"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {{ __('payment_entities.no_payment_entities_found') }}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                لم يتم تسجيل أي جهات دفع في النظام حتى الآن.
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($entities->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $entities->links('dashboard.includes.pagination') !!}
    </div>
@endif
