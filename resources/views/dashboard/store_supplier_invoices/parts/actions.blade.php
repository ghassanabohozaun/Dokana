<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('store_supplier_invoices_update')
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_supplier_invoice_button"
                store_supplier_invoice-id="{!! $invoice->id !!}"
                store_supplier_invoice-supplier-id="{!! $invoice->store_supplier_id !!}"
                store_supplier_invoice-supplier-name="{!! optional($invoice->supplier)->name !!}"
                store_supplier_invoice-total_amount="{!! $invoice->total_amount !!}" 
                store_supplier_invoice-invoice_number="{!! $invoice->invoice_number !!}"
                store_supplier_invoice-store-id="{!! $invoice->store_id !!}"
                store_supplier_invoice-notes="{!! $invoice->notes !!}"
                store_supplier_invoice-date="{!! $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : $invoice->created_at->format('Y-m-d') !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('store_supplier_invoices_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $invoice->id !!}" data-route="{!! route('dashboard.store-supplier-invoices.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
