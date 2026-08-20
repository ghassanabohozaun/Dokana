@php
    $sizeClass = $sizeClass ?? 'h-9 w-9';
    $photoUrl = $user->userPhoto();
@endphp

@if ($photoUrl)
    <div class="group relative {{ $sizeClass }} rounded-2xl overflow-hidden cursor-pointer shadow-xs hover:shadow-md hover:scale-105 transition-all duration-200"
         onclick="window.previewImage('{!! $photoUrl !!}', '{!! addslashes($user->name) !!}')"
         title="{!! __('general.click_to_preview_image') !!}">
        <img src="{!! $photoUrl !!}" class="h-full w-full object-cover select-none" alt="User">
        
        <!-- Hover Zoom Lens Overlay -->
        <div class="absolute inset-0 bg-indigo-950/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity text-white text-[10px]">
            <i class="fas fa-search-plus"></i>
        </div>
    </div>
@else
    <div class="{{ $sizeClass }} rounded-2xl flex items-center justify-center text-white text-xs font-bold shadow-xs"
         style="background-color: {!! $user->getAvatarColor() ?? '#6366f1' !!};">
        {{ $user->initials }}
    </div>
@endif
