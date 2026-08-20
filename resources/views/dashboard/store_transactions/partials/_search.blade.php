<div class="dash-card p-4 md:p-5">
    <form class="js-filter-form flex flex-wrap items-center gap-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Keyword Search Input -->
        <div class="relative flex-1 min-w-[200px] max-w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="keyword" class="form-input-modern ps-9 text-xs"
                placeholder="{!! __('general.search') ?? 'ابحث بالعميل، الهاتف، البيان، المبلغ...' !!}" autocomplete="off">
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

        <!-- Customer Filter -->
        @if (isset($customers) && $customers->count() > 0)
        <div class="w-full sm:w-48 flex-1 sm:flex-initial min-w-[150px]">
            <select name="store_customer_id" id="filter_store_customer_id" class="form-input-modern select2">
                <option value="">{!! __('store_transactions.all_customers') ?? 'كل العملاء' !!}</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Type Filter -->
        <div class="w-full sm:w-36 flex-1 sm:flex-initial min-w-[130px]">
            <select name="type" class="form-input-modern select2">
                <option value="">{!! __('store_transactions.all_types') ?? 'كل أنواع الحركات' !!}</option>
                <option value="debt">{!! __('store_transactions.debt') !!}</option>
                <option value="payment">{!! __('store_transactions.payment') !!}</option>
            </select>
        </div>

        <!-- Bank Account / Safe Filter -->
        @if (isset($bankAccounts) && $bankAccounts->count() > 0)
        <div class="w-full sm:w-48 flex-1 sm:flex-initial min-w-[150px]">
            <select name="store_bank_account_id" id="filter_store_bank_account_id" class="form-input-modern select2">
                <option value="">{!! __('store_transactions.all_bank_accounts') ?? 'كل الصناديق والحسابات' !!}</option>
                @foreach ($bankAccounts as $account)
                    @php
                        $entityName = (optional($account->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                        $accountName = $account->account_type === 'cash' ? $entityName : $entityName . ' - ' . $account->account_number;
                    @endphp
                    <option value="{{ $account->id }}">{{ $accountName }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Date / Range Filter (Flatpickr) -->
        <div class="w-full sm:w-44 flex-1 sm:flex-initial min-w-[140px]">
            <input type="text" name="date" class="form-input-modern text-xs flatpickr-date" placeholder="{!! __('store_transactions.filter_by_date') ?? 'التاريخ أو الفترة...' !!}" autocomplete="off">
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
