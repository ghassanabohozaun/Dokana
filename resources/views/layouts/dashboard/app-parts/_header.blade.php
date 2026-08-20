<header class="sticky top-0 z-30 flex h-16 w-full items-center justify-between border-b border-slate-200/80 bg-white/80 px-4 backdrop-blur-md dark:border-slate-800/80 dark:bg-slate-900/80 sm:px-6 transition-colors">
    <!-- Left Section: Mobile Menu Toggle & Breadcrumbs -->
    <div class="flex items-center gap-3 sm:gap-4">
        <!-- Sidebar Drawer Toggle (Mobile) -->
        <button type="button" data-sidebar-toggle
            class="flex md:hidden h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-600 shadow-xs hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-all focus:outline-none"
            aria-label="Toggle Sidebar">
            <i class="fas fa-bars text-sm"></i>
        </button>

        <!-- Sidebar Collapse Toggle (Desktop Mini-mode) -->
        <button type="button" id="sidebar-collapse-btn"
            class="hidden md:flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-600 shadow-xs hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 transition-all focus:outline-none"
            title="Toggle Mini Sidebar">
            <i class="fas fa-bars-staggered text-sm"></i>
        </button>

        <!-- Current Store / Tenant Badge Indicator -->
        @if (user() && user()->store)
            <div class="hidden sm:flex items-center gap-2 rounded-xl bg-indigo-50/80 px-3 py-1.5 border border-indigo-100 dark:bg-indigo-950/40 dark:border-indigo-900/50">
                <i class="fas fa-store text-xs text-indigo-600 dark:text-indigo-400"></i>
                <span class="text-xs font-bold text-indigo-950 dark:text-indigo-200 truncate max-w-[160px]">
                    {{ user()->store->name }}
                </span>
            </div>
        @endif
    </div>

    <!-- Right Section: Language Switcher, Dark Mode, Notifications, User Profile -->
    <div class="flex items-center gap-2 sm:gap-3">
        
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
            class="flex items-center gap-1.5 rounded-xl border border-slate-200/80 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-xs hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 transition-all"
            title="{{ $targetNative }}">
            <img src="{!! $flagPath !!}" class="h-3.5 w-3.5 rounded-full object-cover" alt="{{ $targetNative }}">
            <span class="hidden sm:inline-block font-bold text-xs">{{ $targetNative }}</span>
        </a>

        <!-- Dark/Light Mode Toggle -->
        <button type="button" data-theme-toggle
            class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200/80 bg-white text-slate-600 shadow-xs hover:bg-slate-50 dark:border-slate-800 dark:bg-slate-900 dark:text-amber-400 dark:hover:bg-slate-800 transition-all focus:outline-none"
            title="Toggle Dark/Light Mode" aria-label="Toggle Dark Mode">
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
            $rawRoleName = $currentUser && $currentUser->role ? $currentUser->role->name : '';
            $roleDisplay = ($rawRoleName && $rawRoleName !== $userName) ? $rawRoleName : __('dashboard.admin');
            $storeName = $currentUser && $currentUser->store ? $currentUser->store->name : null;
        @endphp

        <div class="relative" data-dropdown-container>
            <button type="button" data-dropdown-toggle="user-dropdown-menu"
                class="flex items-center gap-2.5 rounded-xl p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-none">
                <!-- User Avatar with Green Online Dot on Corner -->
                <div class="relative shrink-0">
                    <div class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-xl bg-indigo-600 text-white font-bold text-xs shadow-xs">
                        @if ($photoUrl)
                            <img src="{!! $photoUrl !!}" alt="{{ $userName }}" class="h-full w-full object-cover">
                        @else
                            {{ $currentUser ? $currentUser->initials : 'U' }}
                        @endif
                    </div>
                    <!-- Online Status Indicator (Placed properly on corner) -->
                    <span class="absolute bottom-0 end-0 h-2.5 w-2.5 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-900"></span>
                </div>

                <div class="hidden lg:flex flex-col text-start">
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 leading-tight truncate max-w-[130px]">
                        {{ $userName }}
                    </span>
                    <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 truncate max-w-[130px] mt-0.5">
                        {{ $roleDisplay }}
                    </span>
                </div>

                <i class="fas fa-chevron-down text-[10px] text-slate-400 transition-transform duration-200"></i>
            </button>

            <!-- Dropdown Menu -->
            <div id="user-dropdown-menu" data-dropdown-menu
                class="hidden absolute end-0 mt-2 w-[275px] rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xl p-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                <!-- User Card Header -->
                <div class="p-3 rounded-xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/50 mb-1.5 text-start">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-indigo-600 text-white font-bold text-sm shadow-xs">
                            @if ($photoUrl)
                                <img src="{!! $photoUrl !!}" alt="{{ $userName }}" class="h-full w-full object-cover">
                            @else
                                {{ $currentUser ? $currentUser->initials : 'U' }}
                            @endif
                        </div>
                        <div class="min-w-0 flex-1 text-start">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate leading-tight">{{ $userName }}</p>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5 text-start font-mono">{{ $userEmail }}</p>
                        </div>
                    </div>

                    <!-- Role & Store Pills (Side by Side on Single Line) -->
                    <div class="mt-2.5 pt-2 border-t border-slate-200/70 dark:border-slate-700/50 flex items-center gap-1.5 flex-nowrap">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 whitespace-nowrap shrink-0">
                            <i class="fas fa-shield-alt text-[9px]"></i>
                            <span>{{ $roleDisplay }}</span>
                        </span>
                        @if($storeName)
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-[10px] font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 whitespace-nowrap truncate min-w-0 flex-1">
                            <i class="fas fa-store text-[9px] shrink-0"></i>
                            <span class="truncate">{{ $storeName }}</span>
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="space-y-0.5">
                    @can('settings_read')
                    <a href="{!! route('dashboard.settings.index') !!}"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        <i class="fas fa-cog text-slate-400 dark:text-slate-400 text-xs w-4 text-center"></i>
                        <span>{!! __('settings.settings') ?? 'الإعدادات العامة' !!}</span>
                    </a>
                    @endcan

                    <a href="{!! route('dashboard.lock.screen') !!}"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                        <i class="fas fa-lock text-slate-400 dark:text-slate-400 text-xs w-4 text-center"></i>
                        <span>{!! __('dashboard.lock_screen') !!}</span>
                    </a>

                    <hr class="my-1 border-slate-100 dark:border-slate-800">

                    <a href="{!! route('dashboard.logout') !!}"
                        class="flex items-center gap-2.5 rounded-xl px-3 py-2 text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-colors">
                        <i class="fas fa-sign-out-alt text-rose-500 text-xs w-4 text-center"></i>
                        <span>{!! __('auth.logout') !!}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
