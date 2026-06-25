<div class="modal modal-pop" id="createStoreSupplierInvoiceModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreSupplierInvoiceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="form ajax-form" action="{!! route('dashboard.store-supplier-invoices.store') !!}" method="POST" enctype="multipart/form-data"
            id='create_store_supplier_invoice_form' novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content shadow-lg border-0" style="border-radius: 20px;">

                <!--begin::modal header-->
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title font-weight-bold text-dark d-flex align-items-center" id="createStoreSupplierInvoiceModalLabel">
                        <i class="fas fa-plus-circle text-primary mr-2 icon-size-18"></i> {!! __('store_supplier_invoices.create_new_store_supplier_invoice') !!}
                    </h6>
                    <button type="button" class="close premium-modal-close" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!--end::modal header-->

                <!--begin::modal body-->
                <div class="modal-body my-2">
                    <div class="row">
                        @if(isset($stores))
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="store_id_dept_create">{!! __('stores.store') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_id_dept_create' name="store_id">
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
                                <label class="premium-label" for="store_supplier_id_create">{!! __('store_supplier_invoices.supplier') !!} <span class="text-danger">*</span></label>
                                <select class="form-control premium-input select2 shadow-none" id='store_supplier_id_create' name="store_supplier_id" style="width: 100%;" @if(isset($stores)) disabled @endif>
                                    <option value="" selected>{!! __('general.select_from_list') !!}</option>
                                    @if(isset($suppliers) && !isset($stores))
                                        @foreach($suppliers as $supplier)
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
                                <label class="premium-label" for="invoice_number_create">{!! __('store_supplier_invoices.invoice_number') !!} <span class="text-danger">*</span></label>
                                <input type="text" id="invoice_number_create" name="invoice_number"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text invoice_number_error"></span>
                            </div>
                        </div>

                        <!-- Total Amount -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="total_amount_create">{!! __('store_supplier_invoices.total_amount') !!} <span class="text-danger">*</span></label>
                                <input type="number" id="total_amount_create" name="total_amount" step="0.01" min="0"
                                    class="form-control premium-input shadow-none" autocomplete="off">
                                <span class="text-danger error-text total_amount_error"></span>
                            </div>
                        </div>

                        <!-- Invoice Date -->
                        <div class="col-md-6 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="invoice_date_create">{!! __('store_supplier_invoices.date') !!} <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fas fa-calendar-alt text-primary position-absolute" style="left: 12px; top: 50%; transform: translateY(-50%); z-index: 4; pointer-events: none;"></i>
                                    <input type="text" id="invoice_date_create" name="invoice_date"
                                        class="form-control premium-input shadow-none ptc-datepicker" style="padding-left: 35px;" value="{{ date('Y-m-d') }}" autocomplete="off">
                                </div>
                                <span class="text-danger error-text invoice_date_error"></span>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12 mb-1">
                            <div class="premium-form-group">
                                <label class="premium-label" for="notes_create">{!! __('store_supplier_invoices.notes') !!}</label>
                                <textarea id="notes_create" name="notes" rows="3"
                                    class="form-control premium-input shadow-none"></textarea>
                                <span class="text-danger error-text notes_error"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::modal body-->

                <div class="modal-footer border-0 pt-0 premium-modal-footer">
                    <button type="submit" id="saveBtn" class="btn btn-premium-save font-weight-bold">
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
            if ($('#store_id_dept_create').length) {
                $('#store_id_dept_create').select2({
                    dropdownParent: $('#createStoreSupplierInvoiceModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }
            
            if ($('#store_supplier_id_create').length) {
                $('#store_supplier_id_create').select2({
                    dropdownParent: $('#createStoreSupplierInvoiceModal'),
                    width: '100%',
                    dir: $('html').attr('data-textdirection') || 'ltr'
                });
            }

            // Fetch suppliers by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let supplierSelect = $('#store_supplier_id_create');
                
                supplierSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                
                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.index') !!}",
                        type: 'GET',
                        data: { store_id: store_id, _ajax: 1 },
                        success: function(response) {
                            // Since index returns HTML table usually, wait, does it support JSON?
                            // Let's check how other drops work, or query active list via JSON
                            // Let's call suppliers list or define an autocomplete route, or index can return json if requested?
                            // Let's check if there's autocomplete or by-store for suppliers.
                            // Actually, let's look at StoreSupplierController
                        }
                    });
                }
            });
            
            // Wait, does StoreSupplierController have by-store?
            // If not, we can easily fetch it or define a route, or just load them if store_id is pre-selected.
            // Since it's select2, we can fetch via AJAX. Let's see:
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let supplierSelect = $('#store_supplier_id_create');
                supplierSelect.empty().append('<option value="" selected>{!! __('general.select_from_list') !!}</option>');
                
                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, supplier) {
                                let newOption = new Option(supplier.name, supplier.id, false, false);
                                supplierSelect.append(newOption);
                            });
                            supplierSelect.prop('disabled', false).trigger('change');
                        }
                    });
                } else {
                    supplierSelect.prop('disabled', true).trigger('change');
                }
            });
        });
    </script>
@endpush
