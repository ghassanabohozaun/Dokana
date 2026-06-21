    <!-- 1. EXTERNAL LIBRARIES  -->
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/vendors.min.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/vendors/js/extensions/sweetalert.min.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/core/app-menu.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/core/app.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/customizer.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/extensions/sweet-alerts.js" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord') !!}/js/scripts/my-scripts.js" type="text/javascript"></script>


    <script src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/bootstrap-datepicker.min.js') }}">
    </script>
    @if (Lang() == 'ar')
        <script
            src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/locales/bootstrap-datepicker.ar.min.js') }}">
        </script>
    @endif
    <script src="{{ asset('assets/dashbaord/js/datepicker-initializer.js') }}?v=1.1"></script>

    <script src="{{ asset('assets/dashbaord/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{!! asset('vendor/fileInput/js/fileinput.min.js') !!}" type="text/javascript"></script>
    <script src="{!! asset('vendor/fileInput/themes/fa5/theme.min.js') !!}" type="text/javascript"></script>
    @if (Lang() == 'ar')
        <script src="{!! asset('vendor/fileInput/js/locales/ar.js') !!}" type="text/javascript"></script>
    @endif

    <script src="{!! asset('assets/dashbaord/js/ajax-table.js') !!}?v={{ time() }}" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord/js/premium-ajax-form.js') !!}?v={{ time() }}" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord/js/generic-select2.js') !!}?v={{ time() }}" type="text/javascript"></script>
    <script src="{!! asset('assets/dashbaord/js/premium-fileinput-initializer.js') !!}?v={{ time() }}" type="text/javascript"></script>

    <!-- 2. INLINE SCRIPTS & CONFIGURATIONS -->
    <script type="text/javascript">
        window.LockScreenConfig = {
            lock_route: "{{ route('dashboard.lock.screen') }}",
            idle_limit: 900 // 15 minutes
        };

        // Premium Global Settings
        window.PremiumSettings = {
            messages: {
                success: "{{ __('general.success') }}",
                error: "{{ __('general.error') }}",
                add_success: "{{ __('general.add_success_message') }}",
                update_success: "{{ __('general.update_success_message') }}",
                validation_error: "{{ __('general.validation_error_message') }}",
                access_denied: "{{ __('general.access_denied_msg') }}"
            }
        };

        // Global I18N Bridge
        window.DOKANA_I18N = {
            select2: {
                searching: function() {
                    return "{{ __('general.searching') }}";
                },
                noResults: function() {
                    return "{{ __('general.noResults2') }}";
                },
                errorLoading: function() {
                    return "{{ __('general.errorLoading') }}";
                },
                inputTooShort: function(args) {
                    return "{{ __('general.inputTooShort') }}";
                },
                inputTooLong: function(args) {
                    return "{{ __('general.inputTooLong') }}";
                }
            },
            common: {
                access_denied: "{{ __('dashboard.access_denied') }}",
                error: "{{ __('general.error') }}",
                ok: "{{ __('general.ok') }}",
                something_went_wrong: "{{ __('general.try_catch_error_message') }}"
            },
            fileinput: {
                browseLabel: "{!! __('general.choose_file') !!}",
                removeLabel: "{!! __('general.delete') !!}"
            }
        };

        // AJAX Global Setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // Global Password Visibility Toggle
        function togglePassword(inputId, icon) {
            var input = document.getElementById(inputId);
            if (!input) return;
            var isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            var wrapper = icon.parentElement;
            if (wrapper) {
                var icons = wrapper.getElementsByTagName('i');
                for (var i = 0; i < icons.length; i++) {
                    var ico = icons[i];
                    if (ico.classList.contains('fa-lock') || ico.classList.contains('fa-unlock-alt')) {
                        ico.className = isPassword ? 'fas fa-unlock-alt text-primary' : 'fas fa-lock text-primary';
                    } else if (ico.classList.contains('fa-eye') || ico.classList.contains('fa-eye-slash')) {
                        ico.className = isPassword ? 'fas fa-eye-slash pointer text-primary premium-icon-opposite' :
                            'fas fa-eye pointer text-primary premium-icon-opposite';
                    }
                }
            }
        }

        // Auto-close mobile navbar and sidebar when clicking outside
        $(document).on('click', function(event) {
            var $navbar = $('.header-navbar');
            var $mobileCollapse = $('#navbar-mobile');
            var $mainMenu = $('.main-menu');
            var $body = $('body');

            // 1. Handle Top Navbar Collapse
            if (!$navbar.is(event.target) && $navbar.has(event.target).length === 0 && $mobileCollapse.hasClass(
                    'show')) {
                $mobileCollapse.collapse('hide');
            }

            // 2. Handle Sidebar (main-menu) Collapse on Mobile
            // Check if we are on mobile and the menu is open (menu-open class)
            if ($body.hasClass('menu-open')) {
                // If click is not on the menu and not on the menu toggle button
                if (!$mainMenu.is(event.target) && $mainMenu.has(event.target).length === 0 &&
                    !$('.menu-toggle').is(event.target) && $('.menu-toggle').has(event.target).length === 0) {

                    // Trigger the toggle to close it
                    if (typeof Unison !== 'undefined') {
                        // Using the theme's built-in toggle if available
                        $('.menu-toggle').click();
                    } else {
                        // Fallback: manually remove classes
                        $body.removeClass('menu-open').addClass('menu-hide');
                    }
                }
            }
        });

        // BFCache Fix: Force reload on browser back button
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted || (typeof window.performance != "undefined" && window
                .performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    </script>
    <script src="{{ asset('assets/dashbaord/js/lock-screen-modern.js') }}"></script>
    <script src="{{ asset('assets/dashbaord/js/notifications.js') }}?v=1.0"></script>
