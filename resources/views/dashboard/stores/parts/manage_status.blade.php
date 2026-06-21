@can('stores_update')
<div class="premium-switch-centered-wrapper">
    <label class="modern-switch">
        <input type="checkbox" class="change_status" id="status_{!! $store->id !!}" data-id="{!! $store->id !!}"
            {!! $store->status == 'active' ? 'checked' : '' !!}>
        <span class="modern-slider"></span>
    </label>
</div>
@endcan


