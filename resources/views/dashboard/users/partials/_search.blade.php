<div class="dash-card p-4 md:p-5">
    <form class="js-filter-form flex flex-wrap items-center gap-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Keyword Search Input -->
        <div class="relative flex-1 min-w-[200px] max-w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="keyword" class="form-input-modern ps-9 text-xs"
                placeholder="{!! __('general.search') ?? 'ابحث بالاسم، البريد، أو رقم الجوال...' !!}" autocomplete="off">
        </div>

        @if (isset($stores) && $stores->count() > 0)
        <!-- Store Filter -->
        <div class="w-full sm:w-44 flex-1 sm:flex-initial min-w-[140px]">
            <select name="store_id" id="filter_store_id" class="form-input-modern select2">
                <option value="">{!! __('general.all_stores') !!}</option>
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Role Filter -->
        @if (isset($roles) && $roles->count() > 0)
        <div class="w-full sm:w-44 flex-1 sm:flex-initial min-w-[140px]">
            <select name="role_id" id="filter_role_id" class="form-input-modern select2">
                <option value="">{!! __('roles.role') ?? 'كل الصلاحيات' !!}</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Status Filter -->
        <div class="w-full sm:w-36 flex-1 sm:flex-initial min-w-[130px]">
            <select name="status" class="form-input-modern select2">
                <option value="">{!! __('general.status') ?? 'كل الحالات' !!}</option>
                <option value="1">{!! __('general.enable') ?? 'مفعل' !!}</option>
                <option value="0">{!! __('general.disabled') ?? 'معطل' !!}</option>
            </select>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center gap-2 ms-auto shrink-0">
            <button type="submit" class="btn-primary-gradient text-xs py-2.5 px-4">
                <i class="fas fa-filter text-xs"></i>
                <span>{!! __('general.apply') !!}</span>
            </button>

            <button type="button" class="btn-secondary-modern text-xs py-2.5 px-3.5 js-reset-btn" title="{!! __('general.reset') !!}">
                <i class="fas fa-sync text-xs"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
