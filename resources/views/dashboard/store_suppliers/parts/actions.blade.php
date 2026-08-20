<div class="flex items-center justify-center gap-1.5">
    <!-- View / Show Details -->
    <a href="{!! route('dashboard.store-suppliers.show', $supplier->id) !!}" 
       class="btn-icon-action text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-950/40"
       title="عرض ملف وكشف حساب المورد">
        <i class="fas fa-eye text-xs"></i>
    </a>

    <!-- Edit -->
    @can('store_suppliers_update')
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreSupplierBtn"
            data-id="{!! $supplier->id !!}" 
            data-name="{!! $supplier->name !!}"
            data-mobile="{!! $supplier->mobile !!}" 
            data-bank_name="{!! $supplier->bank_name !!}"
            data-account_number="{!! $supplier->account_number !!}" 
            data-email="{!! $supplier->email !!}"
            data-address="{!! $supplier->address !!}" 
            data-store_id="{!! $supplier->store_id !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('store_suppliers_delete')
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
            data-id="{!! $supplier->id !!}" data-route="{!! route('dashboard.store-suppliers.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
