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
                                    {!! __('bank_accounts.bank_accounts') !!}
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="content-header-right col-md-6 col-12 text-md-right">
                    <div class="mb-1">
                        @can('bank_accounts_create')
                        <button type="button" class="btn btn-premium-add shadow-pulse" data-toggle="modal"
                            data-target="#createBankAccountModal">
                            <i class="fas fa-plus-circle"></i>
                            {!! __('bank_accounts.create_new_bank_account') !!}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Search Filters (Moved standalone out) -->
            @include('dashboard.bank_accounts.partials._search')

            <!-- begin: content body -->
            <div class="content-body">
                <section id="basic-form-layouts">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card premium-card">
                                <!-- begin: card header -->
                                <div class="premium-mandatory-header py-2">
                                    <div class="title-wrapper">
                                        <i class="fas fa-university"></i>
                                        <span class="font-weight-bold">{!! __('bank_accounts.bank_accounts') !!}</span>
                                        <span id="bank_accountsCountBadge" class="badge badge-primary badge-pill badge-glow ml-2 font-11">{!! $bankAccounts->total() !!}</span>
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
                                                @include('dashboard.bank_accounts.partials._table')
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

    @can('bank_accounts_create')
    @include('dashboard.bank_accounts.modals.create')
    @endcan

    @can('bank_accounts_update')
    @include('dashboard.bank_accounts.modals.edit')
    @endcan

    @include('dashboard.bank_accounts.modals.details')
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/js/ajax-table.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            if (typeof initIndexTable === "function") {
                initIndexTable({
                    detailsModal: "#detailsBankAccountModal",
                    detailsModalBody: "#detailsBankAccountModalBody"
                });
            }
        });
    </script>
@endpush


