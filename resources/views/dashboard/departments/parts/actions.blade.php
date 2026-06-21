<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        <!-- Edit -->
        @can('departments_update')
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit mr-1 edit_department_button"
                department-id="{!! $department->id !!}" department-name-ar="{!! $department->getTranslation('name', 'ar') !!}"
                department-name-en="{!! $department->getTranslation('name', 'en') !!}" department-store-id="{!! $department->store_id !!}"
                department-store-name="{!! optional($department->store)->name !!}" title="{!! __('general.edit') !!}">
                <i class="fas fa-edit"></i>
            </a>
        @endcan

        <!-- Delete -->
        @can('departments_delete')
            <a href="javascript:void(0)"
                class="btn-premium-action btn-premium-action-danger delete-confirm text-decoration-none"
                data-id="{!! $department->id !!}" data-route="{!! route('dashboard.departments.destroy') !!}" data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @endcan

    </div>
</div>
