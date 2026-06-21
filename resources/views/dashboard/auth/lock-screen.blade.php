@extends('layouts.dashboard.auth')

@section('title')
    {!! __('dashboard.lock_screen') !!}
@endsection

@push('style')
@endpush

@section('content')
    <div class="login-main-wrapper" dir="{{ Config::get('app.locale') == 'ar' ? 'rtl' : 'ltr' }}"
        data-textdirection="{{ Config::get('app.locale') == 'ar' ? 'rtl' : 'ltr' }}">

        <!-- 1. Visual/Welcome Side -->
        <div class="welcome-side">
            <div class="welcome-content-premium">
                <span class="badge-welcome">MJK-ALTHANI GROUP</span>
                <h1 class="welcome-title">
                    {!! __('auth.welcome_title') !!}
                </h1>
                <p class="welcome-text">
                    {!! __('auth.welcome_desc') !!}
                </p>
                <div class="welcome-footer">
                    <div class="footer-stats">
                        <span class="stat-item"><i class="fas fa-shield-alt"></i> {!! __('dashboard.secured_session') ?? 'Secured Session' !!}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Form Side -->
        <div class="form-side">
            <!-- Language Toggle -->
            <div class="lang-toggle-container">
                @php
                    $currentLocale = Lang();
                    $targetLocale = $currentLocale == 'ar' ? 'en' : 'ar';
                    $targetNative = LaravelLocalization::getSupportedLocales()[$targetLocale]['native'];
                @endphp
                <a href="{{ LaravelLocalization::getLocalizedURL($targetLocale, null, [], true) }}" class="btn-lang-toggle">
                    <i class="fas fa-language"></i>
                    <span>{{ $targetNative }}</span>
                </a>
            </div>

            <div class="login-form-box">
                <!-- User Profile -->
                <div class="text-center">
                    @php
                        $user = user();
                        $photoUrl = $user->userPhoto();
                        $colors = ['#5A8DEE', '#FDAC41', '#FF5B5C', '#39DA8A', '#00CFDD', '#7117EA', '#272727'];
                        $charIndex = abs(crc32($user->name)) % count($colors);
                        $bgColor = $colors[$charIndex];
                    @endphp

                    <div class="lock-avatar-premium">
                        @if ($photoUrl)
                            <img src="{!! $photoUrl !!}" alt="User Avatar">
                        @else
                            <div class="lock-avatar-initials" style="background-color: {!! $bgColor !!}">
                                {!! $user->initials !!}
                            </div>
                        @endif
                    </div>

                    <div class="form-header-modern">
                        <div class="lock-status-pill">
                            <span class="status-dot"></span>
                            {!! __('dashboard.active_session') ?? 'Session Locked' !!}
                        </div>
                        <h2>{{ $user ? $user->getTranslation('name', Lang()) : 'Admin' }}</h2>
                        <p>{!! __('auth.enter_password_to_unlock') ?? 'Enter your password to unlock the screen' !!}</p>
                    </div>
                </div>

                <form id="lock-form" action="{{ route('dashboard.unlock.screen') }}" method="POST" autocomplete="off"
                    class="modern-form" novalidate>
                    @csrf

                    <div class="form-group-modern">
                        <label class="form-label-modern">{!! __('auth.enter_you_password') !!}</label>
                        <div class="input-container-modern">
                            <input type="password" class="form-control-modern @error('password') is-invalid @enderror" 
                                name="password" id="lock-password"
                                placeholder="••••••••" autofocus autocomplete="off">
                            <i class="fas fa-lock input-icon-modern"></i>
                        </div>
                        @if($errors->has('password'))
                            <div class="text-danger font-weight-bold mt-1" style="display: block !important;">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <button type="submit" id="unlock-btn" class="btn-login-modern">
                        <span>{!! __('auth.unlock') !!}</span>
                        <i class="fas fa-key"></i>
                    </button>

                    <a href="{{ route('dashboard.logout') }}" class="different-account">
                        <i class="fas fa-sign-out-alt"></i>
                        {!! __('auth.sign_in_different_account') !!}
                    </a>
                </form>
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
