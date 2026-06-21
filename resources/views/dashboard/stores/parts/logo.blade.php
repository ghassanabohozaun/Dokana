@php
    $size = $size ?? 45;
    $logoUrl = $store->logo_url;
@endphp

@if ($logoUrl)
    <div class="premium-avatar-wrapper mx-auto"
        style="width: {!! $size !!}px; height: {!! $size !!}px;">
        <img src="{!! $logoUrl !!}" alt="{!! $store->name !!}" class="premium-avatar shadow-sm"
            style="width:100%; height:100%; border-radius: 8px; object-fit: cover;">
    </div>
@else
    <div class="avatar-circle avatar-size-{!! $size !!} d-inline-flex align-items-center justify-content-center text-white shadow-sm"
        style="background-color: {!! $store->getAvatarColor() !!}; width: {!! $size !!}px; height: {!! $size !!}px; border-radius: 8px; font-weight: bold; font-size: {!! $size / 2.5 !!}px;">
        {!! $store->initials !!}
    </div>
@endif


