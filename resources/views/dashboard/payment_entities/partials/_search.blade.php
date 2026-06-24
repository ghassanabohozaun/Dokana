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
                        <label class="premium-label mb-2">{!! __('payment_entities.payment_entities') !!}</label>
                        <div class="premium-input-wrapper">
                            <input type="text" class="form-control premium-input shadow-none"
                                name="keyword" placeholder="{!! __('general.search') !!}..."
                                autocomplete="off">
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

            <!-- Type Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="type_search_popover">
                    <i class="fas fa-list text-primary"></i>
                    <span class="chip-text">{!! __('payment_entities.type') !!}</span>
                </div>

                <!-- Type Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="type_search_popover" style="min-width: 250px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('payment_entities.type') !!}</label>
                        <div class="premium-input-wrapper">
                            <select name="type" id="filter_type"
                                class="form-control premium-input shadow-none js-select2"
                                data-placeholder="{!! __('payment_entities.select_type') !!}"
                                data-parent="#type_search_popover">
                                <option value="">{!! __('payment_entities.select_type') !!}</option>
                                <option value="bank">{!! __('payment_entities.type_bank') !!}</option>
                                <option value="wallet">{!! __('payment_entities.type_wallet') !!}</option>
                            </select>
                            <i class="fas fa-list text-primary"></i>
                        </div>
                    </div>
                    <div class="popover-actions mt-4 text-right">
                        <button type="button" class="btn btn-premium-blue btn-sm js-apply-filter px-4">
                            <i class="fas fa-check-circle mr-1"></i> {!! __('general.apply') !!}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="filter-item">
                <div class="filter-chip js-filter-chip" data-filter-target="status_search_popover">
                    <i class="fas fa-toggle-on text-primary"></i>
                    <span class="chip-text">{!! __('payment_entities.status') !!}</span>
                </div>

                <!-- Status Filter Popover -->
                <div class="ptc-query-panel shadow-lg border-0 radius-16" id="status_search_popover" style="min-width: 250px;">
                    <div class="mb-3">
                        <label class="premium-label mb-2">{!! __('payment_entities.status') !!}</label>
                        <div class="premium-input-wrapper">
                            <select name="status" id="filter_status"
                                class="form-control premium-input shadow-none js-select2"
                                data-placeholder="{!! __('payment_entities.status') !!}"
                                data-parent="#status_search_popover">
                                <option value="">{!! __('payment_entities.status') !!}</option>
                                <option value="1">{!! __('payment_entities.status_active') !!}</option>
                                <option value="0">{!! __('payment_entities.status_inactive') !!}</option>
                            </select>
                            <i class="fas fa-toggle-on text-primary"></i>
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
