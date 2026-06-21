<div class="badge badge-pill badge-glow premium-status-badge department_status_{!! $department->id !!} {!! $department->status == 1 ? 'badge-success' : 'badge-danger' !!}">
    {!! $department->status == 1 ? __('general.enable') : __('general.disabled') !!}
</div>


