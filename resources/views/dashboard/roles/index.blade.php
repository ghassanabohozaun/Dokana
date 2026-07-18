@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/css/permissions.css') !!}">
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
                                    {!! __('roles.roles') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- end: content header left-->

                <!-- begin: content header right-->
                <div class="content-header-right col-md-6 col-12">
                    <div class="float-md-right mb-1">
                        @can('roles_create')
                        <a href="{!! route('dashboard.roles.create') !!}" class="btn btn-premium-add shadow-pulse">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('roles.create_new_role') !!}
                        </a>
                        @endcan
                    </div>
                </div>
                <!-- end: content header right-->

            </div> <!-- end :content header -->

            <!-- begin: content body -->
            <div class="content-body">
                @include('dashboard.roles.partials._search')

                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-user-tag"></i> 
                                        <span class="font-weight-bold">{!! __('roles.roles') !!}</span>
                                        <span id="rolesCountBadge" class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $roles->total() !!}</span>
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
                                        <div class="table-loader-container">
                                            <div class="table-loader-overlay" id="tableLoader">
                                                <span class="premium-loader"></span>
                                            </div>
                                            <div id="table_data">
                                                @include('dashboard.roles.partials._table')
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
    @include('dashboard.roles.modals.details')

    <!-- Bottom Action Bar -->
    <div id="bottom-action-bar" class="bottom-action-bar shadow-lg">
        <div class="bottom-action-bar-content container">
            <div class="d-flex align-items-center justify-content-between w-100 flex-column flex-md-row">
                <div class="bottom-action-info d-flex align-items-center mb-1 mb-md-0 flex-grow-1">
                    <div class="avatar-icon mr-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-user-tag font-18"></i>
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
    <script src="{{ asset('assets/dashbaord/js/generic-select2.js') }}"></script>
    <script>
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable();
            }
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
        });
    </script>
@endpush


