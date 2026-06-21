<div class="badge badge-pill badge-glow premium-status-badge store_status_{!! $store->id !!} {!! $store->status == 'active' ? 'badge-success' : 'badge-danger' !!}">
    {!! $store->status == 'active' ? __('general.enable') : __('general.disabled') !!}
</div>


