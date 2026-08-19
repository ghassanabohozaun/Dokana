@php
    $size = $size ?? 40;
    $logoUrl = $store->logo_url;
@endphp

@if ($logoUrl)
    <div class="h-10 w-10 shrink-0 mx-auto rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 shadow-2xs">
        <img src="{{ $logoUrl }}" alt="{{ $store->name }}" class="h-full w-full object-cover">
    </div>
@else
    <div class="h-10 w-10 shrink-0 mx-auto rounded-xl flex items-center justify-center text-white font-bold text-xs shadow-2xs"
        style="background-color: {{ $store->getAvatarColor() }};">
        {{ $store->initials }}
    </div>
@endif
