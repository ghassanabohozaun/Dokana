<div class="flex items-center justify-center gap-1.5">
    <!-- Edit Button -->
    @can('departments_update')
    <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40"
        onclick="openEditDepartmentModal({
            id: '{{ $department->id }}',
            name_ar: '{{ $department->getTranslation('name', 'ar') }}',
            name_en: '{{ $department->getTranslation('name', 'en') }}',
            store_id: '{{ $department->store_id }}'
        })"
        title="{{ __('general.edit') }}">
        <i class="fas fa-edit text-xs"></i>
    </button>
    @endcan

    <!-- Delete Button -->
    @can('departments_delete')
    <button type="button"
        class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
        data-id="{{ $department->id }}" data-route="{{ route('dashboard.departments.destroy') }}" 
        data-title="{{ __('general.ask_delete_record') }}"
        data-text="{{ __('general.delete_warning_text') }}" data-confirm-btn="{{ __('general.yes') }}"
        data-cancel-btn="{{ __('general.no') }}" data-success-title="{{ __('general.deleted') }}"
        data-success-text="{{ __('general.delete_success_message') }}" title="{{ __('general.delete') }}">
        <i class="fas fa-trash-alt text-xs"></i>
    </button>
    @endcan
</div>
