<div class="space-y-4" wire:poll.20s>
    @section('title', __('notifications.notifications'))

    <!-- 1. Sleek Navigation & Breadcrumb Top Bar -->
    <div class="flex items-center justify-between gap-4">
        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
            <a href="{{ route('dashboard.index') }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <i class="fas fa-home text-xs"></i>
                <span>{{ __('dashboard.home') }}</span>
            </a>
            <span>/</span>
            <span class="text-slate-700 dark:text-slate-200 font-bold">{{ __('notifications.notifications') }}</span>
        </nav>

        <!-- Live Status Indicator -->
        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800/80 border border-slate-200/60 dark:border-slate-700/60 text-[11px] text-slate-500 dark:text-slate-400">
            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>{{ __('notifications.live_updates') }}</span>
        </div>
    </div>

    <!-- 2. Master Dashboard Enterprise Card -->
    <div class="dash-card overflow-hidden">
        
        <!-- Card Master Header -->
        <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Left: Card Title & Icon -->
            <div class="flex items-center gap-3.5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white text-base shadow-md shadow-indigo-500/20">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm md:text-base font-bold text-slate-800 dark:text-white">
                            {{ __('notifications.notifications') }}
                        </h3>
                        <span class="badge-pill badge-pill-info text-[10px]">
                            {{ $notifications->total() }}
                        </span>
                    </div>
                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                        {{ __('notifications.center_subtitle') }}
                    </p>
                </div>
            </div>

            <!-- Right: Bulk Actions & Selection -->
            <div class="flex items-center gap-2.5 flex-wrap">
                @if ($notifications->count() > 0)
                    <!-- Select All Checkbox -->
                    <label class="flex items-center gap-2 cursor-pointer select-none px-3 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-2xs hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        <input type="checkbox" id="selectAllNotifications" wire:model.live="selectAll"
                            class="h-4 w-4 rounded-md border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/20">
                        <span>{{ __('general.select_all') ?? 'تحديد الكل' }}</span>
                    </label>

                    <!-- Mark All As Read -->
                    <button type="button" wire:click="markAllAsRead"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 shadow-2xs active:scale-[0.98] transition-all">
                        <i class="fas fa-check-double text-indigo-500 text-xs"></i>
                        <span>{{ __('notifications.mark_all_read') }}</span>
                    </button>

                    <!-- Delete Actions -->
                    @if (count($selectedNotifications) > 0)
                        <button type="button" onclick="confirmDeleteSelected()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 transition-colors shadow-2xs">
                            <i class="fas fa-trash-alt text-xs"></i>
                            <span>{{ __('notifications.delete_selected') }} ({{ count($selectedNotifications) }})</span>
                        </button>
                    @else
                        <button type="button" onclick="confirmDeleteAll()"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 hover:bg-rose-100 transition-colors shadow-2xs">
                            <i class="fas fa-trash-alt text-xs"></i>
                            <span>{{ __('notifications.delete_all') }}</span>
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <!-- Notification Items List -->
        <div class="divide-y divide-slate-100 dark:divide-slate-800/60 bg-white dark:bg-slate-900">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = is_null($notification->read_at);
                    $title = __($data['title_key'] ?? 'notifications.system_alert', $data['params'] ?? []);
                    $message = __($data['message_key'] ?? '', $data['params'] ?? []);
                    $icon = $data['icon'] ?? 'fas fa-bell';
                    if (strpos($icon, 'la la-') !== false) {
                        $icon = str_replace('la la-', 'fas fa-', $icon);
                    }
                    $url = $data['action_url'] ?? '#';
                @endphp
                <div class="group flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 hover:bg-slate-50/90 dark:hover:bg-slate-800/40 transition-all {{ $isUnread ? 'bg-indigo-50/25 dark:bg-indigo-950/20 border-s-4 border-indigo-600' : 'border-s-4 border-transparent' }}">
                    <div class="flex items-start gap-3.5 flex-1 min-w-0">
                        <!-- Row Checkbox -->
                        <div class="pt-1.5">
                            <input type="checkbox" id="chk_{{ $notification->id }}"
                                wire:model.live="selectedNotifications" value="{{ $notification->id }}"
                                class="h-4 w-4 rounded-md border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500/20">
                        </div>

                        <!-- Notification Icon -->
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl {{ $isUnread ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-600/30' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400' }} text-sm transition-transform group-hover:scale-105">
                            <i class="{{ $icon }}"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="text-xs md:text-sm font-bold text-slate-800 dark:text-white truncate {{ $isUnread ? 'text-indigo-950 dark:text-indigo-100' : '' }}">
                                    {{ $title }}
                                </h4>
                                @if ($isUnread)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400">
                                        {{ __('notifications.new') }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed line-clamp-2">
                                {{ $message }}
                            </p>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-2 inline-flex items-center gap-1.5">
                                <i class="far fa-clock text-[10px]"></i>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Direct Action Buttons -->
                    <div class="flex items-center gap-1.5 self-end sm:self-center shrink-0">
                        @if ($url && $url !== '#' && $url !== 'javascript:void(0)')
                            <a href="{{ route('dashboard.notifications.redirect', $notification->id) }}"
                                class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40"
                                title="{{ __('notifications.view_details') }}">
                                <i class="fas fa-external-link-alt text-xs"></i>
                            </a>
                        @endif

                        @if ($isUnread)
                            <button type="button" wire:click.prevent="markAsRead('{{ $notification->id }}')"
                                class="btn-icon-action text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40"
                                title="{{ __('notifications.mark_as_read') }}">
                                <i class="fas fa-check text-xs"></i>
                            </button>
                        @endif

                        <button type="button" onclick="confirmDeleteSingle('{{ $notification->id }}')"
                            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40"
                            title="{{ __('general.delete') }}">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </div>
            @empty
                <!-- Ultra-Premium Empty State -->
                <div class="py-16 px-6 text-center">
                    <div class="relative mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-gradient-to-b from-indigo-50/60 to-slate-100/80 dark:from-slate-800/80 dark:to-indigo-950/40 border border-slate-200/80 dark:border-slate-700/60 shadow-inner mb-4">
                        <div class="absolute inset-0 rounded-3xl bg-indigo-500/10 dark:bg-indigo-500/20 blur-xl"></div>
                        <i class="fas fa-bell-slash text-3xl text-indigo-500 dark:text-indigo-400"></i>
                        <span class="absolute top-2.5 end-2.5 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                    </div>

                    <h4 class="text-sm md:text-base font-bold text-slate-800 dark:text-slate-100 mb-1.5">
                        {{ __('notifications.no_new_notifications') }}
                    </h4>
                    <p class="text-xs text-slate-400 dark:text-slate-500 max-w-sm mx-auto leading-relaxed">
                        {{ __('notifications.no_notifications_desc') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Card Pagination Footer -->
        @if ($notifications->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/60 flex justify-center">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteSingle(id) {
        if (typeof swal !== 'undefined') {
            swal({
                title: '{!! __('notifications.confirm_delete_title') !!}',
                text: '{!! __('notifications.confirm_delete_single_text') !!}',
                icon: 'warning',
                buttons: {
                    cancel: { text: '{!! __('general.no') !!}', value: null, visible: true, className: "", closeModal: true },
                    confirm: { text: '{!! __('general.yes') !!}', value: true, visible: true, className: "btn-danger", closeModal: true }
                }
            }).then((isConfirm) => {
                if (isConfirm) {
                    @this.deleteNotification(id);
                }
            });
        } else if (confirm('{!! __('notifications.confirm_delete_single_text') !!}')) {
            @this.deleteNotification(id);
        }
    }

    function confirmDeleteSelected() {
        if (typeof swal !== 'undefined') {
            swal({
                title: '{!! __('notifications.confirm_delete_title') !!}',
                text: '{!! __('notifications.confirm_delete_selected_text') !!}',
                icon: 'warning',
                buttons: {
                    cancel: { text: '{!! __('general.no') !!}', value: null, visible: true, className: "", closeModal: true },
                    confirm: { text: '{!! __('general.yes') !!}', value: true, visible: true, className: "btn-danger", closeModal: true }
                }
            }).then((isConfirm) => {
                if (isConfirm) {
                    @this.deleteSelected();
                }
            });
        } else if (confirm('{!! __('notifications.confirm_delete_selected_text') !!}')) {
            @this.deleteSelected();
        }
    }

    function confirmDeleteAll() {
        if (typeof swal !== 'undefined') {
            swal({
                title: '{!! __('notifications.confirm_delete_title') !!}',
                text: '{!! __('notifications.confirm_delete_all_text') !!}',
                icon: 'warning',
                buttons: {
                    cancel: { text: '{!! __('general.no') !!}', value: null, visible: true, className: "", closeModal: true },
                    confirm: { text: '{!! __('general.yes') !!}', value: true, visible: true, className: "btn-danger", closeModal: true }
                }
            }).then((isConfirm) => {
                if (isConfirm) {
                    @this.deleteAllNotifications();
                }
            });
        } else if (confirm('{!! __('notifications.confirm_delete_all_text') !!}')) {
            @this.deleteAllNotifications();
        }
    }
</script>
@endpush
