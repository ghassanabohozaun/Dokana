<div class="flex items-center justify-center gap-1.5">
    <!-- Edit -->
    @can('store_transactions_update')
        @php
            $bankAccountName = '';
            if ($store_transaction->store_bank_account_id && $store_transaction->bankAccount) {
                $entityName = (optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale())) ?: optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $bankAccountName = $store_transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $store_transaction->bankAccount->account_number;
            }
        @endphp
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreTransactionBtn"
            data-id="{!! $store_transaction->id !!}" 
            data-store_customer_id="{!! $store_transaction->store_customer_id !!}"
            data-customer_name="{!! optional($store_transaction->customer)->name !!}{{ optional($store_transaction->customer)->phone ? ' - ' . optional($store_transaction->customer)->phone : '' }}"
            data-type="{!! $store_transaction->type !!}" 
            data-amount="{!! $store_transaction->amount !!}"
            data-description="{!! $store_transaction->description !!}" 
            data-store_id="{!! $store_transaction->store_id !!}"
            data-bank_account_id="{!! $store_transaction->store_bank_account_id !!}" 
            data-bank_account_name="{!! $bankAccountName !!}"
            data-date="{!! $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d') : $store_transaction->created_at->format('Y-m-d') !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('store_transactions_delete')
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
            data-id="{!! $store_transaction->id !!}" data-route="{!! route('dashboard.store-transactions.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
