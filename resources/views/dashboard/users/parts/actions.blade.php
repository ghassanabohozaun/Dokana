<div class="d-flex justify-content-center align-items-center mb-0">
    <div class="btn-group" role="group">

        @can('users_update')
        {{-- edit --}}
        <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-edit edit_user_button"
            title="{!! __('general.edit') !!}" user-id="{!! $user->id !!}" user-name-ar="{!! $user->getTranslation('name', 'ar') !!}"
            user-name-en="{!! $user->getTranslation('name', 'en') !!}" user-email="{!! $user->email !!}"
            user-mobile="{!! $user->mobile !!}"
            user-role-id="{!! $user->role_id !!}" user-status="{!! $user->status !!}"
            user-store-id="{!! $user->store_id !!}" user-store-name="{!! optional($user->store)->name !!}"
            user-photo="{!! $user->photo !!}" user-photo-url="{!! $user->userPhoto() !!}"
            data-toggle="modal" data-target="#updateUserModal">
            <i class="fas fa-edit"></i>
        </a>
        @endcan

        @can('users_delete')
        {{-- delete --}}
        @if (auth()->id() != $user->id)
            <a href="javascript:void(0)" class="btn-premium-action btn-premium-action-danger delete-confirm"
                data-id="{!! $user->id !!}" data-route="{!! route('dashboard.users.destroy') !!}"
                data-title="{!! __('general.ask_delete_record') !!}" data-text="{!! __('general.delete_warning_text') !!}"
                data-confirm-btn="{!! __('general.yes') !!}" data-cancel-btn="{!! __('general.no') !!}"
                data-success-title="{!! __('general.deleted') !!}"
                data-success-text="{!! __('general.delete_success_message') !!}" title="{!! __('general.delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </a>
        @else
            <button type="button" class="btn-premium-action btn-premium-action-danger disabled"
                style="opacity: 0.5; cursor: not-allowed;" title="{!! __('general.prevent_delete') !!}">
                <i class="fas fa-trash-alt"></i>
            </button>
        @endif
        @endcan

    </div>
</div>


