<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('errors.error_500_title') }}</title>
    
    <link rel="shortcut icon" type="image/x-icon" href="{!! !empty(setting()->favicon) ? asset('uploads/settings/' . setting()->favicon) : asset('logo/dokkana-logo.png') !!}">

    <!-- 100% Local Fonts & Icons -->
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-400.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="{!! asset('assets/dashbaord/fonts/Tajawal-700.ttf') !!}" as="font" type="font/ttf" crossorigin>
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/dashbaord/vendors/fontawesome/css/all.min.css') !!}">
    
    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])

    <style>
        @keyframes radarSweep {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(1deg); }
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
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-rose-50/20 dark:from-slate-950 dark:via-slate-900 dark:to-rose-950/20 text-slate-800 dark:text-slate-100 flex flex-col justify-between relative overflow-x-hidden selection:bg-rose-500 selection:text-white font-sans antialiased">

    <!-- 1. Interactive Canvas Particles -->
    <canvas id="particle-canvas" class="absolute inset-0 w-full h-full pointer-events-none z-0 opacity-50"></canvas>

    <!-- 2. Ambient Lighting Glow -->
    <div class="absolute top-10 start-1/4 w-[450px] h-[450px] bg-rose-500/10 dark:bg-rose-600/15 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 end-1/4 w-[450px] h-[450px] bg-amber-500/10 dark:bg-amber-600/15 rounded-full blur-[120px] pointer-events-none"></div>

    <!-- 3. Giant Watermark in Background -->
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none z-0 opacity-[0.04] dark:opacity-[0.03]">
        <span class="text-[35vw] font-black tracking-tighter text-slate-900 dark:text-white font-mono leading-none">500</span>
    </div>

    <!-- Top Navigation Header Bar -->
    <header class="relative z-10 w-full max-w-7xl mx-auto px-6 py-5 flex items-center justify-between border-b border-slate-200/60 dark:border-slate-800/80">
        <!-- Brand Logo & Platform Name -->
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-rose-500 to-amber-500 p-0.5 shadow-md shadow-rose-500/20">
                <div class="h-full w-full bg-white dark:bg-slate-900 rounded-[14px] flex items-center justify-center">
                    <i class="fas fa-server text-rose-500 text-base"></i>
                </div>
            </div>
            <div>
                <span class="text-sm font-bold text-slate-900 dark:text-white tracking-wide block leading-tight">{{ __('errors.platform_name') }}</span>
                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">{{ __('errors.operations_hub') }}</span>
            </div>
        </div>

        <!-- Live Status Pill -->
        <div class="flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 shadow-2xs backdrop-blur-md">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
            </span>
            <span class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ __('errors.error_500_code') }}</span>
        </div>
    </header>

    <!-- Main Content Stage (Full Screen Immersive Grid) -->
    <main class="relative z-10 flex-1 flex items-center w-full max-w-7xl mx-auto px-6 py-8">
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            
            <!-- Left Side: Interactive 3D Server Diagnostics Core (5 Cols) -->
            <div class="lg:col-span-5 flex flex-col items-center justify-center relative order-2 lg:order-1">
                <div class="relative w-72 h-72 sm:w-88 sm:h-88 flex items-center justify-center anim-float">
                    
                    <!-- Outer Pulse Halo -->
                    <div class="absolute inset-0 rounded-full border border-rose-500/30 dark:border-rose-500/20 anim-pulse"></div>
                    <div class="absolute -inset-6 rounded-full border border-amber-500/20 dark:border-amber-500/10 anim-pulse" style="animation-delay: -1.8s;"></div>

                    <!-- Radar Grid Circles -->
                    <div class="absolute inset-4 rounded-full border border-slate-200 dark:border-slate-800 bg-white/70 dark:bg-slate-900/60 backdrop-blur-md shadow-xl"></div>
                    <div class="absolute inset-16 rounded-full border border-slate-300 dark:border-slate-800/80 border-dashed"></div>
                    <div class="absolute inset-28 rounded-full border border-rose-500/30 dark:border-rose-500/20"></div>

                    <!-- Radar Sweep Beam -->
                    <div class="absolute inset-4 rounded-full overflow-hidden pointer-events-none">
                        <div class="w-full h-full origin-center anim-radar bg-[conic-gradient(from_0deg,transparent_0_300deg,rgba(244,63,94,0.35)_360deg)]"></div>
                    </div>

                    <!-- Center Holographic Server Core -->
                    <div class="relative z-10 h-24 w-24 rounded-3xl bg-gradient-to-tr from-rose-500 via-rose-600 to-amber-500 p-0.5 shadow-xl shadow-rose-500/25">
                        <div class="h-full w-full bg-white dark:bg-slate-950 rounded-[22px] flex items-center justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-tr from-rose-50/50 to-amber-50/50 dark:from-rose-950/20 dark:to-amber-950/20"></div>
                            <svg class="h-11 w-11 text-rose-500 relative z-10 drop-shadow-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"/>
                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"/>
                                <line x1="6" y1="6" x2="6.01" y2="6"/>
                                <line x1="6" y1="18" x2="6.01" y2="18"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Floating Satellite Status Badges -->
                    <div class="absolute -top-2 start-2 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-bold text-amber-600 dark:text-amber-400 shadow-md flex items-center gap-1.5">
                        <i class="fas fa-triangle-exclamation text-[9px]"></i>
                        <span>{{ __('errors.exception_caught') }}</span>
                    </div>

                    <div class="absolute -bottom-2 end-2 px-3 py-1 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-[11px] font-bold text-rose-600 dark:text-rose-400 shadow-md flex items-center gap-1.5">
                        <i class="fas fa-bug text-[9px]"></i>
                        <span>{{ __('errors.log_reported') }}</span>
                    </div>

                </div>
            </div>

            <!-- Right Side: Content & Control Console (7 Cols) -->
            <div class="lg:col-span-7 space-y-6 order-1 lg:order-2 text-start">
                
                <!-- Badge & Main Heading -->
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/40 text-rose-700 dark:text-rose-400 text-xs font-bold shadow-2xs">
                        <i class="fas fa-circle-exclamation text-xs"></i>
                        <span>{{ __('errors.error_500_badge') }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ __('errors.error_500_heading') }}
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 leading-relaxed max-w-2xl">
                        {{ __('errors.error_500_description') }}
                    </p>
                </div>

                <!-- Server Incident Log Box -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs space-y-2">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-bold block">{{ __('errors.incident_reference_label') }}:</span>
                    <div class="font-mono text-xs text-rose-600 dark:text-rose-400 bg-slate-50 dark:bg-slate-950 px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-800 break-all select-all flex items-center justify-between gap-2" dir="ltr">
                        <span>ERR-{{ date('Ymd-His') }}-{{ rand(1000, 9999) }}</span>
                        <span class="text-[11px] text-slate-400 font-sans font-semibold">{{ __('errors.status_logged') }}</span>
                    </div>
                </div>

                <!-- Interactive Action Buttons -->
                <div class="flex flex-wrap items-center gap-3.5 pt-2">
                    <a href="{{ route('dashboard.index') }}" 
                       class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-lg shadow-indigo-600/25 transition-all hover:scale-[1.02] active:scale-[0.98]">
                        <i class="fas fa-home text-xs"></i>
                        <span>{{ __('errors.back_to_dashboard') }}</span>
                    </a>

                    <button type="button" onclick="window.location.reload()" 
                            class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 font-semibold text-xs sm:text-sm border border-slate-200 dark:border-slate-700/80 transition-all hover:scale-[1.02] active:scale-[0.98] shadow-2xs">
                        <i class="fas fa-redo-alt text-xs"></i>
                        <span>{{ __('errors.retry') }}</span>
                    </button>
                </div>

            </div>

        </div>
    </main>

    <!-- Bottom Footer Bar -->
    <footer class="relative z-10 w-full max-w-7xl mx-auto px-6 py-4 border-t border-slate-200/60 dark:border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
        <div class="flex items-center gap-2 font-medium">
            <i class="fas fa-shield-check text-emerald-500"></i>
            <span>{{ __('errors.tenant_protection_system') }}</span>
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
                    color: Math.random() > 0.4 ? 'rgba(244, 63, 94, ' : 'rgba(245, 158, 11, '
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
