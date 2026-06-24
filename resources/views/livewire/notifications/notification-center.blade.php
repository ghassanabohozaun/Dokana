<div class="app-content content" wire:poll.20s>
    @section('title', __('notifications.notifications'))
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-6 col-12 mb-2 mb-md-0">
                <div class="row breadcrumbs-top">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb premium-breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard.index') }}">
                                    <i class="fas fa-home"></i> {{ __('dashboard.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active font-weight-bold">
                                {{ __('notifications.notifications') }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12 text-md-right mb-2">
                <div class="d-flex align-items-center justify-content-end mb-1 gap-15px">
                    @if ($notifications->count() > 0)
                        <button wire:click="markAllAsRead" class="btn btn-premium-add shadow-pulse mr-2">
                            <i class="fas fa-check-double mr-1"></i> {{ __('notifications.mark_all_read') }}
                        </button>
                        @if (count($selectedNotifications) > 0)
                            <button
                                onclick="confirmDeleteSelected()"
                                class="btn btn-premium-add shadow-pulse">
                                <i class="fas fa-trash-alt text-danger mr-1"></i> <span class="text-danger">{{ __('notifications.delete_selected') }}
                                    ({{ count($selectedNotifications) }})</span>
                            </button>
                        @else
                            <button
                                onclick="confirmDeleteAll()"
                                class="btn btn-premium-add shadow-pulse">
                                <i class="fas fa-trash-alt text-danger mr-1"></i> <span class="text-danger">{{ __('notifications.delete_all') }}</span>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <div class="content-body mt-3">
            <div class="card premium-card">
                <div class="premium-mandatory-header py-2">
                    <div class="title-wrapper">
                        <i class="fas fa-bell"></i>
                        <span class="font-weight-bold">{{ __('notifications.notifications') }}</span>
                    </div>
                </div>

                <div class="card-header border-0 px-0 pt-0 mx-2 pb-2 text-center d-flex justify-content-center">
                    <ul class="nav premium-nav-tabs" id="notificationTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'all' ? 'active' : '' }}"
                                data-text="{{ __('notifications.tab_all') }}" href="#"
                                wire:click.prevent="setTab('all')">
                                {{ __('notifications.tab_all') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'financial' ? 'active' : '' }}"
                                data-text="{{ __('notifications.tab_financial') }}" href="#"
                                wire:click.prevent="setTab('financial')">
                                <i class="fas fa-money-bill-wave"></i> {{ __('notifications.tab_financial') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'contracts' ? 'active' : '' }}"
                                data-text="{{ __('notifications.tab_contracts') }}" href="#"
                                wire:click.prevent="setTab('contracts')">
                                <i class="fas fa-file-contract"></i> {{ __('notifications.tab_contracts') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $activeTab === 'system' ? 'active' : '' }}"
                                data-text="{{ __('notifications.tab_system') }}" href="#"
                                wire:click.prevent="setTab('system')">
                                <i class="fas fa-server"></i> {{ __('notifications.tab_system') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;" class="border-top-0 text-center align-middle py-3">
                                        @if ($notifications->count() > 0)
                                            <div class="premium-checkbox-custom" style="margin-right: -10px;">
                                                <input type="checkbox" id="selectAllNotifications"
                                                    wire:model.live="selectAll">
                                            </div>
                                        @endif
                                    </th>
                                    <th colspan="2" class="border-top-0 align-middle text-muted font-weight-bold" style="font-size: 0.95rem;">
                                        {{ __('notifications.notifications') ?? 'Notifications' }}
                                    </th>
                                    <th class="border-top-0 text-center align-middle text-muted font-weight-bold" style="width: 150px; font-size: 0.95rem;">
                                        <i class="far fa-clock"></i> {{ __('general.date') ?? 'Date' }}
                                    </th>
                                    <th class="border-top-0 text-center align-middle sticky-actions text-muted font-weight-bold" style="width: 150px; font-size: 0.95rem;">
                                        <i class="fas fa-cog"></i> {{ __('general.actions') ?? 'Actions' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications as $notification)
                                    @php
                                        $data = $notification->data;
                                        $isUnread = is_null($notification->read_at);
                                        $bgClass = $isUnread ? 'bg-light-primary' : '';
                                        $title = __(
                                            $data['title_key'] ?? 'notifications.system_alert',
                                            $data['params'] ?? [],
                                        );
                                        $message = __($data['message_key'] ?? '', $data['params'] ?? []);
                                        $icon = $data['icon'] ?? 'fas fa-bell';
                                        // Convert LineAwesome to FontAwesome safely
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
                                        $url = $data['action_url'] ?? '#';
                                    @endphp
                                    <tr class="{{ $bgClass }}" style="border-bottom: 1px solid #f1f1f1;">
                                        <td class="text-center align-middle">
                                            <div class="premium-checkbox-custom">
                                                <input type="checkbox" id="chk_{{ $notification->id }}"
                                                    wire:model.live="selectedNotifications"
                                                    value="{{ $notification->id }}">
                                            </div>
                                        </td>
                                        <td width="60" class="text-center align-middle">
                                            <div class="avatar avatar-sm bg-{{ $level }} shadow-sm">
                                                <span class="avatar-content"><i
                                                        class="{{ $icon }} text-white"></i></span>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <h6
                                                class="mb-0 {{ $isUnread ? 'font-weight-bold text-dark' : 'text-secondary' }}">
                                                {{ $title }}
                                                @if ($isUnread)
                                                    <span class="badge badge-pill badge-danger ml-1 shadow-sm"
                                                        style="font-size: 0.65rem;">{{ __('notifications.new') }}</span>
                                                @endif
                                            </h6>
                                            <p class="text-muted mb-0 mt-1" style="font-size: 0.95rem;">
                                                {{ $message }}</p>
                                        </td>
                                        <td width="150" class="text-center align-middle text-muted"
                                            style="font-size: 0.85rem;">
                                            <i class="far fa-clock"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </td>
                                        <td width="150" class="text-center align-middle sticky-actions">
                                            <div class="d-flex align-items-center justify-content-center">
                                                @if ($url && $url !== '#' && $url !== 'javascript:void(0)')
                                                    <a href="{{ route('dashboard.notifications.redirect', $notification->id) }}"
                                                        class="btn-premium-action btn-premium-action-edit mr-1"
                                                        title="{{ __('notifications.view_details') }}">
                                                        <i class="fas fa-external-link-alt"></i>
                                                    </a>
                                                @endif
                                                @if ($isUnread)
                                                    <a href="#"
                                                        wire:click.prevent="markAsRead('{{ $notification->id }}')"
                                                        class="btn-premium-action btn-premium-action-success mr-1"
                                                        title="{{ __('notifications.mark_as_read') }}">
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                @endif
                                                <a href="#"
                                                    onclick="confirmDeleteSingle('{{ $notification->id }}'); return false;"
                                                    class="btn-premium-action btn-premium-action-danger"
                                                    title="{{ __('general.delete') }}">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="fas fa-bell-slash text-muted"
                                                style="font-size: 4rem; opacity: 0.3;"></i>
                                            <h5 class="mt-3 mb-0 text-muted font-weight-bold">
                                                {{ __('notifications.no_new_notifications') }}</h5>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($notifications->hasPages())
                        <div class="p-3 d-flex justify-content-center" style="border-top: 1px solid #e4e4e4;">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDeleteSingle(id) {
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
    }

    function confirmDeleteSelected() {
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
    }

    function confirmDeleteAll() {
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
    }
</script>
@endpush
