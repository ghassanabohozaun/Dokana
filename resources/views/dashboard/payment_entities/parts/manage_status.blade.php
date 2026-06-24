<div class="custom-control custom-switch custom-control-inline mb-0 align-items-center">
    <input type="checkbox" class="custom-control-input change-status" id="statusSwitch{{ $entity->id }}"
        data-id="{{ $entity->id }}" data-url="{{ route('dashboard.payment-entities.change.status') }}"
        {{ $entity->status ? 'checked' : '' }}>
    <label class="custom-control-label" for="statusSwitch{{ $entity->id }}">
    </label>
</div>
