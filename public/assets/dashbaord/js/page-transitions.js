/**
 * Dokana Global Page Transition Engine
 * Features:
 * - Ultra-Smooth Initial Page Shimmer Skeleton Animation
 * - Zero-Double-Load on Browser Back/Forward (Hardware Navigation Protection)
 */

(function (window, $) {
    'use strict';

    const PageTransition = {
        skeletonOverlay: '#global-page-skeleton',
        timer: null,
        hasFinished: false,

        finish: function (duration) {
            if (this.hasFinished) return;
            this.hasFinished = true;

            const delay = typeof duration === 'number' ? duration : 200;
            const $skeleton = $(this.skeletonOverlay);
            if (!$skeleton.length) return;

            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                $skeleton.css('opacity', '0');
                setTimeout(() => {
                    $skeleton.addClass('hidden').css('visibility', 'hidden');
                    $('#main-page-content').css('opacity', '1');
                }, 150);
            }, delay);
        },

        hideImmediate: function () {
            this.hasFinished = true;
            clearTimeout(this.timer);
            const $skeleton = $(this.skeletonOverlay);
            if ($skeleton.length) {
                $skeleton.addClass('hidden').css({ 'opacity': '0', 'visibility': 'hidden' });
            }
            $('#main-page-content').css('opacity', '1');
        },

        init: function () {
            const self = this;

            // 1. If Browser Back/Forward Navigation, bypass immediately
            try {
                const navEntries = performance.getEntriesByType('navigation');
                if (navEntries.length > 0 && navEntries[0].type === 'back_forward') {
                    self.hideImmediate();
                    return;
                }
            } catch (e) {}

            if (document.documentElement.classList.contains('no-skeleton')) {
                self.hideImmediate();
                return;
            }

            // 2. Normal initial page load: single clean reveal
            self.finish(180);

            // 3. BFCache Safety Events
            window.addEventListener('pageshow', function (event) {
                if (event.persisted) {
                    self.hideImmediate();
                }
            });

            window.addEventListener('pagehide', function () {
                self.hideImmediate();
            });
        }
    };

    window.PageTransition = PageTransition;

    // Single DOM ready execution
    $(document).ready(function () {
        PageTransition.init();
    });

})(window, jQuery);
