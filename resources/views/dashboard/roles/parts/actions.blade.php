<div class="flex items-center justify-center gap-1.5">
    {{-- Edit --}}
    @can('update', $role)
        <a href="{!! route('dashboard.roles.edit', $role->id) !!}" 
           class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40" 
           title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </a>
    @else
        @if($role->isSystemRole())
            <span class="btn-icon-action text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/40 cursor-help" 
                  title="{!! __('roles.system_role_protected') !!}">
                <i class="fas fa-lock text-xs"></i>
            </span>
        @endif
    @endcan

    {{-- Delete --}}
    @can('delete', $role)
        <button type="button"
            class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm" 
            data-id="{!! $role->id !!}" 
            data-route="{!! route('dashboard.roles.destroy') !!}"
            data-title="{!! __('general.ask_delete_record') !!}" 
            data-text="{!! __('general.delete_warning_text') !!}"
            data-confirm-btn="{!! __('general.yes') !!}" 
            data-cancel-btn="{!! __('general.no') !!}"
            data-success-title="{!! __('general.deleted') !!}" 
            data-success-text="{!! __('general.delete_success_message') !!}"
            title="{!! __('general.delete') !!}">
            <i class="fas fa-trash-alt text-xs"></i>
        </button>
    @endcan
</div>
