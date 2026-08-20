<!DOCTYPE html>
<html lang="{{ Lang() }}" dir="{{ Lang() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">

<head>
    <!-- 0. INSTANT DARK MODE INITIALIZATION (MUST RUN BEFORE FIRST PAINT) -->
    <script>
        (function() {
            try {
                const theme = localStorage.getItem('dokana-theme');
                if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Dokana Enterprise Management Platform">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dokana | @yield('title')</title>

    <link rel="apple-touch-icon" href="{!! asset('logo/dokkana-logo.png') !!}">
    <link rel="shortcut icon" type="image/x-icon" href="{!! asset('logo/dokkana-logo.png') !!}">

    <!-- 100% LOCAL FONTS PRELOAD -->
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-400.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-500.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-700.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-500.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-600.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Manrope-700.ttf') !!}" as="font" type="font/ttf" crossorigin>

    <!-- Local Icons -->
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">

    <!-- TAILWIND CORE VITE ASSETS -->
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('style')
</head>

<body
    class="h-screen w-screen overflow-hidden bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Main Auth Viewport (Full Screen Zero Scroll & Zero Flicker) -->
    <main class="h-full w-full">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
