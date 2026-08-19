<div class="flex items-center justify-center gap-1.5">
    <!-- Edit -->
    @can('store_withdrawals_update')
        @php
            $bankAccountName = '';
            if ($withdrawal->store_bank_account_id && $withdrawal->bankAccount) {
                $entityName = optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($withdrawal->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $bankAccountName = $withdrawal->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $withdrawal->bankAccount->account_number;
            }
        @endphp
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreWithdrawalBtn"
            data-id="{!! $withdrawal->id !!}" 
            data-store_bank_account_id="{!! $withdrawal->store_bank_account_id !!}"
            data-bank_account_name="{!! $bankAccountName !!}"
            data-bank_account_balance="{!! optional($withdrawal->bankAccount)->current_balance ?? 0 !!}"
            data-amount="{!! $withdrawal->amount !!}" 
            data-reason="{!! $withdrawal->reason !!}"
            data-store_id="{!! $withdrawal->store_id !!}"
            data-withdrawal_date="{!! $withdrawal->withdrawal_date ? \Carbon\Carbon::parse($withdrawal->withdrawal_date)->format('Y-m-d') : $withdrawal->created_at->format('Y-m-d') !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('store_withdrawals_delete')
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
            data-id="{!! $withdrawal->id !!}" data-route="{!! route('dashboard.store-withdrawals.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
