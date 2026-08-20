@php
    $logoUrl = $store->logo_url;
@endphp

@if ($logoUrl)
    <div class="group relative h-11 w-11 shrink-0 mx-auto rounded-2xl p-1 bg-white dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 shadow-xs hover:shadow-md hover:border-indigo-400 dark:hover:border-indigo-500 hover:scale-105 transition-all duration-200 cursor-pointer flex items-center justify-center overflow-hidden"
         onclick="window.previewImage('{{ $logoUrl }}', '{{ addslashes($store->name) }}')"
         title="{!! __('general.click_to_preview_image') !!}">
        <img src="{{ $logoUrl }}" alt="{{ $store->name }}" class="max-h-full max-w-full object-contain select-none">
        
        <!-- Hover Zoom Lens Overlay -->
        <div class="absolute inset-0 bg-indigo-950/40 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white text-xs">
            <i class="fas fa-search-plus"></i>
        </div>
    </div>
@else
    <div class="h-11 w-11 shrink-0 mx-auto rounded-2xl flex items-center justify-center text-white font-bold text-xs shadow-xs"
        style="background-color: {{ $store->getAvatarColor() }};">
        {{ $store->initials }}
    </div>
@endif
