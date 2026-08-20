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
                    placeholder="{!! __('general.search') ?? 'ابحث بالعميل، الهاتف، البيان، المبلغ...' !!}" autocomplete="off">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-800/80">
            
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

            <!-- Customer Filter -->
            @if (isset($customers) && $customers->count() > 0)
            <div>
                <select name="store_customer_id" id="filter_store_customer_id" class="form-input-modern select2 w-full">
                    <option value="">{!! __('store_transactions.all_customers') ?? 'كل العملاء' !!}</option>
                    @foreach ($customers as $customer)
                        @php
                            $storeName = (isset($stores) && $customer->store) ? ' (' . $customer->store->name . ')' : '';
                        @endphp
                        <option value="{{ $customer->id }}">{{ $customer->name }}{{ $storeName }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Type Filter -->
            <div>
                <select name="type" class="form-input-modern select2 w-full">
                    <option value="">{!! __('store_transactions.all_types') ?? 'كل أنواع الحركات' !!}</option>
                    <option value="debt">{!! __('store_transactions.debt') !!}</option>
                    <option value="payment">{!! __('store_transactions.payment') !!}</option>
                </select>
            </div>

            <!-- Bank Account / Safe Filter -->
            @if (isset($bankAccounts) && $bankAccounts->count() > 0)
            <div>
                <select name="store_bank_account_id" id="filter_store_bank_account_id" class="form-input-modern select2 w-full">
                    <option value="">{!! __('store_transactions.all_bank_accounts') ?? 'كل الصناديق' !!}</option>
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
            <div>
                <input type="text" name="date" class="form-input-modern text-xs flatpickr-date w-full" placeholder="{!! __('store_transactions.filter_by_date') ?? 'التاريخ أو الفترة...' !!}" autocomplete="off">
            </div>

        </div>
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
