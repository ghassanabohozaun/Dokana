<!-- Universal Pure Tailwind Confirm & Alert Dialog (Zero External Packages) -->
<div id="app_confirm_modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered max-w-md w-full" role="document">
        <div class="modal-content relative overflow-hidden rounded-3xl border border-slate-200/80 dark:border-slate-800/80 bg-white/95 dark:bg-slate-900/95 shadow-2xl backdrop-blur-xl transition-all">
            
            <!-- Close Button Top End -->
            <button type="button" class="app-confirm-cancel-btn absolute top-4 end-4 w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                <i class="fas fa-times text-xs"></i>
            </button>

            <div class="p-6 text-center">
                <!-- Pulsing Icon Container -->
                <div class="dialog-icon-wrapper mx-auto mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/50 ring-8 ring-rose-50/60 dark:ring-rose-950/30">
                    <i class="dialog-icon fas fa-trash-alt text-2xl"></i>
                </div>

                <!-- Dialog Title -->
                <h3 class="dialog-title text-base font-bold text-slate-900 dark:text-white leading-snug mb-2">
                    {!! __('general.ask_delete_record') !!}
                </h3>

                <!-- Dialog Description / Body -->
                <p class="dialog-message text-xs font-normal text-slate-500 dark:text-slate-400 leading-relaxed max-w-sm mx-auto mb-6">
                    {!! __('general.delete_warning_text') !!}
                </p>

                <!-- Error Alert Box (Hidden by default) -->
                <div class="dialog-error-box hidden mb-5 p-3 rounded-2xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 text-xs text-start flex items-start gap-2.5">
                    <i class="fas fa-exclamation-circle text-rose-500 mt-0.5 shrink-0"></i>
                    <span class="dialog-error-text leading-normal flex-1"></span>
                </div>

                <!-- Action Buttons: Confirm First (Right in RTL), Cancel Second (Left in RTL) -->
                <div class="flex items-center justify-center gap-3">
                    <button type="button" class="app-confirm-submit-btn flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-xs font-semibold text-white bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 shadow-lg shadow-rose-500/25 active:scale-[0.98] transition-all min-w-[110px]">
                        <i class="dialog-btn-icon fas fa-trash-alt text-xs"></i>
                        <i class="fas fa-spinner fa-spin dialog-spinner text-xs hidden d-none"></i>
                        <span class="dialog-btn-text">{!! __('general.delete') !!}</span>
                    </button>

                    <button type="button" class="app-confirm-cancel-btn flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200/60 dark:border-slate-700/60 active:scale-[0.98] transition-all">
                        <i class="fas fa-times text-xs"></i>
                        <span>{!! __('general.cancel') !!}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
