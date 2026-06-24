@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

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
                                    {!! $title !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('store_withdrawals_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createStoreWithdrawalModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('store_withdrawals.create_new_store_withdrawal') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters -->
            @include('dashboard.store_withdrawals.partials._search')

            <!-- begin: content body -->
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-hand-holding-usd"></i>
                                        <span class="font-weight-bold">{!! __('store_withdrawals.store_withdrawals_list') !!}</span>
                                        <span id="store_withdrawals-total-count"
                                            class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $withdrawals->total() !!}</span>
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
                                                @include('dashboard.store_withdrawals.partials._table')
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

    @can('store_withdrawals_create')
        @include('dashboard.store_withdrawals.modals.create')
    @endcan

    @can('store_withdrawals_update')
        @include('dashboard.store_withdrawals.modals.edit')
    @endcan

    @include('dashboard.store_withdrawals.modals.details')

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if ($('#store_id_dept_filter').length) {
            $('#store_id_dept_filter').select2({
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'ltr'
            });
        }

        // Live Search & Filter Validation
        $('#keyword').on('keyup', function() {
            fetch_data(1);
        });

        $('#store_id_dept_filter').on('change', function() {
            fetch_data(1);
        });

        $('#reset_filter').on('click', function() {
            $('#keyword').val('');
            if ($('#store_id_dept_filter').length) {
                $('#store_id_dept_filter').val('').trigger('change.select2');
            }
            fetch_data(1);
        });
    });

    function fetch_data(page) {
        let keyword = $('#keyword').val();
        let store_id = $('#store_id_dept_filter').val() || '';

        $.ajax({
            url: "{!! route('dashboard.store-withdrawals.index') !!}?page=" + page,
            data: {
                keyword: keyword,
                store_id: store_id,
            },
            success: function(data) {
                $('#table_data').html(data);
                $('#total-count-badge').text($('#store_withdrawals-total-count').val() + ' {!! __('general.records') !!}');
                
                // Re-init popovers/tooltips if any
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        fetch_data(page);
    });

    // Handle AJAX Success Custom Event (reload-table)
    $(document).on('ajax-form-success', function(e, data) {
        if(data.action === 'reload-table') {
            fetch_data(1);
        }
    });

    $(document).ready(function() {
        if (typeof initIndexTable === "function") {
            initIndexTable({
                detailsModal: "#detailsStoreWithdrawalModal",
                detailsModalBody: "#detailsStoreWithdrawalModalBody"
            });
        }
    });
</script>
<script src="{{ asset('assets/dashboard/js/ajax-table.js') }}"></script>
@endpush
