<div class="dash-card p-4 space-y-3">
    <form class="js-filter-form space-y-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Top Row: Search Input & Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Keyword Search Input -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" name="keyword" class="form-input-modern ps-9 text-xs w-full"
                    placeholder="{!! __('store_customers.enter_name') ?? 'ابحث باسم العميل أو رقم الجوال...' !!}" autocomplete="off">
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                <button type="submit" class="btn-primary-gradient text-xs py-2.5 px-4 flex-1 sm:flex-initial justify-center shadow-sm">
                    <i class="fas fa-filter text-xs"></i>
                    <span>{!! __('general.apply') !!}</span>
                </button>

                <button type="button" class="btn-secondary-modern text-xs py-2.5 px-3.5 js-reset-btn justify-center" title="{!! __('general.reset') !!}">
                    <i class="fas fa-sync text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Bottom Row: Filter Dropdowns Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-800/80">
            
            @if (isset($stores) && $stores->count() > 0)
            <!-- Store Filter -->
            <div>
                <select name="store_id" id="filter_store_id" class="form-input-modern select2 w-full">
                    <option value="">{!! __('general.all_stores') !!}</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Balance Status Filter -->
            <div>
                <select name="balance_status" class="form-input-modern select2 w-full">
                    <option value="">{!! __('store_customers.all_balances') ?? 'كل الأرصدة' !!}</option>
                    <option value="has_debt">{!! __('store_customers.has_debts') ?? 'عليهم ديون (مدين)' !!}</option>
                    <option value="has_credit">{!! __('store_customers.has_credit') ?? 'لهم رصيد (دائن)' !!}</option>
                    <option value="cleared">{!! __('store_customers.cleared_balance') ?? 'رصيد خالص (0)' !!}</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select name="status" class="form-input-modern select2 w-full">
                    <option value="">{!! __('general.status') ?? 'كل الحالات' !!}</option>
                    <option value="1">{!! __('general.enable') ?? 'مفعل' !!}</option>
                    <option value="0">{!! __('general.disabled') ?? 'معطل' !!}</option>
                </select>
            </div>

            <!-- Sort By Filter -->
            <div>
                <select name="sort_by" class="form-input-modern select2 w-full">
                    <option value="">{!! __('store_customers.sort_by_default') ?? 'الترتيب: الافتراضي' !!}</option>
                    <option value="highest_debts">{!! __('store_customers.highest_debts') ?? 'الأعلى ديوناً' !!}</option>
                    <option value="lowest_debts">{!! __('store_customers.lowest_debts') ?? 'الأقل ديوناً' !!}</option>
                    <option value="highest_payments">{!! __('store_customers.highest_payments') ?? 'الأكثر سداداً' !!}</option>
                    <option value="oldest_debts">{!! __('store_customers.oldest_debts') ?? 'أقدم الديون' !!}</option>
                    <option value="name_asc">{!! __('store_customers.sort_name_asc') ?? 'الاسم: أ - ي' !!}</option>
                    <option value="name_desc">{!! __('store_customers.sort_name_desc') ?? 'الاسم: ي - أ' !!}</option>
                </select>
            </div>

        </div>
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
