<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.error_403_title') }}</title>
    
    <link rel="shortcut icon" type="image/x-icon" href="{!! !empty(setting()->favicon) ? asset('uploads/settings/' . setting()->favicon) : asset('logo/dokkana-logo.png') !!}">

    <!-- 100% Local Fonts & Icons -->
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-400.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-700.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/line-awesome/css/line-awesome.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/fonts/feather/style.min.css') !!}">
    
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

    <script>
        (function () {
            const savedTheme = localStorage.getItem('dokana-theme') || localStorage.getItem('theme');
            if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <style>
        @keyframes radarSweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(-1.5deg); }
        }
        @keyframes pulseHalo {
            0%, 100% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.08); opacity: 0.2; }
        }
        .anim-radar { animation: radarSweep 5s linear infinite; }
        .anim-float { animation: floatSlow 5s ease-in-out infinite; }
        .anim-pulse { animation: pulseHalo 3.5s ease-in-out infinite; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-rose-50/30 dark:from-slate-950 dark:via-slate-900 dark:to-rose-950/20 text-slate-800 dark:text-slate-100 flex flex-col justify-between relative overflow-x-hidden selection:bg-rose-500 selection:text-white font-sans antialiased">

    <!-- 1. Interactive Canvas Particles -->
    <canvas id="particle-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-50"></canvas>

    <!-- 2. Ambient Lighting Glow -->
    <div class="absolute top-10 start-1/4 w-[450px] h-[450px] bg-rose-500/10 dark:bg-rose-600/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 end-1/4 w-[450px] h-[450px] bg-indigo-500/10 dark:bg-indigo-600/15 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- 3. Giant Watermark in Background -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0 opacity-[0.04] dark:opacity-[0.03]">
        <span class="text-[35vw] font-black tracking-tighter text-slate-900 dark:text-white font-mono leading-none">403</span>
    </div>

    <!-- Top Navigation Header Bar -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between border-b border-slate-200/60 dark:border-slate-800/80">
        <!-- Brand Logo & Platform Name -->
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-rose-500 to-indigo-600 p-0.5 shadow-md shadow-rose-500/20">
                <div class="h-full w-full bg-white dark:bg-slate-900 rounded-[14px] flex items-center justify-center">
                    <i class="fas fa-shield-alt text-rose-500 dark:text-rose-400 text-base"></i>
                </div>
            </div>
            <div>
                <span class="text-sm font-bold text-slate-900 dark:text-white tracking-wide block leading-tight">{{ __('errors.platform_name') }}</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">{{ __('errors.security_hub') }}</span>
            </div>
        </div>

        <!-- Controls: Language Switcher + Dark Mode + Live Status Pill -->
        <div class="flex items-center gap-2">
            @php
                $currentLocale = Lang();
                $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
                $flagPath = $targetLocale == 'ar'
                    ? asset('assets/dashbaord/media/svg/flags/العربية.svg')
                    : asset('assets/dashbaord/media/svg/flags/English.svg');
            @endphp
            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/90 dark:bg-slate-800/90 text-slate-700 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700/80 text-xs font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors shadow-2xs backdrop-blur-md"
                title="{{ $targetNative }}">
                <img src="{!! $flagPath !!}" class="h-3.5 w-3.5 rounded-full object-cover" alt="{{ $targetNative }}">
                <span class="font-bold text-xs">{{ $targetNative }}</span>
            </a>

            <button type="button" data-theme-toggle
                class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/90 dark:bg-slate-800/90 text-slate-600 dark:text-amber-400 border border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors shadow-2xs backdrop-blur-md"
                title="Toggle Dark / Light Mode" aria-label="Toggle Dark Mode">
                <i class="fas fa-moon dark:hidden text-xs"></i>
                <i class="fas fa-sun hidden dark:block text-xs"></i>
            </button>

            <!-- Live Status Pill -->
            <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-2xs backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                </span>
                <span class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ __('errors.error_403_code') }}</span>
            </div>
        </div>
    </header>

    <!-- Main Content Stage (Full Screen Immersive Grid) -->
    <main class="relative z-10 flex-1 flex items-center w-full max-w-7xl mx-auto px-6 py-8">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Side: Interactive 3D Cyber Security Radar (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center relative order-2 lg:order-1">
                <div class="relative w-72 h-72 sm:w-88 sm:h-88 flex items-center justify-center anim-float">
                    
                    <!-- Outer Pulse Halo -->
                    <div class="absolute inset-0 rounded-full border border-rose-500/30 dark:border-rose-500/20 anim-pulse"></div>
                    <div class="absolute -inset-6 rounded-full border border-indigo-500/20 dark:border-indigo-500/10 anim-pulse" style="animation-delay: -1.8s;"></div>

                    <!-- Radar Grid Circles -->
                    <div class="absolute inset-4 rounded-full border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/60 backdrop-blur-md shadow-xl"></div>
                    <div class="absolute inset-16 rounded-full border border-slate-300 dark:border-slate-800/80 border-dashed"></div>
                    <div class="absolute inset-28 rounded-full border border-rose-500/30 dark:border-rose-500/20"></div>

                    <!-- Radar Sweep Beam -->
                    <div class="absolute inset-4 rounded-full overflow-hidden pointer-events-none">
                        <div class="w-full h-full origin-center anim-radar bg-[conic-gradient(from_0deg,transparent_0_300deg,rgba(244,63,94,0.35)_360deg)]"></div>
                    </div>

                    <!-- Center Holographic Security Shield -->
                    <div class="relative z-10 h-24 w-24 rounded-3xl bg-gradient-to-tr from-rose-500 via-rose-600 to-indigo-600 p-0.5 shadow-xl shadow-rose-500/25">
                        <div class="h-full w-full bg-white dark:bg-slate-950 rounded-[22px] flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-tr from-rose-50/50 to-indigo-50/50 dark:from-rose-950/20 dark:to-indigo-950/20"></div>
                            <svg class="h-11 w-11 text-rose-500 relative z-10 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                <rect x="9" y="11" width="6" height="5" rx="1" fill="currentColor" fill-opacity="0.3"/>
                                <path d="M10 11V9a2 2 0 1 1 4 0v2"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Floating Satellite Status Badges -->
                    <div class="absolute -top-2 start-2 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 shadow-md flex items-center gap-1.5">
                        <i class="fas fa-lock text-[9px]"></i>
                        <span>{{ __('errors.enc_active') }}</span>
                    </div>

                    <div class="absolute -bottom-2 end-2 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 shadow-md flex items-center gap-1.5">
                        <i class="fas fa-network-wired text-[9px]"></i>
                        <span>{{ __('errors.tenant_isolated') }}</span>
                    </div>

                </div>
            </div>

            <!-- Right Side: Content & Control Console (7 Cols) -->
            <div class="lg:col-span-7 space-y-6 order-1 lg:order-2 text-start">
                
                <!-- Badge & Main Heading -->
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/40 text-rose-700 dark:text-rose-400 text-xs font-bold shadow-2xs">
                        <i class="fas fa-hand text-xs"></i>
                        <span>{{ __('errors.error_403_badge') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ __('errors.error_403_heading') }}
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
                        {{ $exception?->getMessage() ?: __('errors.error_403_description') }}
                    </p>
                </div>

                <!-- Active Session Inspector Card -->
                @if(auth()->check())
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3">
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <span class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-300">
                            <i class="fas fa-user-shield text-indigo-600 dark:text-indigo-400"></i>
                            <span>{{ __('errors.current_user') }}</span>
                        </span>
                        <span class="font-mono text-slate-600 dark:text-slate-400" dir="ltr">{{ auth()->user()->email }}</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                        <!-- Current User Role -->
                        <div class="bg-slate-50 dark:bg-slate-950/70 border border-slate-200 dark:border-slate-800 rounded-xl p-3 flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-xs text-slate-700 dark:text-slate-300 font-bold shrink-0">
                                {{ auth()->user()->initials }}
                            </div>
                            <div class="min-w-0">
                                <span class="text-[11px] text-slate-400 block font-medium">{{ __('errors.current_role') }}</span>
                                <span class="text-xs font-bold text-slate-800 dark:text-white truncate block">
                                    {{ auth()->user()->role->name ?? __('general.user') }}
                                    @if(auth()->user()->store)
                                        <span class="text-slate-500 font-normal">({{ auth()->user()->store->name }})</span>
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Required Permission -->
                        <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-800/40 rounded-xl p-3 flex items-center gap-3">
                            <div class="h-9 w-9 rounded-xl bg-rose-100 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 flex items-center justify-center text-sm shrink-0">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="text-[11px] text-rose-500 dark:text-rose-400/80 block font-medium">{{ __('errors.required_role') }}</span>
                                <span class="text-xs font-bold text-rose-700 dark:text-rose-300 truncate block">
                                    {{ __('errors.super_admin_only') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Interactive Action Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('dashboard.index') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-home text-xs"></i>
                        <span>{{ __('errors.back_to_dashboard') }}</span>
                    </a>

                    <button type="button" onclick="window.history.back()" 
                            class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm border border-slate-200 dark:border-slate-700/80 transition-all hover:scale-[1.02] active:scale-[0.98] shadow-2xs">
                        <i class="fas fa-arrow-right rtl:rotate-0 ltr:rotate-180 text-xs"></i>
                        <span>{{ __('errors.go_back') }}</span>
                    </button>

                    @if(auth()->check())
                    <a href="{{ route('dashboard.logout') }}" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-3.5 rounded-2xl text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors text-xs font-bold">
                        <i class="fas fa-right-from-bracket text-xs"></i>
                        <span>{{ __('errors.switch_account') }}</span>
                    </a>
                    @endif
                </div>

            </div>

        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-4 border-t border-slate-200/60 dark:border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
        <div class="flex items-center gap-2 font-medium">
            <i class="fas fa-shield-check text-emerald-500"></i>
            <span>{{ __('errors.tenant_isolation_active') }}</span>
        </div>

        <div class="flex items-center gap-4 text-[11px] text-slate-500 dark:text-slate-400" dir="ltr">
            <span>{{ __('errors.ip_address') }}: {{ request()->ip() }}</span>
            <span>•</span>
            <span>{{ date('Y') }} &copy; {{ config('app.name', 'Dokana') }}</span>
        </div>
    </footer>

    <!-- Interactive Particle Network Script (100% Local Vanilla JS) -->
    <script>
        (function() {
            const canvas = document.getElementById('particle-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height, particles = [];
            let mouse = { x: null, y: null, maxDist: 140 };

            function resize() {
                width = canvas.width = window.innerWidth;
                height = canvas.height = window.innerHeight;
            }

            window.addEventListener('resize', resize);
            resize();

            window.addEventListener('mousemove', function(e) {
                mouse.x = e.x;
                mouse.y = e.y;
            });

            window.addEventListener('mouseleave', function() {
                mouse.x = null;
                mouse.y = null;
            });

            const particleCount = Math.min(Math.floor(window.innerWidth / 20), 55);
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * width,
                    y: Math.random() * height,
                    vx: (Math.random() - 0.5) * 0.6,
                    vy: (Math.random() - 0.5) * 0.6,
                    radius: Math.random() * 2 + 1,
                    color: Math.random() > 0.4 ? 'rgba(244, 63, 94, ' : 'rgba(99, 102, 241, '
                });
            }

            function animate() {
                ctx.clearRect(0, 0, width, height);

                for (let i = 0; i < particles.length; i++) {
                    let p = particles[i];
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0 || p.x > width) p.vx *= -1;
                    if (p.y < 0 || p.y > height) p.vy *= -1;

                    // Mouse Interaction
                    if (mouse.x !== null && mouse.y !== null) {
                        let dx = mouse.x - p.x;
                        let dy = mouse.y - p.y;
                        let dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < mouse.maxDist) {
                            ctx.beginPath();
                            ctx.strokeStyle = p.color + (1 - dist / mouse.maxDist) * 0.35 + ')';
                            ctx.lineWidth = 0.8;
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(mouse.x, mouse.y);
                            ctx.stroke();
                        }
                    }

                    // Draw Node Point
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color + '0.5)';
                    ctx.fill();

                    // Connect neighboring particles
                    for (let j = i + 1; j < particles.length; j++) {
                        let p2 = particles[j];
                        let dx = p.x - p2.x;
                        let dy = p.y - p2.y;
                        let dist = Math.sqrt(dx * dx + dy * dy);

                        if (dist < 110) {
                            ctx.beginPath();
                            ctx.strokeStyle = p.color + (1 - dist / 110) * 0.12 + ')';
                            ctx.lineWidth = 0.6;
                            ctx.moveTo(p.x, p.y);
                            ctx.lineTo(p2.x, p2.y);
                            ctx.stroke();
                        }
                    }
                }

                requestAnimationFrame(animate);
            }

            animate();
        })();
    </script>
</body>
</html>
