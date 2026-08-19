<div class="flex items-center justify-center gap-1.5">
    <!-- Edit -->
    @can('users_update')
        <button type="button" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 editUserBtn"
            data-id="{!! $user->id !!}" 
            data-name_ar="{!! $user->getTranslation('name', 'ar') !!}"
            data-name_en="{!! $user->getTranslation('name', 'en') !!}" 
            data-email="{!! $user->email !!}"
            data-mobile="{!! $user->mobile !!}"
            data-role_id="{!! $user->role_id !!}" 
            data-status="{!! $user->status !!}"
            data-store_id="{!! $user->store_id !!}" 
            data-photo_url="{!! $user->userPhoto() !!}"
            title="{!! __('general.edit') !!}">
            <i class="fas fa-edit text-xs"></i>
        </button>
    @endcan

    <!-- Delete -->
    @can('users_delete')
        @if (auth()->id() != $user->id)
            <button type="button"
                class="btn-icon-action text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 delete-confirm"
                data-id="{!! $user->id !!}" data-route="{!! route('dashboard.users.destroy') !!}" 
                data-title="{!! __('general.ask_delete_record') !!}"
                data-text="{!! __('general.delete_warning_text') !!}" data-confirm-btn="{!! __('general.yes') !!}"
                data-cancel-btn="{!! __('general.no') !!}" data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        @else
            <button type="button" class="btn-icon-action text-slate-300 dark:text-slate-600 cursor-not-allowed opacity-50"
                disabled title="{!! __('general.prevent_delete') !!}">
                <i class="fas fa-trash-alt text-xs"></i>
            </button>
        @endif
    @endcan
</div>
