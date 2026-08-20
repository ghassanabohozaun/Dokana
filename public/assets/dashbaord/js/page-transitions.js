/**
 * Dokana Global Top Progress Bar
 * 100% Non-Blocking, Ultra-Fast Native Performance & AJAX Lifecycle Integration
 */

(function (window, $) {
    'use strict';

    const ProgressBar = {
        $bar: null,
        $inner: null,
        timer: null,
        safetyTimer: null,

        init: function () {
            this.$bar = $('#top-progress-bar');
            this.$inner = this.$bar.find('.top-progress-bar-inner');
            const self = this;

            // Flash complete on initial page ready
            self.complete();

            // 1. When user clicks a regular navigation link
            document.addEventListener('click', function (e) {
                const target = e.target.closest('a[href]');
                if (!target) return;

                const href = target.getAttribute('href');
                if (!href || href === '#' || href.startsWith('#') || href.startsWith('javascript:') ||
                    href.startsWith('mailto:') || href.startsWith('tel:') || target.target === '_blank' ||
                    e.ctrlKey || e.metaKey || e.shiftKey || e.which === 2 ||
                    target.hasAttribute('data-no-loader') || target.hasAttribute('data-toggle') ||
                    target.hasAttribute('data-bs-toggle') || target.classList.contains('dropdown-toggle') ||
                    target.closest('.pagination')) {
                    return;
                }

                self.start();
            }, { passive: true });

            // 2. Standard Form submissions
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.classList.contains('ajax-form') || form.classList.contains('js-filter-form') ||
                    form.hasAttribute('data-no-loader') || form.target === '_blank') {
                    return;
                }
                self.start();
            }, { passive: true });

            // 3. Global AJAX Lifecycle Integration (Auto-start & Auto-complete on AJAX save/load)
            $(document).ajaxStart(function () {
                self.start();
            });

            $(document).ajaxStop(function () {
                self.complete();
            });

            $(document).ajaxError(function () {
                self.complete();
            });

            // 4. Clean reset on BFCache restore
            window.addEventListener('pageshow', function () {
                self.reset();
            });

            window.addEventListener('pagehide', function () {
                self.reset();
            });
        },

        start: function () {
            if (!this.$bar || !this.$bar.length) return;
            clearTimeout(this.timer);
            clearTimeout(this.safetyTimer);
            this.$inner.css({ 'width': '0%', 'transition': 'none' });
            this.$bar.addClass('is-loading');

            requestAnimationFrame(() => {
                this.$inner.css({ 'width': '80%', 'transition': 'width 0.25s ease' });
            });

            // Safety Watchdog: never remain stuck if navigation is cancelled or local
            this.safetyTimer = setTimeout(() => {
                this.complete();
            }, 3000);
        },

        complete: function () {
            if (!this.$bar || !this.$bar.length) return;
            clearTimeout(this.timer);
            clearTimeout(this.safetyTimer);
            this.$inner.css({ 'width': '100%', 'transition': 'width 0.15s ease-out' });
            this.timer = setTimeout(() => {
                this.$bar.removeClass('is-loading');
                setTimeout(() => {
                    this.$inner.css({ 'width': '0%', 'transition': 'none' });
                }, 100);
            }, 120);
        },

        reset: function () {
            if (!this.$bar || !this.$bar.length) return;
            clearTimeout(this.timer);
            clearTimeout(this.safetyTimer);
            this.$bar.removeClass('is-loading');
            this.$inner.css({ 'width': '0%', 'transition': 'none' });
        }
    };

    window.DokanaProgressBar = ProgressBar;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => ProgressBar.init());
    } else {
        ProgressBar.init();
    }

})(window, jQuery);
