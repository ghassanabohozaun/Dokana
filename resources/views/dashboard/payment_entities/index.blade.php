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
                                    {!! __('payment_entities.payment_entities') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('payment_entities_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createPaymentEntityModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('payment_entities.create_new_payment_entity') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters -->
            @include('dashboard.payment_entities.partials._search')

            <!-- begin: content body -->
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-landmark"></i>
                                        <span class="font-weight-bold">{!! __('payment_entities.payment_entities') !!}</span>
                                        <span id="payment_entitiesCountBadge" class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $entities->total() !!}</span>
                                    </div>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="fas fa-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="fas fa-sync"></i></a></li>
                                            <li><a data-action="expand"><i class="fas fa-expand"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body pt-0">
                                        <div class="table-loader-container">
                                            <div class="table-loader-overlay" id="tableLoader">
                                                <span class="premium-loader"></span>
                                            </div>
                                            <div id="table_data">
                                                @include('dashboard.payment_entities.partials._table')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </section>
            </div>
        </div> 
    </div>

    @can('payment_entities_create')
    @include('dashboard.payment_entities.modals.create')
    @endcan

    @can('payment_entities_update')
    @include('dashboard.payment_entities.modals.edit')
    @endcan
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable();
            }
        });

        // change status
        $(document).on('change', '.change-status', function(e) {
            var id = $(this).data('id');
            var url = $(this).data('url');
            var statusSwitch = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    if (data.status === true) {
                        flasher.success("{!! __('general.change_status_success_message') !!}");
                        // Reload the table data to reflect the new status badge
                        if (typeof filterTable === 'function') {
                            filterTable();
                        } else {
                            location.reload();
                        }
                    } else {
                        flasher.error("{!! __('general.change_status_error_message') !!}");
                        // Revert switch state
                        var checkbox = $('#statusSwitch' + id);
                        checkbox.prop('checked', !checkbox.prop('checked'));
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 403) {
                        flasher.error("{!! __('dashboard.access_denied') !!}");
                    } else {
                        flasher.error("{!! __('general.try_catch_error_message') !!}");
                    }
                    // Revert switch state
                    var checkbox = $('#statusSwitch' + id);
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            });
        });
    </script>
@endpush
