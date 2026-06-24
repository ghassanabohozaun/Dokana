<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('store_transactions_update')
            @php
                $bankAccountName = '';
                if ($store_transaction->store_bank_account_id && $store_transaction->bankAccount) {
                    $entityName = $store_transaction->bankAccount->paymentEntity->getTranslation('name', app()->getLocale()) ?: $store_transaction->bankAccount->paymentEntity->getTranslation('name', 'ar');
                    $bankAccountName = $store_transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $store_transaction->bankAccount->account_number;
                }
            @endphp
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_transaction_button"
                store_transaction-id="{!! $store_transaction->id !!}" store_transaction-store-customer-id="{!! $store_transaction->store_customer_id !!}"
                store_transaction-type="{!! $store_transaction->type !!}" store_transaction-amount="{!! $store_transaction->amount !!}"
                store_transaction-description="{!! $store_transaction->description !!}" store_transaction-store-id="{!! $store_transaction->store_id !!}"
                store_transaction-bank-account-id="{!! $store_transaction->store_bank_account_id !!}" store_transaction-bank-account-name="{!! $bankAccountName !!}"
                store_transaction-date="{!! $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d') : $store_transaction->created_at->format('Y-m-d') !!}"
                store_transaction-customer-name="{!! optional($store_transaction->customer)->name !!} - {!! optional($store_transaction->customer)->phone !!}" title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('store_transactions_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $store_transaction->id !!}" data-route="{!! route('dashboard.store-transactions.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
