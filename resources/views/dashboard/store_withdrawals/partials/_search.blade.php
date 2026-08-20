<div class="dash-card p-4 space-y-3">
    <form class="js-filter-form space-y-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Top Row: Search Input & Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" name="keyword" class="form-input-modern ps-9 text-xs w-full"
                    placeholder="{!! __('store_withdrawals.search_by_reason') !!}..." autocomplete="off">
            </div>

            <!-- Action Buttons -->
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

        <!-- Bottom Row: Dropdown Filters Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 pt-3 border-t border-slate-100 dark:border-slate-800/80">
            @if (isset($stores) && $stores->count() > 0)
            <!-- Store Filter -->
            <div class="w-full">
                <select name="store_id" id="filter_store_id" class="form-input-modern select2 w-full">
                    <option value="">{!! __('general.all_stores') !!}</option>
                    @foreach ($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Bank Account Filter -->
            @if (isset($bankAccounts) && count($bankAccounts) > 0)
            <div class="w-full">
                <select name="store_bank_account_id" id="filter_store_bank_account_id" class="form-input-modern select2 w-full">
                    <option value="">{!! __('notebook.bank_account') ?? 'كل الحسابات' !!}</option>
                    @foreach ($bankAccounts as $account)
                        @php
                            $storeName = (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) && $account->store ? $account->store->name . ' - ' : '';
                            $entityName = optional($account->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($account->paymentEntity)->getTranslation('name', 'ar');
                        @endphp
                        <option value="{{ $account->id }}">{{ $storeName }}{{ $entityName }} ({{ $account->account_number }})</option>
                    @endforeach
                </select>
            </div>
            @endif

            <!-- Date Filter (Flatpickr) -->
            <div class="w-full">
                <input type="text" name="specific_date" class="form-input-modern text-xs flatpickr-date w-full" placeholder="{!! __('general.date') !!}" autocomplete="off">
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
