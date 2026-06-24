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
            <button @click="showAccountsSheet = true" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95">
                <i class="ph-fill ph-wallet text-xl text-primary"></i>
            </button>
            <button id="themeToggleBtn" onclick="document.documentElement.classList.toggle('dark')" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors active:scale-95">
                <i class="ph-fill ph-moon text-xl dark:hidden text-gray-600"></i>
                <i class="ph-fill ph-sun text-xl hidden dark:block text-yellow-400"></i>
            </button>
        </div>

        <!-- Loading Indicator -->
        <div x-show="isLoading" class="absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 flex items-center justify-center z-20 pointer-events-none" x-cloak 
            x-transition:enter="transition-opacity duration-150"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="bg-white/80 dark:bg-black/50 p-2 rounded-full backdrop-blur-sm shadow-sm border border-gray-100 dark:border-gray-800">
                <i class="ph-bold ph-spinner animate-spin text-xl text-primary drop-shadow-md"></i>
            </div>
        </div>
    </header>
