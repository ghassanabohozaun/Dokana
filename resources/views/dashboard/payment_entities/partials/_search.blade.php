<div class="dash-card p-4">
    <form class="js-filter-form flex flex-col md:flex-row items-center gap-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Keyword Search Input -->
        <div class="relative flex-1 w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="keyword" class="form-input-modern ps-9"
                placeholder="{!! __('payment_entities.payment_entities') !!}..." autocomplete="off">
        </div>

        <!-- Type Filter Select -->
        <div class="w-full md:w-48">
            <select name="type" id="filter_type" class="form-input-modern select2">
                <option value="">{!! __('payment_entities.select_type') !!}</option>
                <option value="bank">{!! __('payment_entities.type_bank') !!}</option>
                <option value="wallet">{!! __('payment_entities.type_wallet') !!}</option>
            </select>
        </div>

        <!-- Status Filter Select -->
        <div class="w-full md:w-48">
            <select name="status" id="filter_status" class="form-input-modern select2">
                <option value="">{!! __('payment_entities.status') !!}</option>
                <option value="1">{!! __('payment_entities.status_active') !!}</option>
                <option value="0">{!! __('payment_entities.status_inactive') !!}</option>
            </select>
        </div>

        <!-- Filter Actions -->
        <div class="flex items-center gap-2 w-full md:w-auto">
            <button type="submit" class="btn-primary-gradient text-xs py-2.5 px-4 w-full md:w-auto">
                <i class="fas fa-filter text-xs"></i>
                <span>{!! __('general.apply') !!}</span>
            </button>

            <button type="button" class="btn-secondary-modern text-xs py-2.5 px-3 js-reset-btn" title="{!! __('general.reset') !!}">
                <i class="fas fa-sync text-xs"></i>
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="{!! asset('assets/dashbaord/js/filter-system.js') !!}"></script>
@endpush
