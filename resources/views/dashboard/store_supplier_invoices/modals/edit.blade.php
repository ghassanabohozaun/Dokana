<div class="modal modal-pop" id="updateStoreSupplierInvoiceModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierInvoiceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="" method="POST" enctype="multipart/form-data"
            id='update_store_supplier_invoice_form' data-success-msg="{!! __('general.update_success_message') !!}"
            data-success-action="reload-table" data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center"
                        id="updateStoreSupplierInvoiceModalLabel">
                        <i class="fas fa-edit text-primary mr-2 icon-size-18"></i> {!! __('store_supplier_invoices.update_store_supplier_invoice') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        <input type="hidden" id="id_edit" name="id">
                        @if (isset($stores))
                            <div class="col-md-12 mb-1">
                                <div class="premium-form-group">
                                    <label class="premium-label" for="store_id_dept_edit">{!! __('stores.store') !!} <span
                                            class="text-danger">*</span></label>
                                    <select class="form-control premium-input select2 shadow-none"
                                        id='store_id_dept_edit' name="store_id">
                                        <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                        @foreach ($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text store_id_error"></span>
                                </div>
                            </div>
                        @endif

                        <!-- Supplier -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_supplier_id_edit">{!! __('store_supplier_invoices.supplier') !!} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none"
                                    id='store_supplier_id_edit' name="store_supplier_id" style="width: 100%;" @if (isset($stores)) disabled @endif>
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if (isset($suppliers) && !isset($stores))
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <span class="text-danger error-text store_supplier_id_error"></span>
                            </div>
                        </div>

                        <!-- Invoice Number -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="invoice_number_edit">{!! __('store_supplier_invoices.invoice_number') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="invoice_number_edit" name="invoice_number"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text invoice_number_error"></span>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="total_amount_edit">{!! __('store_supplier_invoices.total_amount') !!} <span
                                        class="text-danger">*</span></label>
                                <input type="number" id="total_amount_edit" name="total_amount" step="0.01"
                                    min="0" class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text total_amount_error"></span>
                            </div>
                        </div>

                        <!-- Invoice Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="invoice_date_edit">{!! __('store_supplier_invoices.date') !!} <span
                                        class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute"
                                        style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="invoice_date_edit" name="invoice_date"
                                        class="form-control premium-input shadow-none ptc-datepicker"
                                        style="padding-left: 35px;" autocomplete="off">
                                </div>
                                <span class="text-danger error-text invoice_date_error"></span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="notes_edit">{!! __('store_supplier_invoices.notes') !!}</label>
                                <textarea id="notes_edit" name="notes" rows="3" class="form-control premium-input shadow-none"></textarea>
                                <span class="text-danger error-text notes_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtnEdit" class="btn btn-premium-save font-weight-bold">
                        <i class="fas fa-save mr-2"></i>
                        <i class="fas fa-spinner fa-spin d-none spinner_loading mr-2"></i>
                        {{ __('general.save') }}
                    </button>

                    <button type="button" class="btn btn-premium-secondary font-weight-bold" data-dismiss="modal">
                        <i class="fas fa-times-circle mr-2"></i> {{ __('general.cancel') }}
                    </button>
                </div>
                <!--end::modal footer-->

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically
            $('body').on('click', '.edit_store_supplier_invoice_button', function(e) {
                e.preventDefault();

                let store_supplier_invoice_id = $(this).attr('store_supplier_invoice-id');
                let store_supplier_invoice_total_amount = $(this).attr(
                    'store_supplier_invoice-total_amount');
                let store_supplier_invoice_invoice_number = $(this).attr(
                    'store_supplier_invoice-invoice_number');
                let store_supplier_invoice_store_id = $(this).attr('store_supplier_invoice-store-id');
                let store_supplier_invoice_date = $(this).attr('store_supplier_invoice-date');
                let store_supplier_invoice_supplier_id = $(this).attr('store_supplier_invoice-supplier-id');
                let store_supplier_invoice_notes = $(this).attr('store_supplier_invoice-notes');

                // Populate form fields
                $('#id_edit').val(store_supplier_invoice_id);
                $('#total_amount_edit').val(store_supplier_invoice_total_amount);
                $('#invoice_number_edit').val(store_supplier_invoice_invoice_number);
                $('#invoice_date_edit').val(store_supplier_invoice_date);
                $('#notes_edit').val(store_supplier_invoice_notes);

                // Populate Select2 for Store and Supplier
                if ($('#store_id_dept_edit').length) {
                    $('#store_supplier_id_edit').attr('data-pending-val', store_supplier_invoice_supplier_id || '');
                    if (store_supplier_invoice_store_id) {
                        $('#store_id_dept_edit').val(store_supplier_invoice_store_id).trigger('change');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change');
                    }
                } else {
                    if ($('#store_supplier_id_edit').length) {
                        $('#store_supplier_id_edit').val(store_supplier_invoice_supplier_id).trigger('change');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-supplier-invoices.update', 'id') !!}".replace('id', store_supplier_invoice_id);
                $('#update_store_supplier_invoice_form').attr('action', url);

                // Show modal
                $('#updateStoreSupplierInvoiceModal').modal('show');
            });

            // Initialize Select2
            if ($('#store_id_dept_edit').length) {
                $('#store_id_dept_edit').select2({
                    dropdownParent: $('#updateStoreSupplierInvoiceModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            if ($('#store_supplier_id_edit').length) {
                $('#store_supplier_id_edit').select2({
                    dropdownParent: $('#updateStoreSupplierInvoiceModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Fetch suppliers by store on change
            $('#store_id_dept_edit').on('change', function() {
                let store_id = $(this).val();
                let supplierSelect = $('#store_supplier_id_edit');

                supplierSelect.empty().append(
                    '<option value="" selected>{!! __('general.select_from_list') !!}</option>');

                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: {
                            store_id: store_id
                        },
                        success: function(data) {
                            let pendingVal = supplierSelect.attr('data-pending-val') || '';
                            $.each(data, function(key, supplier) {
                                let newOption = new Option(supplier.name, supplier.id, false, false);
                                supplierSelect.append(newOption);
                            });
                            supplierSelect.prop('disabled', false);
                            if (pendingVal) {
                                supplierSelect.val(pendingVal).trigger('change.select2');
                                supplierSelect.removeAttr('data-pending-val');
                            } else {
                                supplierSelect.trigger('change.select2');
                            }
                        }
                    });
                } else {
                    supplierSelect.prop('disabled', true).trigger('change.select2');
                }
            });
        });
    </script>
@endpush
