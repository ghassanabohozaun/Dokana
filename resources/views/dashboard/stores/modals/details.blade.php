<!-- Details Modal for Stores -->
<div class="modal fade" id="detailsStoreModal" tabindex="-1" role="dialog" aria-labelledby="detailsStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                <h5 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2" id="detailsStoreModalLabel">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    <span>{!! __('general.details') !!}</span>
                </h5>
                <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
            <div class="p-6" id="detailsStoreModalBody">
                <!-- Loaded dynamically from row-details via AJAX JS -->
            </div>
            <div class="flex items-center justify-center p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                <button type="button" class="btn-secondary-modern text-xs" data-dismiss="modal">
                    <i class="fas fa-times-circle mr-1"></i> {!! __('general.close') !!}
                </button>
            </div>
        </div>
    </div>
</div>
