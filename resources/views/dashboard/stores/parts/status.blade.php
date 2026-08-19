<span class="badge-pill store_status_{{ $store->id }} {{ $store->status == 'active' ? 'badge-pill-success' : 'badge-pill-danger' }}">
    {{ $store->status == 'active' ? __('general.enable') : __('general.disabled') }}
</span>
