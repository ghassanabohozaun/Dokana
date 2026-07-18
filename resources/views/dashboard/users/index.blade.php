@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <!-- begin: content header -->
            <div class="content-header row">
                <!-- begin: content header left-->
                <div class="content-header-left col-md-6 col-12 mb-2 mb-md-0">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb premium-breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{!! route('dashboard.index') !!}">
                                        <i class="fas fa-home"></i> {!! __('dashboard.home') !!}
                                    </a>
                                </li>
                                <li class="breadcrumb-item active font-weight-bold">
                                    {!! __('users.users') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- end: content header left-->

                <!-- begin: content header right-->
                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('users_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createUserModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('users.create_new_user') !!}
                        </button>
                        @endcan
                    </div>
                </div>
                <!-- end: content header right-->
            </div> <!-- end :content header -->

            <!-- begin: content body -->
            <div class="content-body">
                <!-- begin: stats cards -->
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-12 mb-2">
                        <div class="premium-stat-card h-100 card-contracts">
                            <div class="stat-content">
                                <h3 class="stat-value">{{ $stats['total_users'] }}</h3>
                                <h6 class="stat-title">{!! __('users.users') !!}</h6>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12 mb-2">
                        <div class="premium-stat-card h-100 card-active">
                            <div class="stat-content">
                                <h3 class="stat-value">{{ $stats['active_users'] }}</h3>
                                <h6 class="stat-title">{!! __('users.active_users') !!}</h6>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12 mb-2">
                        <div class="premium-stat-card h-100 card-revenue">
                            <div class="stat-content">
                                <h3 class="stat-value">{{ $stats['managers'] }}</h3>
                                <h6 class="stat-title">{!! __('users.managers') !!}</h6>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-user-shield"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-12 mb-2">
                        <div class="premium-stat-card h-100 card-expiring">
                            <div class="stat-content">
                                <h3 class="stat-value">{{ $stats['super_users'] }}</h3>
                                <h6 class="stat-title">{!! __('users.super_users') !!}</h6>
                            </div>
                            <div class="stat-icon-wrapper">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: stats cards -->
                @include('dashboard.users.partials._search')

                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-users-cog"></i>
                                        <span class="font-weight-bold">{!! __('users.users') !!}</span>
                                        <span id="usersCountBadge" class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $users->total() !!}</span>
                                    </div>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="fas fa-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="fas fa-sync"></i></a></li>
                                            <li><a data-action="expand"><i class="fas fa-expand"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- end: card header -->

                                <!-- begin: card content -->
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <!-- Container with Loader -->
                                        <div class="table-loader-container">
                                            <div class="table-loader-overlay">
                                                <span class="premium-loader"></span>
                                            </div>
                                            <div id="table_data">
                                                @include('dashboard.users.partials._table')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: card content -->
                            </div>
                        </div> <!-- end: card  -->
                    </div><!-- end: row  -->
                </section><!-- end: sections  -->
            </div><!-- end: content body  -->
        </div> <!-- end: content wrapper  -->
    </div><!-- end: content app  -->
    @can('users_create')
    @include('dashboard.users.modals.create')
    @endcan
    @can('users_update')
    @include('dashboard.users.modals.edit')
    @endcan

    @include('dashboard.users.modals.details')

    <!-- Bottom Action Bar -->
    <div id="bottom-action-bar" class="bottom-action-bar shadow-lg">
        <div class="bottom-action-bar-content container">
            <div class="d-flex align-items-center justify-content-between w-100 flex-column flex-md-row">
                <div class="bottom-action-info d-flex align-items-center mb-1 mb-md-0 flex-grow-1">
                    <div class="avatar-icon mr-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-users-cog font-18"></i>
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
@endsection
@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize AJAX Table
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    container: '#table_data',
                    loader: '.table-loader-overlay',
                    detailsControl: '.details-control'
                });
            }

            // Initialize Modern Filter System
            if (typeof initFilterSystem === "function") {
                initFilterSystem();
            }

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

            // Status Change Handler (preserving existing logic)
            $('body').on('change', '.change_status', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var statusSwitch = $(this).is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ route('dashboard.users.change.status') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        statusSwitch: statusSwitch,
                        id: id
                    },
                    type: 'post',
                    dataType: 'JSON',
                    success: function(data) {
                        $('.user_status_' + data.data.id).empty();
                        $('.user_status_' + data.data.id).removeClass(
                            'badge-danger badge-success');
                        if (data.data.status == 1) {
                            $('.user_status_' + data.data.id)
                                .addClass('badge-pill badge-glow badge-success')
                                .css({
                                    'font-size': '11px',
                                    'padding': '4px 10px'
                                })
                                .text("{!! __('general.enable') !!}");
                        } else {
                            $('.user_status_' + data.data.id)
                                .addClass('badge-pill badge-glow badge-danger')
                                .css({
                                    'font-size': '11px',
                                    'padding': '4px 10px'
                                })
                                .text("{!! __('general.disabled') !!}");
                        }
                        if (data.status === true) {
                            flasher.success("{!! __('general.change_status_success_message') !!}");
                        } else {
                            flasher.error("{!! __('general.change_status_error_message') !!}");
                        }
                    }
                });
            });
        });
    </script>
@endpush


