<!-- Details Modal for Bank Accounts -->
<div class="modal modal-pop" id="detailsBankAccountModal" tabindex="-1" role="dialog" aria-labelledby="detailsBankAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-bold text-primary" id="detailsBankAccountModalLabel">
                    <i class="fas fa-info-circle mr-1"></i> {!! __('general.details') !!}
                </h5>
                <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body my-2" id="detailsBankAccountModalBody">
                <!-- Content loaded from row-details via AJAX JS -->
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button type="button" class="btn btn-premium-secondary px-4 shadow-sm" style="border-radius: 12px; font-weight: 600; transition: all 0.3s ease;" data-dismiss="modal">
                    <i class="fas fa-times-circle mr-1"></i> {!! __('general.close') !!}
                </button>
            </div>
        </div>
    </div>
</div>


