<div class="d-flex justify-content-center align-items-center" style="gap: 8px;">

    <!-- Show Button -->
    <a href="{{ route('dashboard.bank-accounts.show', $account->id) }}" class="btn-premium-action btn-premium-action-primary mr-1" title="عرض التفاصيل">
        <i class="fas fa-eye"></i>
    </a>

    <!-- Edit -->
    @can('bank_accounts_update')
        <a href="javascript:void(0)" 
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
            class="btn-premium-action btn-premium-action-edit mr-1 editBankAccountBtn"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit"></i>
        </a>
        @endcan

        <!-- Delete -->
        @can('bank_accounts_delete')
        <a href="javascript:void(0)"
            class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
            data-id="{!! $account->id !!}" data-route="{!! route('dashboard.bank-accounts.destroy', $account->id) !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt"></i>
        </a>
        @endcan
    </div>
</div>


