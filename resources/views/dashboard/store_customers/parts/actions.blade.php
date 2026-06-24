<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- View Profile -->
        @can('store_customers_read')
            <a href="{!! route('dashboard.store-customers.show', $store_customer->id) !!}" class="btn-premium-action btn-premium-action-primary mr-1" title="{!! __('general.show') !!}">
                <i class="fas fa-eye"></i>
            </a>
        @endcan

        <!-- Edit -->
        @can('store_customers_update')
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_store_customer_button"
                store_customer-id="{!! $store_customer->id !!}" store_customer-name="{!! $store_customer->name !!}"
                store_customer-phone="{!! $store_customer->phone !!}" store_customer-store-id="{!! $store_customer->store_id !!}"
                store_customer-store-name="{!! optional($store_customer->store)->name !!}" 
                store_customer-bypass-debt-limit="{!! $store_customer->bypass_debt_limit ? 1 : 0 !!}"
                title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Add Transaction -->
        @can('store_transactions_create')
            @if($store_customer->status == 1)
                <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-success mr-1 add_transaction_button"
                    data-customer-id="{!! $store_customer->id !!}" data-customer-name="{!! $store_customer->name !!}" 
                    data-store-id="{!! $store_customer->store_id !!}" data-store-name="{!! optional($store_customer->store)->name !!}"
                    title="{!! __('store_transactions.create_new_store_transaction') !!}">
                    <i class="fas fa-hand-holding-usd"></i>
                </a>
            @else
                <a href="javascript:void(0)" class="btn-premium-action mr-1" style="background-color: #e2e8f0; color: #94a3b8; cursor: not-allowed;"
                    title="{!! __('store_transactions.customer_is_disabled') !!}">
                    <i class="fas fa-hand-holding-usd"></i>
                </a>
            @endif
        @endcan

        <!-- Delete -->
        @can('store_customers_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $store_customer->id !!}" data-route="{!! route('dashboard.store-customers.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
