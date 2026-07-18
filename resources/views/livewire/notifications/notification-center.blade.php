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
                                    <!-- Actions Column Removed for Bottom Action Bar -->
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
                                    <tr class="premium-table-row pointer {{ $bgClass }}" data-row-title="{{ $title }}" style="border-bottom: 1px solid #f1f1f1;">
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
                                            <!-- Hidden Actions for Bottom Bar -->
                                            <div class="row-actions-html d-none">
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
                                            </div>

                                            <!-- Hidden Subtitle for Bottom Bar -->
                                            <div class="row-subtitle-html d-none">
                                                <span class="badge badge-secondary"><i class="far fa-clock mr-25"></i> {{ $notification->created_at->diffForHumans() }}</span>
                                                @if ($isUnread)
                                                    <span class="badge badge-light-danger"><i class="fas fa-circle mr-25"></i> {{ __('notifications.new') }}</span>
                                                @endif
                                            </div>

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
                                        <!-- Actions Column Removed -->
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

    <!-- Bottom Action Bar -->
    <div id="bottom-action-bar" class="bottom-action-bar shadow-lg">
        <div class="bottom-action-bar-content container">
            <div class="d-flex align-items-center justify-content-between w-100 flex-column flex-md-row">
                <div class="bottom-action-info d-flex align-items-center mb-1 mb-md-0 flex-grow-1">
                    <div class="avatar-icon mr-2 bg-light-danger text-danger rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-bell font-18"></i>
                    </div>
                    <div class="d-flex flex-column ml-2">
                        <span id="action-bar-title" class="font-15 font-weight-bold text-dark mb-25">{!! __('general.select_row') !!}</span>
                        <div id="action-bar-subtitle" class="font-12 text-muted d-flex align-items-center flex-wrap" style="gap: 8px;">
                            <!-- Subtitle badges injected here -->
                        </div>
                    </div>
                </div>
                <div class="bottom-action-buttons d-flex align-items-center justify-content-center flex-wrap" id="action-bar-buttons">
                    <!-- Buttons injected here via JS -->
                </div>
                <div class="bottom-action-close ml-md-3 mt-1 mt-md-0 position-absolute position-md-relative" style="top: -10px; right: 10px;">
                    <button type="button" class="btn btn-sm btn-danger radius-10 shadow-sm" id="close-action-bar" title="{!! __('general.close') !!}">
                        <i class="fas fa-times"></i>
                    </button>
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

    $(document).ready(function() {
        // --- Bottom Action Bar Logic ---
        const $actionBar = $('#bottom-action-bar');
        const $actionTitle = $('#action-bar-title');
        const $actionButtons = $('#action-bar-buttons');

        // Handle Row Click
        $(document).on('click', '.premium-table-row', function(e) {
            // Ignore clicks on existing links, buttons, or the details control icon
            if ($(e.target).closest('a, button, .details-control, .select2, input, label').length) {
                return;
            }

            // Manage row highlight
            $('.premium-table-row').removeClass('selected-row-premium');
            $(this).addClass('selected-row-premium');

            // Get row data
            let title = $(this).attr('data-row-title');
            let actionsHtml = $(this).find('.row-actions-html').html();
            let subtitleHtml = $(this).find('.row-subtitle-html').html();

            if(actionsHtml && actionsHtml.trim() !== '') {
                // Populate and Show
                $actionTitle.text(title);
                $actionButtons.html(actionsHtml);
                
                if(subtitleHtml && subtitleHtml.trim() !== '') {
                    $('#action-bar-subtitle').html(subtitleHtml).show();
                } else {
                    $('#action-bar-subtitle').hide();
                }
                
                $actionBar.addClass('show');
            }
        });

        // Handle Close Bar Button
        $('#close-action-bar').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $actionBar.removeClass('show');
            $('.premium-table-row').removeClass('selected-row-premium');
        });

        // Hide when clicking completely outside the table and the bar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.premium-table-row, #bottom-action-bar').length) {
                $actionBar.removeClass('show');
                $('.premium-table-row').removeClass('selected-row-premium');
            }
        });
        
        // Hide bar after Livewire update
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('request', ({ component, options, payload, respond, fail }) => {
                respond(({ status, response }) => {
                    $actionBar.removeClass('show');
                    $('.premium-table-row').removeClass('selected-row-premium');
                })
            })
        });
    });
</script>
@endpush
