<span class="badge-pill store_customer_status_{!! $store_customer->id !!} {!! $store_customer->status == 1 ? 'badge-pill-success' : 'badge-pill-danger' !!}">
    {!! $store_customer->status == 1 ? __('general.enable') : __('general.disabled') !!}
</span>
