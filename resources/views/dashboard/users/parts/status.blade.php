<span class="badge-pill user_status_{!! $user->id !!} {!! $user->status == 1 ? 'badge-pill-success' : 'badge-pill-danger' !!}">
    {!! $user->status == 1 ? __('general.enable') : __('general.disabled') !!}
</span>
