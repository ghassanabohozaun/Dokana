<div class="flex items-center justify-center gap-1.5">
    <!-- 1. Show Details -->
    <a href="{{ route('dashboard.bank-accounts.show', $account->id) }}" 
       class="btn-icon-action text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-950/40" 
       title="{{ __('general.show') ?? 'عرض التفاصيل' }}">
        <i class="fas fa-eye text-xs"></i>
    </a>

    <!-- 2. Adjust Balance -->
    @can('bank_accounts_update')
    <button type="button" 
            class="btn-icon-action text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40 adjustBankAccountBtn"
            data-id="{!! $account->id !!}" 
            data-current_balance="{!! $account->current_balance !!}"
            title="{!! __('bank_accounts.adjust_balance') !!}">
        <i class="fas fa-balance-scale text-xs"></i>
    </button>
    @endcan

    <!-- 3. Edit (Placed right next to Delete) -->
    @can('bank_accounts_update')
        @if($account->account_type !== 'cash')
            <button type="button" 
                    class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editBankAccountBtn"
                    data-id="{!! $account->id !!}" 
                    data-account_type="{!! $account->account_type !!}"
                    data-payment_entity_id="{!! $account->payment_entity_id !!}"
                    data-account_number="{!! $account->account_number !!}"
                    data-account_holder_name_ar="{!! $account->getTranslation('account_holder_name', 'ar') !!}"
                    data-account_holder_name_en="{!! $account->getTranslation('account_holder_name', 'en') !!}"
                    data-iban="{!! $account->iban !!}"
                    data-is_default="{!! $account->is_default !!}"
                    data-store_id="{!! $account->store_id !!}"
                    data-store_name="{!! $account->store->name ?? '' !!}"
                    title="{!! __('general.edit') !!}">
                <i class="fas fa-edit text-xs"></i>
            </button>
        @else
            <button type="button" 
                    class="btn-icon-action opacity-30 cursor-not-allowed text-slate-400 dark:text-slate-600 hover:bg-transparent"
                    disabled
                    title="{!! __('bank_accounts.cannot_edit_main_cash_account') !!}">
                <i class="fas fa-edit text-xs"></i>
            </button>
        @endif
    @endcan

    <!-- 4. Delete (Disabled for cash drawer) -->
    @can('bank_accounts_delete')
        @if($account->account_type !== 'cash')
            <button type="button" 
                    class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
                    data-id="{!! $account->id !!}" 
                    data-route="{!! route('dashboard.bank-accounts.destroy', $account->id) !!}" 
                    data-title="{!! __('general.ask_delete_record') !!}"
                    data-text="{!! __('general.delete_warning_text') !!}" 
                    data-confirm-btn="{!! __('general.yes') !!}"
                    data-cancel-btn="{!! __('general.no') !!}" 
                    data-success-title="{!! __('general.deleted') !!}"
                    data-success-text="{!! __('general.delete_success_message') !!}" 
                    title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        @else
            <button type="button" 
                    class="btn-icon-action opacity-30 cursor-not-allowed text-slate-400 dark:text-slate-600 hover:bg-transparent"
                    disabled
                    title="{!! __('bank_accounts.cannot_delete_main_cash_account') !!}">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        @endif
    @endcan
</div>
