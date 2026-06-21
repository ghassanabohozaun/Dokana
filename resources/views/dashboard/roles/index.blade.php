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
        });
    </script>
@endpush


