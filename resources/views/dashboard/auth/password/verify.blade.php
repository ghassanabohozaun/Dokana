@extends('layouts.dashboard.auth')

@section('title')
    {!! __('auth.confirm') !!}
@endsection

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
                        <span class="stat-item"><i class="fas fa-shield-check"></i> {!! __('auth.verify_password') !!}</span>
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
                <!-- Brand logo -->
                <div class="brand-logo-modern">
                    @if (setting()->logo)
                        <img src="{!! asset('uploads/settings/' . setting()->logo) !!}" alt="{{ setting()->site_name }}">
                    @else
                        <img src="{{ asset('assets/dashbaord/images/mjk/logo.png') }}" alt="MJK-ALTHANI">
                    @endif
                </div>

                <div class="form-header-modern">
                    <h2>{!! __('auth.verify_password') !!}</h2>
                    <p>{!! __('auth.reset_password_verification_code') !!}</p>
                </div>

                @if ($errors->has('error'))
                    <div class="alert alert-danger mb-4 py-3"
                        style="font-size: 0.82rem; border-radius: 12px; border: none; background: #fff1f2; color: #be123c;">
                        <i class="fas fa-exclamation-triangle me-2"></i> {!! $errors->first('error') !!}
                    </div>
                @endif

                <form action="{!! route('dashboard.password.post.verify') !!}" method="post" class="modern-form" autocomplete="off">
                    @csrf

                    <div class="form-group-modern">
                        <label class="form-label-modern">{!! __('auth.you_email_address') !!}</label>
                        <div class="input-container-modern">
                            <input type="email" class="form-control-modern" id="email" name="email"
                                value="{{ $email }}" readonly autocomplete="off">
                            <i class="fas fa-envelope input-icon-modern"></i>
                        </div>
                    </div>

                    <div class="form-group-modern">
                        <label class="form-label-modern">{!! __('auth.enter_reset_code') !!}</label>
                        <div class="input-container-modern">
                            <input type="text" class="form-control-modern @error('code') is-invalid @enderror"
                                id="code" name="code" placeholder="123456" required autofocus autocomplete="off">
                            <i class="fas fa-key input-icon-modern"></i>
                        </div>
                        @error('code')
                            <span style="color: #e11d48; font-size: 0.75rem; margin-top: 5px; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-login-modern">
                        <span>{!! __('auth.verify_password') !!}</span>
                        <i class="fas fa-shield-alt"></i>
                    </button>

                    <div style="text-align: center; margin-top: 24px;">
                        <a href="{!! route('dashboard.get.login') !!}" class="forgot-modern">
                            {!! __('auth.login') !!} <i class="fas fa-arrow-{{ Config::get('app.locale') == 'ar' ? 'left' : 'right' }}"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


