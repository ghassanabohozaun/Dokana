@extends('layouts.website.app')

@section('title', __('dashboard.casher') . ' - ' . setting()->getTranslation('site_name', app()->getLocale()))

@section('content')
<div x-data="{ 
    showPassword: false, 
    isSubmitting: false, 
    mobile: '{{ old('mobile') }}',
    isDark: document.documentElement.classList.contains('dark'),
    normalizeArabicNumbers(val) {
        if (!val) return '';
        let s = String(val);
        s = s.replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
        s = s.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
        return s.replace(/[^0-9]/g, '');
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
}" class="min-h-[100dvh] flex items-center justify-center p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-gray-100 to-emerald-50/50 dark:from-[#080d1a] dark:via-[#0c1322] dark:to-[#081b19] relative overflow-hidden">

    <!-- Ambient Decorative Blobs -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-gradient-to-br from-emerald-400/20 to-teal-500/10 dark:from-emerald-500/15 dark:to-teal-500/5 blur-3xl rounded-full pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-gradient-to-tr from-blue-500/20 to-emerald-400/10 dark:from-blue-600/15 dark:to-emerald-500/5 blur-3xl rounded-full pointer-events-none"></div>

    <!-- Login Glass Card -->
    <div class="w-full max-w-[430px] bg-white/85 dark:bg-[#111928]/85 backdrop-blur-2xl rounded-[2.25rem] shadow-2xl shadow-slate-900/10 dark:shadow-black/50 border border-white/80 dark:border-slate-800/80 p-6 sm:p-8 relative z-10 transition-all duration-300">
        
        <!-- Top Action Controls (Language & Theme) -->
        <div class="flex items-center justify-between mb-4">
            <!-- Language Switcher -->
            @php
                $currentLocale = app()->getLocale();
                $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
            @endphp
            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-gray-100/80 dark:bg-slate-800/80 hover:bg-gray-200/80 dark:hover:bg-slate-700/80 transition-all text-gray-600 dark:text-gray-300 font-bold text-xs active:scale-95 shadow-sm border border-gray-200/50 dark:border-slate-700/50">
                <i class="ph-bold ph-globe text-sm text-primary"></i>
                <span>{{ $targetNative }}</span>
            </a>

            <!-- Theme Toggle Switcher -->
            <button type="button" 
                    @click="toggleTheme()" 
                    class="w-9 h-9 rounded-full bg-gray-100/80 dark:bg-slate-800/80 hover:bg-gray-200/80 dark:hover:bg-slate-700/80 transition-all flex items-center justify-center text-gray-600 dark:text-gray-300 active:scale-95 shadow-sm border border-gray-200/50 dark:border-slate-700/50"
                    title="{{ __('dashboard.theme') ?? 'المظهر' }}">
                <template x-if="!isDark">
                    <i class="ph-fill ph-moon text-base text-slate-700"></i>
                </template>
                <template x-if="isDark">
                    <i class="ph-fill ph-sun text-base text-amber-400"></i>
                </template>
            </button>
        </div>

        <!-- Header: Logo & Title -->
        <div class="text-center mb-6">
            <div class="w-20 h-20 mx-auto mb-3.5 p-2 rounded-2xl bg-white dark:bg-slate-800 shadow-md shadow-emerald-500/10 border border-gray-100 dark:border-slate-700/80 flex items-center justify-center relative group">
                <img src="{!! setting()->logo ? asset('uploads/settings/' . setting()->logo) : asset('logo/dokkana-logo.png') !!}" 
                     alt="Store Logo" 
                     class="max-h-14 max-w-14 object-contain rounded-xl group-hover:scale-105 transition-transform duration-300">
            </div>

            <h1 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                {{ setting()->getTranslation('site_name', app()->getLocale()) ?: 'متجري' }}
            </h1>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 mt-2 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ __('dashboard.casher') ?? 'الكاشير' }}</span>
            </div>
        </div>

        <!-- Login Form -->
        <form action="{{ route('website.casher.login.submit') }}" 
              method="POST" 
              @submit="isSubmitting = true" 
              autocomplete="off" 
              class="space-y-4" 
              novalidate>
            @csrf

            <!-- Off-screen decoy trap to intercept aggressive browser credential autofill -->
            <div style="position: absolute; top: -9999px; left: -9999px; width: 0; height: 0; opacity: 0; pointer-events: none; overflow: hidden;" aria-hidden="true">
                <input type="text" name="fake_email_decoy" tabindex="-1" autocomplete="username">
                <input type="password" name="fake_pass_decoy" tabindex="-1" autocomplete="current-password">
            </div>

            <!-- Error Notifications -->
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/60 rounded-2xl p-3.5 mb-4 shadow-sm animate-shake">
                    <div class="flex items-start gap-2.5 text-red-600 dark:text-red-400">
                        <i class="ph-fill ph-warning-circle text-lg shrink-0 mt-0.5"></i>
                        <ul class="text-xs font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Mobile Field -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                    {{ __('users.mobile') ?? 'رقم الجوال' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                        <i class="ph-fill ph-phone-call text-lg"></i>
                    </div>
                    <input type="tel" 
                           inputmode="numeric" 
                           name="mobile" 
                           id="cashier_mobile"
                           x-model="mobile" 
                           @input="mobile = normalizeArabicNumbers($event.target.value)" 
                           autocomplete="new-password" 
                           readonly
                           onfocus="this.removeAttribute('readonly');"
                           class="w-full bg-gray-50/90 dark:bg-[#090f1d] border border-gray-200 dark:border-slate-700/80 rounded-2xl py-3.5 {{ app()->getLocale() == 'ar' ? 'pr-11 pl-4 text-right' : 'pl-11 pr-4 text-left' }} text-sm text-gray-900 dark:text-white font-bold placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 focus:bg-white dark:focus:bg-[#070c17] transition-all" 
                           placeholder="05xxxxxxxx" 
                           required>
                </div>
            </div>
            
            <!-- Password Field -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                    {{ __('users.password') ?? 'كلمة المرور' }} <span class="text-red-500">*</span>
                </label>
                <div class="relative group">
                    <!-- Lock Icon -->
                    <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-3.5' : 'left-0 pl-3.5' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-emerald-500 transition-colors">
                        <i class="ph-fill ph-lock-key text-lg"></i>
                    </div>
                    
                    <!-- Password Input -->
                    <input :type="showPassword ? 'text' : 'password'" 
                           name="password" 
                           id="cashier_password"
                           autocomplete="new-password" 
                           readonly
                           onfocus="this.removeAttribute('readonly');"
                           class="w-full bg-gray-50/90 dark:bg-[#090f1d] border border-gray-200 dark:border-slate-700/80 rounded-2xl py-3.5 {{ app()->getLocale() == 'ar' ? 'pr-11 pl-11 text-right' : 'pl-11 pr-11 text-left' }} text-sm text-gray-900 dark:text-white font-bold placeholder-gray-400 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 focus:bg-white dark:focus:bg-[#070c17] transition-all" 
                           placeholder="••••••••" 
                           required>

                    <!-- Toggle Visibility Button -->
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'left-0 pl-3' : 'right-0 pr-3' }} flex items-center text-gray-400 hover:text-emerald-500 focus:outline-none transition-colors p-1"
                            tabindex="-1">
                        <i class="ph-bold text-lg" :class="showPassword ? 'ph-eye-slash text-emerald-500' : 'ph-eye'"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer group select-none">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="peer sr-only">
                        <div class="w-5 h-5 rounded-lg border border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-800 peer-checked:bg-emerald-500 peer-checked:border-emerald-500 transition-all flex items-center justify-center shadow-sm">
                            <i class="ph-bold ph-check text-white text-xs opacity-0 peer-checked:opacity-100 transition-opacity"></i>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                        {{ __('auth.remember_me') ?? 'تذكرني' }}
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    :disabled="isSubmitting"
                    class="w-full bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black text-base py-3.5 px-6 rounded-2xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 transition-all duration-150 active:scale-[0.98] disabled:opacity-75 disabled:cursor-not-allowed flex items-center justify-center gap-2 mt-4 cursor-pointer">
                <span x-show="!isSubmitting" class="flex items-center gap-2">
                    <span>{{ __('auth.login') ?? 'تسجيل الدخول' }}</span>
                    <i class="ph-bold ph-sign-in text-lg rtl:rotate-180"></i>
                </span>
                <span x-show="isSubmitting" class="flex items-center gap-2" style="display: none;">
                    <i class="ph-bold ph-spinner-gap animate-spin text-xl"></i>
                    <span>{{ __('notebook.loading') ?? 'جاري تسجيل الدخول...' }}</span>
                </span>
            </button>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/website/vendor/alpine/alpine.min.js') }}"></script>
@endpush
