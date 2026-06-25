<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('store_supplier_payments_update')
            @php
                $bankAccountName = '';
                if ($payment->store_bank_account_id && $payment->bankAccount) {
                    $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                    $bankAccountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
                }
            @endphp
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_supplier_payment_button"
                store_supplier_payment-id="{!! $payment->id !!}" store_supplier_payment-store-bank-account-id="{!! $payment->store_bank_account_id !!}"
                store_supplier_payment-bank-account-name="{!! $bankAccountName !!}"
                store_supplier_payment-bank-account-balance="{!! optional($payment->bankAccount)->current_balance ?? 0 !!}"
                store_supplier_payment-amount="{!! $payment->amount !!}" store_supplier_payment-notes="{!! $payment->notes !!}"
                store_supplier_payment-store-id="{!! $payment->store_id !!}"
                store_supplier_payment-store-supplier-id="{!! $payment->store_supplier_id !!}"
                store_supplier_payment-supplier-name="{!! optional($payment->supplier)->name !!}"
                store_supplier_payment-store-supplier-invoice-id="{!! $payment->store_supplier_invoice_id !!}"
                store_supplier_payment-date="{!! $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('store_supplier_payments_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $payment->id !!}" data-route="{!! route('dashboard.store-supplier-payments.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
