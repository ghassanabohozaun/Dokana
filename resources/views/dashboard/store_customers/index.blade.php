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
                                    {!! __('store_customers.store_customers') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('store_customers_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createStoreCustomerModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('store_customers.create_new_store_customer') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters (Moved standalone out) -->
            @include('dashboard.store_customers.partials._search')

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
                                        <span class="font-weight-bold">{!! __('store_customers.store_customers') !!}</span>
                                        <span id="store_customersCountBadge"
                                            class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $store_customers->total() !!}</span>
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
                                                @include('dashboard.store_customers.partials._table')
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

    @can('store_customers_create')
    @include('dashboard.store_customers.modals.create')
    @endcan

    @can('store_customers_update')
    @include('dashboard.store_customers.modals.edit')
    @endcan

    @can('store_transactions_create')
    @include('dashboard.store_customers.modals.add_transaction')
    @endcan

    @include('dashboard.store_customers.modals.details')
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    detailsModal: "#detailsStoreCustomerModal",
                    detailsModalBody: "#detailsStoreCustomerModalBody"
                });
            }
        });

        // change status
        $(document).on('change', '.change_status', function(e) {
            var id = $(this).data('id');
            var statusSwitch = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('dashboard.store-customers.change.status') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    statusSwitch: statusSwitch,
                    id: id
                },
                type: 'post',
                dataType: 'JSON',
                success: function(data) {
                    let statusBadge = $('.store_customer_status_' + data.data.id);
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

        // Add Transaction from Customers
        $(document).on('click', '.add_transaction_button', function() {
            let customerId = $(this).data('customer-id');
            let customerName = $(this).data('customer-name');
            let storeId = $(this).data('store-id');
            let storeName = $(this).data('store-name');

            // Set hidden inputs for submission
            $('#hidden_store_id_create').val(storeId || '');
            $('#hidden_store_customer_id_create').val(customerId);

            // Set visible disabled inputs
            if ($('#visible_store_id_create').length) {
                $('#visible_store_id_create').val(storeName || "{!! __('roles.global_role') !!}");
            }
            $('#visible_store_customer_id_create').val(customerName);

            // Reset form fields
            $('#transaction_amount_create').val('');
            $('#transaction_description_create').val('');
            $('#transaction_type_create').val('');
            
            // Clear any previous errors
            $('.error-text').text('');

            $('#addCustomerTransactionModal').modal('show');
        });

    </script>
@endpush


