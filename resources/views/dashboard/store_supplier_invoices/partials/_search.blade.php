<div class="dash-card p-4 md:p-5">
    <form class="js-filter-form flex flex-wrap items-center gap-3" data-container="#table_data" data-loader=".table-loader-overlay">
        
        <!-- Keyword Search Input -->
        <div class="relative flex-1 min-w-[200px] max-w-full">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400">
                <i class="fas fa-search text-xs"></i>
            </div>
            <input type="text" name="keyword" class="form-input-modern ps-9 text-xs"
                placeholder="{!! __('store_supplier_invoices.search_by_invoice_number') !!}" autocomplete="off">
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

        <!-- Supplier Filter -->
        @if (isset($suppliers) && $suppliers->count() > 0)
        <div class="w-full sm:w-52 flex-1 sm:flex-initial min-w-[150px]">
            <select name="store_supplier_id" id="filter_store_supplier_id" class="form-input-modern select2">
                <option value="">{!! __('store_suppliers.plural') ?? 'كل الموردين' !!}</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Date Filter (Flatpickr) -->
        <div class="w-full sm:w-44 flex-1 sm:flex-initial min-w-[140px]">
            <input type="text" name="specific_date" class="form-input-modern text-xs flatpickr-date" placeholder="{!! __('general.date') !!}" autocomplete="off">
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
