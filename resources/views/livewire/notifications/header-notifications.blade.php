<div class="relative" x-data="{ open: false }" @click.outside="open = false" wire:poll.15s="checkNotifications">
    <!-- Notification Bell Trigger Button -->
    <button type="button" @click.prevent="open = !open"
        class="relative flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 transition-colors focus:outline-none"
        aria-label="{{ __('notifications.notifications') }}">
        <i class="fas fa-bell text-sm"></i>
        @if ($unreadCount > 0)
            <span class="absolute top-1 end-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-rose-500 px-1 text-[9px] font-extrabold text-white ring-2 ring-white dark:ring-slate-900 animate-pulse">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notifications Dropdown Popover Card -->
    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="absolute end-0 mt-2 w-80 sm:w-96 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-dropdown z-50 overflow-hidden"
        style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
            <div class="flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 text-xs">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="text-xs font-bold text-slate-800 dark:text-white">{{ __('notifications.notifications') }}</h3>
            </div>
            
            <div class="flex items-center gap-2">
                @if ($unreadCount > 0)
                    <span class="rounded-full bg-rose-50 dark:bg-rose-950/50 px-2 py-0.5 text-[10px] font-bold text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/40">
                        {{ $unreadCount }} {{ __('notifications.new') }}
                    </span>
                    <button type="button" wire:click.prevent="markAllAsRead"
                        class="text-[10px] font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 hover:underline">
                        {{ __('notifications.mark_all_read') }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-800/60 custom-scrollbar">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $title = __($data['title_key'] ?? 'notifications.system_alert', $data['params'] ?? []);
                    $message = __($data['message_key'] ?? '', $data['params'] ?? []);

                    $icon = $data['icon'] ?? 'fas fa-bell';
                    if (strpos($icon, 'la la-') !== false) {
                        $icon = str_replace('la la-', 'fas fa-', $icon);
                    }
                    $url = $data['action_url'] ?? '#';
                @endphp
                <div class="group flex items-start gap-3 p-3.5 hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors {{ $isUnread ? 'bg-indigo-50/25 dark:bg-indigo-950/20' : '' }}">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $isUnread ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-500' }} text-xs transition-transform group-hover:scale-105">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate {{ $isUnread ? 'text-indigo-950 dark:text-indigo-200' : '' }}">{{ $title }}</p>
                            @if ($isUnread)
                                <span class="h-1.5 w-1.5 rounded-full bg-rose-500 shrink-0"></span>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">{{ $message }}</p>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 inline-flex items-center gap-1">
                            <i class="far fa-clock text-[9px]"></i>
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </span>
                    </div>
                </div>
            @empty
                <!-- Dropdown Empty State -->
                <div class="py-8 px-4 text-center">
                    <div class="relative mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-slate-200/60 dark:border-slate-800 text-indigo-500 dark:text-indigo-400 text-lg mb-2 shadow-inner">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mb-0.5">
                        {{ __('notifications.no_new_notifications') }}
                    </p>
                    <p class="text-[10px] text-slate-400 dark:text-slate-500">
                        {{ __('notifications.no_notifications_desc') }}
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <div class="border-t border-slate-100 dark:border-slate-800 p-2 text-center bg-slate-50/60 dark:bg-slate-900/90">
            <a href="{{ route('dashboard.notifications') }}"
                class="inline-flex items-center justify-center gap-1.5 w-full py-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-white dark:hover:bg-slate-800 rounded-xl transition-all">
                <span>{{ __('notifications.view_all') }}</span>
                <i class="fas fa-arrow-left rtl:rotate-180 text-[10px]"></i>
            </a>
        </div>
    </div>
</div>
