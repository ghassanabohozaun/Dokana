@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    
    <!-- 1. Header & Actions Toolbar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <i class="fas fa-home text-xs"></i>
                    <span>{!! __('dashboard.home') !!}</span>
                </a>
                <span>/</span>
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $title !!}</span>
            </nav>

            <!-- Page Title & Counter Badge -->
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                    <i class="fas fa-truck-loading"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('store_suppliers.store_suppliers_list') !!}
                        </h1>
                        <span id="total-count-badge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $suppliers->total() !!} {!! __('general.records') !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('store_suppliers_create')
                <button type="button" class="btn-primary-gradient" data-toggle="modal" data-target="#createStoreSupplierModal">
                    <i class="fas fa-plus text-xs"></i>
                    <span>{!! __('store_suppliers.create_new_store_supplier') !!}</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- 2. Search & Filter Bar -->
    @include('dashboard.store_suppliers.partials._search')

    <!-- 3. Main Suppliers Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.store_suppliers.partials._table')
        </div>
    </div>

</div>

<!-- Modals -->
@can('store_suppliers_create')
    @include('dashboard.store_suppliers.modals.create')
@endcan

@can('store_suppliers_update')
    @include('dashboard.store_suppliers.modals.edit')
@endcan

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Scoped Select2 for Modal dropdowns
        if ($('#store_id_dept_create').length) {
            $('#store_id_dept_create').select2({
                dropdownParent: $('#createStoreSupplierModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }

        if ($('#store_id_dept_edit').length) {
            $('#store_id_dept_edit').select2({
                dropdownParent: $('#updateStoreSupplierModal'),
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }
    });
</script>
@endpush
