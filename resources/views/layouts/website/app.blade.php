<!DOCTYPE html>
<html lang="{{ Lang() }}" dir="{{ Lang() == 'ar' ? 'rtl' : 'ltr' }}" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'الكاشير')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                    colors: {
                        primary: '#10b981', // emerald-500
                        secondary: '#3b82f6', // blue-500
                        dark: '#0f172a', // slate-900
                        darkCard: '#1e293b', // slate-800
                    },
                    keyframes: {
                        warningPop: {
                            '0%': {
                                transform: 'scale(0.8)',
                                opacity: '0'
                            },
                            '60%': {
                                transform: 'scale(1.05)',
                                opacity: '1'
                            },
                            '100%': {
                                transform: 'scale(1)'
                            },
                        },
                        swalHide: {
                            '0%': {
                                transform: 'scale(1) translateY(0)',
                                opacity: '1'
                            },
                            '100%': {
                                transform: 'scale(0.85) translateY(20px)',
                                opacity: '0'
                            },
                        }
                    },
                    animation: {
                        warningPop: 'warningPop 0.15s ease-out forwards',
                        swalHide: 'swalHide 0.1s ease-in forwards',
                    }
                }
            }
        }
    </script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/website/css/style.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('assets/website/css/toast.css') }}?v={{ time() }}">
    <link rel="shortcut icon" href="{!! setting()->favicon
        ? asset('uploads/settings/' . setting()->favicon)
        : asset('assets/dashboard/images/dokkana-logo.png') !!}" />
    @stack('css')
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-800 dark:bg-dark dark:text-gray-100 transition-colors duration-300 antialiased">

    @isset($slot)
        {{ $slot }}
    @else
        @yield('content')
    @endisset

    <!-- Scripts -->
    @livewireScripts
    <script src="{{ asset('assets/website/vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/website/js/toast.js') }}?v={{ time() }}"></script>
    @stack('scripts')
</body>

</html>
