<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('stores_update')
        <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1"
            onclick="openEditStoreModal({
                id: '{!! $store->id !!}',
                name_ar: '{!! $store->getTranslation('name', 'ar') !!}',
                name_en: '{!! $store->getTranslation('name', 'en') !!}',
                email: '{!! $store->email !!}',
                phone: '{!! $store->phone !!}',
                address: '{!! $store->address !!}',
                subscription_plan: '{!! $store->subscription_plan !!}',
                status: '{!! $store->status !!}',
                logo_url: '{!! $store->logo_url !!}'
            })"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit"></i>
        </a>
        @endcan

        <!-- Delete -->
        @can('stores_delete')
        <a href="javascript:void(0)"
            class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
            data-id="{!! $store->id !!}" data-route="{!! route('dashboard.stores.destroy', $store->id) !!}" 
            data-title="{!! __('general.ask_delete_record') !!}"
            data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
            data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
            data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt"></i>
        </a>
        @endcan

    </div>
</div>


