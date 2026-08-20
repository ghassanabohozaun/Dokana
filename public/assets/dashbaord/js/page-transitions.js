/**
 * Dokana Global Top Progress Bar
 * 100% Non-Blocking, Ultra-Fast Native Performance
 */

(function (window, $) {
    'use strict';

    const ProgressBar = {
        $bar: null,
        $inner: null,
        timer: null,

        init: function () {
            this.$bar = $('#top-progress-bar');
            this.$inner = this.$bar.find('.top-progress-bar-inner');
            const self = this;

            // Flash complete on initial page ready
            self.complete();

            // When user clicks a link, start bar without blocking native browser navigation
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

            // Form submissions
            document.addEventListener('submit', function (e) {
                const form = e.target;
                if (form.classList.contains('ajax-form') || form.classList.contains('js-filter-form') ||
                    form.hasAttribute('data-no-loader') || form.target === '_blank') {
                    return;
                }
                self.start();
            }, { passive: true });

            // Clean reset on BFCache restore
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
            this.$inner.css({ 'width': '0%', 'transition': 'none' });
            this.$bar.addClass('is-loading');

            requestAnimationFrame(() => {
                this.$inner.css({ 'width': '75%', 'transition': 'width 0.3s ease' });
            });
        },

        complete: function () {
            if (!this.$bar || !this.$bar.length) return;
            clearTimeout(this.timer);
            this.$inner.css({ 'width': '100%', 'transition': 'width 0.15s ease-out' });
            this.timer = setTimeout(() => {
                this.$bar.removeClass('is-loading');
                setTimeout(() => {
                    this.$inner.css({ 'width': '0%', 'transition': 'none' });
                }, 150);
            }, 180);
        },

        reset: function () {
            if (!this.$bar || !this.$bar.length) return;
            clearTimeout(this.timer);
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
