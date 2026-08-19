<input type="hidden" id="payment_entities-total-count" value="{{ $entities->total() }}">

<div class="overflow-x-auto">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                <th>{{ __('payment_entities.name') }}</th>
                <th class="text-center">{{ __('payment_entities.type') }}</th>
                <th class="hidden sm:table-cell">{{ __('departments.created_by') }}</th>
                <th class="text-center">{{ __('payment_entities.status') }}</th>
                @can('payment_entities_update')
                <th class="text-center">{{ __('departments.manage_status') }}</th>
                @endcan
                <th class="text-center w-24">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($entities as $entity)
                <tr id="row{{ $entity->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
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
                    @can('payment_entities_update')
                    <td class="text-center">
                        @include('dashboard.payment_entities.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.payment_entities.parts.actions')
                    </td>
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

<!-- Pagination Footer -->
@if ($entities->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $entities->links() !!}
    </div>
@endif
