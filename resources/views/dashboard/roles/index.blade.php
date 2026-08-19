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
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                            {!! __('roles.roles') !!}
                        </h1>
                        <span id="total-count-badge" class="badge-pill badge-pill-info text-[11px]">
                            {!! $roles->total() !!} {!! __('general.records') !!}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <div class="flex items-center gap-2.5 self-start sm:self-center">
            @can('roles_create')
                <a href="{!! route('dashboard.roles.create') !!}" class="btn-primary-gradient">
                    <i class="fas fa-plus text-xs"></i>
                    <span>{!! __('roles.create_new_role') !!}</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- 2. Search & Filter Bar -->
    @include('dashboard.roles.partials._search')

    <!-- 3. Main Roles Data Table Card -->
    <div class="dash-card overflow-hidden relative">
        <div class="table-loader-overlay hidden">
            <span class="premium-loader"></span>
        </div>
        <div id="table_data">
            @include('dashboard.roles.partials._table')
        </div>
    </div>

</div>
@endsection
