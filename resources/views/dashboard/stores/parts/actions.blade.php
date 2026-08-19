<div class="flex items-center justify-center gap-1.5">
    <!-- Edit Button -->
    @can('stores_update')
    <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40"
        onclick="openEditStoreModal({
            id: '{{ $store->id }}',
            name_ar: '{{ $store->getTranslation('name', 'ar') }}',
            name_en: '{{ $store->getTranslation('name', 'en') }}',
            email: '{{ $store->email }}',
            phone: '{{ $store->phone }}',
            address: '{{ $store->address }}',
            subscription_plan: '{{ $store->subscription_plan }}',
            status: '{{ $store->status }}',
            logo_url: '{{ $store->logo_url }}'
        })"
        title="{{ __('general.edit') }}">
        <i class="fas fa-edit text-xs"></i>
    </button>
    @endcan

    <!-- Delete Button -->
    @can('stores_delete')
    <button type="button"
        class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
        data-id="{{ $store->id }}" data-route="{{ route('dashboard.stores.destroy') }}" 
        data-title="{{ __('general.ask_delete_record') }}"
        data-text="{{ __('general.delete_warning_text') }}" data-confirm-btn="{{ __('general.yes') }}"
        data-cancel-btn="{{ __('general.no') }}" data-success-title="{{ __('general.deleted') }}"
        data-success-text="{{ __('general.delete_success_message') }}" title="{{ __('general.delete') }}">
        <i class="fas fa-trash-alt text-xs"></i>
    </button>
    @endcan
</div>
