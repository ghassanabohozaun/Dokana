<div class="premium-switch-centered-wrapper">
    <label class="modern-switch">
        <input type="checkbox" class="change_status" id="status_{!! $user->id !!}" data-id="{!! $user->id !!}"
            {!! $user->status == 1 ? 'checked' : '' !!}>
        <span class="modern-slider"></span>
    </label>
</div>
