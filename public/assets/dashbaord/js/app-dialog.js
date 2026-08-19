/**
 * UNIVERSAL CONFIRM & ALERT DIALOG SYSTEM (AppDialog)
 * 100% Native Pure Tailwind & Vanilla/jQuery JS - Zero External Packages
 */

(function (window, $) {
    'use strict';

    const AppDialog = {
        _modalId: '#app_confirm_modal',
        _isBusy: false,

        /**
         * Open a generic confirmation dialog
         * @param {Object} options
         */
        confirm: function (options) {
            const defaults = {
                title: window.DOKANA_I18N?.common?.confirm_title || 'تأكيد الإجراء',
                message: window.DOKANA_I18N?.common?.confirm_message || 'هل أنت متأكد من الاستمرار في هذه العملية؟',
                type: 'danger', // danger | warning | info | success
                icon: 'fas fa-trash-alt',
                confirmText: window.DOKANA_I18N?.common?.yes || 'نعم، متأكد',
                cancelText: window.DOKANA_I18N?.common?.cancel || 'إلغاء',
                onConfirm: null,
                onCancel: null
            };

            const config = $.extend({}, defaults, options);
            const $modal = $(this._modalId);
            if (!$modal.length) return;

            // Reset state
            this._isBusy = false;
            $modal.find('.dialog-error-box').addClass('hidden');
            $modal.find('.dialog-error-text').text('');
            $modal.find('.dialog-spinner').addClass('hidden d-none');
            $modal.find('.dialog-btn-icon').removeClass('hidden d-none');
            $modal.find('.app-confirm-submit-btn').prop('disabled', false);

            // Populate content
            $modal.find('.dialog-title').html(config.title);
            $modal.find('.dialog-message').html(config.message);
            $modal.find('.dialog-btn-text').html(config.confirmText);
            $modal.find('.app-confirm-cancel-btn span').html(config.cancelText);

            // Styling based on type
            const $iconWrap = $modal.find('.dialog-icon-wrapper');
            const $submitBtn = $modal.find('.app-confirm-submit-btn');

            $iconWrap.removeClass('bg-rose-50 text-rose-600 border-rose-200/60 ring-rose-50/60 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800/50 dark:ring-rose-950/30');
            $iconWrap.removeClass('bg-amber-50 text-amber-600 border-amber-200/60 ring-amber-50/60 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/50 dark:ring-amber-950/30');
            $iconWrap.removeClass('bg-indigo-50 text-indigo-600 border-indigo-200/60 ring-indigo-50/60 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-800/50 dark:ring-indigo-950/30');

            $submitBtn.removeClass('from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 shadow-rose-500/25');
            $submitBtn.removeClass('from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 shadow-amber-500/25');
            $submitBtn.removeClass('from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 shadow-indigo-500/25');

            if (config.type === 'danger') {
                $iconWrap.addClass('bg-rose-50 text-rose-600 border-rose-200/60 ring-rose-50/60 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-800/50 dark:ring-rose-950/30');
                $submitBtn.addClass('from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 shadow-rose-500/25');
                $modal.find('.dialog-icon').attr('class', 'dialog-icon ' + (config.icon || 'fas fa-trash-alt') + ' text-2xl');
            } else if (config.type === 'warning') {
                $iconWrap.addClass('bg-amber-50 text-amber-600 border-amber-200/60 ring-amber-50/60 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-800/50 dark:ring-amber-950/30');
                $submitBtn.addClass('from-amber-600 to-amber-700 hover:from-amber-500 hover:to-amber-600 shadow-amber-500/25');
                $modal.find('.dialog-icon').attr('class', 'dialog-icon ' + (config.icon || 'fas fa-exclamation-triangle') + ' text-2xl');
            } else {
                $iconWrap.addClass('bg-indigo-50 text-indigo-600 border-indigo-200/60 ring-indigo-50/60 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-800/50 dark:ring-indigo-950/30');
                $submitBtn.addClass('from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 shadow-indigo-500/25');
                $modal.find('.dialog-icon').attr('class', 'dialog-icon ' + (config.icon || 'fas fa-info-circle') + ' text-2xl');
            }

            // Unbind previous click events
            $submitBtn.off('click').on('click', (e) => {
                e.preventDefault();
                if (this._isBusy) return;

                if (typeof config.onConfirm === 'function') {
                    config.onConfirm({
                        showLoading: () => this.showLoading(),
                        hideLoading: () => this.hideLoading(),
                        showError: (msg) => this.showError(msg),
                        close: () => this.close()
                    });
                } else {
                    this.close();
                }
            });

            $modal.find('.app-confirm-cancel-btn').off('click').on('click', (e) => {
                e.preventDefault();
                if (typeof config.onCancel === 'function') {
                    config.onCancel();
                }
                this.close();
            });

            // Show modal
            if (typeof $modal.modal === 'function') {
                $modal.modal('show');
            } else {
                $modal.addClass('show').css('display', 'flex');
                $('body').addClass('modal-open');
            }
        },

        showLoading: function () {
            this._isBusy = true;
            const $modal = $(this._modalId);
            $modal.find('.dialog-btn-icon').addClass('hidden d-none');
            $modal.find('.dialog-spinner').removeClass('hidden d-none');
            $modal.find('.app-confirm-submit-btn').prop('disabled', true).addClass('opacity-80 cursor-not-allowed');
            $modal.find('.app-confirm-cancel-btn').prop('disabled', true);
        },

        hideLoading: function () {
            this._isBusy = false;
            const $modal = $(this._modalId);
            $modal.find('.dialog-spinner').addClass('hidden d-none');
            $modal.find('.dialog-btn-icon').removeClass('hidden d-none');
            $modal.find('.app-confirm-submit-btn').prop('disabled', false).removeClass('opacity-80 cursor-not-allowed');
            $modal.find('.app-confirm-cancel-btn').prop('disabled', false);
        },

        showError: function (errorMessage) {
            this.hideLoading();
            const $modal = $(this._modalId);
            const $errorBox = $modal.find('.dialog-error-box');
            $modal.find('.dialog-error-text').html(errorMessage || 'حدث خطأ أثناء تنفيذ الإجراء.');
            $errorBox.removeClass('hidden');

            // Trigger shake animation
            const $card = $modal.find('.modal-content');
            $card.addClass('animate-shake');
            setTimeout(() => $card.removeClass('animate-shake'), 600);
        },

        close: function () {
            const $modal = $(this._modalId);
            if (typeof $modal.modal === 'function') {
                $modal.modal('hide');
            } else {
                $modal.removeClass('show').css('display', 'none');
                $('body').removeClass('modal-open');
            }
            this._isBusy = false;
        }
    };

    // Expose globally
    window.AppDialog = AppDialog;

    // 2. Global Universal Delete Confirm Handler
    $(document).ready(function () {
        $('body').on('click', '.delete-confirm', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const id = $btn.data('id');
            const url = $btn.data('route') || $btn.data('url') || $btn.attr('href');
            
            const title = $btn.data('title') || 'هل تريد حذف هذا السجل؟';
            const message = $btn.data('text') || $btn.data('message') || 'لن تتمكن من استرجاع هذا السجل بعد الحذف!';
            const confirmBtnText = $btn.data('confirm-btn') || 'نعم، احذف';
            const cancelBtnText = $btn.data('cancel-btn') || 'إلغاء';
            const successText = $btn.data('success-text') || 'تم الحذف بنجاح';

            AppDialog.confirm({
                title: title,
                message: message,
                type: 'danger',
                icon: 'fas fa-trash-alt',
                confirmText: confirmBtnText,
                cancelText: cancelBtnText,
                onConfirm: function (dialog) {
                    dialog.showLoading();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            id: id,
                            _method: 'POST',
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status === true) {
                                dialog.close();
                                
                                // Show modern toast
                                if (window.PremiumToast) {
                                    window.PremiumToast.success(response.message || successText);
                                }

                                // Reload table
                                if (typeof window.fetch_data === 'function') {
                                    window.fetch_data(window.currentPage || 1);
                                } else {
                                    const targetTable = $('#table_data').length ? '#table_data' : ($('#myTable').length ? '#myTable' : null);
                                    if (targetTable) {
                                        $.ajax({
                                            url: location.href,
                                            type: 'GET',
                                            success: function (responseHtml) {
                                                $(targetTable).html(responseHtml);
                                                $(document).trigger('record-deleted', [id]);
                                            }
                                        });
                                    }
                                }

                                $(document).trigger('record-deleted', [id]);
                            } else {
                                dialog.showError(response.message || 'تعذر إتمام عملية الحذف');
                            }
                        },
                        error: function (xhr) {
                            let errorMsg = 'حدث خطأ غير متوقع أثناء الحذف.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            } else if (xhr.status === 403) {
                                errorMsg = window.DOKANA_I18N?.common?.access_denied || 'ليس لديك صلاحية لحذف هذا العنصر.';
                            }
                            dialog.showError(errorMsg);
                        }
                    });
                }
            });
        });
    });

})(window, jQuery);
