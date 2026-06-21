/**
 * Premium Toast Notification System
 * A centralized, elegant notification replacement for Flasher.
 */

 (function (window) {
    'use strict';

    const PremiumToast = {
        options: {
            duration: 5000,
            icons: {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                warning: 'fa-exclamation-triangle',
                info: 'fa-info-circle'
            }
        },

        // Init the container if not exists
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
        show: function (message, type = 'info') {
            const container = this._getContainer();
            
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `premium-toast toast-${type}`;
            
            // Icon
            const iconHtml = `<div class="toast-icon"><i class="fas ${this.options.icons[type]}"></i></div>`;
            
            // Body (handles arrays of messages or single string)
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
            
            // Close button
            const closeHtml = `<button class="toast-close" type="button"><i class="fas fa-times"></i></button>`;

            toast.innerHTML = iconHtml + bodyHtml + closeHtml;

            // Append to container
            container.appendChild(toast);

            // Setup dismiss logic
            const dismiss = () => {
                if (toast.classList.contains('toast-hide')) return;
                toast.classList.add('toast-hide');
                setTimeout(() => {
                    if (toast && toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 400); // Matches CSS fade-out animation duration
            };

            // Close button click
            const closeBtn = toast.querySelector('.toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', dismiss);
            }

            // Auto dismiss
            setTimeout(dismiss, this.options.duration);
        },

        // Helpers
        success: function (message) { this.show(message, 'success'); },
        error: function (message) { this.show(message, 'error'); },
        warning: function (message) { this.show(message, 'warning'); },
        info: function (message) { this.show(message, 'info'); }
    };

    // Make globally available
    window.PremiumToast = PremiumToast;

    // Backwards compatibility with old Flasher JS calls
    window.flasher = {
        success: function(message) { window.PremiumToast.success(message); },
        error: function(message) { window.PremiumToast.error(message); },
        warning: function(message) { window.PremiumToast.warning(message); },
        info: function(message) { window.PremiumToast.info(message); },
        use: function() { return this; }, // Dummy for flasher.use('flasher').renderOptions
        renderOptions: function() { return this; }
    };

})(window);
