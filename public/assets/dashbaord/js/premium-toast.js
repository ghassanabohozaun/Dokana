/**
 * DOKANA ENTERPRISE TOAST NOTIFICATION ENGINE
 * 100% Native Pure JS, High Performance, Multi-stacking, Zero Dependencies
 */

(function (window) {
    'use strict';

    const PremiumToast = {
        options: {
            duration: 4500, // 4.5 seconds
            icons: {
                success: 'fa-check',
                error: 'fa-exclamation',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info'
            }
        },

        // Init or get the global container
        _getContainer: function () {
            let container = document.getElementById('premium-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'premium-toast-container';
                container.className = 'premium-toast-container';
                document.body.appendChild(container);
            }
            return container;
        },

        // Core show method
        show: function (message, type = 'info', duration = null) {
            if (!message) return;

            const container = this._getContainer();
            const toastDuration = duration || this.options.duration;
            const iconClass = this.options.icons[type] || 'fa-info';
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `premium-toast toast-${type}`;
            
            // Icon Badge
            const iconHtml = `
                <div class="toast-icon-badge">
                    <i class="fas ${iconClass}"></i>
                </div>
            `;
            
            // Body Content
            let bodyContent = '';
            if (Array.isArray(message)) {
                if (message.length === 1) {
                    bodyContent = message[0];
                } else {
                    bodyContent = '<ul>' + message.map(m => `<li>${m}</li>`).join('') + '</ul>';
                }
            } else {
                bodyContent = message;
            }
            const bodyHtml = `<div class="toast-body">${bodyContent}</div>`;
            
            // Close Button
            const closeHtml = `
                <button class="toast-close" type="button" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            `;

            // Progress Bar
            const progressHtml = `
                <div class="toast-progress">
                    <div class="toast-progress-bar" style="animation-duration: ${toastDuration}ms;"></div>
                </div>
            `;

            toast.innerHTML = iconHtml + bodyHtml + closeHtml + progressHtml;

            // Append toast to container
            container.appendChild(toast);

            // Dismiss logic
            let timer = null;
            const dismiss = () => {
                if (toast.classList.contains('toast-hide')) return;
                if (timer) clearTimeout(timer);
                toast.classList.add('toast-hide');
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            };

            // Pause on hover
            toast.addEventListener('mouseenter', () => {
                if (timer) clearTimeout(timer);
                const progressBar = toast.querySelector('.toast-progress-bar');
                if (progressBar) progressBar.style.animationPlayState = 'paused';
            });

            toast.addEventListener('mouseleave', () => {
                const progressBar = toast.querySelector('.toast-progress-bar');
                if (progressBar) progressBar.style.animationPlayState = 'running';
                timer = setTimeout(dismiss, 2000);
            });

            // Close button click
            const closeBtn = toast.querySelector('.toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    dismiss();
                });
            }

            // Auto dismiss
            timer = setTimeout(dismiss, toastDuration);
        },

        // Helper Shorthands
        success: function (message, duration) { this.show(message, 'success', duration); },
        error: function (message, duration) { this.show(message, 'error', duration); },
        warning: function (message, duration) { this.show(message, 'warning', duration); },
        info: function (message, duration) { this.show(message, 'info', duration); }
    };

    // Make globally available
    window.PremiumToast = PremiumToast;

    // Backwards compatibility with old Flasher JS calls
    window.flasher = {
        success: function (message) { window.PremiumToast.success(message); },
        error: function (message) { window.PremiumToast.error(message); },
        warning: function (message) { window.PremiumToast.warning(message); },
        info: function (message) { window.PremiumToast.info(message); },
        use: function () { return this; },
        renderOptions: function () { return this; }
    };

})(window);
