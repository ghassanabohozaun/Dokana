<div class="badge badge-pill badge-glow user_status_{!! $user->id !!} {!! $user->status == 1 ? 'badge-success' : 'badge-danger' !!}"
    style="font-size: 12px; font-weight: bold; padding: 5px 12px;">
    {!! $user->status == 1 ? __('general.enable') : __('general.disabled') !!}
</div>


