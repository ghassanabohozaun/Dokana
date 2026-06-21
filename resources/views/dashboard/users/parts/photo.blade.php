@php
    $size = $size ?? 40;
    $photoUrl = $user->userPhoto();
@endphp

@if ($photoUrl)
    <img src="{!! $photoUrl !!}" class="avatar-circle avatar-size-{!! $size !!}" alt="User">
@else
    <div class="avatar-circle avatar-size-{!! $size !!} d-flex align-items-center justify-content-center text-white"
         style="background-color: {!! $user->getAvatarColor() !!};">
        <i class="fas fa-user avatar-icon-{!! $size !!}"></i>
    </div>
@endif


