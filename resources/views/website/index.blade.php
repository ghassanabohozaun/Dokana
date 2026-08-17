@extends('layouts.website.app')

@section('title', optional(setting())->getTranslation('site_name', app()->getLocale()) ? (optional(setting())->getTranslation('site_name', app()->getLocale()) . ' - ' . __('website.hero_badge')) : 'دكانة - ' . __('website.hero_badge'))

@section('content')
<div x-data="{ 
    mobileMenuOpen: false,
    activeWorkflowTab: 1,
    cockpitTab: 'debts',
    debtsAmount: 14820.0,
    collectionsAmount: 3450.0,
    suppliersAmount: 8900.0,
    toastMessage: '',
    showToast: false,
    liveTime: '',
    isDark: document.documentElement.classList.contains('dark'),
    
    init() {
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
    },
    
    updateTime() {
        const now = new Date();
        this.liveTime = now.toLocaleTimeString('{{ app()->getLocale() == 'ar' ? 'ar-EG' : 'en-US' }}', { hour: '2-digit', minute: '2-digit' });
    },
    
    triggerAction(type) {
        if (type === 'add_debt') {
            this.debtsAmount += 50.0;
            this.showNotification('✓ تم تسجيل دين جديد (+50.0 ₪) في دفتر الزبون');
        } else if (type === 'pay_debt') {
            this.debtsAmount = Math.max(0, this.debtsAmount - 100.0);
            this.collectionsAmount += 100.0;
            this.showNotification('✓ تم استلام دفعة (+100.0 ₪) وإرسال الكشف للواتساب');
        } else if (type === 'supplier_inv') {
            this.suppliersAmount += 350.0;
            this.showNotification('✓ تم تسجيل فاتورة مورد جديدة (+350.0 ₪)');
        }
    },
    
    showNotification(msg) {
        this.toastMessage = msg;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3500);
    },
    
    toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
            this.isDark = false;
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
            this.isDark = true;
        }
    }
}" class="min-h-screen bg-slate-50 dark:bg-[#070d19] text-gray-800 dark:text-gray-100 font-sans overflow-x-hidden selection:bg-emerald-500 selection:text-white">

    <!-- ========================================== -->
    <!-- 1. AMBIENT GLOW EFFECTS                   -->
    <!-- ========================================== -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 rtl:-right-40 ltr:-left-40 w-[600px] h-[600px] bg-gradient-to-br from-emerald-500/10 via-teal-500/5 to-transparent blur-[120px] rounded-full"></div>
        <div class="absolute top-[35%] rtl:-left-40 ltr:-right-40 w-[500px] h-[500px] bg-gradient-to-tr from-blue-500/10 via-indigo-500/5 to-transparent blur-[120px] rounded-full"></div>
        <div class="absolute bottom-10 rtl:-right-20 ltr:-left-20 w-[500px] h-[500px] bg-gradient-to-t from-emerald-500/10 via-emerald-400/5 to-transparent blur-[130px] rounded-full"></div>
    </div>

    <!-- ========================================== -->
    <!-- 2. FIXED GLASS NAVIGATION BAR             -->
    <!-- ========================================== -->
    <header class="fixed top-0 inset-x-0 z-50 backdrop-blur-xl bg-white/85 dark:bg-[#0c1322]/85 border-b border-gray-200/60 dark:border-slate-800/80 transition-colors shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Brand & Logo -->
                <a href="{{ route('website.home') }}" class="flex items-center gap-3.5 group">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 p-0.5 shadow-lg shadow-emerald-500/25 group-hover:scale-105 transition-transform flex items-center justify-center">
                        <div class="w-full h-full bg-white dark:bg-slate-900 rounded-[14px] flex items-center justify-center p-1.5 overflow-hidden">
                            @if(optional(setting())->logo)
                                <img src="{{ asset('uploads/settings/' . optional(setting())->logo) }}" alt="Logo" class="max-h-8 max-w-8 object-contain">
                            @else
                                <i class="ph-fill ph-storefront text-2xl text-emerald-500"></i>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-xl font-black text-gray-900 dark:text-white tracking-tight block">
                            {{ optional(setting())->getTranslation('site_name', app()->getLocale()) ?: 'دكانة' }}
                        </span>
                        <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ __('website.casher_portal') }}</span>
                        </span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-8 text-sm font-bold text-gray-600 dark:text-gray-300">
                    <a href="#features" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">{{ __('website.features') }}</a>
                    <a href="#workflow" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">{{ __('website.workflow') }}</a>
                    <a href="#stats" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">{{ __('website.stats') }}</a>
                </nav>

                <!-- Action Controls (Language, Theme, CTA) -->
                <div class="hidden sm:flex items-center gap-3">
                    @php
                        $currentLocale = app()->getLocale();
                        $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                        $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
                    @endphp
                    <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" 
                       class="p-2.5 rounded-xl bg-gray-100 dark:bg-slate-800/80 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-bold text-xs flex items-center gap-1.5 transition-all active:scale-95 border border-gray-200/50 dark:border-slate-700/50"
                       title="{{ $targetNative }}">
                        <i class="ph-bold ph-globe text-base text-emerald-500"></i>
                        <span>{{ $targetNative }}</span>
                    </a>

                    <button type="button" 
                            @click="toggleTheme()" 
                            class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-slate-800/80 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 flex items-center justify-center transition-all active:scale-95 border border-gray-200/50 dark:border-slate-700/50"
                            title="{{ __('dashboard.theme') ?? 'المظهر' }}">
                        <template x-if="!isDark">
                            <i class="ph-fill ph-moon text-lg text-slate-700"></i>
                        </template>
                        <template x-if="isDark">
                            <i class="ph-fill ph-sun text-lg text-amber-400"></i>
                        </template>
                    </button>

                    @if(Route::has('dashboard.get.login'))
                    <a href="{{ route('dashboard.get.login') }}" 
                       class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-slate-700 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-700 dark:text-gray-300 font-bold text-xs transition-all active:scale-95">
                        {{ __('website.admin_dashboard') }}
                    </a>
                    @endif

                    <a href="{{ route('website.casher.login') }}" 
                       class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-200 active:scale-95 flex items-center gap-2">
                        <i class="ph-fill ph-storefront text-base"></i>
                        <span>{{ __('website.login_casher') }}</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="md:hidden w-11 h-11 rounded-xl bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-700 dark:text-gray-200 focus:outline-none">
                    <i class="ph-bold text-2xl" :class="mobileMenuOpen ? 'ph-x' : 'ph-list'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Dropdown Menu -->
        <div x-show="mobileMenuOpen" 
             x-transition 
             @click.away="mobileMenuOpen = false" 
             class="md:hidden border-t border-gray-200 dark:border-slate-800 bg-white/95 dark:bg-[#0c1322]/95 backdrop-blur-xl px-4 pt-4 pb-6 space-y-3" 
             style="display: none;">
            <a href="#features" @click="mobileMenuOpen = false" class="block py-2 font-bold text-gray-700 dark:text-gray-200 hover:text-emerald-500">{{ __('website.features') }}</a>
            <a href="#workflow" @click="mobileMenuOpen = false" class="block py-2 font-bold text-gray-700 dark:text-gray-200 hover:text-emerald-500">{{ __('website.workflow') }}</a>
            <a href="#stats" @click="mobileMenuOpen = false" class="block py-2 font-bold text-gray-700 dark:text-gray-200 hover:text-emerald-500">{{ __('website.stats') }}</a>
            
            <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex flex-col gap-2.5">
                <a href="{{ route('website.casher.login') }}" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl text-center shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                    <i class="ph-fill ph-storefront"></i>
                    <span>{{ __('website.login_casher') }}</span>
                </a>
                
                <div class="flex items-center gap-2 pt-1">
                    <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" class="flex-1 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-center font-bold text-xs text-gray-700 dark:text-gray-300 flex items-center justify-center gap-1.5">
                        <i class="ph-bold ph-globe text-emerald-500"></i>
                        <span>{{ $targetNative }}</span>
                    </a>
                    <button type="button" @click="toggleTheme()" class="flex-1 py-2.5 rounded-xl bg-gray-100 dark:bg-slate-800 text-center font-bold text-xs text-gray-700 dark:text-gray-300 flex items-center justify-center gap-1.5">
                        <template x-if="!isDark">
                            <i class="ph-fill ph-moon text-slate-700"></i>
                        </template>
                        <template x-if="isDark">
                            <i class="ph-fill ph-sun text-amber-400"></i>
                        </template>
                        <span>{{ __('dashboard.theme') ?? 'المظهر' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- ======================================================== -->
    <!-- 3. HERO SECTION WITH LUXURY INTERACTIVE COCKPIT TERMINAL -->
    <!-- ======================================================== -->
    <section class="relative pt-28 pb-20 sm:pt-36 sm:pb-28 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                
                <!-- Left/Right Text Column (Crisp, Elegant & Balanced Size) -->
                <div class="lg:col-span-5 text-center lg:rtl:text-right lg:ltr:text-left space-y-6">
                    
                    <!-- Pulsing Badge -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 dark:bg-emerald-500/15 border border-emerald-500/20 text-emerald-700 dark:text-emerald-400 text-xs font-black shadow-sm">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>{{ __('website.hero_badge') }}</span>
                    </div>

                    <!-- Balanced Refined Headline (Scaled Down for Elegance) -->
                    <h1 class="text-2xl sm:text-3xl lg:text-[2.65rem] font-extrabold text-gray-900 dark:text-white leading-[1.3] tracking-tight">
                        <span>{{ __('website.hero_title_1') }}</span><br>
                        <span class="bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 bg-clip-text text-transparent">
                            {{ __('website.hero_title_2') }}
                        </span>
                    </h1>

                    <!-- Short & Punchy Subtitle -->
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 font-medium leading-relaxed max-w-lg mx-auto lg:mx-0">
                        {{ __('website.hero_subtitle') }}
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                        <a href="{{ route('website.casher.login') }}" 
                           class="w-full sm:w-auto px-7 py-3.5 rounded-2xl bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-sm sm:text-base shadow-xl shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center justify-center gap-2.5 group cursor-pointer">
                            <span>{{ __('website.start_cashier_now') }}</span>
                            <i class="ph-bold ph-arrow-left rtl:rotate-0 ltr:rotate-180 text-lg group-hover:translate-x-[-4px] rtl:group-hover:translate-x-[-4px] ltr:group-hover:translate-x-[4px] transition-transform"></i>
                        </a>

                        <a href="#features" 
                           class="w-full sm:w-auto px-5 py-3.5 rounded-2xl bg-white/80 dark:bg-slate-800/80 hover:bg-white dark:hover:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-800 dark:text-gray-200 font-bold text-sm shadow-sm hover:shadow-md transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                            <i class="ph-bold ph-squares-four text-base text-emerald-500"></i>
                            <span>{{ __('website.explore_features') }}</span>
                        </a>
                    </div>

                    <!-- Clean 2 Trust Points -->
                    <div class="pt-3 flex items-center justify-center lg:justify-start gap-5 text-xs font-bold text-gray-500 dark:text-gray-400">
                        <div class="flex items-center gap-1.5">
                            <i class="ph-fill ph-shield-check text-emerald-500 text-base"></i>
                            <span>{{ __('website.mockup_secured_cloud') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <i class="ph-fill ph-lightning text-amber-500 text-base"></i>
                            <span>{{ __('website.mockup_offline_ready') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right/Left ULTRA-PREMIUM INTERACTIVE TERMINAL COCKPIT -->
                <div class="lg:col-span-7 relative">
                    
                    <!-- Soft Static Glow Halo behind Cockpit -->
                    <div class="absolute -inset-2 bg-gradient-to-r from-emerald-500/15 via-teal-500/15 to-blue-500/15 rounded-[3rem] blur-2xl"></div>

                    <!-- Floating Orbit Badge 1 (Top Left/Right) -->
                    <div class="absolute -top-5 rtl:-left-3 ltr:-right-3 z-30 hidden sm:flex items-center gap-2 bg-white dark:bg-slate-900 px-3.5 py-2 rounded-2xl shadow-xl border border-emerald-500/30 text-[11px] font-bold text-gray-800 dark:text-gray-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-black">⚡ استجابة لحظية 0.05s</span>
                    </div>

                    <!-- Toast Notification inside Cockpit -->
                    <div x-show="showToast" 
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="-translate-y-4 opacity-0 scale-95"
                         x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-200 transform"
                         x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                         x-transition:leave-end="-translate-y-4 opacity-0 scale-95"
                         class="absolute top-16 inset-x-8 z-40 bg-emerald-500 text-white font-bold text-xs py-2.5 px-4 rounded-2xl shadow-2xl flex items-center justify-between gap-2"
                         style="display: none;">
                        <span x-text="toastMessage"></span>
                        <i class="ph-bold ph-check-circle text-base"></i>
                    </div>

                    <!-- THE COCKPIT DEVICE FRAME (Titanium Glass POS Cockpit - 100% Rock Solid) -->
                    <div class="relative rounded-[2.5rem] bg-gradient-to-b from-slate-900/90 to-slate-950/95 p-1 sm:p-1.5 shadow-[0_25px_60px_-15px_rgba(16,185,129,0.2)] border border-slate-700/80 overflow-hidden">
                        
                        <!-- Internal Screen -->
                        <div class="rounded-[2.2rem] bg-white dark:bg-[#0c1322] p-5 sm:p-6 text-gray-800 dark:text-gray-100 relative overflow-hidden">
                            
                            <!-- Real Laser Scanner Bar & Beam -->
                            <div class="pointer-events-none absolute inset-0 z-30 overflow-hidden rounded-[2.2rem]">
                                <div class="laser-scanner-beam">
                                    <div class="laser-line"></div>
                                    <div class="laser-glow"></div>
                                </div>
                            </div>

                            <!-- Terminal Dynamic Header Island -->
                            <div class="flex items-center justify-between pb-3.5 mb-4 border-b border-gray-100 dark:border-slate-800/80 text-xs">
                                
                                <!-- Store Profile & Dynamic Status -->
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-500/15 text-emerald-500 flex items-center justify-center font-bold">
                                        <i class="ph-fill ph-storefront text-base"></i>
                                    </div>
                                    <div>
                                        <p class="font-black text-xs text-gray-900 dark:text-white">نقطة الكاشير الذكية</p>
                                        <p class="text-[10px] text-emerald-600 dark:text-emerald-400 font-bold flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>سحابي متصل</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Live Digital Clock & Cashier Badge -->
                                <div class="flex items-center gap-2">
                                    <div class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-[11px] font-mono font-bold text-gray-600 dark:text-gray-300" x-text="liveTime"></div>
                                    <div class="hidden sm:flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold border border-emerald-500/20">
                                        <i class="ph-fill ph-user-circle text-xs"></i>
                                        <span>كاشير 01</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Interactive Dashboard Mode Switcher (Cockpit Tabs) -->
                            <div class="grid grid-cols-3 gap-2 mb-4 p-1 bg-gray-100/90 dark:bg-slate-900/90 rounded-2xl text-xs font-bold">
                                <button type="button" 
                                        @click="cockpitTab = 'debts'"
                                        :class="cockpitTab === 'debts' ? 'bg-white dark:bg-slate-800 text-red-500 dark:text-red-400 shadow-sm' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200'"
                                        class="py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="ph-bold ph-receipt text-xs"></i>
                                    <span>ديون الزبائن</span>
                                </button>
                                
                                <button type="button" 
                                        @click="cockpitTab = 'collections'"
                                        :class="cockpitTab === 'collections' ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 shadow-sm' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200'"
                                        class="py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="ph-bold ph-hand-coins text-xs"></i>
                                    <span>التحصيلات</span>
                                </button>

                                <button type="button" 
                                        @click="cockpitTab = 'suppliers'"
                                        :class="cockpitTab === 'suppliers' ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-gray-500 hover:text-gray-800 dark:hover:text-gray-200'"
                                        class="py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                    <i class="ph-bold ph-truck text-xs"></i>
                                    <span>الموردين</span>
                                </button>
                            </div>

                            <!-- Dynamic Live Metrics Cards (Switch with Tab) -->
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                
                                <!-- Metric 1 -->
                                <div class="p-3.5 rounded-2xl border transition-all"
                                     :class="{
                                         'bg-red-50/70 dark:bg-red-950/20 border-red-200/60 dark:border-red-900/40': cockpitTab === 'debts',
                                         'bg-emerald-50/70 dark:bg-emerald-950/20 border-emerald-200/60 dark:border-emerald-900/40': cockpitTab === 'collections',
                                         'bg-blue-50/70 dark:bg-blue-950/20 border-blue-200/60 dark:border-blue-900/40': cockpitTab === 'suppliers'
                                     }">
                                    <div class="flex items-center justify-between text-[11px] font-bold mb-1"
                                         :class="{
                                             'text-red-600 dark:text-red-400': cockpitTab === 'debts',
                                             'text-emerald-600 dark:text-emerald-400': cockpitTab === 'collections',
                                             'text-blue-600 dark:text-blue-400': cockpitTab === 'suppliers'
                                         }">
                                        <span x-text="cockpitTab === 'debts' ? 'إجمالي ديون السوق' : (cockpitTab === 'collections' ? 'تحصيلات اليوم' : 'ذمم الموردين')"></span>
                                        <i class="ph-bold text-sm" :class="cockpitTab === 'debts' ? 'ph-trend-down' : (cockpitTab === 'collections' ? 'ph-trend-up' : 'ph-truck')"></i>
                                    </div>
                                    <div class="text-xl sm:text-2xl font-black animate-pop-number" dir="ltr"
                                         :class="{
                                             'text-red-600 dark:text-red-400': cockpitTab === 'debts',
                                             'text-emerald-600 dark:text-emerald-400': cockpitTab === 'collections',
                                             'text-blue-600 dark:text-blue-400': cockpitTab === 'suppliers'
                                         }">
                                        <span x-text="cockpitTab === 'debts' ? debtsAmount.toFixed(1) : (cockpitTab === 'collections' ? collectionsAmount.toFixed(1) : suppliersAmount.toFixed(1))"></span>
                                        <span class="text-xs font-normal">₪</span>
                                    </div>
                                </div>

                                <!-- Metric 2 (Cash & Treasury) -->
                                <div class="bg-gray-50/80 dark:bg-slate-900/80 p-3.5 rounded-2xl border border-gray-200/60 dark:border-slate-800">
                                    <div class="flex items-center justify-between text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1">
                                        <span>رصيد الخزينة (الكاش)</span>
                                        <i class="ph-bold ph-vault text-sm text-amber-500"></i>
                                    </div>
                                    <div class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white" dir="ltr">
                                        <span>4,120.0</span> <span class="text-xs font-normal text-gray-400">₪</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Simulated List -->
                            <div class="space-y-2 mb-4">
                                <div class="text-[11px] font-bold text-gray-400 flex items-center justify-between">
                                    <span>سجل العمليات المباشر</span>
                                    <span class="text-emerald-500 text-[10px] font-black flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                                        <span>تحديث حي</span>
                                    </span>
                                </div>

                                <!-- Dynamic Item 1 -->
                                <div class="bg-gray-50 dark:bg-[#121a2c] p-2.5 rounded-2xl border border-gray-100 dark:border-slate-800 flex items-center justify-between hover:border-emerald-500/30 transition-colors">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center font-bold text-xs">
                                            <i class="ph-bold ph-arrow-up-right"></i>
                                        </div>
                                        <div>
                                            <p class="font-black text-xs text-gray-900 dark:text-white">أحمد محمود الخالدي</p>
                                            <p class="text-[10px] text-gray-400">سكر وأرز • قبل دقيقة</p>
                                        </div>
                                    </div>
                                    <div class="text-xs font-black text-red-500" dir="ltr">+45.0 ₪</div>
                                </div>

                                <!-- Dynamic Item 2 -->
                                <div class="bg-gray-50 dark:bg-[#121a2c] p-2.5 rounded-2xl border border-gray-100 dark:border-slate-800 flex items-center justify-between hover:border-emerald-500/30 transition-colors">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-xs">
                                            <i class="ph-bold ph-arrow-down-left"></i>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <p class="font-black text-xs text-gray-900 dark:text-white">خليل إبراهيم النجار</p>
                                                <span class="text-[9px] bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 px-1.5 py-0.2 rounded-full font-bold flex items-center gap-0.5">
                                                    <i class="ph-fill ph-whatsapp-logo text-[10px]"></i> واتساب
                                                </span>
                                            </div>
                                            <p class="text-[10px] text-gray-400">سداد دفعة (جوال باي)</p>
                                        </div>
                                    </div>
                                    <div class="text-xs font-black text-emerald-500" dir="ltr">-200.0 ₪</div>
                                </div>
                            </div>

                            <!-- Interactive Live Simulator Action Keypad (جرب النظام بنفسك) -->
                            <div class="pt-3 border-t border-gray-100 dark:border-slate-800/80">
                                <div class="flex items-center justify-between text-[11px] font-bold text-gray-400 mb-2">
                                    <span>جرب إجراء حركة تفاعلية حية:</span>
                                    <span class="text-emerald-500 text-[10px]">اضغط للتجربة 👆</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <button type="button" 
                                            @click="triggerAction('add_debt')"
                                            class="py-2 px-2 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-950/40 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 font-black text-[11px] transition-all active:scale-95 flex items-center justify-center gap-1 border border-red-200/50 dark:border-red-900/30 cursor-pointer">
                                        <i class="ph-bold ph-plus-circle text-xs"></i>
                                        <span>دين (+50 ₪)</span>
                                    </button>

                                    <button type="button" 
                                            @click="triggerAction('pay_debt')"
                                            class="py-2 px-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 font-black text-[11px] transition-all active:scale-95 flex items-center justify-center gap-1 border border-emerald-200/50 dark:border-emerald-900/30 cursor-pointer">
                                        <i class="ph-bold ph-hand-coins text-xs"></i>
                                        <span>دفعة (-100 ₪)</span>
                                    </button>

                                    <button type="button" 
                                            @click="triggerAction('supplier_inv')"
                                            class="py-2 px-2 rounded-xl bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 font-black text-[11px] transition-all active:scale-95 flex items-center justify-center gap-1 border border-blue-200/50 dark:border-blue-900/30 cursor-pointer">
                                        <i class="ph-bold ph-truck text-xs"></i>
                                        <span>فاتورة (+350 ₪)</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 4. CORE MODULES & ECOSYSTEM               -->
    <!-- ========================================== -->
    <section id="features" class="py-20 bg-white/50 dark:bg-[#09101d]/50 border-y border-gray-200/50 dark:border-slate-800/50 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-black border border-emerald-500/20">
                    <i class="ph-bold ph-circles-three-plus"></i>
                    <span>{{ __('website.ecosystem_badge') }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                    {{ __('website.ecosystem_title') }}
                </h2>
                <p class="text-base text-gray-600 dark:text-gray-300 font-medium">
                    {{ __('website.ecosystem_subtitle') }}
                </p>
            </div>

            <!-- Features 6-Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card 1: Debts & Collections -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-emerald-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-notebook"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_1_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_1_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <i class="ph-bold ph-check"></i>
                        <span>مشاركة فورية لكشوف الحساب عبر WhatsApp</span>
                    </div>
                </div>

                <!-- Card 2: Suppliers & Invoices -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-blue-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-truck"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_2_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_2_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400">
                        <i class="ph-bold ph-check"></i>
                        <span>فواتير آجلة ونقدية وسندات صرف دقيقة</span>
                    </div>
                </div>

                <!-- Card 3: Treasury & Banks -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-amber-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-vault"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_3_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_3_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-amber-600 dark:text-amber-400">
                        <i class="ph-bold ph-check"></i>
                        <span>تتبع الكاش وجوال باي وحسابات البنوك</span>
                    </div>
                </div>

                <!-- Card 4: Dashboard & Analytics -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-purple-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-chart-line-up"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_4_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_4_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-purple-600 dark:text-purple-400">
                        <i class="ph-bold ph-check"></i>
                        <span>مراقبة أعمار الديون ومؤشرات الأداء اللحظية</span>
                    </div>
                </div>

                <!-- Card 5: Multi-Cashier & Roles -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-teal-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 dark:bg-teal-950/40 text-teal-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-users-three"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_5_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_5_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-teal-600 dark:text-teal-400">
                        <i class="ph-bold ph-check"></i>
                        <span>تسجيل اسم الكاشير وصلاحيات محددة بدقة</span>
                    </div>
                </div>

                <!-- Card 6: Universal & Responsive -->
                <div class="bg-white dark:bg-[#121b2c] p-8 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-emerald-500/30 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone ph-device-mobile-camera"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white mb-3">{{ __('website.feature_6_title') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed font-medium">{{ __('website.feature_6_desc') }}</p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                        <i class="ph-bold ph-check"></i>
                        <span>دعم الأرقام العربية والإنجليزية وسلاسة تامة</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 5. INTERACTIVE DAILY WORKFLOW TABS        -->
    <!-- ========================================== -->
    <section id="workflow" class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="text-center max-w-3xl mx-auto mb-14 space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 text-xs font-black border border-blue-500/20">
                    <i class="ph-bold ph-arrows-clockwise"></i>
                    <span>{{ __('website.workflow_badge') }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">
                    {{ __('website.workflow_title') }}
                </h2>
            </div>

            <!-- Tabs Container -->
            <div class="bg-white/80 dark:bg-[#10192a]/80 backdrop-blur-xl rounded-[2.5rem] p-6 sm:p-10 border border-gray-200/60 dark:border-slate-800 shadow-xl max-w-5xl mx-auto">
                
                <!-- Tab Selector Buttons -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-8 p-1.5 bg-gray-100/80 dark:bg-slate-900/80 rounded-2xl">
                    <button type="button" 
                            @click="activeWorkflowTab = 1" 
                            :class="activeWorkflowTab === 1 ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
                            class="py-3 px-4 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-user-plus"></i>
                        <span>{{ __('website.workflow_tab_1') }}</span>
                    </button>

                    <button type="button" 
                            @click="activeWorkflowTab = 2" 
                            :class="activeWorkflowTab === 2 ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
                            class="py-3 px-4 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-hand-coins"></i>
                        <span>{{ __('website.workflow_tab_2') }}</span>
                    </button>

                    <button type="button" 
                            @click="activeWorkflowTab = 3" 
                            :class="activeWorkflowTab === 3 ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
                            class="py-3 px-4 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-truck"></i>
                        <span>{{ __('website.workflow_tab_3') }}</span>
                    </button>

                    <button type="button" 
                            @click="activeWorkflowTab = 4" 
                            :class="activeWorkflowTab === 4 ? 'bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 shadow-md' : 'text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200'"
                            class="py-3 px-4 rounded-xl font-bold text-xs sm:text-sm transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-chart-pie-slice"></i>
                        <span>{{ __('website.workflow_tab_4') }}</span>
                    </button>
                </div>

                <!-- Tab Content 1 -->
                <div x-show="activeWorkflowTab === 1" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-7 space-y-4">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('website.workflow_tab_1') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 font-medium leading-relaxed">{{ __('website.workflow_tab_1_desc') }}</p>
                            <ul class="space-y-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>بحث فوري عن الزبون بالاسم أو رقم الجوال</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>إضافة سريعة لملاحظات وأصناف الدين (سكر، زيت، إلخ)</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>تحديث فوري لرصيد الزبون دون الحاجة لإعادة التحميل</span></li>
                            </ul>
                        </div>
                        <div class="md:col-span-5 bg-emerald-50/60 dark:bg-emerald-950/20 p-6 rounded-3xl border border-emerald-500/20 text-center">
                            <i class="ph-duotone ph-receipt text-6xl text-emerald-500 mb-3 inline-block"></i>
                            <p class="font-black text-emerald-700 dark:text-emerald-300 text-sm">تسجيل الدين بأقل من ثانيتين</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Content 2 -->
                <div x-show="activeWorkflowTab === 2" x-transition class="space-y-6" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-7 space-y-4">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('website.workflow_tab_2') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 font-medium leading-relaxed">{{ __('website.workflow_tab_2_desc') }}</p>
                            <ul class="space-y-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>تحديد طريقة الدفع (كاش، جوال باي، تحويل بنكي)</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>توليد رابط كشف حساب فوري ومشاركته على WhatsApp</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>حساب الرصيد المتبقي بدقة متناهية</span></li>
                            </ul>
                        </div>
                        <div class="md:col-span-5 bg-blue-50/60 dark:bg-blue-950/20 p-6 rounded-3xl border border-blue-500/20 text-center">
                            <i class="ph-duotone ph-whatsapp-logo text-6xl text-emerald-500 mb-3 inline-block"></i>
                            <p class="font-black text-blue-700 dark:text-blue-300 text-sm">إرسال الكشف بضغطة زر</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Content 3 -->
                <div x-show="activeWorkflowTab === 3" x-transition class="space-y-6" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-7 space-y-4">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('website.workflow_tab_3') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 font-medium leading-relaxed">{{ __('website.workflow_tab_3_desc') }}</p>
                            <ul class="space-y-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>تسجيل فواتير المشتريات النقدية والآجلة</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>تتبع دفعات المورد والمبالغ المتبقية في ذمة المحل</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>سندات صرف آلية مرتبطة بالخزينة</span></li>
                            </ul>
                        </div>
                        <div class="md:col-span-5 bg-amber-50/60 dark:bg-amber-950/20 p-6 rounded-3xl border border-amber-500/20 text-center">
                            <i class="ph-duotone ph-file-text text-6xl text-amber-500 mb-3 inline-block"></i>
                            <p class="font-black text-amber-700 dark:text-amber-300 text-sm">أرشفة وحفظ فواتير الموردين</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Content 4 -->
                <div x-show="activeWorkflowTab === 4" x-transition class="space-y-6" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                        <div class="md:col-span-7 space-y-4">
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white">{{ __('website.workflow_tab_4') }}</h3>
                            <p class="text-gray-600 dark:text-gray-300 font-medium leading-relaxed">{{ __('website.workflow_tab_4_desc') }}</p>
                            <ul class="space-y-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>تقرير تحصيلات اليوم الفورية</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>مطابقة رصيد الخزينة مع الكاش الفعلي</span></li>
                                <li class="flex items-center gap-2.5"><i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i> <span>إجمالي ديون السوق في الوقت الفعلي</span></li>
                            </ul>
                        </div>
                        <div class="md:col-span-5 bg-purple-50/60 dark:bg-purple-950/20 p-6 rounded-3xl border border-purple-500/20 text-center">
                            <i class="ph-duotone ph-vault text-6xl text-purple-500 mb-3 inline-block"></i>
                            <p class="font-black text-purple-700 dark:text-purple-300 text-sm">جرد فوري للصندوق</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 6. KEY STATS & METRICS SECTION            -->
    <!-- ========================================== -->
    <section id="stats" class="py-16 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white relative overflow-hidden z-10 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                
                <div class="space-y-2">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight" dir="ltr">{{ __('website.stat_1_val') }}</div>
                    <div class="text-xs sm:text-sm font-bold text-emerald-100">{{ __('website.stat_1_lbl') }}</div>
                </div>

                <div class="space-y-2">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight" dir="ltr">{{ __('website.stat_2_val') }}</div>
                    <div class="text-xs sm:text-sm font-bold text-emerald-100">{{ __('website.stat_2_lbl') }}</div>
                </div>

                <div class="space-y-2">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight" dir="ltr">{{ __('website.stat_3_val') }}</div>
                    <div class="text-xs sm:text-sm font-bold text-emerald-100">{{ __('website.stat_3_lbl') }}</div>
                </div>

                <div class="space-y-2">
                    <div class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight" dir="ltr">{{ __('website.stat_4_val') }}</div>
                    <div class="text-xs sm:text-sm font-bold text-emerald-100">{{ __('website.stat_4_lbl') }}</div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 7. CALL TO ACTION BANNER                  -->
    <!-- ========================================== -->
    <section class="py-20 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 via-[#0c1629] to-emerald-950 p-8 sm:p-12 md:p-16 rounded-[3rem] text-center text-white relative overflow-hidden shadow-2xl border border-emerald-500/30">
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-emerald-500/20 blur-3xl rounded-full pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-teal-500/20 blur-3xl rounded-full pointer-events-none"></div>

                <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                    <div class="w-16 h-16 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mx-auto flex items-center justify-center text-3xl">
                        <i class="ph-fill ph-storefront"></i>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight">{{ __('website.cta_title') }}</h2>
                    <p class="text-gray-300 font-medium text-sm sm:text-base leading-relaxed">{{ __('website.cta_subtitle') }}</p>
                    <div class="pt-4">
                        <a href="{{ route('website.casher.login') }}" 
                           class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-base shadow-xl shadow-emerald-500/30 hover:scale-105 active:scale-95 transition-all">
                            <span>{{ __('website.cta_button') }}</span>
                            <i class="ph-bold ph-arrow-left rtl:rotate-0 ltr:rotate-180 text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- 8. FOOTER                                  -->
    <!-- ========================================== -->
    <footer class="border-t border-gray-200/60 dark:border-slate-800 bg-white dark:bg-[#070c17] py-12 relative z-10 text-xs text-gray-500 dark:text-gray-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-center md:rtl:text-right md:ltr:text-left">
                
                <!-- Brand Info -->
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-xl font-bold">
                        <i class="ph-fill ph-storefront"></i>
                    </div>
                    <div>
                        <span class="font-black text-gray-900 dark:text-white text-sm block">
                            {{ optional(setting())->getTranslation('site_name', app()->getLocale()) ?: 'دكانة' }}
                        </span>
                        <span>{{ __('website.footer_about') }}</span>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="font-medium">
                    &copy; {{ date('Y') }} {{ optional(setting())->getTranslation('site_name', app()->getLocale()) ?: 'دكانة' }}. {{ __('website.all_rights_reserved') }}.
                </div>
            </div>
        </div>
    </footer>

</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/website/vendor/alpine/alpine.min.js') }}"></script>
@endpush
