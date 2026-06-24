<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">
        @can('payment_entities_update')
        <!-- Edit -->
        <a href="javascript:void(0)" 
            data-id="{!! $entity->id !!}" 
            data-type="{!! $entity->type !!}"
            data-name_ar="{!! $entity->getTranslation('name', 'ar') !!}"
            data-name_en="{!! $entity->getTranslation('name', 'en') !!}"
            data-status="{!! $entity->status !!}"
            class="btn-premium-action btn-premium-action-edit mr-1 editPaymentEntityBtn"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit"></i>
        </a>
        @endcan

        <!-- Delete -->
        @can('payment_entities_delete')
        <a href="javascript:void(0)"
            class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
            data-id="{!! $entity->id !!}" data-route="{!! route('dashboard.payment-entities.destroy') !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt"></i>
        </a>
        @endcan
    </div>
</div>
