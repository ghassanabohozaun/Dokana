<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Dokana Enterprise Management Platform">
<meta name="csrf-token" content="{{ csrf_token() }}">
@php
    $pageTitle = trim($__env->yieldContent('title'));
    $appBrand = setting()->site_name ?? 'Dokana';
    if (empty($pageTitle) || $pageTitle === __('dashboard.dashboard')) {
        $finalTitle = $appBrand . ' | ' . __('dashboard.dashboard');
    } else {
        $finalTitle = $appBrand . ' | ' . $pageTitle;
    }
@endphp
<title>{!! $finalTitle !!}</title>

<link rel="apple-touch-icon" href="{!! !empty(setting()->favicon) ? asset('uploads/settings/' . setting()->favicon) : asset('logo/dokkana-logo.png') !!}">
<link rel="shortcut icon" type="image/x-icon" href="{!! !empty(setting()->favicon) ? asset('uploads/settings/' . setting()->favicon) : asset('logo/dokkana-logo.png') !!}">
@if (!empty(setting()->logo) && file_exists(public_path('uploads/settings/' . setting()->logo)))
<link rel="preload" as="image" href="{!! asset('uploads/settings/' . setting()->logo) !!}">
@endif

<!-- 100% LOCAL FONTS & ICONS PRELOAD (Zero External CDN Dependencies & Zero FOIT Flicker) -->
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-400.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-500.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-700.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-500.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-600.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-700.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/vendors/fontawesome/webfonts/fa-solid-900.woff2') !!}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/vendors/fontawesome/webfonts/fa-regular-400.woff2') !!}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/feather/fonts/feather.woff') !!}" as="font" type="font/woff" crossorigin>

<!-- Local Icons -->
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/line-awesome/css/line-awesome.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/feather/style.min.css') !!}">

<!-- Essential Local Vendor Form Plugins -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/vendors/css/forms/selects/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/vendors/flatpickr/flatpickr.min.css') }}">


<!-- TAILWIND CORE DASHBOARD VITE ASSETS -->
@vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

<!-- Theme & Sidebar Init (Zero Flicker / Instant Pre-Render State) -->
<script>
    if (localStorage.getItem('dokana-theme') === 'dark' || (!('dokana-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    if (localStorage.getItem('dokana-sidebar-collapsed') === 'true') {
        document.documentElement.classList.add('sidebar-collapsed');
    }
</script>
