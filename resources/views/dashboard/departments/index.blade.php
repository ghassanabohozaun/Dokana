@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
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
                                    {!! __('departments.departments') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('departments_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createDepartmentModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('departments.create_new_department') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters (Moved standalone out) -->
            @include('dashboard.departments.partials._search')

            <!-- begin: content body -->
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-sitemap"></i>
                                        <span class="font-weight-bold">{!! __('departments.departments') !!}</span>
                                        <span id="departmentsCountBadge"
                                            class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $departments->total() !!}</span>
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
                                <div class="card-content collapse show">
                                    <div class="card-body pt-0">
                                        <div class="table-loader-container">
                                            <div class="table-loader-overlay" id="tableLoader">
                                                <span class="premium-loader"></span>
                                            </div>
                                            <div id="table_data">
                                                @include('dashboard.departments.partials._table')
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

    @can('departments_create')
    @include('dashboard.departments.modals.create')
    @endcan

    @can('departments_update')
    @include('dashboard.departments.modals.edit')
    @endcan

    @include('dashboard.departments.modals.details')

    <!-- Bottom Action Bar -->
    <div id="bottom-action-bar" class="bottom-action-bar shadow-lg">
        <div class="bottom-action-bar-content container">
            <div class="d-flex align-items-center justify-content-between w-100 flex-column flex-md-row">
                <div class="bottom-action-info d-flex align-items-center mb-1 mb-md-0 flex-grow-1">
                    <div class="avatar-icon mr-2 bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-sitemap font-18"></i>
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
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    detailsModal: "#detailsDepartmentModal",
                    detailsModalBody: "#detailsDepartmentModalBody"
                });
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

        // change status
        $(document).on('change', '.change_status', function(e) {
            // e.preventDefault();
            var id = $(this).data('id');

            if ($(this).is(':checked')) {
                statusSwitch = 1;
            } else {
                statusSwitch = 0;
            }


            $.ajax({
                url: "{{ route('dashboard.departments.change.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'post',
                dataType: 'JSON',
                success: function(data) {

                    $('.department_status_' + data.data.id).empty();
                    $('.department_status_' + data.data.id).removeClass('badge-danger');
                    $('.department_status_' + data.data.id).removeClass('badge-success');
                    if (data.data.status == 1) {
                        $('.department_status_' + data.data.id).addClass('badge-success');
                        $('.department_status_' + data.data.id).text("{!! __('general.enable') !!}");
                    } else if (data.data.status == 0) {
                        $('.department_status_' + data.data.id).addClass('badge-danger');
                        $('.department_status_' + data.data.id).text("{!! __('general.disabled') !!}");
                    }

                    if (data.status === true) {
                        flasher.success("{!! __('general.change_status_success_message') !!}");
                    } else {
                        flasher.error("{!! __('general.change_status_error_message') !!}");
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        flasher.error("{!! __('dashboard.access_denied') !!}");
                        // Revert switch state
                        var checkbox = $('#status_' + id);
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    } else {
                        flasher.error("{!! __('general.try_catch_error_message') !!}");
                    }
                }
            });

        });
    </script>
@endpush


