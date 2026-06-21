@can('departments_update')
<div class="premium-switch-centered-wrapper">
    <label class="modern-switch">
        <input type="checkbox" class="change_status" id="status_{!! $department->id !!}" data-id="{!! $department->id !!}"
            {!! $department->status == 1 ? 'checked' : '' !!}>
        <span class="modern-slider"></span>
    </label>
</div>
@endcan
