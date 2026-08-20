@extends('layouts.dashboard.auth')

@section('title')
    {!! __('dashboard.lock_screen') !!}
@endsection

@section('content')
<div class="h-screen w-screen grid grid-cols-1 lg:grid-cols-12 overflow-hidden bg-slate-50 dark:bg-slate-950 relative selection:bg-amber-500 selection:text-white"
    x-data="{ showPassword: false, isUnlocking: false }">

    <!-- Mobile Ambient Floating Glows -->
    <div class="lg:hidden absolute -top-20 -start-20 w-80 h-80 bg-amber-500/20 dark:bg-amber-600/15 rounded-full blur-3xl pointer-events-none animate-float-slow"></div>
    <div class="lg:hidden absolute -bottom-20 -end-20 w-80 h-80 bg-indigo-500/20 dark:bg-indigo-600/15 rounded-full blur-3xl pointer-events-none animate-float-slow [animation-delay:4s]"></div>

    <!-- 1. Hero / SaaS Visual Side (Desktop/Laptop Only) -->
    <div class="hidden lg:flex lg:col-span-6 xl:col-span-7 h-screen bg-gradient-to-br from-amber-700 via-indigo-900 to-slate-950 dark:from-slate-950 dark:via-slate-900 dark:to-amber-950 text-white p-10 xl:p-16 flex-col relative overflow-hidden select-none border-e border-white/10 dark:border-slate-800">
        <!-- Ambient Decorative Elements -->
        <div class="absolute inset-0 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>
        <div class="absolute -top-32 -start-32 w-96 h-96 bg-amber-500/20 dark:bg-amber-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -end-32 w-96 h-96 bg-indigo-500/25 dark:bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Brand Identity -->
        <div class="shrink-0 relative z-10 flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 border border-white/20 text-white font-black text-lg shadow-lg">
                <i class="fas fa-layer-group text-amber-300"></i>
            </div>
            <div>
                <span class="block font-black text-lg tracking-tight leading-none text-white">DOKANA</span>
                <span class="text-[11px] font-bold text-amber-200/80 tracking-wider">ENTERPRISE SAAS</span>
            </div>
        </div>

        <!-- Center Content -->
        <div class="flex-1 flex flex-col justify-center relative z-10 max-w-xl py-6">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-bold text-amber-200 shadow-xs mb-5 w-fit">
                <i class="fas fa-shield-alt text-amber-300"></i>
                <span>{{ __('dashboard.secured_session') ?? 'جلسة عمل مؤمنة' }}</span>
            </div>

            <h1 class="text-2xl xl:text-4xl font-black tracking-tight leading-snug mb-3 text-white">
                {{ __('auth.saas_title') }}
            </h1>
            <p class="text-amber-100/85 text-sm xl:text-base leading-relaxed mb-8">
                {{ __('auth.saas_desc') }}
            </p>

            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl bg-white/[0.07] border border-white/15 hover:bg-white/[0.12] transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/30 text-amber-200 text-sm mb-3">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="text-xs xl:text-sm font-bold text-white mb-1">{{ __('dashboard.lock_screen') }}</h3>
                    <p class="text-[11px] text-amber-100/75 leading-normal">{{ __('auth.enter_password_to_unlock') }}</p>
                </div>

                <div class="p-4 rounded-2xl bg-white/[0.07] border border-white/15 hover:bg-white/[0.12] transition-colors">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500/30 text-emerald-200 text-sm mb-3">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-xs xl:text-sm font-bold text-white mb-1">{{ __('auth.saas_feature_2_title') }}</h3>
                    <p class="text-[11px] text-indigo-100/75 leading-normal">{{ __('auth.saas_feature_2_desc') }}</p>
                </div>
            </div>
        </div>

        <!-- Bottom Platform Info -->
        <div class="shrink-0 relative z-10 flex items-center justify-between text-xs text-amber-200/60 pt-4 border-t border-white/10">
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

        @php
            $user = user();
            $photoUrl = $user ? $user->userPhoto() : null;
            $userName = $user ? $user->getTranslation('name', Lang()) : 'User';
        @endphp

        <!-- Center Form Container -->
        <div class="flex-1 flex flex-col justify-center w-full max-w-sm mx-auto py-2 sm:py-6 text-center">
            
            <!-- Snake Rotating Border Container (Amber Glow on Lock Screen) -->
            <div class="snake-border-card snake-amber shadow-2xl shadow-amber-500/10 lg:shadow-none">

                <!-- Main Card Body -->
                <div class="snake-border-inner p-6 sm:p-8 bg-white/95 dark:bg-slate-900/95 lg:bg-transparent lg:dark:bg-transparent lg:p-0 border border-slate-200/80 dark:border-slate-800 lg:border-0 backdrop-blur-xl lg:backdrop-blur-none transition-all">

                    <!-- Mobile Inside-Card Header -->
                    <div class="flex lg:hidden items-center justify-between gap-2 pb-5 mb-5 border-b border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-amber-500 to-amber-600 text-white font-bold text-xs shadow-md shadow-amber-500/25">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <div class="leading-none text-start">
                                <span class="block font-black text-slate-900 dark:text-white text-xs tracking-tight">DOKANA</span>
                                <span class="text-[9px] font-bold text-amber-600 dark:text-amber-400 tracking-wider">SECURED</span>
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

                    <!-- User Avatar & Badge -->
                    <div class="relative inline-block mb-4">
                        <div class="h-20 w-20 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white font-bold text-2xl flex items-center justify-center overflow-hidden shadow-lg mx-auto ring-4 ring-slate-100 dark:ring-slate-800">
                            @if ($photoUrl)
                                <img src="{!! $photoUrl !!}" alt="{{ $userName }}" class="h-full w-full object-cover">
                            @else
                                {{ $user ? $user->initials : 'U' }}
                            @endif
                        </div>
                        <div class="absolute -bottom-1 -end-1 h-7 w-7 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xs shadow-md ring-2 ring-white dark:ring-slate-900">
                            <i class="fas fa-lock text-[10px]"></i>
                        </div>
                    </div>

                    <!-- Session Status Pill -->
                    <div class="mb-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/40">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>{!! __('dashboard.active_session') ?? 'تم قفل الجلسة للأمان' !!}</span>
                        </span>
                    </div>

                    <h2 class="text-xl font-black text-slate-900 dark:text-white truncate">{{ $userName }}</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 mb-6">
                        {!! __('auth.enter_password_to_unlock') !!}
                    </p>

                    <!-- Errors Alert -->
                    @if ($errors->any())
                        <div class="mb-5 p-3 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200/80 dark:border-rose-800/60 text-rose-700 dark:text-rose-300 text-xs text-start">
                            <i class="fas fa-exclamation-circle text-rose-500 me-1.5"></i>
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Unlock Form (Anti-Autofill Protected) -->
                    <form id="lock-form" action="{{ route('dashboard.unlock.screen') }}" method="POST" @submit="isUnlocking = true" autocomplete="off" class="space-y-4" novalidate>
                        @csrf

                        <!-- Trap fields -->
                        <input type="text" name="_fake_user_name" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" autocomplete="off" />
                        <input type="password" name="_fake_password" style="position: absolute; opacity: 0; height: 0; width: 0; z-index: -1;" tabindex="-1" autocomplete="new-password" />

                        <div class="relative group text-start">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none text-slate-400 group-focus-within:text-amber-500 dark:group-focus-within:text-amber-400 transition-colors">
                                <i class="fas fa-key text-xs"></i>
                            </div>
                            <input type="password" :type="showPassword ? 'text' : 'password'" name="password" id="lock-password" required autofocus autocomplete="new-password"
                                readonly onfocus="this.removeAttribute('readonly');"
                                class="w-full ps-10 pe-10 py-3 rounded-xl text-xs bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-4 focus:ring-amber-500/10 focus:border-amber-500 font-medium cursor-text transition-all"
                                placeholder="••••••••">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 focus:outline-none transition-colors"
                                title="إظهار / إخفاء كلمة المرور">
                                <i class="fas text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>

                        <button type="submit" id="unlock-btn" :disabled="isUnlocking"
                            class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-bold text-xs shadow-lg shadow-amber-500/25 active:scale-[0.98] disabled:opacity-75 flex items-center justify-center gap-2 transition-all duration-200">
                            <i class="fas fa-spinner fa-spin text-xs" x-show="isUnlocking" x-cloak style="display: none;"></i>
                            <span x-text="isUnlocking ? '{{ __('auth.unlocking') ?? 'جاري فتح القفل...' }}' : '{{ __('auth.unlock') }}'">{!! __('auth.unlock') !!}</span>
                            <i class="fas fa-lock-open text-xs" x-show="!isUnlocking"></i>
                        </button>
                    </form>

                    <!-- Different Account Link -->
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ route('dashboard.logout') }}"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-rose-600 dark:text-slate-400 dark:hover:text-rose-400 transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{!! __('auth.sign_in_different_account') !!}</span>
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

@push('scripts')
<script>
    window.LockScreenData = {
        routes: {
            unlock: "{{ route('dashboard.unlock.screen') }}",
            dashboard: "{{ route('dashboard.index') }}",
            keep_alive: "{{ route('dashboard.keep.alive') }}"
        },
        labels: {
            unlock: "{{ __('auth.unlock') }}",
            unlocking: "{{ __('auth.unlocking') ?? 'Unlocking...' }}"
        },
        messages: {
            failed: "{{ __('auth.failed') }}"
        }
    };
</script>
<script src="{{ asset('assets/dashbaord/js/lock-screen-modern.js') }}"></script>
@endpush
