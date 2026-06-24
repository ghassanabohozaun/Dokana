<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('store_withdrawals_update')
            @php
                $bankAccountName = '';
                if ($withdrawal->store_bank_account_id && $withdrawal->bankAccount) {
                    $entityName = optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                    $bankAccountName = $withdrawal->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $withdrawal->bankAccount->account_number;
                }
            @endphp
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_withdrawal_button"
                store_withdrawal-id="{!! $withdrawal->id !!}" store_withdrawal-store-bank-account-id="{!! $withdrawal->store_bank_account_id !!}"
                store_withdrawal-bank-account-name="{!! $bankAccountName !!}"
                store_withdrawal-bank-account-balance="{!! optional($withdrawal->bankAccount)->current_balance ?? 0 !!}"
                store_withdrawal-amount="{!! $withdrawal->amount !!}" store_withdrawal-reason="{!! $withdrawal->reason !!}"
                store_withdrawal-store-id="{!! $withdrawal->store_id !!}"
                store_withdrawal-date="{!! $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('Y-m-d') : $withdrawal->created_at->format('Y-m-d') !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('store_withdrawals_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $withdrawal->id !!}" data-route="{!! route('dashboard.store-withdrawals.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
