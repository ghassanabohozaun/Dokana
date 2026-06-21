@extends('layouts.dashboard.auth')

@section('title')
    {!! setting()->site_name !!} {!! __('auth.login') !!}
@endsection
@push('style')
@endpush

@section('content')
    <div class="login-main-wrapper" dir="{{ Config::get('app.locale') == 'ar' ? 'rtl' : 'ltr' }}"
        data-textdirection="{{ Config::get('app.locale') == 'ar' ? 'rtl' : 'ltr' }}">

        <!-- 1. Visual/Welcome Side (Hidden on Mobile) -->
        <div class="welcome-side">
            <div class="welcome-content-premium">
                <span class="badge-welcome">{!! setting()->auth_welcome_badge !!}</span>
                <h1 class="welcome-title">
                    {!! setting()->auth_welcome_title !!}
                </h1>
                <p class="welcome-text">
                    {!! setting()->auth_welcome_desc !!}
                </p>
                <div class="welcome-footer">
                    <div class="footer-stats">
                        <span class="stat-item"><i class="fas fa-briefcase"></i> {!! setting()->auth_welcome_footer !!}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Form Side / Side Pane -->
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
                <!-- Brand logo -->
                <div class="brand-logo-modern">
                    @if (setting()->logo)
                        <img src="{!! asset('uploads/settings/' . setting()->logo) !!}" alt="{{ setting()->site_name }}">
                    @else
                        <img src="{{ asset('assets/dashbaord/images/mjk/logo.png') }}" alt="MJK-ALTHANI">
                    @endif
                </div>

                <div class="form-header-modern">
                    <h2>{!! __('auth.login') !!}</h2>
                    <p>{!! __('auth.login_dashboard') !!}</p>
                </div>


                <form action="{!! route('dashboard.post.login') !!}" method="post" class="modern-form" autocomplete="off" novalidate>
                    @csrf

                    <!-- Email -->
                    <div class="premium-form-group">
                        <label class="form-label-modern">{!! __('auth.enter_you_email') !!}</label>
                        <div class="input-container-modern">
                            <input type="text" class="form-control-modern @error('email') is-invalid @enderror"
                                name="email" id="email" placeholder="email@example.com" autofocus
                                autocomplete="off">
                            <i class="fas fa-envelope input-icon-modern"></i>
                        </div>
                        @error('email')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="premium-form-group">
                        <label class="form-label-modern">{!! __('auth.enter_you_password') !!}</label>
                        <div class="input-container-modern">
                            <input type="password" class="form-control-modern @error('password') is-invalid @enderror"
                                name="password" id="password" placeholder="••••••••" autocomplete="new-password">
                            <i class="fas fa-lock input-icon-modern"></i>
                        </div>
                        @error('password')
                            <span class="text-danger error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-options-modern">
                        <label class="remember-modern">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>{!! __('auth.remmber_me') !!}</span>
                        </label>
                        <a href="{!! route('dashboard.password.get.email') !!}" class="forgot-modern">
                            {!! __('auth.forget_password') !!}
                        </a>
                    </div>

                    <button type="submit" class="btn-login-modern">
                        <span>{!! __('auth.login') !!}</span>
                        <i class="fas fa-arrow-{{ Config::get('app.locale') == 'ar' ? 'left' : 'right' }}"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection




