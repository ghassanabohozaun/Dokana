@extends('layouts.website.app')

@section('title', __('dashboard.casher') . ' - ' . setting()->getTranslation('site_name', app()->getLocale()))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-[#0b1121] transition-colors duration-300 p-4 relative overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-[-10%] right-[-5%] w-96 h-96 bg-primary/20 blur-[100px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-[-10%] left-[-5%] w-96 h-96 bg-blue-500/20 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md bg-white/80 dark:bg-[#151f32]/80 backdrop-blur-xl rounded-[2rem] shadow-2xl border border-white/20 dark:border-gray-800/50 p-8 relative z-10 transform transition-all hover:scale-[1.01]">
        
        <!-- Top Actions (Theme & Language) -->
        <div class="absolute top-6 rtl:left-6 ltr:right-6 flex items-center gap-2">
            @php
                $currentLocale = app()->getLocale();
                $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
            @endphp
            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" 
               class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95 text-gray-500 dark:text-gray-400 font-bold text-sm flex items-center justify-center min-w-[36px]">
                {{ $targetNative }}
            </a>
            <div class="w-px h-4 bg-gray-300 dark:bg-gray-700"></div>
            <button id="themeToggleBtn" onclick="document.documentElement.classList.toggle('dark')" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95 text-gray-500 dark:text-gray-400">
                <i class="ph-fill ph-moon text-xl dark:hidden"></i>
                <i class="ph-fill ph-sun text-xl hidden dark:block text-yellow-400"></i>
            </button>
        </div>

        <div class="text-center mb-8 mt-4">
            <img src="{!! setting()->logo ? asset('uploads/settings/' . setting()->logo) : asset('logo/dokkana-logo.png') !!}" alt="Logo" class="h-16 w-auto mx-auto rounded-xl shadow-sm mb-4">
            <h2 class="text-2xl font-black text-gray-800 dark:text-white mb-2">{{ __('dashboard.casher') ?? 'الكاشير' }}</h2>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('users.enter_mobile') ?? 'أدخل رقم الجوال وكلمة المرور للدخول' }}</p>
        </div>

        <form action="{{ route('website.casher.login.submit') }}" method="POST" class="space-y-5" autocomplete="off">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 rounded-2xl p-4 mb-6">
                    <div class="flex items-start gap-3 text-red-600 dark:text-red-400">
                        <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
                        <ul class="text-sm font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                    {{ __('users.mobile') ?? 'رقم الجوال' }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 rtl:right-0 ltr:left-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <i class="ph-fill ph-phone text-lg"></i>
                    </div>
                    <input type="tel" inputmode="numeric" name="mobile" class="w-full bg-gray-50 dark:bg-[#0b1121] border-2 border-gray-100 dark:border-gray-800 rounded-xl px-4 py-3.5 rtl:pr-12 ltr:pl-12 text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-bold rtl:text-right ltr:text-left dir-ltr" placeholder="05xxxxxxxx" required autocomplete="new-password" value="{{ old('mobile') }}">
                </div>
            </div>
            
            <div class="space-y-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                    {{ __('users.password') ?? 'كلمة المرور' }}
                </label>
                <div class="relative group">
                    <div class="absolute inset-y-0 rtl:right-0 ltr:left-0 flex items-center rtl:pr-4 ltr:pl-4 pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                        <i class="ph-fill ph-lock-key text-lg"></i>
                    </div>
                    <input type="password" name="password" class="w-full bg-gray-50 dark:bg-[#0b1121] border-2 border-gray-100 dark:border-gray-800 rounded-xl px-4 py-3.5 rtl:pr-12 ltr:pl-12 text-gray-800 dark:text-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all font-bold rtl:text-right ltr:text-left dir-ltr" placeholder="••••••" required autocomplete="new-password">
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <label class="flex items-center gap-2 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="peer sr-only">
                        <div class="w-5 h-5 rounded border-2 border-gray-300 dark:border-gray-600 peer-checked:bg-primary peer-checked:border-primary transition-colors"></div>
                        <i class="ph-bold ph-check text-white absolute opacity-0 peer-checked:opacity-100 text-xs"></i>
                    </div>
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400 group-hover:text-primary transition-colors select-none">
                        {{ __('auth.remember_me') ?? 'تذكرني' }}
                    </span>
                </label>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-lg py-4 px-6 rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition-all active:scale-95 flex items-center justify-center gap-2 mt-4">
                <span>{{ __('auth.login') ?? 'تسجيل الدخول' }}</span>
                <i class="ph-bold ph-sign-in"></i>
            </button>
        </form>
    </div>
</div>
@endsection
