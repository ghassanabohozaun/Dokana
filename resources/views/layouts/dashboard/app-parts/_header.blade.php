<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 dark:border-slate-800/80 bg-white/90 dark:bg-slate-900/90 px-4 md:px-6 backdrop-blur-md transition-colors">
    <!-- Left / Start Section: Mobile Sidebar Toggle & Store Context -->
    <div class="flex items-center gap-3 md:gap-4">
        <!-- Mobile Sidebar Toggle -->
        <button type="button" data-sidebar-toggle
            class="flex md:hidden h-10 w-10 items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-white dark:hover:bg-slate-800 transition-colors focus:outline-none"
            aria-label="Toggle Sidebar">
            <i class="fas fa-bars text-lg"></i>
        </button>

        @if(auth()->check() && auth()->user()->store)
        <!-- Current Store Context Pill -->
        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/70 border border-slate-200/60 dark:border-slate-700/60">
            <div class="flex h-5 w-5 items-center justify-center rounded-md bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs">
                <i class="fas fa-store text-[10px]"></i>
            </div>
            <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                {{ auth()->user()->store->name }}
            </span>
        </div>
        @endif
    </div>

    <!-- Right / End Section: Quick Actions, Notifications & Profile -->
    <div class="flex items-center gap-1.5 md:gap-3">
        <!-- Language Switcher -->
        @php
            $currentLocale = Lang();
            $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
            $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
            $flagPath = $targetLocale == 'ar'
                ? asset('assets/dashbaord/media/svg/flags/العربية.svg')
                : asset('assets/dashbaord/media/svg/flags/English.svg');
        @endphp
        <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}"
            class="flex items-center gap-2 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200/60 dark:border-slate-800/60 transition-colors"
            title="{{ $targetNative }}">
            <img src="{!! $flagPath !!}" class="h-4 w-4 rounded-full object-cover shadow-xs" alt="{{ $targetNative }}">
            <span class="hidden sm:inline-block">{{ $targetNative }}</span>
        </a>

        <!-- Dark Mode Toggle Button -->
        <button type="button" data-theme-toggle
            class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-amber-300 dark:hover:bg-slate-800 transition-colors"
            title="Toggle Dark/Light Mode">
            <i class="fas fa-moon dark:hidden text-sm"></i>
            <i class="fas fa-sun hidden dark:block text-sm"></i>
        </button>

        <!-- Livewire Notifications Component -->
        @livewire('notifications.header-notifications')

        <!-- User Profile Dropdown -->
        @php
            $currentUser = user();
            $photoUrl = $currentUser ? $currentUser->userPhoto() : null;
            $userName = $currentUser ? $currentUser->name : 'User';
            $userEmail = $currentUser ? $currentUser->email : '';
            $roleName = $currentUser && $currentUser->role ? $currentUser->role->name : __('dashboard.admin');
        @endphp

        <div class="relative" data-dropdown-container>
            <button type="button" data-dropdown-toggle="user-dropdown-menu"
                class="flex items-center gap-2.5 rounded-xl p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                <div class="relative flex h-8 w-8 items-center justify-center overflow-hidden rounded-lg bg-gradient-to-tr from-indigo-600 to-blue-500 text-white font-bold text-xs shadow-xs">
                    @if ($photoUrl)
                        <img src="{!! $photoUrl !!}" alt="{{ $userName }}" class="h-full w-full object-cover">
                    @else
                        {{ $currentUser ? $currentUser->initials : 'U' }}
                    @endif
                </div>

                <div class="hidden lg:flex flex-col text-start">
                    <span class="text-xs font-bold text-slate-800 dark:text-white leading-tight">
                        {{ $userName }}
                    </span>
                    <span class="text-[10px] text-slate-400 dark:text-slate-500">
                        {{ $roleName }}
                    </span>
                </div>

                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu" data-dropdown-menu
                class="hidden absolute end-0 mt-2 w-64 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 shadow-dropdown p-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                <!-- User Card Header -->
                <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 p-3 mb-1">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ $userName }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ $userEmail }}</p>
                </div>

                <div class="space-y-0.5">
                    <a href="{!! route('dashboard.lock.screen') !!}"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-amber-950/30 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                        <i class="fas fa-lock text-xs w-4 text-center"></i>
                        <span>{!! __('dashboard.lock_screen') !!}</span>
                    </a>

                    <hr class="my-1 border-slate-100 dark:border-slate-800">

                    <a href="{!! route('dashboard.logout') !!}"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                        <i class="fas fa-sign-out-alt text-xs w-4 text-center"></i>
                        <span>{!! __('auth.logout') !!}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
