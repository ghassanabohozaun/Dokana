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
                        <label class="premium-label mb-2">{!! __('store_customers.store_customers') !!}</label>
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

            <!-- Status Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="status_search_popover">
                    <i class="fas fa-check-circle text-primary"></i>
                    <span class="chip-text">{!! __('general.status') !!}</span>
                </div>

                <!-- Status Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="status_search_popover"
                    style="min-width: 280px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('general.status') !!}</label>
                        <div class="premium-input-wrapper">
                            <select name="status" class="form-control premium-input shadow-none js-select2"
                                data-placeholder="{!! __('general.all') !!}" data-parent="#status_search_popover">
                                <option value="">{!! __('general.all') !!}</option>
                                <option value="1">{!! __('general.enable') ?? 'مفعل' !!}</option>
                                <option value="0">{!! __('general.disabled') ?? 'معطل' !!}</option>
                            </select>
                            <i class="fas fa-check-circle text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sort Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="sort_search_popover">
                    <i class="fas fa-sort-amount-down text-primary"></i>
                    <span class="chip-text">{!! __('general.sort_by') ?? 'ترتيب حسب' !!}</span>
                </div>

                <!-- Sort Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="sort_search_popover"
                    style="min-width: 280px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('general.sort_by') ?? 'ترتيب حسب' !!}</label>
                        <div class="premium-input-wrapper">
                            <select name="sort_by" class="form-control premium-input shadow-none js-select2"
                                data-placeholder="{!! __('general.default') ?? 'الافتراضي' !!}" data-parent="#sort_search_popover">
                                <option value="">{!! __('general.default') ?? 'الافتراضي (الأحدث)' !!}</option>
                                <option value="highest_debts">{!! __('store_customers.highest_debts') ?? 'الأعلى ديوناً' !!}</option>
                                <option value="highest_payments">{!! __('store_customers.highest_payments') ?? 'الأكثر سداداً' !!}</option>
                                <option value="oldest_debts">{!! __('store_customers.oldest_debts') ?? 'أقدم الديون' !!}</option>
                            </select>
                            <i class="fas fa-sort-amount-down text-primary"></i>
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
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush


