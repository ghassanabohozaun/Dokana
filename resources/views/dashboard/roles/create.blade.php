@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
    
@endpush

@section('content')
    <div class="app-content content">
        <form class="form ajax-form" id='myForm' action="{!! route('dashboard.roles.store') !!}" method="post" enctype="multipart/form-data" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="redirect"
            data-redirect-url="{!! route('dashboard.roles.index') !!}">
            @csrf
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
                                    <li class="breadcrumb-item">
                                        <a href="{!! route('dashboard.roles.index') !!}">
                                            {!! __('roles.roles') !!}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active font-weight-bold">
                                        {!! __('roles.create_new_role') !!}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="content-header-right col-md-6 col-12 text-md-right mb-2">
                        <div class="d-flex align-items-center justify-content-end mb-1 gap-15px">
                            <a href="{!! route('dashboard.roles.index') !!}" class="btn-premium-back">
                                <i class="fas fa-arrow-right"></i> {!! __('general.back') !!}
                            </a>
                            <button class="btn btn-premium-save" type="submit" id="saveBtn">
                                <i class="fas fa-save mr-2 save-icon"></i>
                                <i class="fas fa-spinner fa-spin d-none spinner_loading mr-2"></i>
                                {!! __('general.save') !!}
                            </button>
                        </div>
                    </div>
                </div>
                <!-- end :content header -->

                <!-- begin: content body -->
                <div class="content-body">

                    <section id="basic-form-layouts">
                        <div class="row match-height">
                            <div class="col-md-12">
                                <div class="card premium-card shadow-lg border-0">
                                    <!-- begin: card header -->
                                    <div class="premium-mandatory-header py-2">
                                        <div class="title-wrapper">
                                            <i class="fas fa-users-cog"></i>
                                            <span class="font-weight-bold">{!! __('roles.create_new_role') !!}</span>
                                        </div>
                                    </div>
                                    <!-- end: card header -->

                                    <!-- begin: card content -->
                                    <div class="card-content collapse show">
                                        <div class="card-body">

                                            <div class="form-body">
                                                @if(isset($stores))
                                                <!-- begin: Global Role Note -->
                                                <div class="alert alert-icon-left alert-arrow-left alert-info mb-3 shadow-sm border-0" role="alert" style="border-radius: 12px;">
                                                    <span class="alert-icon"><i class="fas fa-info-circle"></i></span>
                                                    <h6 class="alert-heading font-weight-bold mb-1">{!! __('general.pro_tip') !!}</h6>
                                                    <p class="mb-0" style="font-size: 1.1rem;">
                                                        {!! __('roles.global_role_note') !!}
                                                    </p>
                                                </div>
                                                <!-- end: Global Role Note -->

                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        <div class="premium-form-group">
                                                            <label for="store_id" class="premium-label">{!! __('stores.store') !!}</label>
                                                            <select id="store_id" name="store_id" class="form-control premium-input shadow-none select2">
                                                                <option value="">{!! __('roles.global_role') !!}</option>
                                                                @foreach ($stores as $store)
                                                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="text-danger error-text store_id_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="row mb-4px">
                                                    <div class="col-md-6 mb-2">
                                                        <div class="premium-form-group">
                                                            <label for="name_ar"
                                                                class="premium-label">{!! __('roles.role_ar') !!} <span class="text-danger">*</span></label>
                                                            <input type="text" id="name_ar" name="name[ar]"
                                                                value="{!! old('name.ar') !!}"
                                                                class="form-control premium-input shadow-none"
                                                                autocomplete="off"
                                                                placeholder="{!! __('roles.enter_role_ar') !!}">
                                                            <span class="text-danger error-text name_ar_error"></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 mb-2">
                                                        <div class="premium-form-group">
                                                            <label for="name_en"
                                                                class="premium-label">{!! __('roles.role_en') !!} <span class="text-danger">*</span></label>
                                                            <input type="text" id="name_en" name="name[en]"
                                                                value="{!! old('name.en') !!}"
                                                                class="form-control premium-input shadow-none"
                                                                autocomplete="off"
                                                                placeholder="{!! __('roles.enter_role_en') !!}">
                                                            <span class="text-danger error-text name_en_error"></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- begin: row -->
                                                <div class="row mb-4px">
                                                    <div class="col-md-12 mb-2">
                                                        <div class="premium-form-group">
                                                            <label for="description"
                                                                class="premium-label">{!! __('roles.description') !!}</label>
                                                            <input type="text" id="description" name="description"
                                                                value="{!! old('description') !!}"
                                                                class="form-control premium-input shadow-none"
                                                                autocomplete="off"
                                                                placeholder="{!! __('roles.enter_description') ?? 'ادخل وصفاً لهذا الدور...' !!}">
                                                            <span class="text-danger error-text description_error"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end: row -->

                                                <!-- begin: Premium Permissions Grid -->
                                                <div class="row mt-4px">
                                                    <div class="col-md-12">
                                                        <h5 class="premium-section-title d-flex align-items-center justify-content-between">
                                                            <span>
                                                                <i class="fas fa-key"></i> {!! __('roles.permissions') !!} <span class="text-danger">*</span>
                                                            </span>
                                                            <span class="permissions_error premium-error-alert-chip"></span>
                                                        </h5>

                                                        <div class="permissions-grid">
                                                            @foreach (config('global.modules') as $moduleKey => $moduleLangKey)
                                                                {{-- Check if user has ANY permission in this module or is Super Admin --}}
                                                                @if(auth()->user()->id === 1 || auth()->user()->role_id === 1 || Gate::allows($moduleKey))
                                                                    <div class="permission-card {{ $moduleKey === 'stores' ? 'disabled-card' : '' }}">
                                                                        <div class="permission-card-header">
                                                                            <div class="permission-card-title">
                                                                                <i class="{{ config('global.module_icons.' . $moduleKey, 'la la-dot-circle') }}"></i>
                                                                                {!! __($moduleLangKey) !!}
                                                                            </div>
                                                                            <label class="modern-switch">
                                                                                <input type="checkbox" class="select-all-module" data-module="module-{{ $moduleKey }}" @disabled($moduleKey === 'stores')>
                                                                                <span class="modern-slider"></span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="permission-card-body">
                                                                            @php 
                                                                                $operations = config("global.custom_operations.{$moduleKey}") ?? config('global.crud_operations');
                                                                            @endphp
                                                                            @foreach ($operations as $opKey => $opLangKey)
                                                                                @php $permName = $moduleKey . '_' . $opKey; @endphp
                                                                                {{-- Only show permission if the current user HAS it --}}
                                                                                @if(auth()->user()->id === 1 || auth()->user()->role_id === 1 || auth()->user()->hasAbility($permName))
                                                                                    <div class="permission-item">
                                                                                        <div class="permission-info">
                                                                                            <label class="permission-label">{!! __($opLangKey) !!}</label>
                                                                                            <p class="permission-desc">{!! __($opLangKey . '_desc') !!}</p>
                                                                                        </div>
                                                                                        <label class="modern-switch">
                                                                                            <input type="checkbox" class="permission-checkbox module-{{ $moduleKey }}" name="permissions[]" value="{{ $permName }}" @disabled($moduleKey === 'stores')>
                                                                                            <span class="modern-slider"></span>
                                                                                        </label>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <div class="text-center mt-3">
                                                            <span class="permissions_error premium-error-alert-chip"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- end: Premium Permissions Grid -->

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Permission UI Logic
            $('.select-all-module').on('change', function() {
                let moduleClass = $(this).data('module');
                let isChecked = $(this).is(':checked');
                $('.' + moduleClass).prop('checked', isChecked).trigger('change');
            });

            $('.permission-checkbox').on('change', function() {
                let classes = $(this).attr('class').split(' ');
                let moduleClass = classes.find(c => c.startsWith('module-'));
                let allChecked = $('.' + moduleClass).length === $('.' + moduleClass + ':checked').length;
                $('.select-all-module[data-module="' + moduleClass + '"]').prop('checked', allChecked);
            });

            // Initialize Store Select2 Standard
            if ($('#store_id').length) {
                $('#store_id').select2({
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
        });
    </script>
@endpush


