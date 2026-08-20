/**
 * Dokana Global Page Transition Engine
 * Features:
 * - Full-Page Shimmer Skeleton (Active on Refresh F5 and Between-Screen Navigation)
 * - Smooth Entry Micro-Fade Transition
 * - BFCache and Multi-Tab Navigation Safety
 */

(function (window, $) {
    'use strict';

    const PageTransition = {
        skeletonOverlay: '#global-page-skeleton',

        start: function () {
            const $skeleton = $(this.skeletonOverlay);
            $skeleton.removeClass('hidden').css({ 'opacity': '1', 'visibility': 'visible' });
            $('#main-page-content').css('opacity', '0.1');
        },

        finish: function () {
            const $skeleton = $(this.skeletonOverlay);
            $skeleton.css('opacity', '0');
            setTimeout(() => {
                $skeleton.addClass('hidden');
                $('#main-page-content').css('opacity', '1');
            }, 300);
        },

        init: function () {
            const self = this;

            // 1. Reveal page smoothly on initial load / refresh (F5)
            setTimeout(() => {
                self.finish();
            }, 350);

            // 2. Intercept navigation links
            $(document).on('click', 'a[href]', function (e) {
                const href = $(this).attr('href');
                const target = $(this).attr('target');

                // Ignore anchors, javascript links, downloads, modals, tabs, pagination, modifier keys
                if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript:') ||
                    href.startsWith('mailto:') || href.startsWith('tel:') || target === '_blank' ||
                    e.ctrlKey || e.metaKey || e.shiftKey || e.which === 2 ||
                    $(this).data('no-loader') !== undefined || $(this).data('toggle') || $(this).data('bs-toggle') ||
                    $(this).hasClass('dropdown-toggle') || $(this).hasClass('nav-link') && $(this).attr('role') === 'tab' ||
                    $(this).closest('.pagination').length > 0) {
                    return;
                }

                // Check if internal domain link
                try {
                    const url = new URL(this.href, window.location.origin);
                    if (url.origin === window.location.origin && (url.pathname !== window.location.pathname || url.search !== window.location.search)) {
                        self.start();
                    }
                } catch (err) {
                    // Fallback
                }
            });

            // 3. Intercept ONLY regular full-page form submissions (Exclude AJAX, Modals, Filters)
            $(document).on('submit', 'form:not(.ajax-form):not(.js-filter-form):not([data-ajax]):not([data-table-id]):not([data-no-loader]):not([target="_blank"])', function (e) {
                if ($(this).closest('.modal').length > 0 || $(this).hasClass('ajax-form') || $(this).hasClass('js-filter-form') || e.isDefaultPrevented()) {
                    return;
                }
                self.start();
            });

            // 4. BFCache (Browser Back/Forward navigation)
            window.addEventListener('pageshow', function () {
                self.finish();
            });
        }
    };

    window.PageTransition = PageTransition;

    $(document).ready(function () {
        PageTransition.init();
    });

})(window, jQuery);

