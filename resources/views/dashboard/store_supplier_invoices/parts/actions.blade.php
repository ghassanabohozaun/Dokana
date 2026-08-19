<div class="flex items-center justify-center gap-1.5">
    <!-- Edit -->
    @can('store_supplier_invoices_update')
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreSupplierInvoiceBtn"
            data-id="{!! $invoice->id !!}"
            data-supplier_id="{!! $invoice->store_supplier_id !!}"
            data-supplier_name="{!! optional($invoice->supplier)->name !!}"
            data-total_amount="{!! $invoice->total_amount !!}" 
            data-invoice_number="{!! $invoice->invoice_number !!}"
            data-store_id="{!! $invoice->store_id !!}"
            data-notes="{!! $invoice->notes !!}"
            data-date="{!! $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : $invoice->created_at->format('Y-m-d') !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('store_supplier_invoices_delete')
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
            data-id="{!! $invoice->id !!}" data-route="{!! route('dashboard.store-supplier-invoices.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
