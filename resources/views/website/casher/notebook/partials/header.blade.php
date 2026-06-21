    <!-- Header -->
    <header class="p-4 flex items-center justify-between border-b dark:border-gray-800 glass-effect sticky top-0 z-10 transition-colors duration-300">
        <h1 class="text-xl font-bold text-primary flex items-center gap-2">
            <img src="{!! setting()->logo ? asset('uploads/settings/' . setting()->logo) : asset('logo/dokkana-logo.png') !!}" alt="Logo" class="h-8 w-auto rounded shadow-sm">
            {{ auth('casher')->user() && auth('casher')->user()->store ? auth('casher')->user()->store->name : __('notebook.store_notebook') }}
        </h1>
        <div class="flex items-center gap-2">
            @php
                $currentLocale = app()->getLocale();
                $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
            @endphp
            <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" 
               class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95 text-gray-600 dark:text-gray-300 font-bold text-sm flex items-center gap-1">
                <span>{{ $targetNative }}</span>
            </a>
            <button id="themeToggleBtn" onclick="document.documentElement.classList.toggle('dark')" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95">
                <i class="ph-fill ph-moon text-xl dark:hidden text-gray-600"></i>
                <i class="ph-fill ph-sun text-xl hidden dark:block text-yellow-400"></i>
            </button>
            <a href="{{ route('website.casher.logout') }}" 
               class="p-2 rounded-full hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/30 dark:hover:text-red-400 text-gray-600 dark:text-gray-300 transition-colors active:scale-95" 
               title="{{ __('notebook.logout') ?? 'تسجيل الخروج' }}">
                <i class="ph-bold ph-sign-out text-xl"></i>
            </a>
        </div>
        
        <!-- Loading Indicator (Absolute positioned to prevent shifting) -->
        <div x-show="isLoading" class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2" x-cloak x-transition.opacity>
            <i class="ph-bold ph-spinner animate-spin text-2xl text-primary drop-shadow-md"></i>
        </div>
    </header>
