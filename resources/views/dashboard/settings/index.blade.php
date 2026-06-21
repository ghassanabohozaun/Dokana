@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
    {{-- Unified styles in pages.css --}}
@endpush

@section('content')
    <div class="app-content content">
        <form class="form" id="settings_form" action="" method="post" enctype="multipart/form-data" novalidate>
            @csrf
            @method('PUT')
            <input type="hidden" id='id' name="id" value="{!! setting()->id !!}">

            <div class="content-wrapper">
                <div class="content-header row align-items-center mb-2">
                    <!-- begin: content header left-->
                    <div class="content-header-left col-md-6 col-12 mb-2 mb-md-0">
                        <div class="row breadcrumbs-top">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb premium-breadcrumb shadow-sm">
                                    <li class="breadcrumb-item">
                                        <a href="{!! route('dashboard.index') !!}">
                                            <i class="fas fa-home"></i> {!! __('dashboard.home') !!}
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active font-weight-bold">
                                        {!! __('settings.settings') !!}
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    <!-- end: content header left-->

                    <!-- begin: content header right-->
                    <div class="content-header-right col-md-6 col-12 text-md-right mb-2">
                        <div class="d-flex justify-content-md-end justify-content-center gap-2">
                            @can('settings_update')
                            <button class="btn btn-premium-save" type="submit" id="saveBtn">
                                <i class="fas fa-save mr-2 save-icon"></i>
                                <i class="fas fa-spinner fa-spin spinner_loading d-none mr-2"></i>
                                {!! __('general.save') !!}
                            </button>
                            @endcan
                        </div>
                    </div>
                    <!-- end: content header right-->
                </div>
                <!-- end :content header -->

                <!-- begin: content body -->
                <div class="content-body">
                    <section id="basic-form-layouts">
                        <div class="row">
                            <!-- Main Form Column (8) -->
                            <div class="col-lg-8 col-md-12">

                                <!-- Card 1: Basic Information -->
                                <div class="card premium-card mb-3">
                                    <div class="premium-mandatory-header py-2">
                                        <div class="title-wrapper">
                                            <i class="fas fa-globe"></i>
                                            <span class="font-weight-bold">{!! __('settings.basic_settings_section') !!}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.site_name_ar') !!} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="site_name_ar" name="site_name[ar]"
                                                        value="{!! old('site_name.ar', setting()->getTranslation('site_name', 'ar')) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_site_name_ar') !!}">
                                                    <span class="text-danger error-text site_name_ar_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.site_name_en') !!} <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" id="site_name_en" name="site_name[en]"
                                                        value="{!! old('site_name.en', setting()->getTranslation('site_name', 'en')) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_site_name_en') !!}">
                                                    <span class="text-danger error-text site_name_en_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.currency') !!}</label>
                                                    <select name="currency_id" id="currency_id"
                                                        class="form-control premium-input shadow-none">
                                                        <option value="">{!! __('settings.select_currency') !!}</option>
                                                        @foreach ($currencies as $currency)
                                                            <option value="{{ $currency->id }}"
                                                                {{ setting()->currency_id == $currency->id ? 'selected' : '' }}>
                                                                {{ app()->getLocale() == 'ar' ? $currency->name_ar : $currency->name_en }}
                                                                ({{ app()->getLocale() == 'ar' ? $currency->symbol_ar : $currency->symbol_en }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger error-text currency_id_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 2: Social Media -->
                                <div class="card premium-card mb-3">
                                    <div class="premium-mandatory-header py-2"
                                        style="border-bottom-color: var(--premium-success);">
                                        <div class="title-wrapper">
                                            <i class="fas fa-share-alt"></i>
                                            <span class="font-weight-bold">{!! __('settings.social_section') !!}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.facebook') !!}</label>
                                                    <input type="text" id="facebook" name="facebook"
                                                        value="{!! old('facebook', setting()->facebook) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_facebook') !!}">
                                                    <span class="text-danger error-text facebook_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.twitter') !!}</label>
                                                    <input type="text" id="twitter" name="twitter"
                                                        value="{!! old('twitter', setting()->twitter) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_twitter') !!}">
                                                    <span class="text-danger error-text twitter_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.instegram') !!}</label>
                                                    <input type="text" id="instegram" name="instegram"
                                                        value="{!! old('instegram', setting()->instegram) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_instegram') !!}">
                                                    <span class="text-danger error-text instegram_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.youtube') !!}</label>
                                                    <input type="text" id="youtube" name="youtube"
                                                        value="{!! old('youtube', setting()->youtube) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_youtube') !!}">
                                                    <span class="text-danger error-text youtube_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 3: Contact Information -->
                                <div class="card premium-card mb-3">
                                    <div class="premium-mandatory-header py-2"
                                        style="border-bottom-color: var(--premium-info);">
                                        <div class="title-wrapper">
                                            <i class="fas fa-headset"></i>
                                            <span class="font-weight-bold">{!! __('settings.contact_section') !!}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.phone') !!}</label>
                                                    <input type="text" id="phone" name="phone"
                                                        value="{!! old('phone', setting()->phone) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_phone') !!}">
                                                    <span class="text-danger error-text phone_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.mobile') !!}</label>
                                                    <input type="text" id="mobile" name="mobile"
                                                        value="{!! old('mobile', setting()->mobile) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_mobile') !!}">
                                                    <span class="text-danger error-text mobile_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.whatsapp') !!}</label>
                                                    <input type="text" id="whatsapp" name="whatsapp"
                                                        value="{!! old('whatsapp', setting()->whatsapp) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_whatsapp') !!}">
                                                    <span class="text-danger error-text whatsapp_error"></span>
                                                </div>
                                            </div>

                                            <div class="col-md-6 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.email') !!}</label>
                                                    <input type="email" id="email" name="email"
                                                        value="{!! old('email', setting()->email) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_email') !!}">
                                                    <span class="text-danger error-text email_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.email_support') !!}</label>
                                                    <input type="email" id="email_support" name="email_support"
                                                        value="{!! old('email_support', setting()->email_support) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_email_support') !!}">
                                                    <span class="text-danger error-text email_support_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card 4: Auth Welcome Content -->
                                <div class="card premium-card mb-3">
                                    <div class="premium-mandatory-header py-2"
                                        style="border-bottom-color: var(--premium-warning);">
                                        <div class="title-wrapper">
                                            <i class="fas fa-sign-in-alt"></i>
                                            <span class="font-weight-bold">{!! __('settings.auth_welcome_section') !!}</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Badge & Footer -->
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_badge') !!}</label>
                                                    <input type="text" id="auth_welcome_badge"
                                                        name="auth_welcome_badge[{{ app()->getLocale() }}]"
                                                        value="{!! old(
                                                            'auth_welcome_badge.' . app()->getLocale(),
                                                            setting()->getTranslation('auth_welcome_badge', app()->getLocale()),
                                                        ) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_auth_welcome_badge') !!}">
                                                    <span
                                                        class="text-danger error-text auth_welcome_badge_ar_error"></span>
                                                    <span
                                                        class="text-danger error-text auth_welcome_badge_en_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_footer') !!}</label>
                                                    <input type="text" id="auth_welcome_footer"
                                                        name="auth_welcome_footer[{{ app()->getLocale() }}]"
                                                        value="{!! old(
                                                            'auth_welcome_footer.' . app()->getLocale(),
                                                            setting()->getTranslation('auth_welcome_footer', app()->getLocale()),
                                                        ) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_auth_welcome_footer') !!}">
                                                    <span
                                                        class="text-danger error-text auth_welcome_footer_ar_error"></span>
                                                    <span
                                                        class="text-danger error-text auth_welcome_footer_en_error"></span>
                                                </div>
                                            </div>

                                            <!-- Title Ar & En -->
                                            <div class="col-md-6 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_title') !!} (AR)</label>
                                                    <input type="text" id="auth_welcome_title_ar"
                                                        name="auth_welcome_title[ar]" value="{!! old('auth_welcome_title.ar', setting()->getTranslation('auth_welcome_title', 'ar')) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_auth_welcome_title') !!}">
                                                    <span
                                                        class="text-danger error-text auth_welcome_title_ar_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_title') !!} (EN)</label>
                                                    <input type="text" id="auth_welcome_title_en"
                                                        name="auth_welcome_title[en]" value="{!! old('auth_welcome_title.en', setting()->getTranslation('auth_welcome_title', 'en')) !!}"
                                                        class="form-control premium-input shadow-none"
                                                        placeholder="{!! __('settings.enter_auth_welcome_title') !!}">
                                                    <span
                                                        class="text-danger error-text auth_welcome_title_en_error"></span>
                                                </div>
                                            </div>

                                            <!-- Description Ar -->
                                            <div class="col-md-12 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_desc') !!} (AR)</label>
                                                    <textarea name="auth_welcome_desc[ar]" id="auth_welcome_desc_ar" class="form-control premium-input shadow-none"
                                                        rows="3" placeholder="{!! __('settings.enter_auth_welcome_desc') !!}">{!! old('auth_welcome_desc.ar', setting()->getTranslation('auth_welcome_desc', 'ar')) !!}</textarea>
                                                    <span class="text-danger error-text auth_welcome_desc_ar_error"></span>
                                                </div>
                                            </div>

                                            <!-- Description En -->
                                            <div class="col-md-12 mt-1">
                                                <div class="premium-form-group mb-2">
                                                    <label class="premium-label">{!! __('settings.auth_welcome_desc') !!} (EN)</label>
                                                    <textarea name="auth_welcome_desc[en]" id="auth_welcome_desc_en" class="form-control premium-input shadow-none"
                                                        rows="3" placeholder="{!! __('settings.enter_auth_welcome_desc') !!}">{!! old('auth_welcome_desc.en', setting()->getTranslation('auth_welcome_desc', 'en')) !!}</textarea>
                                                    <span class="text-danger error-text auth_welcome_desc_en_error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sidebar Area (4) -->
                            <div class="col-lg-4 col-md-12">
                                <div class="sticky-top" style="top: 20px;">
                                    <!-- Identity & Media Card -->
                                    <div class="identity-summary-card mb-3">
                                        <div class="premium-mandatory-header py-2">
                                            <div class="title-wrapper">
                                                <i class="fas fa-images"></i>
                                                <span class="font-weight-bold">{!! __('settings.media_section') !!}</span>
                                            </div>
                                        </div>

                                        <div class="premium-form-group mb-3">
                                            <label class="premium-label">{!! __('settings.logo') !!}</label>
                                            <div class="premium-photo-container">
                                                <input type="file" name="logo" id="settings_logo"
                                                    class="form-control" accept="image/*" data-show-caption="true"
                                                    data-show-upload="false">
                                            </div>
                                            <span class="text-danger error-text logo_error"></span>
                                        </div>

                                        <div class="premium-form-group">
                                            <label class="premium-label">{!! __('settings.favicon') !!}</label>
                                            <div class="premium-photo-container">
                                                <input type="file" id="settings_favicon" name="favicon"
                                                    class="form-control" accept="image/*" data-show-caption="true"
                                                    data-show-upload="false">
                                            </div>
                                            <span class="text-danger error-text favicon_error"></span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                </div>
                </section>
            </div>
            <!-- end: content body -->
    </div>
    </form>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function resetUpdateSettings() {
            let errors = ['site_name_ar', 'site_name_en', 'currency_id', 'facebook', 'twitter', 'instegram', 'youtube',
                'phone', 'mobile',
                'whatsapp', 'email', 'email_support', 'logo', 'favicon',
                'auth_welcome_title_ar', 'auth_welcome_title_en', 'auth_welcome_desc_ar', 'auth_welcome_desc_en',
                'auth_welcome_badge_ar', 'auth_welcome_badge_en', 'auth_welcome_footer_ar', 'auth_welcome_footer_en'
            ];
            $.each(errors, function(index, id) {
                let field = $('#' + id);
                field.removeClass('is-invalid-premium');
                let photoWrapper = field.closest('.premium-photo-container');
                if (photoWrapper) photoWrapper.removeClass('is-invalid-premium');

                let errorSpan = field.closest('.premium-form-group').find('.error-text');
                errorSpan.text('');
            });
        };

        $('#settings_form').on('submit', function(e) {
            e.preventDefault();
            resetUpdateSettings();
            var settings_id = "{{ setting()->id }}";
            var data = new FormData(this);
            var url = "{!! route('dashboard.settings.update', 'id') !!}".replace('id', settings_id);

            $.ajax({
                url: url,
                data: data,
                type: "POST",
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $('.spinner_loading').removeClass('d-none');
                    $('.save-icon').addClass('d-none');
                    $('#saveBtn').prop('disabled', true);
                },
                success: function(data) {
                    if (data.status == true) {
                        $('.site_name_logo_section').load(location.href + ' .site_name_logo_section');
                        flasher.success("{!! __('general.update_success_message') !!}");
                    } else {
                        flasher.error("{!! __('general.upload_error_message') !!}");
                    }
                },
                error: function(reject) {
                    var response = $.parseJSON(reject.responseText);
                    $.each(response.errors, function(key, value) {
                        if (key == 'site_name.en') key = 'site_name_en';
                        if (key == 'site_name.ar') key = 'site_name_ar';
                        if (key == 'auth_welcome_title.ar') key = 'auth_welcome_title_ar';
                        if (key == 'auth_welcome_title.en') key = 'auth_welcome_title_en';
                        if (key == 'auth_welcome_desc.ar') key = 'auth_welcome_desc_ar';
                        if (key == 'auth_welcome_desc.en') key = 'auth_welcome_desc_en';
                        if (key == 'auth_welcome_badge.ar') key = 'auth_welcome_badge_ar';
                        if (key == 'auth_welcome_badge.en') key = 'auth_welcome_badge_en';
                        if (key == 'auth_welcome_footer.ar') key = 'auth_welcome_footer_ar';
                        if (key == 'auth_welcome_footer.en') key = 'auth_welcome_footer_en';

                        let field = $('#' + key);
                        field.addClass('is-invalid-premium');
                        let photoWrapper = field.closest('.premium-photo-container');
                        if (photoWrapper) photoWrapper.addClass('is-invalid-premium');

                        let errorSpan = field.closest('.premium-form-group').find(
                            '.error-text');
                        errorSpan.text(value[0]);
                    });
                },
                complete: function() {
                    $('.spinner_loading').addClass('d-none');
                    $('.save-icon').removeClass('d-none');
                    $('#saveBtn').prop('disabled', false);
                }
            });
        });

        var lang = "{!! Lang() !!}";
        var logo = "{!! setting()->logo !!}";
        var favicon = "{!! setting()->favicon !!}";

        var fileInputConfig = {
            theme: 'fa5',
            language: lang,
            allowedFileTypes: ['image'],
            maxFileCount: 1,
            showCancel: false,
            showUpload: false,
            dropZoneEnabled: false,
            initialPreviewAsData: true,
            browseClass: "btn btn-sm btn-primary px-3",
            removeClass: "btn btn-danger",
            removeLabel: "{!! __('general.delete') !!}",
            browseLabel: "{!! __('general.choose_file') !!}"
        };

        $("#settings_logo").fileinput(Object.assign({}, fileInputConfig, {
            initialPreview: logo === '' ? [] : ["{!! asset('/uploads/settings/' . setting()->logo) !!}"]
        }));

        $("#settings_favicon").fileinput(Object.assign({}, fileInputConfig, {
            initialPreview: favicon === '' ? [] : ["{!! asset('/uploads/settings/' . setting()->favicon) !!}"]
        }));


    </script>
@endpush
