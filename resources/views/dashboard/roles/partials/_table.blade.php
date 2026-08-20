<input type="hidden" id="roles-total-count" value="{!! $roles->total() !!}">

<!-- ========================================== -->
<!-- 1. DESKTOP / TABLET DATA TABLE (md: & up)  -->
<!-- ========================================== -->
<div class="hidden md:block overflow-x-auto custom-scrollbar">
    <table class="table-modern w-full" id="myTable">
        <thead>
            <tr>
                <th class="w-12 text-center">#</th>
                @if (isset($stores))
                    <th>{!! __('stores.store') !!}</th>
                @endif
                <th>{!! __('roles.role_name') !!}</th>
                <th class="text-center">{!! __('roles.permissions') !!}</th>
                <th class="text-center">{!! __('users.users') !!}</th>
                <th>{!! __('general.created_at') !!}</th>
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($roles as $role)
                <tr id="row{{ $role->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($role->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $role->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Role Name & Description -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-slate-800 dark:text-white truncate">
                                        {{ $role->name }}
                                    </span>
                                    @if($role->isSystemRole())
                                        <span class="badge-pill badge-pill-warning text-[9px]">
                                            دور نظام محمي
                                        </span>
                                    @endif
                                </div>
                                @if($role->description)
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 block truncate max-w-xs">
                                        {{ $role->description }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Permissions Count -->
                    <td class="text-center">
                        <span class="badge-pill badge-pill-info text-[11px]">
                            {{ $role->permissions ? $role->permissions->count() : 0 }} {!! __('roles.permissions') !!}
                        </span>
                    </td>

                    <!-- Users Count -->
                    <td class="text-center">
                        <span class="badge-pill badge-pill-secondary text-[11px]">
                            {{ $role->users_count ?? $role->users()->count() }} {!! __('users.users') !!}
                        </span>
                    </td>

                    <!-- Created At -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $role->created_at ? $role->created_at->format('Y-m-d') : '—' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.roles.parts.actions', ['role' => $role])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 7 : 6 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-user-shield text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('roles.no_roles_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('roles.no_roles_desc') !!}
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
    @forelse ($roles as $role)
        <div id="mobile-row{{ $role->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Role Shield, Name & Store / System Pill -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm font-bold shadow-xs">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                {{ $role->name }}
                            </h3>
                            @if($role->isSystemRole())
                                <span class="badge-pill badge-pill-warning text-[9px] px-1.5 py-0.2">
                                    نظام محمي
                                </span>
                            @endif
                        </div>
                        @if (isset($stores))
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                @if ($role->store)
                                    <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                    <span class="truncate">{{ $role->store->name }}</span>
                                @else
                                    <span class="badge-pill badge-pill-warning text-[9px] px-1.5 py-0.2">
                                        عام (كل الفروع)
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($role->description)
                <p class="text-xs text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl border border-slate-100 dark:border-slate-800">
                    {{ $role->description }}
                </p>
            @endif

            <!-- Mini Matrix: Permissions & Users Count -->
            <div class="grid grid-cols-2 gap-2 p-2.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/60 text-center">
                <!-- Permissions -->
                <div class="space-y-0.5">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">الصلاحيات</span>
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400">
                        <i class="fas fa-key text-[10px]"></i>
                        <span>{{ $role->permissions ? $role->permissions->count() : 0 }}</span>
                    </span>
                </div>

                <!-- Users -->
                <div class="space-y-0.5 border-s border-slate-200 dark:border-slate-700/60 ps-1">
                    <span class="block text-[10px] font-bold text-slate-500 dark:text-slate-400">المستخدمين</span>
                    <span class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 dark:text-slate-200">
                        <i class="fas fa-users text-[10px] text-slate-400"></i>
                        <span>{{ $role->users_count ?? $role->users()->count() }}</span>
                    </span>
                </div>
            </div>

            <!-- Footer: Date & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $role->created_at ? $role->created_at->format('Y-m-d') : '—' }}
                </span>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.roles.parts.actions', ['role' => $role])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('roles.no_roles_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('roles.no_roles_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($roles->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $roles->links('dashboard.includes.pagination') !!}
    </div>
@endif
