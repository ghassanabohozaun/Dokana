<div class="badge badge-pill badge-glow premium-status-badge store_customer_status_{!! $store_customer->id !!} {!! $store_customer->status == 1 ? 'badge-success' : 'badge-danger' !!}">
    {!! $store_customer->status == 1 ? __('general.enable') : __('general.disabled') !!}
</div>



