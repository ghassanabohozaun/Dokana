<input type="hidden" id="departments-total-count" value="{{ $departments->total() }}">

<div class="overflow-x-auto">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if(isset($stores))
                <th>{{ __('stores.store') }}</th>
                @endif
                <th>{{ __('departments.name') }}</th>
                <th class="hidden sm:table-cell">{{ __('departments.created_by') }}</th>
                <th class="text-center">{{ __('departments.status') }}</th>
                @can('departments_update')
                <th class="text-center">{{ __('departments.manage_status') }}</th>
                @endcan
                <th class="text-center w-24">{{ __('general.actions') ?? 'الإجراءات' }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
            @forelse($departments as $department)
                <tr id="row{{ $department->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors">
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="text-xs font-bold text-slate-400 dark:text-slate-500">
                            {{ $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() }}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if(isset($stores))
                    <td>
                        @if($department->store_id)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                {{ optional($department->store)->name }}
                            </span>
                        @else
                            <span class="badge-pill badge-pill-warning text-[10px]">
                                <i class="fas fa-globe text-[9px] me-1"></i> {{ __('roles.global_role') ?? 'عام' }}
                            </span>
                        @endif
                    </td>
                    @endif

                    <!-- Department Name -->
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-xs md:text-sm text-slate-800 dark:text-white">
                                {{ $department->name }}
                            </span>
                        </div>
                    </td>

                    <!-- Created By -->
                    <td class="hidden sm:table-cell">
                        @if($department->creator)
                            <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                                <i class="fas fa-user-tie text-[10px] text-slate-400"></i> {{ $department->creator->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Status Badge -->
                    <td class="text-center">
                        @include('dashboard.departments.parts.status')
                    </td>

                    <!-- Manage Status Toggle Switch -->
                    @can('departments_update')
                    <td class="text-center">
                        @include('dashboard.departments.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Row Action Buttons -->
                    <td class="text-center">
                        @include('dashboard.departments.parts.actions')
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 7 : 6 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-sitemap text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {{ __('departments.no_departments_found') }}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            لم يتم تسجيل أي أقسام في النظام حتى الآن. يمكنك إضافة قسم جديد من الزر أعلاه.
                        </p>
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
@if ($departments->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
        {!! $departments->links() !!}
    </div>
@endif
