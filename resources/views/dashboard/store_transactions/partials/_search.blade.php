<div class="query-bar-container">
    <div class="query-bar js-query-bar">
        <span class="query-bar-label">
            <i class="fas fa-filter"></i> {!! __('general.filters') !!}:
        </span>

        <form class="js-filter-form d-flex align-items-center gap-2" data-container="#table_data"
            data-loader=".table-loader-overlay">
            <!-- Keyword Search -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="keyword_search_popover">
                    <i class="fas fa-search text-primary"></i>
                    <span class="chip-text">{!! __('general.search') !!}</span>
                </div>

                <!-- Keyword Search Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="keyword_search_popover">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('store_transactions.store_transactions') !!}</label>
                        <div class="premium-input-wrapper">
                            <input type="text" class="form-control premium-input shadow-none" name="keyword"
                                placeholder="{!! __('general.search') !!}..." autocomplete="off">
                            <i class="fas fa-search text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="submit" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            @if (isset($stores) && $stores->count() > 0)
                <!-- Store Filter -->
                <div class="filter-item">
                    <div class="filter-chip js-filter-chip" data-filter-target="store_search_popover">
                        <i class="fas fa-briefcase text-primary"></i>
                        <span class="chip-text">{!! __('stores.store') !!}</span>
                    </div>

                    <!-- Store Filter Popover -->
                    <div class="ptc-query-panel shadow-lg border-0 radius-16" id="store_search_popover"
                        style="min-width: 280px;">
                        <div class="mb-3">
                            <label class="premium-label mb-2">{!! __('stores.store') !!}</label>
                            <div class="premium-input-wrapper">
                                <select name="store_id" id="filter_store_id"
                                    class="form-control premium-input shadow-none js-select2"
                                    data-placeholder="{!! __('general.all_stores') !!}" data-parent="#store_search_popover">
                                    <option value="">{!! __('general.all_stores') !!}</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}">{{ $store->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-briefcase text-primary"></i>
                            </div>
                        </div>
                        <div class="popover-actions mt-4 text-right">
                            <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                                <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($customers) && $customers->count() > 0)
                <!-- Customer Filter -->
                <div class="filter-item">
                    <div class="filter-chip js-filter-chip" data-filter-target="customer_search_popover">
                        <i class="fas fa-users text-primary"></i>
                        <span class="chip-text">{!! __('store_customers.store_customer') ?? 'العميل' !!}</span>
                    </div>

                    <div class="ptc-query-panel shadow-lg border-0 radius-16" id="customer_search_popover" style="min-width: 280px;">
                        <div class="mb-3">
                            <label class="premium-label mb-2">{!! __('store_customers.store_customer') ?? 'العميل' !!}</label>
                            <div class="premium-input-wrapper">
                                <select name="store_customer_id"
                                    class="form-control premium-input shadow-none js-select2"
                                    data-placeholder="{!! __('general.all') !!}" data-parent="#customer_search_popover">
                                    <option value="">{!! __('general.all') !!}</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-users text-primary"></i>
                            </div>
                        </div>
                        <div class="popover-actions mt-4 text-right">
                            <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                                <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($bankAccounts))
                <!-- Bank Account Filter -->
                <div class="filter-item">
                    <div class="filter-chip js-filter-chip" data-filter-target="bank_account_search_popover">
                        <i class="fas fa-wallet text-primary"></i>
                        <span class="chip-text">{!! __('bank_accounts.bank_account') ?? 'الحساب' !!}</span>
                    </div>

                    <div class="ptc-query-panel shadow-lg border-0 radius-16" id="bank_account_search_popover" style="min-width: 280px;">
                        <div class="mb-3">
                            <label class="premium-label mb-2">{!! __('bank_accounts.bank_account') ?? 'الحساب' !!}</label>
                            <div class="premium-input-wrapper">
                                <select name="store_bank_account_id"
                                    class="form-control premium-input shadow-none js-select2"
                                    data-placeholder="{!! __('general.all') !!}" data-parent="#bank_account_search_popover">
                                    <option value="">{!! __('general.all') !!}</option>
                                    @foreach ($bankAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->paymentEntity->name ?? 'حساب' }} ({{ $account->account_number }})</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-wallet text-primary"></i>
                            </div>
                        </div>
                        <div class="popover-actions mt-4 text-right">
                            <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                                <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Type Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="type_search_popover">
                    <i class="fas fa-exchange-alt text-primary"></i>
                    <span class="chip-text">{!! __('store_transactions.type') !!}</span>
                </div>

                <!-- Type Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="type_search_popover"
                    style="min-width: 250px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('store_transactions.type') !!}</label>
                        <div class="premium-input-wrapper">
                            <select name="type" class="form-control premium-input shadow-none js-select2"
                                data-placeholder="{!! __('general.all') !!}" data-parent="#type_search_popover">
                                <option value="">{!! __('general.all') !!}</option>
                                <option value="debt">{!! __('store_transactions.debt') !!}</option>
                                <option value="payment">{!! __('store_transactions.payment') !!}</option>
                            </select>
                            <i class="fas fa-exchange-alt text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Specific Date Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="specific_date_search_popover">
                    <i class="fas fa-calendar-check text-primary"></i>
                    <span class="chip-text">{!! __('general.specific_date') !!}</span>
                </div>

                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="specific_date_search_popover"
                    style="min-width: 280px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('general.specific_date') !!}</label>
                        <div class="premium-input-wrapper mb-3">
                            <input type="text" class="form-control premium-input shadow-none ptc-datepicker" name="specific_date"
                                placeholder="{!! __('general.specific_date') !!}" autocomplete="off">
                            <i class="fas fa-calendar-check text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Date Range Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="date_search_popover">
                    <i class="fas fa-calendar-alt text-primary"></i>
                    <span class="chip-text">{!! __('store_transactions.date') !!}</span>
                </div>

                <!-- Date Range Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="date_search_popover"
                    style="min-width: 280px;">
                    <div class="mb-3">

                        <label class="premium-label mb-2">{!! __('general.from_date') !!}</label>
                        <div class="premium-input-wrapper mb-3">
                            <input type="text" class="form-control premium-input shadow-none ptc-datepicker" name="start_date"
                                placeholder="{!! __('general.from_date') !!}" autocomplete="off">
                            <i class="fas fa-calendar-day text-primary"></i>
                        </div>

                        <label class="premium-label mb-2">{!! __('general.to_date') !!}</label>
                        <div class="premium-input-wrapper">
                            <input type="text" class="form-control premium-input shadow-none ptc-datepicker" name="end_date"
                                placeholder="{!! __('general.to_date') !!}" autocomplete="off">
                            <i class="fas fa-calendar-check text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Reset Button -->
            <div class="filter-chip reset-chip js-reset-btn">
                <i class="fas fa-sync"></i>
                <span>{!! __('general.reset') !!}</span>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}?v={{ time() }}"></script>
@endpush


