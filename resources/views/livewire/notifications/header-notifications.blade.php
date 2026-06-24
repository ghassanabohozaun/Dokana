<li class="dropdown dropdown-notification nav-item" x-data="{ open: false }" @click.outside="open = false"
    wire:poll.10s="checkNotifications">
    <a class="nav-link nav-link-label" href="#" @click.prevent="open = !open">
        <div class="enterprise-action-btn">
            <i class="ficon la la-bell"></i>
            @if ($unreadCount > 0)
                <span class="enterprise-badge badge-danger">{{ $unreadCount }}</span>
            @endif
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" x-show="open" style="display: none;"
        :class="{ 'show': open }">
        <div class="dropdown-header-main pb-1">
            <h6 class="header-title"><i class="fas fa-bell mr-1"></i> {{ __('notifications.notifications') }}</h6>
        </div>

        <div class="px-0 pt-0 pb-1 text-center d-flex justify-content-center border-bottom">
            <ul class="nav premium-nav-tabs" id="headerNotificationTabs" role="tablist"
                style="transform: scale(0.85); transform-origin: center top; margin-bottom: -5px;">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}"
                        data-text="{{ __('notifications.tab_all') }}" href="#" wire:click.prevent="setTab('all')">
                        {{ __('notifications.tab_all') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'financial' ? 'active' : '' }}"
                        data-text="{{ __('notifications.tab_financial') }}" href="#"
                        wire:click.prevent="setTab('financial')">
                        {{ __('notifications.tab_financial') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTab === 'contracts' ? 'active' : '' }}"
                        data-text="{{ __('notifications.tab_contracts') }}" href="#"
                        wire:click.prevent="setTab('contracts')">
                        {{ __('notifications.tab_contracts') }}
                    </a>
                </li>
            </ul>
        </div>

        <div class="scrollable-container media-list w-100 custom-scrollbar"
            style="max-height: 350px; overflow-y: auto;">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $title = __($data['title_key'] ?? 'notifications.system_alert', $data['params'] ?? []);
                    $message = __($data['message_key'] ?? '', $data['params'] ?? []);

                    // Convert old la la- to fas fa- safely
                    $icon = $data['icon'] ?? 'fas fa-bell';
                    if (strpos($icon, 'la la-') !== false) {
                        $icon = str_replace('la la-', 'fas fa-', $icon);
                        if (strpos($icon, 'fa-file-text') !== false) {
                            $icon = str_replace('fa-file-text', 'fa-file-alt', $icon);
                        }
                        if ($icon === 'fas fa-money') {
                            $icon = 'fas fa-money-bill-wave';
                        }
                    }

                    $level = $data['level'] ?? 'info';
                    $bgClass = 'bg-' . $level;
                @endphp
                <a class="preview-item-premium" href="javascript:void(0)"
                    style="cursor: default; {{ $isUnread ? 'background-color: rgba(102, 110, 232, 0.05); border-right: 3px solid #666ee8;' : 'transition: background-color 0.2s;' }}">
                    <div class="preview-thumbnail-premium {{ $bgClass }}">
                        <i class="{{ $icon }}"></i>
                    </div>
                    <div class="preview-item-content-premium w-100">
                        <span class="subject">
                            {{ $title }}
                            @if ($isUnread)
                                <span class="badge badge-pill badge-danger shadow-sm ml-1"
                                    style="font-size: 0.55rem; padding: 2px 5px;">{{ __('notifications.new') }}</span>
                            @endif
                        </span>
                        <span class="message-text d-block mb-1">{{ $message }}</span>

                        <span class="time text-muted d-block mt-1" style="font-size: 0.7rem;"><i
                                class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                </a>
            @empty
                <div class="p-3 text-center text-muted">
                    <i class="fas fa-bell-slash" style="font-size: 2rem;"></i>
                    <p class="mt-1 mb-0">{{ __('notifications.no_new_notifications') }}</p>
                </div>
            @endforelse
        </div>
        <div class="dropdown-footer-premium">
            <a href="{{ route('dashboard.notifications') }}"><i class="fas fa-list-ul mr-1"></i>
                {{ __('notifications.view_all') }}</a>
        </div>
    </div>
</li>
