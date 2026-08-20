<div class="dash-card p-4 md:p-5">
    <form class="js-filter-form flex flex-wrap items-center gap-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Keyword Search Input -->
        <div class="relative flex-1 min-w-[200px] max-w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="keyword" class="form-input-modern ps-9 text-xs"
                placeholder="{!! __('store_customers.enter_name') ?? 'ابحث باسم العميل أو رقم الجوال...' !!}" autocomplete="off">
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

        <!-- Balance Status Filter -->
        <div class="w-full sm:w-44 flex-1 sm:flex-initial min-w-[140px]">
            <select name="balance_status" class="form-input-modern select2">
                <option value="">{!! __('store_customers.all_balances') ?? 'كل الأرصدة' !!}</option>
                <option value="has_debt">{!! __('store_customers.has_debts') ?? 'عليهم ديون (مدين)' !!}</option>
                <option value="has_credit">{!! __('store_customers.has_credit') ?? 'لهم رصيد (دائن)' !!}</option>
                <option value="cleared">{!! __('store_customers.cleared_balance') ?? 'رصيد خالص (0)' !!}</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div class="w-full sm:w-36 flex-1 sm:flex-initial min-w-[130px]">
            <select name="status" class="form-input-modern select2">
                <option value="">{!! __('general.status') ?? 'كل الحالات' !!}</option>
                <option value="1">{!! __('general.enable') ?? 'مفعل' !!}</option>
                <option value="0">{!! __('general.disabled') ?? 'معطل' !!}</option>
            </select>
        </div>

        <!-- Sort By Filter -->
        <div class="w-full sm:w-48 flex-1 sm:flex-initial min-w-[150px]">
            <select name="sort_by" class="form-input-modern select2">
                <option value="">{!! __('store_customers.sort_by_default') ?? 'الترتيب: الافتراضي' !!}</option>
                <option value="highest_debts">{!! __('store_customers.highest_debts') ?? 'الأعلى ديوناً' !!}</option>
                <option value="lowest_debts">{!! __('store_customers.lowest_debts') ?? 'الأقل ديوناً' !!}</option>
                <option value="highest_payments">{!! __('store_customers.highest_payments') ?? 'الأكثر سداداً' !!}</option>
                <option value="oldest_debts">{!! __('store_customers.oldest_debts') ?? 'أقدم الديون' !!}</option>
                <option value="name_asc">{!! __('store_customers.sort_name_asc') ?? 'الاسم: أ - ي' !!}</option>
                <option value="name_desc">{!! __('store_customers.sort_name_desc') ?? 'الاسم: ي - أ' !!}</option>
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
