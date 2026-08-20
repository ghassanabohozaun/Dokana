@extends('layouts.dashboard.auth')

@section('title')
    {!! __('auth.forget_password') !!}
@endsection

@section('content')
<div class="h-screen w-screen grid grid-cols-1 lg:grid-cols-12 overflow-hidden bg-slate-50 dark:bg-slate-950 relative selection:bg-indigo-500 selection:text-white"
    x-data="{ isSubmitting: false }">

    <!-- Mobile Ambient Floating Glows -->
    <div class="lg:hidden absolute -top-20 -start-20 w-80 h-80 bg-indigo-500/20 dark:bg-indigo-600/15 rounded-full blur-3xl pointer-events-none animate-float-slow"></div>
    <div class="lg:hidden absolute -bottom-20 -end-20 w-80 h-80 bg-blue-500/20 dark:bg-blue-600/15 rounded-full blur-3xl pointer-events-none animate-float-slow [animation-delay:4s]"></div>
    <div class="lg:hidden absolute top-1/2 start-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/10 dark:bg-purple-600/10 rounded-full blur-3xl pointer-events-none animate-pulse [animation-duration:6s]"></div>

    <!-- 1. Hero / SaaS Visual Side (Desktop/Laptop Only) -->
    <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 h-screen bg-gradient-to-br from-indigo-700 via-indigo-800 to-slate-950 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950 text-white p-10 xl:p-16 flex-col relative overflow-hidden select-none border-e border-white/10 dark:border-slate-800">
        <!-- Ambient Decorative Elements -->
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>
        <div class="absolute -top-32 -start-32 w-96 h-96 bg-blue-500/20 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -end-32 w-96 h-96 bg-indigo-500/25 dark:bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Brand Identity -->
        <div class="shrink-0 relative z-10 flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 border border-white/20 text-white font-black text-lg shadow-lg">
                <i class="fas fa-layer-group text-indigo-300"></i>
            </div>
            <div>
                <span class="block font-black text-lg tracking-tight leading-none text-white">DOKANA</span>
                <span class="text-[11px] font-bold text-indigo-200/80 tracking-wider">ENTERPRISE SAAS</span>
            </div>
        </div>

        <!-- Center Content -->
        <div class="flex-1 flex flex-col justify-center relative z-10 max-w-xl py-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-indigo-100 shadow-xs mb-5 w-fit">
                <i class="fas fa-key text-amber-300"></i>
                <span>{{ __('auth.forget_password') }}</span>
            </div>

            <h1 class="text-2xl xl:text-4xl font-black tracking-tight leading-snug mb-3 text-white">
                {{ __('auth.saas_title') }}
            </h1>
            <p class="text-indigo-100/85 text-sm xl:text-base leading-relaxed mb-8">
                {{ __('auth.saas_desc') }}
            </p>

            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-white/[0.07] border border-white/15 hover:bg-white/[0.12] transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/30 text-indigo-200 text-sm mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xs xl:text-sm font-bold text-white mb-1">{{ __('auth.forget_password') }}</h3>
                    <p class="text-[11px] text-indigo-100/75 leading-normal">{{ __('auth.enter_you_email_to_reset_password') }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-white/[0.07] border border-white/15 hover:bg-white/[0.12] transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/30 text-emerald-200 text-sm mb-3">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3 class="text-xs xl:text-sm font-bold text-white mb-1">{{ __('auth.saas_feature_2_title') }}</h3>
                    <p class="text-[11px] text-indigo-100/75 leading-normal">{{ __('auth.saas_feature_2_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Bottom Platform Info -->
        <div class="shrink-0 relative z-10 flex items-center justify-between text-xs text-indigo-200/60 pt-4 border-t border-white/10">
            <span>{{ __('auth.saas_portal_title') }}</span>
            <span>Dokana Platform &copy; {{ date('Y') }}</span>
        </div>
    </div>

    <!-- 2. Form Side (Desktop Clean Panel & Mobile Snake Glowing Card) -->
    <div class="lg:col-span-6 xl:col-span-5 h-screen flex flex-col justify-between p-4 sm:p-8 xl:p-14 lg:bg-white lg:dark:bg-slate-900 relative overflow-y-auto z-10">

        <!-- Top Desktop Controls (Desktop Only) -->
        <div class="hidden lg:flex shrink-0 items-center justify-end w-full gap-2">
            @php
                $currentLocale = Lang();
                $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
                $flagPath = $targetLocale == 'ar'
                    ? asset('assets/dashbaord/media/svg/flags/العربية.svg')
                    : asset('assets/dashbaord/media/svg/flags/English.svg');
            @endphp
            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700/80 text-xs font-semibold hover:bg-slate-200/70 dark:hover:bg-slate-700 transition-colors shadow-xs"
                title="{{ $targetNative }}">
                <img src="{!! $flagPath !!}" class="h-3.5 w-3.5 rounded-full object-cover" alt="{{ $targetNative }}">
                <span class="font-bold text-xs">{{ $targetNative }}</span>
            </a>

            <button type="button" data-theme-toggle
                class="flex h-8 w-8 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-amber-400 border border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-200/70 dark:hover:bg-slate-700 transition-colors shadow-xs"
                title="Toggle Dark / Light Mode" aria-label="Toggle Dark Mode">
                <i class="fas fa-moon dark:hidden text-xs"></i>
                <i class="fas fa-sun hidden dark:block text-xs"></i>
            </button>
        </div>

        <!-- Center Form Container -->
        <div class="flex-1 flex flex-col justify-center w-full max-w-md mx-auto py-2 sm:py-6">
            
            <!-- Snake Rotating Border Container (Mobile Only) -->
            <div class="snake-border-card shadow-2xl shadow-indigo-500/10 lg:shadow-none">

                <!-- Main Card Body -->
                <div class="snake-border-inner p-6 sm:p-8 bg-white/95 dark:bg-slate-900/95 lg:bg-transparent lg:dark:bg-transparent lg:p-0 border border-slate-200/80 dark:border-slate-800 lg:border-0 backdrop-blur-xl lg:backdrop-blur-none transition-all">
                    
                    <!-- Mobile Inside-Card Header -->
                    <div class="flex lg:hidden items-center justify-between gap-2 pb-5 mb-5 border-b border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 text-white font-bold text-xs shadow-md shadow-indigo-500/25">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="leading-none text-start">
                                <span class="block font-black text-slate-900 dark:text-white text-xs tracking-tight">DOKANA</span>
                                <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 tracking-wider">RECOVERY</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}"
                                class="flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200/80 dark:border-slate-700/80 text-[11px] font-bold hover:bg-slate-200/70 dark:hover:bg-slate-700 transition-colors shadow-2xs"
                                title="{{ $targetNative }}">
                                <img src="{!! $flagPath !!}" class="h-3 w-3 rounded-full object-cover" alt="{{ $targetNative }}">
                                <span>{{ $targetNative }}</span>
                            </a>

                            <button type="button" data-theme-toggle
                                class="flex h-7 w-7 items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-amber-400 border border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-200/70 dark:hover:bg-slate-700 transition-colors shadow-2xs"
                                title="Toggle Dark / Light Mode" aria-label="Toggle Dark Mode">
                                <i class="fas fa-moon dark:hidden text-[11px]"></i>
                                <i class="fas fa-sun hidden dark:block text-[11px]"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="mb-5">
                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600/10 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 text-lg font-bold shadow-xs mb-3">
                            <i class="fas fa-key"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                            {!! __('auth.forget_password') !!}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {!! __('auth.enter_you_email_to_reset_password') !!}
                        </p>
                    </div>

                    <!-- Errors / Status -->
                    @if ($errors->any())
                        <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/80 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 text-xs">
                            <i class="fas fa-exclamation-circle text-rose-500 me-1.5"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="mb-5 p-3.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 flex items-center gap-3 text-emerald-700 dark:text-emerald-300 text-xs">
                            <i class="fas fa-check-circle text-emerald-500 text-sm"></i>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    <!-- Email Form (Anti-Autofill Protected) -->
                    <form action="{!! route('dashboard.password.post.email') !!}" method="POST" @submit="isSubmitting = true" autocomplete="off" class="space-y-4" novalidate>
                        @csrf

                        <!-- Trap fields -->
                        <input type="text" name="_fake_user_name" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" autocomplete="off" />
                        <input type="password" name="_fake_password" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" autocomplete="new-password" />

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">
                                {!! __('auth.enter_you_email') !!} <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors">
                                    <i class="fas fa-envelope text-xs"></i>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="off"
                                    readonly onfocus="this.removeAttribute('readonly');"
                                    class="w-full ps-10 pe-4 py-3 rounded-xl text-xs bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 font-medium cursor-text transition-all"
                                    placeholder="name@domain.com">
                        </div>
                    </div>

                    <button type="submit" :disabled="isSubmitting"
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-600 to-blue-600 hover:from-indigo-700 hover:via-indigo-700 hover:to-blue-700 text-white font-bold text-xs shadow-lg shadow-indigo-500/25 active:scale-[0.98] disabled:opacity-75 flex items-center justify-center gap-2 transition-all duration-200">
                        <i class="fas fa-spinner fa-spin text-xs" x-show="isSubmitting" x-cloak style="display: none;"></i>
                        <span x-text="isSubmitting ? '{{ __('general.loading') ?? 'جاري الإرسال...' }}' : '{{ __('auth.send') }}'">{!! __('auth.send') !!}</span>
                        <i class="fas fa-paper-plane text-xs" x-show="!isSubmitting"></i>
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-center">
                    <a href="{!! route('dashboard.get.login') !!}"
                        class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-colors">
                        <i class="fas fa-arrow-{{ Lang() == 'ar' ? 'right' : 'left' }}"></i>
                        <span>{!! __('auth.back_to_login') ?? 'العودة لتسجيل الدخول' !!}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

        <!-- Bottom Note -->
        <div class="shrink-0 text-center text-[11px] text-slate-400 dark:text-slate-500 pt-2">
            <span>Dokana SaaS Cloud &bull; {{ date('Y') }}</span>
        </div>
    </div>
</div>
@endsection
