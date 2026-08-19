@php
    $sizeClass = $sizeClass ?? 'h-8 w-8';
    $photoUrl = $user->userPhoto();
@endphp

@if ($photoUrl)
    <img src="{!! $photoUrl !!}" class="{{ $sizeClass }} rounded-xl object-cover ring-2 ring-slate-100 dark:ring-slate-800 shadow-sm" alt="User">
@else
    <div class="{{ $sizeClass }} rounded-xl flex items-center justify-center text-white text-xs font-bold ring-2 ring-slate-100 dark:ring-slate-800 shadow-sm"
         style="background-color: {!! $user->getAvatarColor() ?? '#6366f1' !!};">
        <i class="fas fa-user text-xs"></i>
    </div>
@endif
