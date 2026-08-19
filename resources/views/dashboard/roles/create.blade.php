@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    <form class="ajax-form w-full space-y-6" id="myForm" action="{!! route('dashboard.roles.store') !!}" method="POST" enctype="multipart/form-data" novalidate
        data-success-msg="{!! __('general.add_success_message') !!}"
        data-success-action="redirect"
        data-redirect-url="{!! route('dashboard.roles.index') !!}">
        @csrf

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
                    <a href="{!! route('dashboard.roles.index') !!}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        {!! __('roles.roles') !!}
                    </a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-200 font-bold">{!! $title !!}</span>
                </nav>

                <!-- Page Title -->
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h1 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white">
                        {!! __('roles.create_new_role') !!}
                    </h1>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2.5 self-start sm:self-center">
                <a href="{!! route('dashboard.roles.index') !!}" class="btn-secondary-modern text-xs">
                    <i class="fas fa-arrow-right text-xs"></i>
                    <span>{!! __('general.back') !!}</span>
                </a>
                <button type="submit" class="btn-primary-gradient text-xs" id="saveBtn">
                    <i class="fas fa-save text-xs"></i>
                    <i class="fas fa-spinner fa-spin spinner_loading text-xs hidden d-none"></i>
                    <span>{!! __('general.save') !!}</span>
                </button>
            </div>
        </div>

        <!-- 2. Role Info Card -->
        <div class="dash-card p-6 space-y-4">
            
            @if(isset($stores))
            <!-- Global Role Note Banner -->
            <div class="p-4 rounded-2xl bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/50 flex items-start gap-3">
                <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400 mt-0.5 text-sm"></i>
                <div class="text-xs">
                    <span class="font-bold text-indigo-900 dark:text-indigo-200 block mb-0.5">{!! __('general.pro_tip') !!}</span>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{!! __('roles.global_role_note') !!}</p>
                </div>
            </div>

            <!-- Store Select -->
            <div>
                <label class="form-label-modern" for="store_id">
                    {!! __('stores.store') !!}
                </label>
                <select name="store_id" id="store_id" class="form-input-modern select2">
                    <option value="">{!! __('roles.global_role') !!}</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
                <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
            </div>
            @endif

            <!-- Names Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label-modern" for="name_ar">
                        {!! __('roles.role_ar') !!} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name_ar" name="name[ar]" value="{!! old('name.ar') !!}"
                        class="form-input-modern" placeholder="{!! __('roles.enter_role_ar') !!}" autocomplete="off">
                    <span class="text-xs text-rose-500 error-text name_ar_error block mt-1"></span>
                </div>

                <div>
                    <label class="form-label-modern" for="name_en">
                        {!! __('roles.role_en') !!} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="name_en" name="name[en]" value="{!! old('name.en') !!}"
                        class="form-input-modern" placeholder="{!! __('roles.enter_role_en') !!}" autocomplete="off">
                    <span class="text-xs text-rose-500 error-text name_en_error block mt-1"></span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="form-label-modern" for="description">
                    {!! __('roles.description') !!}
                </label>
                <input type="text" id="description" name="description" value="{!! old('description') !!}"
                    class="form-input-modern" placeholder="{!! __('roles.enter_description') ?? 'ادخل وصفاً لهذا الدور...' !!}" autocomplete="off">
                <span class="text-xs text-rose-500 error-text description_error block mt-1"></span>
            </div>

        </div>

        <!-- 3. Permissions Matrix Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200 dark:border-slate-800">
                <div class="flex items-center gap-2.5">
                    <i class="fas fa-key text-indigo-500 text-sm"></i>
                    <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">
                        {!! __('roles.permissions') !!} <span class="text-rose-500">*</span>
                    </h2>
                </div>
                <span class="text-xs text-rose-500 error-text permissions_error"></span>
            </div>

            <!-- Permissions Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach (config('global.modules') as $moduleKey => $moduleLangKey)
                    @if(auth()->user()->id === 1 || auth()->user()->role_id === 1 || Gate::allows($moduleKey))
                        <div class="dash-card overflow-hidden flex flex-col justify-between group hover:border-indigo-200 dark:hover:border-indigo-800 transition-all">
                            
                            <!-- Module Card Header -->
                            <div class="p-4 bg-slate-50/80 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-white dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 text-xs shadow-sm">
                                        <i class="{{ config('global.module_icons.' . $moduleKey, 'fas fa-shield-alt') }}"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white">
                                        {!! __($moduleLangKey) !!}
                                    </span>
                                </div>

                                <!-- Select All Switch -->
                                <label class="relative inline-flex items-center cursor-pointer select-none" title="تحديد الكل">
                                    <input type="checkbox" class="sr-only peer select-all-module" data-module="module-{{ $moduleKey }}" @disabled($moduleKey === 'stores')>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-indigo-600 shadow-sm"></div>
                                </label>
                            </div>

                            <!-- Module Card Body: Operations -->
                            <div class="p-4 space-y-3 flex-1 divide-y divide-slate-100 dark:divide-slate-800/60">
                                @php 
                                    $operations = config("global.custom_operations.{$moduleKey}") ?? config('global.crud_operations');
                                @endphp
                                @foreach ($operations as $opKey => $opLangKey)
                                    @php $permName = $moduleKey . '_' . $opKey; @endphp
                                    @if(auth()->user()->id === 1 || auth()->user()->role_id === 1 || auth()->user()->hasAbility($permName))
                                        <div class="flex items-center justify-between pt-3 first:pt-0">
                                            <div class="min-w-0 pe-3">
                                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 block">
                                                    {!! __($opLangKey) !!}
                                                </span>
                                                <span class="text-[11px] text-slate-400 dark:text-slate-500 block leading-tight">
                                                    {!! __($opLangKey . '_desc') !!}
                                                </span>
                                            </div>
                                            <label class="relative inline-flex items-center cursor-pointer select-none shrink-0">
                                                <input type="checkbox" class="sr-only peer permission-checkbox module-{{ $moduleKey }}" name="permissions[]" value="{{ $permName }}" @disabled($moduleKey === 'stores')>
                                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500 shadow-sm"></div>
                                            </label>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

            <div class="text-center pt-2">
                <span class="text-xs text-rose-500 error-text permissions_error"></span>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Permission Select All Logic
        $(document).on('change', '.select-all-module', function() {
            let moduleClass = $(this).data('module');
            let isChecked = $(this).is(':checked');
            $('.' + moduleClass).not(':disabled').prop('checked', isChecked);
        });

        $(document).on('change', '.permission-checkbox', function() {
            let classes = $(this).attr('class').split(' ');
            let moduleClass = classes.find(c => c && c.startsWith('module-'));
            if (moduleClass) {
                let total = $('.' + moduleClass).not(':disabled').length;
                let checked = $('.' + moduleClass + ':checked').not(':disabled').length;
                $('.select-all-module[data-module="' + moduleClass + '"]').prop('checked', total > 0 && total === checked);
            }
        });

        // Initialize Store Select2
        if ($('#store_id').length) {
            $('#store_id').select2({
                width: '100%',
                dir: $('html').attr('data-textdirection') || 'rtl'
            });
        }
    });
</script>
@endpush
