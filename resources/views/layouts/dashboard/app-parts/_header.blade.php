<nav class="header-navbar navbar-expand-md navbar navbar-with-menu navbar-without-dd-arrow navbar-light fixed-top navbar-shadow"
    style="background: #fff !important;">
    <div class="navbar-wrapper" style="background: #fff !important;">
        <div class="navbar-header bg-white" style="background: #fff !important;">
            <ul class="nav navbar-nav flex-row premium-mobile-nav-container">
                <li class="nav-item mobile-menu d-md-none premium-mobile-toggle">
                    <a class="nav-link nav-menu-main menu-toggle" href="javascript:void(0)">
                        <div class="premium-burger sidebar-burger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                </li>
                <li class="nav-item site_name_logo_section">
                    <a class="navbar-brand" href="javascript:void(0)">
                        @if (setting()->logo != null)
                            <img class="brand-logo" alt="" src="{!! asset('uploads/settings/' . setting()->logo) !!}">
                        @else
                            @php
                                $brandName = setting()->site_name;
                                if (auth()->check() && auth()->user()->store) {
                                    $brandName = auth()->user()->store->name;
                                }

                                $words = explode(' ', $brandName);
                                $initials = '';
                                foreach ($words as $w) {
                                    $initials .= mb_substr($w, 0, 1);
                                }
                                $displayInitials = mb_strtoupper(mb_substr($initials, 0, 2));
                            @endphp
                            <div class="enterprise-header-brand">
                                <div class="brand-container">
                                    <div class="brand-icon-frame">
                                        <span class="initials-enterprise">
                                            {{ $displayInitials }}
                                        </span>
                                    </div>
                                    <span class="brand-text-enterprise">{{ $brandName }}</span>
                                </div>
                            </div>
                        @endif
                    </a>
                </li>
                <li class="nav-item d-md-none premium-mobile-ellipsis">
                    <a class="nav-link open-navbar-container px-2" data-toggle="collapse" data-target="#navbar-mobile">
                        <div class="premium-burger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">


                <ul class="nav navbar-nav float-right">
                    <li class="dropdown dropdown-user nav-item">
                        <div id="user_header_content" class="d-flex align-items-center">
                            <a class="dropdown-toggle nav-link dropdown-user-link p-0" href="javascript:void(0)"
                                data-toggle="dropdown">
                                <div class="enterprise-user-pill">
                                    <div class="user-info-text d-none d-lg-flex">
                                        <span class="greeting-text">{!! __('dashboard.hello') !!}</span>
                                        <span class="user-name-text">{!! user()->getTranslation('name', Lang()) !!}</span>
                                    </div>
                                    @php
                                        $user = user();
                                        $photoUrl = $user->userPhoto();
                                        $colors = ['#455a64', '#37474f', '#263238', '#1e293b', '#334155', '#475569'];
                                        $charIndex = abs(crc32($user->name)) % count($colors);
                                        $bgColor = $colors[$charIndex];
                                    @endphp
                                    <div class="avatar-wrapper-enterprise">
                                        @if ($photoUrl)
                                            <img src="{!! $photoUrl !!}" alt="avatar"
                                                class="avatar-img-enterprise shadow-sm">
                                        @else
                                            <span class="avatar-initials-enterprise shadow-sm"
                                                style="background: {!! $bgColor !!};">
                                                {!! $user->initials !!}
                                            </span>
                                        @endif
                                    </div>
                                    <i class="la la-angle-down ml-1 chevron-icon d-none d-lg-block"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                                <div class="dropdown-header-premium">
                                    <span class="user-name">{!! user()->name !!}</span>
                                    <span class="user-email">{!! user()->email !!}</span>
                                </div>
                                <a class="dropdown-item premium-dropdown-item" href="javascript:void(0)">
                                    <div class="dropdown-icon-wrapper bg-soft-primary">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <span>{!! __('dashboard.profile') !!}</span>
                                </a>
                                <a class="dropdown-item premium-dropdown-item" href="{!! route('dashboard.lock.screen') !!}">
                                    <div class="dropdown-icon-wrapper bg-soft-warning">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    <span>{!! __('dashboard.lock_screen') !!}</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item premium-dropdown-item logout-item"
                                    href="{!! route('dashboard.logout') !!}">
                                    <div class="dropdown-icon-wrapper bg-soft-danger">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <span>{!! __('auth.logout') !!}</span>
                                </a>
                            </div>
                        </div>
                    </li>
                    {{-- Premium Language Switcher Toggle --}}
                    @php
                        $currentLocale = Lang();
                        $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                        $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
                        $flagPath =
                            $targetLocale == 'ar'
                                ? asset('assets/dashbaord/media/svg/flags/العربية.svg')
                                : asset('assets/dashbaord/media/svg/flags/English.svg');
                    @endphp
                    <li class="nav-item">
                        <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}"
                            class="nav-link p-0 d-flex align-items-center h-100">
                            <div class="language-switcher-premium">
                                <img src="{!! $flagPath !!}" class="flag-icon" alt="{!! $targetNative !!}">
                                <span class="lang-name d-none d-sm-inline-block">{{ $targetNative }}</span>
                            </div>
                        </a>
                    </li>

                    {{-- Font Size Switcher --}}
                    <li class="dropdown dropdown-language nav-item">
                        <a class="nav-link p-0 d-flex align-items-center h-100" id="dropdown-font-size"
                            href="javascript:void(0)" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="language-switcher-premium"
                                style="padding: 0.5rem 0.8rem; background: rgba(0,0,0,0.03);">
                                <i class="fas fa-font" style="font-size: 1.1rem; color: #5b626b;"></i>
                                <i class="fas fa-chevron-down ml-1" style="font-size: 0.7rem; color: #5b626b;"></i>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-font-size">
                            <a class="dropdown-item" href="javascript:void(0)" onclick="setSystemFontSize('15px')">
                                <i class="fas fa-search-plus mr-1"></i> {!! __('general.increase_font') ?? 'تكبير الخط' !!}
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="setSystemFontSize('13px')">
                                <i class="fas fa-equals mr-1"></i> {!! __('general.default_font') ?? 'الخط الافتراضي' !!}
                            </a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="setSystemFontSize('12px')">
                                <i class="fas fa-search-minus mr-1"></i> {!! __('general.decrease_font') ?? 'تصغير الخط' !!}
                            </a>
                        </div>
                    </li>

                    {{-- Enterprise Notifications --}}
                    @livewire('notifications.header-notifications')

                    {{-- Enterprise Messages --}}
                    <li class="dropdown dropdown-notification nav-item">
                        <a class="nav-link nav-link-label" href="javascript:void(0)" data-toggle="dropdown">
                            <div class="enterprise-action-btn">
                                <i class="ficon la la-envelope"></i>
                                <span class="enterprise-badge badge-primary">4</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list">
                            <div class="dropdown-header-main">
                                <h6 class="header-title">Messages</h6>
                                <span class="premium-badge-count">4 New</span>
                            </div>
                            <div class="scrollable-container media-list w-100 custom-scrollbar"
                                style="max-height: 350px; overflow-y: auto;">
                                <a class="preview-item-premium" href="javascript:void(0)">
                                    <div class="preview-thumbnail-premium bg-purple">
                                        <i class="la la-user"></i>
                                    </div>
                                    <div class="preview-item-content-premium">
                                        <span class="subject">Ahmed Mohamed</span>
                                        <span class="message-text">Inquiry about an apartment in Al-Malaz...</span>
                                        <span class="time">10 mins ago</span>
                                    </div>
                                </a>
                                <a class="preview-item-premium" href="javascript:void(0)">
                                    <div class="preview-thumbnail-premium bg-primary">
                                        <i class="la la-user"></i>
                                    </div>
                                    <div class="preview-item-content-premium">
                                        <span class="subject">Sara Ali</span>
                                        <span class="message-text">Rent transferred, find the receipt...</span>
                                        <span class="time">30 mins ago</span>
                                    </div>
                                </a>
                                <a class="preview-item-premium" href="javascript:void(0)">
                                    <div class="preview-thumbnail-premium bg-teal">
                                        <i class="la la-user"></i>
                                    </div>
                                    <div class="preview-item-content-premium">
                                        <span class="subject">Khaled Mahmoud</span>
                                        <span class="message-text">Can we inspect the villa tomorrow?...</span>
                                        <span class="time">1 hour ago</span>
                                    </div>
                                </a>
                                <a class="preview-item-premium" href="javascript:void(0)">
                                    <div class="preview-thumbnail-premium bg-purple">
                                        <i class="ft-user"></i>
                                    </div>
                                    <div class="preview-item-content-premium">
                                        <span class="subject">Nour El-Din</span>
                                        <span class="message-text">Commercial office booking confirmation...</span>
                                        <span class="time">4 hours ago</span>
                                    </div>
                                </a>
                            </div>
                            <div class="dropdown-footer-premium">
                                <a href="javascript:void(0)">View all messages</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
