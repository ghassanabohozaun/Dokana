<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="PIXINVENT">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{!! __('dashboard.dashboard') !!} | @yield('title')</title>

<link rel="apple-touch-icon" href="{!! asset('uploads/settings/' . setting()->favicon) !!}">
<link rel="shortcut icon" type="image/x-icon" href="{!! asset('uploads/settings/' . setting()->favicon) !!}">
<!-- Preload Local Fonts to prevent FOUT/FOIT -->
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/google/Tajawal-400.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/google/Tajawal-500.ttf') !!}" as="font" type="font/ttf" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/google/Tajawal-700.ttf') !!}" as="font" type="font/ttf" crossorigin>

<!-- Preload Icons -->
<link rel="preload" href="{!! asset('assets/dashbaord/vendors/fontawesome/webfonts/fa-solid-900.woff2') !!}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/line-awesome/fonts/line-awesome.woff2') !!}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{!! asset('assets/dashbaord/fonts/feather/fonts/feather.woff') !!}" as="font" type="font/woff" crossorigin>

<!-- Preconnect for external fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Load Poppins from Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=block"
    rel="stylesheet">

<!-- Load local fonts (Tajawal, Open Sans, etc) -->
<link href="{!! asset('assets/dashbaord/fonts/google/font.css') !!}" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/line-awesome/css/line-awesome.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/feather/style.min.css') !!}">



<!-- BEGIN: Dashboard Core CSS -->
<!-- Vendor Assets (Load first to allow overrides) -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/select2.min.css') }}">
<link rel="stylesheet"
    href="{{ asset('assets/dashbaord/vendors/css/pickers/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/filter.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/ajax-table.css') }}?v=1.1">
<link rel="stylesheet" href="{!! asset('vendor/fileInput/css/fileinput.min.css') !!}?v=1.1">

<!-- Select2 Vendor CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/vendors/css/forms/selects/select2.min.css') }}">

<!-- Custom CSS File -->
@if (Lang() == 'ar')
    <link rel="stylesheet" href="{!! asset('vendor/fileInput/css/fileinput-rtl.min.css') !!}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/vendors.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/app.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/custom-rtl.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/core/menu/menu-types/vertical-menu-modern.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/core/colors/palette-gradient.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css-rtl/sidebar-navy-rtl.css') }}?v=1.2">
@else
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/vendors.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/app.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css/vendors.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css/app.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css/core/menu/menu-types/vertical-menu-modern.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css/core/colors/palette-gradient.css') }}?v=1.1">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('assets/dashbaord/css/sidebar-navy.css') }}?v=1.2">
@endif

<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/pages.css') }}?v=1.1">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/dashbaord/css/system-style.css') }}?v=1.1">
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/dashbaord/css/premium-navbar.css') }}?v=1.1">

<!-- Ultra Premium Styles -->
<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/dashbaord/css/premium-sidebar.css') }}?v=1.2">

<link rel="stylesheet" type="text/css"
    href="{{ asset('assets/dashbaord/css/premium-fileinput.css') }}?v=1.1">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/premium-select2.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/store.css') }}?v=1.0">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/store_profile.css') }}?v=1.0">
<!-- END: Core CSS -->

<!-- Base Typography (Loaded LAST to guarantee it overrides RTL Bootstrap and custom files) -->
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/css/typography-base.css') !!}">
<link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/css/custom-sticky.css') !!}?v=1.1">

<!-- Global Font Size Control Script (Runs before body to prevent FOUC) -->
<script>
    (function() {
        var savedSize = localStorage.getItem('system-font-size');
        if (savedSize) {
            document.documentElement.style.setProperty('--global-base-font-size', savedSize);
        }
    })();
    function setSystemFontSize(size) {
        document.documentElement.style.setProperty('--global-base-font-size', size);
        localStorage.setItem('system-font-size', size);
    }
</script>
