@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<form class="form" id="settings_form" action="" method="post" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')
    <input type="hidden" id="id" name="id" value="{!! setting()->id !!}">

    <div class="space-y-6">
        <!-- Top Header & Action Bar -->
        <div class="flex items-center justify-between gap-4 pb-1">
            <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500">
                <a href="{!! route('dashboard.index') !!}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                    <i class="fas fa-home text-xs"></i>
                    <span>{!! __('dashboard.home') !!}</span>
                </a>
                <span>/</span>
                <span class="text-slate-700 dark:text-slate-200 font-bold">{!! __('settings.settings') !!}</span>
            </nav>

            <!-- Save Action Button -->
            <div>
                @can('settings_update')
                <button type="submit" id="saveBtn" class="btn-primary-gradient text-xs">
                    <i class="fas fa-save text-xs save-icon"></i>
                    <i class="fas fa-spinner fa-spin spinner_loading text-xs hidden d-none"></i>
                    <span>{!! __('general.save') !!}</span>
                </button>
                @endcan
            </div>
        </div>

        <!-- Main Form Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Area (8 cols) -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- 1. Basic Information Card -->
                <div class="dash-card overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm shadow-xs">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                                {!! __('settings.basic_settings_section') !!}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Site Name AR -->
                            <div>
                                <label class="form-label-modern" for="site_name_ar">
                                    {!! __('settings.site_name_ar') !!} <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="site_name_ar" name="site_name[ar]"
                                    value="{!! old('site_name.ar', setting()->getTranslation('site_name', 'ar')) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_site_name_ar') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text site_name_ar_error block mt-1"></span>
                            </div>

                            <!-- Site Name EN -->
                            <div>
                                <label class="form-label-modern" for="site_name_en">
                                    {!! __('settings.site_name_en') !!} <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="site_name_en" name="site_name[en]"
                                    value="{!! old('site_name.en', setting()->getTranslation('site_name', 'en')) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_site_name_en') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text site_name_en_error block mt-1"></span>
                            </div>
                        </div>

                        <!-- Currency Selection -->
                        <div>
                            <label class="form-label-modern" for="currency_id">
                                {!! __('settings.currency') !!}
                            </label>
                            <select name="currency_id" id="currency_id" class="form-input-modern select2">
                                <option value="">{!! __('settings.select_currency') !!}</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ setting()->currency_id == $currency->id ? 'selected' : '' }}>
                                        {{ app()->getLocale() == 'ar' ? $currency->name_ar : $currency->name_en }}
                                        ({{ app()->getLocale() == 'ar' ? $currency->symbol_ar : $currency->symbol_en }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-xs text-rose-500 error-text currency_id_error block mt-1"></span>
                        </div>
                    </div>
                </div>

                <!-- 2. Contact Information Card -->
                <div class="dash-card overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 text-sm shadow-xs">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                                {!! __('settings.contact_section') !!}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Phone -->
                            <div>
                                <label class="form-label-modern" for="phone">{!! __('settings.phone') !!}</label>
                                <input type="text" id="phone" name="phone" value="{!! old('phone', setting()->phone) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_phone') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text phone_error block mt-1"></span>
                            </div>

                            <!-- Mobile -->
                            <div>
                                <label class="form-label-modern" for="mobile">{!! __('settings.mobile') !!}</label>
                                <input type="text" id="mobile" name="mobile" value="{!! old('mobile', setting()->mobile) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_mobile') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text mobile_error block mt-1"></span>
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label class="form-label-modern" for="whatsapp">{!! __('settings.whatsapp') !!}</label>
                                <input type="text" id="whatsapp" name="whatsapp" value="{!! old('whatsapp', setting()->whatsapp) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_whatsapp') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text whatsapp_error block mt-1"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Email -->
                            <div>
                                <label class="form-label-modern" for="email">{!! __('settings.email') !!}</label>
                                <input type="email" id="email" name="email" value="{!! old('email', setting()->email) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_email') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text email_error block mt-1"></span>
                            </div>

                            <!-- Email Support -->
                            <div>
                                <label class="form-label-modern" for="email_support">{!! __('settings.email_support') !!}</label>
                                <input type="email" id="email_support" name="email_support" value="{!! old('email_support', setting()->email_support) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_email_support') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text email_support_error block mt-1"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Social Media Card -->
                <div class="dash-card overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-sm shadow-xs">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                                {!! __('settings.social_section') !!}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Facebook -->
                            <div>
                                <label class="form-label-modern" for="facebook">
                                    <i class="fab fa-facebook text-blue-600 me-1"></i> {!! __('settings.facebook') !!}
                                </label>
                                <input type="text" id="facebook" name="facebook" value="{!! old('facebook', setting()->facebook) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_facebook') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text facebook_error block mt-1"></span>
                            </div>

                            <!-- Twitter -->
                            <div>
                                <label class="form-label-modern" for="twitter">
                                    <i class="fab fa-twitter text-sky-500 me-1"></i> {!! __('settings.twitter') !!}
                                </label>
                                <input type="text" id="twitter" name="twitter" value="{!! old('twitter', setting()->twitter) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_twitter') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text twitter_error block mt-1"></span>
                            </div>

                            <!-- Instagram -->
                            <div>
                                <label class="form-label-modern" for="instegram">
                                    <i class="fab fa-instagram text-rose-500 me-1"></i> {!! __('settings.instegram') !!}
                                </label>
                                <input type="text" id="instegram" name="instegram" value="{!! old('instegram', setting()->instegram) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_instegram') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text instegram_error block mt-1"></span>
                            </div>

                            <!-- YouTube -->
                            <div>
                                <label class="form-label-modern" for="youtube">
                                    <i class="fab fa-youtube text-red-600 me-1"></i> {!! __('settings.youtube') !!}
                                </label>
                                <input type="text" id="youtube" name="youtube" value="{!! old('youtube', setting()->youtube) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_youtube') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text youtube_error block mt-1"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Auth & Welcome Screen Settings -->
                <div class="dash-card overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-sm shadow-xs">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                                {!! __('settings.auth_welcome_section') !!}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Auth Badge -->
                            <div>
                                <label class="form-label-modern" for="auth_welcome_badge">
                                    {!! __('settings.auth_welcome_badge') !!}
                                </label>
                                <input type="text" id="auth_welcome_badge"
                                    name="auth_welcome_badge[{{ app()->getLocale() }}]"
                                    value="{!! old('auth_welcome_badge.' . app()->getLocale(), setting()->getTranslation('auth_welcome_badge', app()->getLocale())) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_badge') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text auth_welcome_badge_ar_error auth_welcome_badge_en_error block mt-1"></span>
                            </div>

                            <!-- Auth Footer -->
                            <div>
                                <label class="form-label-modern" for="auth_welcome_footer">
                                    {!! __('settings.auth_welcome_footer') !!}
                                </label>
                                <input type="text" id="auth_welcome_footer"
                                    name="auth_welcome_footer[{{ app()->getLocale() }}]"
                                    value="{!! old('auth_welcome_footer.' . app()->getLocale(), setting()->getTranslation('auth_welcome_footer', app()->getLocale())) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_footer') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text auth_welcome_footer_ar_error auth_welcome_footer_en_error block mt-1"></span>
                            </div>
                        </div>

                        <!-- Titles AR / EN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-modern" for="auth_welcome_title_ar">
                                    {!! __('settings.auth_welcome_title') !!} (AR)
                                </label>
                                <input type="text" id="auth_welcome_title_ar" name="auth_welcome_title[ar]"
                                    value="{!! old('auth_welcome_title.ar', setting()->getTranslation('auth_welcome_title', 'ar')) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_title') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text auth_welcome_title_ar_error block mt-1"></span>
                            </div>

                            <div>
                                <label class="form-label-modern" for="auth_welcome_title_en">
                                    {!! __('settings.auth_welcome_title') !!} (EN)
                                </label>
                                <input type="text" id="auth_welcome_title_en" name="auth_welcome_title[en]"
                                    value="{!! old('auth_welcome_title.en', setting()->getTranslation('auth_welcome_title', 'en')) !!}"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_title') !!}" autocomplete="off">
                                <span class="text-xs text-rose-500 error-text auth_welcome_title_en_error block mt-1"></span>
                            </div>
                        </div>

                        <!-- Description AR / EN -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label-modern" for="auth_welcome_desc_ar">
                                    {!! __('settings.auth_welcome_desc') !!} (AR)
                                </label>
                                <textarea name="auth_welcome_desc[ar]" id="auth_welcome_desc_ar" rows="3"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_desc') !!}">{!! old('auth_welcome_desc.ar', setting()->getTranslation('auth_welcome_desc', 'ar')) !!}</textarea>
                                <span class="text-xs text-rose-500 error-text auth_welcome_desc_ar_error block mt-1"></span>
                            </div>

                            <div>
                                <label class="form-label-modern" for="auth_welcome_desc_en">
                                    {!! __('settings.auth_welcome_desc') !!} (EN)
                                </label>
                                <textarea name="auth_welcome_desc[en]" id="auth_welcome_desc_en" rows="3"
                                    class="form-input-modern" placeholder="{!! __('settings.enter_auth_welcome_desc') !!}">{!! old('auth_welcome_desc.en', setting()->getTranslation('auth_welcome_desc', 'en')) !!}</textarea>
                                <span class="text-xs text-rose-500 error-text auth_welcome_desc_en_error block mt-1"></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Area (4 cols) -->
            <div class="lg:col-span-4 space-y-6 sticky top-6">
                
                <!-- 5. Media & Identity Card -->
                <div class="dash-card overflow-hidden">
                    <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/80">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 text-sm shadow-xs">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">
                                {!! __('settings.media_section') !!}
                            </h3>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        
                        <!-- Logo Upload -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="form-label-modern mb-0">{!! __('settings.logo') !!}</label>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">WEBP, PNG, JPG, SVG</span>
                            </div>
                            
                            <input type="file" name="logo" id="settings_logo" class="sr-only" accept="image/*,.webp,.png,.jpg,.jpeg,.svg,.ico,.avif">
                            
                            @php
                                $hasLogo = !empty(setting()->logo) && file_exists(public_path('uploads/settings/' . setting()->logo));
                                $logoUrl = $hasLogo ? asset('uploads/settings/' . setting()->logo) : '';
                            @endphp

                            <!-- Empty Dropzone -->
                            <div id="dropzone_empty_logo" onclick="document.getElementById('settings_logo').click()"
                                class="group {{ $hasLogo ? 'hidden' : 'flex' }} flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl bg-slate-50/60 dark:bg-slate-800/40 hover:bg-indigo-50/20 dark:hover:bg-indigo-950/20 cursor-pointer transition-all duration-200 text-center">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                                    {!! __('general.click_or_drag_to_upload') !!}
                                </p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    {!! __('general.max_size') !!}: 5MB
                                </p>
                            </div>

                            <!-- Preview Box -->
                            <div id="dropzone_preview_logo" class="{{ $hasLogo ? 'flex' : 'hidden' }} items-center justify-between p-3.5 border border-slate-200 dark:border-slate-700 rounded-2xl bg-slate-50/70 dark:bg-slate-800/60">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-14 w-14 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-1.5 flex items-center justify-center shadow-xs flex-shrink-0">
                                        <img id="preview_img_logo" src="{{ $logoUrl }}" alt="Logo" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                            {!! __('settings.logo') !!}
                                        </span>
                                        <span class="badge-pill badge-pill-success text-[10px] mt-1 inline-flex">{!! __('general.active') !!}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <button type="button" onclick="document.getElementById('settings_logo').click()" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40" title="{!! __('general.change') !!}">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="text-xs text-rose-500 error-text logo_error block mt-1"></span>
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="form-label-modern mb-0">{!! __('settings.favicon') !!}</label>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500">WEBP, ICO, PNG (32x32)</span>
                            </div>
                            
                            <input type="file" name="favicon" id="settings_favicon" class="sr-only" accept="image/*,.webp,.png,.jpg,.jpeg,.svg,.ico,.avif">
                            
                            @php
                                $hasFavicon = !empty(setting()->favicon) && file_exists(public_path('uploads/settings/' . setting()->favicon));
                                $faviconUrl = $hasFavicon ? asset('uploads/settings/' . setting()->favicon) : '';
                            @endphp

                            <!-- Empty Dropzone -->
                            <div id="dropzone_empty_favicon" onclick="document.getElementById('settings_favicon').click()"
                                class="group {{ $hasFavicon ? 'hidden' : 'flex' }} flex-col items-center justify-center p-6 border-2 border-dashed border-slate-200 dark:border-slate-700 hover:border-indigo-500 dark:hover:border-indigo-400 rounded-2xl bg-slate-50/60 dark:bg-slate-800/40 hover:bg-indigo-50/20 dark:hover:bg-indigo-950/20 cursor-pointer transition-all duration-200 text-center">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 mb-2 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-cloud-upload-alt text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-0.5">
                                    {!! __('general.click_or_drag_to_upload') !!}
                                </p>
                                <p class="text-[11px] text-slate-400 dark:text-slate-500">
                                    ICO, PNG, SVG
                                </p>
                            </div>

                            <!-- Preview Box -->
                            <div id="dropzone_preview_favicon" class="{{ $hasFavicon ? 'flex' : 'hidden' }} items-center justify-between p-3.5 border border-slate-200 dark:border-slate-700 rounded-2xl bg-slate-50/70 dark:bg-slate-800/60">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="h-14 w-14 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-1.5 flex items-center justify-center shadow-xs flex-shrink-0">
                                        <img id="preview_img_favicon" src="{{ $faviconUrl }}" alt="Favicon" class="max-h-full max-w-full object-contain">
                                    </div>
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-slate-800 dark:text-white block truncate">
                                            {!! __('settings.favicon') !!}
                                        </span>
                                        <span class="badge-pill badge-pill-success text-[10px] mt-1 inline-flex">{!! __('general.active') !!}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <button type="button" onclick="document.getElementById('settings_favicon').click()" class="btn-icon-action text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40" title="{!! __('general.change') !!}">
                                        <i class="fas fa-sync text-xs"></i>
                                    </button>
                                </div>
                            </div>
                            <span class="text-xs text-rose-500 error-text favicon_error block mt-1"></span>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</form>
@endsection

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        // Initialize Select2 on Settings page
        if ($('#currency_id').length) {
            $('#currency_id').select2({
                width: '100%'
            });
        }

        // Setup File Upload Previews
        function setupImagePreview(inputId, emptyId, previewId, imgId) {
            const input = document.getElementById(inputId);
            const emptyBox = document.getElementById(emptyId);
            const previewBox = document.getElementById(previewId);
            const img = document.getElementById(imgId);

            if (!input) return;

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        if (emptyBox) emptyBox.classList.add('hidden');
                        if (previewBox) {
                            previewBox.classList.remove('hidden');
                            previewBox.classList.add('flex');
                        }
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        setupImagePreview('settings_logo', 'dropzone_empty_logo', 'dropzone_preview_logo', 'preview_img_logo');
        setupImagePreview('settings_favicon', 'dropzone_empty_favicon', 'dropzone_preview_favicon', 'preview_img_favicon');

        // Form Submit via AJAX
        $('#settings_form').on('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            $('.error-text').text('');
            $('.form-input-modern').removeClass('border-rose-500');

            var settings_id = "{{ setting()->id }}";
            var data = new FormData(this);
            var url = "{!! route('dashboard.settings.update', ':id') !!}".replace(':id', settings_id);

            var $btn = $('#saveBtn');
            var $spinner = $btn.find('.spinner_loading');
            var $icon = $btn.find('.save-icon');

            $.ajax({
                url: url,
                data: data,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $spinner.removeClass('hidden d-none');
                    $icon.addClass('hidden d-none');
                    $btn.prop('disabled', true);
                },
                success: function(response) {
                    if (response.status === true) {
                        if (window.PremiumToast) {
                            window.PremiumToast.success("{!! __('general.update_success_message') !!}");
                        }
                    } else {
                        if (window.PremiumToast) {
                            window.PremiumToast.error(response.message || "{!! __('general.update_error_message') !!}");
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, msgs) {
                            var normalizedKey = key.replace('.', '_');
                            var $field = $('#' + normalizedKey);
                            if (!$field.length) {
                                $field = $('[name="' + key + '"]');
                            }
                            $field.addClass('border-rose-500');
                            $('.' + normalizedKey + '_error').text(msgs[0]);
                        });

                        if (window.PremiumToast) {
                            window.PremiumToast.error("{!! __('general.validation_error_message') ?? 'يرجى التأكد من صحة البيانات المدخلة' !!}");
                        }
                    } else {
                        if (window.PremiumToast) {
                            window.PremiumToast.error("{!! __('general.try_catch_error_message') !!}");
                        }
                    }
                },
                complete: function() {
                    $spinner.addClass('hidden d-none');
                    $icon.removeClass('hidden d-none');
                    $btn.prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
