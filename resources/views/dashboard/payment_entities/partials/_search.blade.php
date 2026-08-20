<div class="dash-card p-4 space-y-3">
    <form class="js-filter-form space-y-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Top Row: Search Input, Type & Status Dropdowns, Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <!-- Keyword Search Input -->
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <input type="text" name="keyword" class="form-input-modern ps-9 text-xs w-full"
                    placeholder="{!! __('payment_entities.payment_entities') !!}..." autocomplete="off">
            </div>

            <!-- Type Filter Select -->
            <div class="w-full sm:w-48">
                <select name="type" id="filter_type" class="form-input-modern select2 w-full">
                    <option value="">{!! __('payment_entities.select_type') !!}</option>
                    <option value="bank">{!! __('payment_entities.type_bank') !!}</option>
                    <option value="wallet">{!! __('payment_entities.type_wallet') !!}</option>
                </select>
            </div>

            <!-- Status Filter Select -->
            <div class="w-full sm:w-48">
                <select name="status" id="filter_status" class="form-input-modern select2 w-full">
                    <option value="">{!! __('payment_entities.status') !!}</option>
                    <option value="1">{!! __('payment_entities.status_active') !!}</option>
                    <option value="0">{!! __('payment_entities.status_inactive') !!}</option>
                </select>
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
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
