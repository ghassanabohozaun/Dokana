<!DOCTYPE html>
<html class="loading"
    @if (Config::get('app.locale') == 'ar') lang="ar" data-textdirection="rtl" @else  lang="en" data-textdirection="ltr" @endif>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="PIXINVENT">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {!! __('dashboard.dashboard') !!} | @yield('title')
    </title>

    <link rel="apple-touch-icon" href="{!! asset('uploads/settings/' . setting()->favicon) !!}">
    <link rel="shortcut icon" type="image/x-icon" href="{!! asset('uploads/settings/' . setting()->favicon) !!}">
    <link href="{!! asset('assets/dashbaord/fonts/google/font.css') !!}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/line-awesome/css/line-awesome.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">
    
    <link rel="stylesheet" type="text/css"
        href="{!! asset('assets/dashbaord') !!}/css-rtl/core/menu/menu-types/vertical-menu-modern.css">

    @if (Config::get('app.locale') == 'ar')
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css-rtl/vendors.css">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css-rtl/app.css">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css-rtl/custom-rtl.css">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css-rtl/core/colors/palette-gradient.css">
    @else
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css/vendors.css">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css/app.css">
        <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord') !!}/css/core/colors/palette-gradient.css">
    @endif

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/dashbaord/css/system-style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/pages.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/login.css') }}?v={{ time() }}">

    @stack('style')
</head>

<body class="vertical-layout vertical-menu-modern 1-column bg-lighten-2 menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-menu-modern" data-col="1-column">

    <!-- content ////////////////////////////////////////////////////////////////////////////-->
    @yield('content')
    <!-- footer ////////////////////////////////////////////////////////////////////////////-->
    <footer class="footer footer-static footer-light navbar-border"
        style="  margin-right: 0px !important; margin-top: -12px;">
        <p class="clearfix blue-grey lighten-2 text-sm-center mb-0 px-2">
            <span class="float-md-left d-block d-md-inline-block">{!! __('dashboard.copyright') !!} &copy;
                {!! date('Y') !!}
                <a class="text-bold-800 grey darken-2" href="javascript:void(0)"
                    target="_blank">{!! setting()->site_name !!}
                </a>,
                {!! __('dashboard.all_rights_reserved') !!}. </span>
        </p>
    </footer>

    <!-- BEGIN VENDOR JS-->
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/forms/validation/jqBootstrapValidation.js" type="text/javascript">
    </script>
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/forms/icheck/icheck.min.js" type="text/javascript"></script>
    <!-- BEGIN MODERN JS-->
    <script src="{!! asset('assets/dashbaord') !!}/js/core/app-menu.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/core/app.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/customizer.js" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/forms/form-login-register.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/extensions/sweet-alerts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->

    @stack('scripts')

    @include('layouts.dashboard.app-parts._premium_toast')
</body>

</html>


