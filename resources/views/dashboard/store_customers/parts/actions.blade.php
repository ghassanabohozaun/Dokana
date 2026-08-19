<div class="flex items-center justify-center gap-1.5">
    <!-- View Profile -->
    @can('store_customers_read')
        <a href="{!! route('dashboard.store-customers.show', $store_customer->id) !!}" 
           class="btn-icon-action text-sky-600 hover:bg-sky-50 dark:hover:bg-sky-950/40" 
           title="{!! __('general.show') !!}">
            <i class="fas fa-eye text-xs"></i>
        </a>
    @endcan

    <!-- Add Transaction (Payment / Debt) -->
    @can('store_transactions_create')
        @if($store_customer->status == 1)
            <button type="button" class="btn-icon-action text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 add_transaction_button"
                data-customer-id="{!! $store_customer->id !!}" 
                data-customer-name="{!! $store_customer->name !!}" 
                data-store-id="{!! $store_customer->store_id !!}" 
                data-store-name="{!! optional($store_customer->store)->name !!}"
                title="{!! __('store_transactions.create_new_store_transaction') ?? 'إضافة حركة مالية' !!}">
                <i class="fas fa-hand-holding-usd text-xs"></i>
            </button>
        @else
            <button type="button" class="btn-icon-action text-slate-300 dark:text-slate-600 cursor-not-allowed opacity-60"
                disabled
                title="{!! __('store_transactions.customer_is_disabled') ?? 'العميل معطل' !!}">
                <i class="fas fa-hand-holding-usd text-xs"></i>
            </button>
        @endif
    @endcan

    <!-- Edit -->
    @can('store_customers_update')
        @if (!$store_customer->is_walk_in)
            <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editStoreCustomerBtn"
                data-id="{!! $store_customer->id !!}" 
                data-name="{!! $store_customer->name !!}"
                data-phone="{!! $store_customer->phone !!}" 
                data-store_id="{!! $store_customer->store_id !!}"
                data-store_name="{!! optional($store_customer->store)->name !!}" 
                data-bypass_debt_limit="{!! $store_customer->bypass_debt_limit ? 1 : 0 !!}"
                data-max_debt_limit="{!! $store_customer->max_debt_limit !!}"
                data-is_walk_in="{!! $store_customer->is_walk_in ? 1 : 0 !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit text-xs"></i>
            </button>
        @endif
    @endcan

    <!-- Delete -->
    @can('store_customers_delete')
        @if (!$store_customer->is_walk_in)
            <button type="button"
                class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
                data-id="{!! $store_customer->id !!}" data-route="{!! route('dashboard.store-customers.destroy') !!}" 
                data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        @endif
    @endcan
</div>
