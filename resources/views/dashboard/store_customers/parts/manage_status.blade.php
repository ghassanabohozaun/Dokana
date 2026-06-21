@can('store_customers_update')
<div class="premium-switch-centered-wrapper">
    <label class="modern-switch">
        <input type="checkbox" class="change_status" id="status_{!! $store_customer->id !!}" data-id="{!! $store_customer->id !!}"
            {!! $store_customer->status == 1 ? 'checked' : '' !!}>
        <span class="modern-slider"></span>
    </label>
</div>
@endcan

