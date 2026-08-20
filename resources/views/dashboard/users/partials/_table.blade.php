<input type="hidden" id="users-total-count" value="{!! $users->total() !!}">

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
                <th>{!! __('users.name') !!}</th>
                <th>{!! __('users.mobile') !!}</th>
                <th class="text-center">{!! __('roles.role') !!}</th>
                <th class="text-center">{!! __('general.status') !!}</th>
                <th>{!! __('general.created_at') !!}</th>
                <th class="w-24 text-center">{!! __('general.actions') !!}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @forelse ($users as $user)
                <tr id="row{{ $user->id }}" class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition-colors">
                    
                    <!-- Iteration # -->
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center h-6 min-w-6 px-1.5 rounded-full text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            {!! $loop->iteration + ($users->currentPage() - 1) * $users->perPage() !!}
                        </span>
                    </td>

                    <!-- Store (if admin/multi-store) -->
                    @if (isset($stores))
                        <td>
                            @if ($user->store)
                                <div class="flex items-center gap-1.5">
                                    <i class="fas fa-store text-xs text-slate-400"></i>
                                    <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $user->store->name }}
                                    </span>
                                </div>
                            @else
                                <span class="badge-pill badge-pill-warning text-[10px]">
                                    {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- User Photo & Name & Email -->
                    <td>
                        <div class="flex items-center gap-2.5">
                            @include('dashboard.users.parts.photo', ['user' => $user, 'sizeClass' => 'h-8 w-8'])
                            <div class="min-w-0">
                                <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                    {{ $user->name }}
                                </span>
                                @if ($user->email)
                                    <span class="text-[11px] text-slate-400 dark:text-slate-500 block truncate" dir="ltr">
                                        {{ $user->email }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    <!-- Mobile -->
                    <td>
                        @if ($user->mobile)
                            <a href="tel:{{ $user->mobile }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" dir="ltr">
                                <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                                <span>{{ $user->mobile }}</span>
                            </a>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Role -->
                    <td class="text-center">
                        @if ($user->role)
                            <span class="badge-pill badge-pill-info text-[11px]">
                                {{ $user->role->name }}
                            </span>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="text-center">
                        @include('dashboard.users.parts.status', ['user' => $user])
                    </td>

                    <!-- Created At -->
                    <td>
                        <span class="text-xs text-slate-600 dark:text-slate-400 font-medium" dir="ltr">
                            {{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}
                        </span>
                    </td>

                    <!-- Actions -->
                    <td class="text-center">
                        @include('dashboard.users.parts.actions', ['user' => $user])
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ isset($stores) ? 8 : 7 }}" class="text-center py-16 px-6">
                        <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                            <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                            <i class="fas fa-user-shield text-3xl text-indigo-500 dark:text-indigo-400"></i>
                            <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>

                        <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                            {!! __('users.no_users_found') !!}
                        </h4>
                        <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                            {!! __('users.no_users_desc') !!}
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
    @forelse ($users as $user)
        @php
            $cleanMobile = preg_replace('/[^0-9]/', '', (string)$user->mobile);
        @endphp
        <div id="mobile-row{{ $user->id }}" class="dash-card p-4 space-y-3 relative transition-all duration-200 hover:shadow-md border border-slate-200/90 dark:border-slate-800">
            
            <!-- Header: Photo, Name, Role & Status Switch -->
            <div class="flex items-start justify-between gap-2.5">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="shrink-0">
                        @include('dashboard.users.parts.photo', ['user' => $user, 'sizeClass' => 'h-10 w-10'])
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white truncate">
                                {{ $user->name }}
                            </h3>
                            @if ($user->role)
                                <span class="badge-pill badge-pill-info text-[9px] px-1.5 py-0.2">
                                    {{ $user->role->name }}
                                </span>
                            @endif
                        </div>

                        @if (isset($stores))
                            <div class="flex items-center gap-1 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                @if ($user->store)
                                    <i class="fas fa-store text-[10px] text-indigo-500"></i>
                                    <span class="truncate">{{ $user->store->name }}</span>
                                @else
                                    <span class="badge-pill badge-pill-warning text-[9px] px-1.5 py-0.2">
                                        عام (كل الفروع)
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status Switch -->
                <div class="shrink-0">
                    @include('dashboard.users.parts.status', ['user' => $user])
                </div>
            </div>

            <!-- Contact Information Bar -->
            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60 space-y-1.5 text-xs">
                @if($user->mobile)
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">الهاتف:</span>
                        <div class="flex items-center gap-1.5">
                            <a href="tel:{{ $user->mobile }}" class="inline-flex items-center gap-1 text-slate-700 dark:text-slate-200 font-bold hover:text-indigo-600" dir="ltr">
                                <i class="fas fa-phone-alt text-[10px] text-slate-400"></i>
                                <span>{{ $user->mobile }}</span>
                            </a>
                            @if($cleanMobile)
                                <a href="https://wa.me/{{ $cleanMobile }}" target="_blank" class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-950/60 dark:text-emerald-400 hover:bg-emerald-100 text-xs" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($user->email)
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-[11px] text-slate-400 font-medium">البريد:</span>
                        <a href="mailto:{{ $user->email }}" class="text-indigo-600 dark:text-indigo-400 font-medium truncate text-[11px] hover:underline" dir="ltr">
                            {{ $user->email }}
                        </a>
                    </div>
                @endif
            </div>

            <!-- Footer: Date & Actions -->
            <div class="flex items-center justify-between gap-2 pt-1 border-t border-slate-100 dark:border-slate-800 text-xs">
                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-medium" dir="ltr">
                    <i class="far fa-calendar-alt text-[10px] me-0.5"></i>
                    {{ $user->created_at ? $user->created_at->format('Y-m-d') : '—' }}
                </span>

                <div class="flex items-center gap-1.5">
                    @include('dashboard.users.parts.actions', ['user' => $user])
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 px-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-xl mx-auto mb-3">
                <i class="fas fa-user-shield"></i>
            </div>
            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">
                {!! __('users.no_users_found') !!}
            </h4>
            <p class="text-xs text-slate-400 dark:text-slate-500">
                {!! __('users.no_users_desc') !!}
            </p>
        </div>
    @endforelse
</div>

<!-- ========================================== -->
<!-- 3. RESPONSIVE PAGINATION FOOTER            -->
<!-- ========================================== -->
@if ($users->hasPages())
    <div class="p-3 sm:p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60">
        {!! $users->links('dashboard.includes.pagination') !!}
    </div>
@endif
