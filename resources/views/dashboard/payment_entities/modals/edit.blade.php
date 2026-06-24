<div class="modal modal-pop" id="editPaymentEntityModal" tabindex="-1" role="dialog"
    aria-labelledby="editPaymentEntityModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='edit_payment_entity_form' novalidate
            data-success-msg="{!! __('general.update_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="editPaymentEntityModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('payment_entities.update_payment_entity') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        <!-- Type -->
                        <div class="col-md-12 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="type_edit">{!! __('payment_entities.type') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id="type_edit" name="type">
                                    <option value="">{!! __('general.select_from_list') !!}</option>
                                    <option value="bank">{!! __('payment_entities.type_bank') !!}</option>
                                    <option value="wallet">{!! __('payment_entities.type_wallet') !!}</option>
                                </select>
                                <span class="text-danger error-text type_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Name Arabic -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_ar_edit">{!! __('payment_entities.name_ar') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_ar_edit" name="name[ar]"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('payment_entities.enter_name_ar') !!}">
                                <span class="text-danger error-text name_ar_error"></span>
                            </div>
                        </div>

                        <!-- Name English -->
                        <div class="col-md-6 mb-2">
                            <div class="premium-form-group">
                                <label class="premium-label" for="name_en_edit">{!! __('payment_entities.name_en') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="name_en_edit" name="name[en]"
                                    class="form-control premium-input shadow-none" autocomplete="off"
                                    placeholder="{!! __('payment_entities.enter_name_en') !!}">
                                <span class="text-danger error-text name_en_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="updateBtn" class="btn btn-premium-save font-weight-bold">
                        <i class="fas fa-save mr-2"></i>
                        <i class="fas fa-spinner fa-spin d-none spinner_loading mr-2"></i>
                        {{ __('general.save') }}
                    </button>

                    <button type="button" class="btn btn-premium-secondary font-weight-bold"
                        data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('general.cancel') }}
                    </button>
                </div>
                <!--end::modal footer-->

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            if ($('#type_edit').length) {
                $('#type_edit').select2({
                    dropdownParent: $('#editPaymentEntityModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            $(document).on('click', '.editPaymentEntityBtn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var type = $(this).data('type');
                var name_ar = $(this).data('name_ar');
                var name_en = $(this).data('name_en');

                $('#edit_id').val(id);
                $('#type_edit').val(type).trigger('change.select2');
                $('#name_ar_edit').val(name_ar);
                $('#name_en_edit').val(name_en);

                var url = "{!! route('dashboard.payment-entities.update', ':id') !!}".replace(':id', id);
                $('#edit_payment_entity_form').attr('action', url);
                
                $('#editPaymentEntityModal').modal('show');
            });
        });
    </script>
@endpush
