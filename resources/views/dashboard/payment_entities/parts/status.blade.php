<span class="badge-pill entity_status_{{ $entity->id }} {{ $entity->status == 1 ? 'badge-pill-success' : 'badge-pill-danger' }}">
    {{ $entity->status == 1 ? __('general.enable') : __('general.disabled') }}
</span>
