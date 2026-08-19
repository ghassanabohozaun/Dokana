<div class="modal fade" id="updateStoreSupplierInvoiceModal" tabindex="-1" role="dialog"
    aria-labelledby="updateStoreSupplierInvoiceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="" method="POST" enctype="multipart/form-data"
            id="update_store_supplier_invoice_form" data-success-msg="{!! __('general.update_success_message') !!}" data-success-action="reload-table"
            data-table-id="#table_data" novalidate>
            @csrf
            @method('PUT')
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="updateStoreSupplierInvoiceModalLabel">
                            {!! __('store_supplier_invoices.update_store_supplier_invoice') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    <input type="hidden" id="id_edit" name="id">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_edit">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_edit" class="form-input-modern select2">
                            <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                        <span class="text-xs text-rose-500 error-text store_id_error block mt-1"></span>
                    </div>
                    @endif

                    <!-- Supplier & Invoice Number Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="store_supplier_id_edit">
                                {!! __('store_supplier_invoices.supplier') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_supplier_id" id="store_supplier_id_edit" class="form-input-modern select2">
                                <option value="" disabled>{!! __('general.select_from_list') !!}</option>
                                @if(isset($suppliers))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_id_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="invoice_number_edit">
                                {!! __('store_supplier_invoices.invoice_number') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="invoice_number_edit" name="invoice_number"
                                class="form-input-modern" placeholder="{!! __('store_supplier_invoices.invoice_number') !!}" autocomplete="off" dir="ltr">
                            <span class="text-xs text-rose-500 error-text invoice_number_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Total Amount & Invoice Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="total_amount_edit">
                                {!! __('store_supplier_invoices.total_amount') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="total_amount_edit" name="total_amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text total_amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="invoice_date_edit">
                                {!! __('store_supplier_invoices.invoice_date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="invoice_date_edit" name="invoice_date"
                                class="form-input-modern flatpickr-date" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text invoice_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label-modern" for="notes_edit">
                            {!! __('store_supplier_invoices.notes') !!}
                        </label>
                        <textarea id="notes_edit" name="notes" rows="2"
                            class="form-input-modern" placeholder="{!! __('store_supplier_invoices.notes') !!}" autocomplete="off"></textarea>
                        <span class="text-xs text-rose-500 error-text notes_error block mt-1"></span>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end gap-2.5 px-6 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <button type="submit" class="btn-primary-gradient text-xs">
                        <i class="fas fa-save text-xs"></i>
                        <i class="fas fa-spinner fa-spin spinner_loading text-xs hidden d-none"></i>
                        <span>{!! __('general.save') !!}</span>
                    </button>
                    <button type="button" class="btn-secondary-modern text-xs" data-dismiss="modal">
                        {!! __('general.cancel') !!}
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // Show edit modal and populate data dynamically via event delegation
            $(document).on('click', '.editStoreSupplierInvoiceBtn', function(e) {
                e.preventDefault();
                
                let $btn = $(this);
                let invoice_id = $btn.data('id');
                let supplier_id = $btn.data('supplier_id');
                let supplier_name = $btn.data('supplier_name');
                let total_amount = $btn.data('total_amount');
                let invoice_number = $btn.data('invoice_number');
                let store_id = $btn.data('store_id');
                let notes = $btn.data('notes');
                let invoice_date = $btn.data('date');

                // Populate form fields
                $('#id_edit').val(invoice_id);
                $('#invoice_number_edit').val(invoice_number);
                $('#total_amount_edit').val(total_amount);
                $('#notes_edit').val(notes);
                
                // Set Flatpickr date or input value
                let dateInput = document.querySelector('#invoice_date_edit');
                if (dateInput && dateInput._flatpickr) {
                    dateInput._flatpickr.setDate(invoice_date, true);
                } else {
                    $('#invoice_date_edit').val(invoice_date);
                }

                // Populate Select2 for Supplier
                if ($('#store_supplier_id_edit').length) {
                    if (supplier_id) {
                        if ($('#store_supplier_id_edit').find("option[value='" + supplier_id + "']").length == 0) {
                            let newOpt = new Option(supplier_name, supplier_id, true, true);
                            $('#store_supplier_id_edit').append(newOpt);
                        }
                        $('#store_supplier_id_edit').val(supplier_id).trigger('change.select2');
                    } else {
                        $('#store_supplier_id_edit').val(null).trigger('change.select2');
                    }
                }

                // Populate Select2 for Store
                if ($('#store_id_dept_edit').length) {
                    if (store_id) {
                        $('#store_id_dept_edit').val(store_id).trigger('change.select2');
                    } else {
                        $('#store_id_dept_edit').val(null).trigger('change.select2');
                    }
                }

                // Update form action URL dynamically
                let url = "{!! route('dashboard.store-supplier-invoices.update', ':id') !!}".replace(':id', invoice_id);
                $('#update_store_supplier_invoice_form').attr('action', url);
                
                $('#update_store_supplier_invoice_form').find('.error-text').text('');
                $('#update_store_supplier_invoice_form').find('.form-input-modern').removeClass('border-rose-500');
                $('#updateStoreSupplierInvoiceModal').modal('show');
            });

            // Fetch suppliers by store on change in edit modal
            $('#store_id_dept_edit').on('change', function(e) {
                if (!e.isTrigger || e.type !== 'change') {
                    let store_id = $(this).val();
                    let supplierSelect = $('#store_supplier_id_edit');
                    
                    supplierSelect.empty().append('<option value="" disabled selected>{!! __('general.select_from_list') !!}</option>');
                    
                    if (store_id) {
                        $.ajax({
                            url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                            type: 'GET',
                            data: { store_id: store_id },
                            success: function(data) {
                                $.each(data, function(key, supplier) {
                                    let newOption = new Option(supplier.name + ' - ' + (supplier.mobile || ''), supplier.id, false, false);
                                    supplierSelect.append(newOption);
                                });
                                supplierSelect.trigger('change.select2');
                            }
                        });
                    }
                }
            });
        });
    </script>
@endpush
