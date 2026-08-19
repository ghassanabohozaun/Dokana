@can('stores_update')
<div class="flex items-center justify-center">
    <label class="relative inline-flex items-center cursor-pointer select-none">
        <input type="checkbox" class="sr-only peer change_status" id="status_{{ $store->id }}" data-id="{{ $store->id }}" {{ $store->status == 'active' ? 'checked' : '' }}>
        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-500 shadow-sm"></div>
    </label>
</div>
@endcan
