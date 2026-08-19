<div class="flex items-center justify-center gap-1.5">
    <!-- Edit -->
    @can('store_supplier_payments_update')
        @php
            $bankAccountName = '';
            if ($payment->store_bank_account_id && $payment->bankAccount) {
                $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $bankAccountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
            }
        @endphp
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreSupplierPaymentBtn"
            data-id="{!! $payment->id !!}" 
            data-store_bank_account_id="{!! $payment->store_bank_account_id !!}"
            data-bank_account_name="{!! $bankAccountName !!}"
            data-bank_account_balance="{!! optional($payment->bankAccount)->current_balance ?? 0 !!}"
            data-amount="{!! $payment->amount !!}" 
            data-notes="{!! $payment->notes !!}"
            data-store_id="{!! $payment->store_id !!}"
            data-supplier_id="{!! $payment->store_supplier_id !!}"
            data-supplier_name="{!! optional($payment->supplier)->name !!}"
            data-invoice_id="{!! $payment->store_supplier_invoice_id !!}"
            data-date="{!! $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('store_supplier_payments_delete')
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
            data-id="{!! $payment->id !!}" data-route="{!! route('dashboard.store-supplier-payments.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
