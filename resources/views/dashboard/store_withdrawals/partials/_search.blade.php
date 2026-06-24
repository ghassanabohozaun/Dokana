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
                        <label class="premium-label mb-2">{!! __('store_withdrawals.store_withdrawals_list') !!}</label>
                        <div class="premium-input-wrapper">
                            <input type="text" class="form-control premium-input shadow-none" name="keyword"
                                placeholder="{!! __('store_withdrawals.search_by_reason') !!}" autocomplete="off">
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

            <!-- Bank Account Filter -->
            @if (isset($bankAccounts) && count($bankAccounts) > 0)
                <div class="filter-item">
                    <div class="filter-chip js-filter-chip" data-filter-target="bank_account_search_popover">
                        <i class="fas fa-money-check-alt text-primary"></i>
                        <span class="chip-text">{!! __('notebook.bank_account') ?? 'حساب البنك/المحفظة' !!}</span>
                    </div>

                    <!-- Bank Account Filter Popover -->
                    <div class="ptc-query-panel shadow-lg border-0 radius-16" id="bank_account_search_popover"
                        style="min-width: 280px;">
                        <div class="mb-3">
                            <label class="premium-label mb-2">{!! __('notebook.bank_account') ?? 'حساب البنك/المحفظة' !!}</label>
                            <div class="premium-input-wrapper">
                                <select name="store_bank_account_id" id="filter_store_bank_account_id"
                                    class="form-control premium-input shadow-none js-select2"
                                    data-placeholder="{!! __('general.all') !!}" data-parent="#bank_account_search_popover">
                                    <option value="">{!! __('general.all') !!}</option>
                                    @foreach ($bankAccounts as $account)
                                        @php
                                            $storeName = (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) && $account->store ? $account->store->name . ' - ' : '';
                                        @endphp
                                        <option value="{{ $account->id }}">{{ $storeName }}{{ $account->paymentEntity->name ?? 'حساب' }} ({{ $account->account_number }})</option>
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

            <!-- Date Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="date_search_popover">
                    <i class="fas fa-calendar-alt text-primary"></i>
                    <span class="chip-text">{!! __('general.date') !!}</span>
                </div>

                <!-- Date Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="date_search_popover">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('general.date') !!}</label>
                        <div class="premium-input-wrapper">
                            <input type="date" class="form-control premium-input shadow-none" name="specific_date">
                            <i class="fas fa-calendar text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="submit" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
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
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
