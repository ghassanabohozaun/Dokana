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
                                    {!! __('stores.stores') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('stores_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#addStoreModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('stores.create_new_store') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters -->
            @include('dashboard.stores.partials._search')

            <!-- begin: content body -->
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-briefcase"></i>
                                        <span class="font-weight-bold">{!! __('stores.stores') !!}</span>
                                        <span id="storesCountBadge" class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $stores->total() !!}</span>
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
                                                @include('dashboard.stores.partials._table')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: card content -->
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @can('stores_create')
    @include('dashboard.stores.modals.create')
    @endcan
    
    @can('stores_update')
    @include('dashboard.stores.modals.edit')
    @endcan
    
    @include('dashboard.stores.modals.details')
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    detailsModal: "#detailsStoreModal",
                    detailsModalBody: "#detailsStoreModalBody"
                });
            }

            // Global Select2 Initialization for all modals in this page
            $('.select2').each(function() {
                var $el = $(this);
                var parentModal = $el.closest('.modal');
                
                $el.select2({
                    dropdownParent: parentModal.length ? parentModal : $(document.body),
                    width: '100%',
                    placeholder: $el.attr('placeholder') || "{!! __('general.choose') !!}",
                });
            });
        });

        // change status
        $(document).on('change', '.change_status', function(e) {
            var id = $(this).data('id');
            var statusSwitch = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('dashboard.stores.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    let statusBadge = $('.store_status_' + data.data.id);
                    statusBadge.removeClass('badge-danger badge-success');
                    
                    if (data.data.status == 1) {
                        statusBadge.addClass('badge-success').text("{!! __('general.enable') !!}");
                    } else {
                        statusBadge.addClass('badge-danger').text("{!! __('general.disabled') !!}");
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


