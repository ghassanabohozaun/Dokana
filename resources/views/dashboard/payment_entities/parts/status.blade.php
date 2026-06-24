@if ($entity->status == 1)
    <span class="badge badge-success badge-glow badge-pill px-2">{!! __('general.enable') !!}</span>
@else
    <span class="badge badge-danger badge-glow badge-pill px-2">{!! __('general.disabled') !!}</span>
@endif
