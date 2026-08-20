<!DOCTYPE html>
<html lang="{{ Lang() }}" dir="{{ Lang() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full overflow-hidden">

<head>
    @include('layouts.dashboard.app-parts._head')

    @stack('style')
    @livewireStyles
</head>

<body class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 font-sans antialiased selection:bg-indigo-500 selection:text-white overflow-hidden">
    <div class="h-screen flex flex-col overflow-hidden">
        <!-- Sidebar Navigation -->
        @include('layouts.dashboard.app-parts._sidebar')

        <!-- Main Content Layout (Responsive start-padding for fixed desktop sidebar) -->
        <div class="flex-1 flex flex-col md:ps-72 h-full overflow-hidden transition-all duration-300" id="main-content-layout">
            <!-- Top Navbar (Fixed at top) -->
            @include('layouts.dashboard.app-parts._header')

            <!-- Dedicated Vertical Scrollable Viewport -->
            <div class="flex-1 overflow-y-auto custom-scrollbar flex flex-col relative" id="main-viewport">
                <!-- Global Page Shimmer Skeleton Overlay -->
                @include('layouts.dashboard.app-parts._page_skeleton')

                <!-- Page Content Viewport -->
                <main class="flex-1 p-4 md:p-6 lg:p-8 w-full max-w-7xl mx-auto transition-opacity duration-200" id="main-page-content">
                    @isset($slot)
                        {{ $slot }}
                    @else
                        @yield('content')
                    @endisset
                </main>

                <!-- Footer -->
                @include('layouts.dashboard.app-parts._footer')
            </div>
        </div>
    </div>

    <!-- Scripts & Toast Notifications & Universal Dialogs -->
    @include('layouts.dashboard.app-parts._scripts')
    @stack('scripts')
    @livewireScripts
    @include('layouts.dashboard.app-parts._premium_toast')
    @include('layouts.dashboard.app-parts._confirm_dialog')
    @include('layouts.dashboard.app-parts._image_lightbox')
</body>

</html>
