<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('store_suppliers_update')
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_supplier_button"
                store_supplier-id="{!! $supplier->id !!}" store_supplier-name="{!! $supplier->name !!}"
                store_supplier-mobile="{!! $supplier->mobile !!}" store_supplier-bank_name="{!! $supplier->bank_name !!}"
                store_supplier-account_number="{!! $supplier->account_number !!}" store_supplier-email="{!! $supplier->email !!}"
                store_supplier-address="{!! $supplier->address !!}" store_supplier-store-id="{!! $supplier->store_id !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('store_suppliers_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $supplier->id !!}" data-route="{!! route('dashboard.store-suppliers.destroy') !!}"
                data-title="{!! __('general.ask_delete_record') !!}" data-text="{!! __('general.delete_warning_text') !!}"
                data-confirm-btn="{!! __('general.yes') !!}" data-cancel-btn="{!! __('general.no') !!}"
                data-success-title="{!! __('general.deleted') !!}" data-success-text="{!! __('general.delete_success_message') !!}"
                title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
