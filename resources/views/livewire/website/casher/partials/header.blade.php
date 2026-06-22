    <!-- Header -->
    <header class="p-4 flex items-center justify-between border-b dark:border-gray-800 glass-effect sticky top-0 z-10 transition-colors duration-300">
        <h1 class="text-xl font-bold text-primary flex items-center gap-2">
            <img src="{!! setting()->logo ? asset('uploads/settings/' . setting()->logo) : asset('logo/dokkana-logo.png') !!}" alt="Logo" class="h-8 w-auto rounded shadow-sm">
            {{ auth('casher')->user() && auth('casher')->user()->store ? auth('casher')->user()->store->name : __('notebook.store_notebook') }}
        </h1>
        <div class="flex items-center gap-2">
            <!-- User Widget -->
            <div class="hidden sm:flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800/50 rounded-full px-2.5 py-1 border border-gray-200 dark:border-gray-700 shadow-sm me-1 transition-all hover:shadow-md">
                <div class="bg-primary/10 text-primary rounded-full p-1 flex items-center justify-center h-6 w-6">
                    <i class="ph-fill ph-user text-sm"></i>
                </div>
                <div class="flex flex-col justify-center">
                    <span class="text-[9px] text-gray-500 dark:text-gray-400 font-medium leading-none mb-0.5">{{ __('dashboard.welcome') ?? 'مرحباً' }}</span>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-200 leading-none">{{ auth('casher')->user() ? auth('casher')->user()->name : 'Cashier' }}</span>
                </div>
            </div>
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
            <div wire:loading class="ml-2">
                <i class="ph-bold ph-spinner animate-spin text-xl text-primary"></i>
            </div>
        </div>
    </header>
