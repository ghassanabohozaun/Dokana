<span class="badge-pill department_status_{{ $department->id }} {{ $department->status == 1 ? 'badge-pill-success' : 'badge-pill-danger' }}">
    {{ $department->status == 1 ? __('general.enable') : __('general.disabled') }}
</span>
