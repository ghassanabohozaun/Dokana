<div class="modal fade" id="createStoreSupplierInvoiceModal" tabindex="-1" role="dialog"
    aria-labelledby="createStoreSupplierInvoiceModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form class="ajax-form w-full" action="{!! route('dashboard.store-supplier-invoices.store') !!}" method="POST" enctype="multipart/form-data"
            id="create_store_supplier_invoice_form" novalidate
            data-success-msg="{!! __('general.add_success_message') !!}"
            data-success-action="reload-table"
            data-table-id="#table_data">
            @csrf
            <div class="modal-content rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl overflow-hidden">

                <!-- Modal Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/90">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-sm">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white" id="createStoreSupplierInvoiceModalLabel">
                            {!! __('store_supplier_invoices.create_new_store_supplier_invoice') !!}
                        </h4>
                    </div>
                    <button type="button" class="btn-icon-action" data-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">
                    
                    @if(isset($stores))
                    <!-- Store Select (for admin) -->
                    <div>
                        <label class="form-label-modern" for="store_id_dept_create">
                            {!! __('stores.store') !!} <span class="text-rose-500">*</span>
                        </label>
                        <select name="store_id" id="store_id_dept_create" class="form-input-modern select2">
                            <option value="" selected>{!! __('general.select_from_list') !!}</option>
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
                            <label class="form-label-modern" for="store_supplier_id_create">
                                {!! __('store_supplier_invoices.supplier') !!} <span class="text-rose-500">*</span>
                            </label>
                            <select name="store_supplier_id" id="store_supplier_id_create" class="form-input-modern select2" @if(isset($stores)) disabled @endif>
                                <option value="" disabled selected>{!! __('general.select_from_list') !!}</option>
                                @if(isset($suppliers) && !isset($stores))
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}{{ $supplier->mobile ? ' - ' . $supplier->mobile : '' }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <span class="text-xs text-rose-500 error-text store_supplier_id_error block mt-1"></span>
                        </div>

                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5 flex-wrap">
                                <label class="form-label-modern mb-0" for="invoice_number_create">
                                    {!! __('store_supplier_invoices.invoice_number') !!} <span class="text-rose-500">*</span>
                                </label>
                                <button type="button" id="btn_generate_invoice_number"
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200/60 dark:border-indigo-800/40 transition-colors"
                                    title="توليد رقم فاتورة عشوائي جديد">
                                    <i class="fas fa-magic text-[9px]"></i>
                                    <span>رقم عشوائي</span>
                                </button>
                            </div>
                            <div class="relative">
                                <input type="text" id="invoice_number_create" name="invoice_number"
                                    class="form-input-modern pe-9 font-mono" placeholder="{!! __('store_supplier_invoices.invoice_number') !!}" autocomplete="off">
                                <div class="absolute inset-y-0 end-0 flex items-center pe-3 pointer-events-none text-slate-400 text-xs font-mono">
                                    #
                                </div>
                            </div>
                            <span class="text-xs text-rose-500 error-text invoice_number_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Total Amount & Invoice Date Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label-modern" for="total_amount_create">
                                {!! __('store_supplier_invoices.total_amount') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="total_amount_create" name="total_amount" step="0.01" min="0"
                                class="form-input-modern" placeholder="0.00" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text total_amount_error block mt-1"></span>
                        </div>

                        <div>
                            <label class="form-label-modern" for="invoice_date_create">
                                {!! __('store_supplier_invoices.invoice_date') !!} <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="invoice_date_create" name="invoice_date"
                                class="form-input-modern flatpickr-date" value="{{ date('Y-m-d') }}" placeholder="YYYY-MM-DD" autocomplete="off">
                            <span class="text-xs text-rose-500 error-text invoice_date_error block mt-1"></span>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="form-label-modern" for="notes_create">
                            {!! __('store_supplier_invoices.notes') !!}
                        </label>
                        <textarea id="notes_create" name="notes" rows="2"
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
    <script>
        $(document).ready(function() {
            // Helper to generate a random ERP invoice number (INV-YYMMDD-XXXX)
            function generateSupplierInvoiceNumber() {
                let now = new Date();
                let year = now.getFullYear().toString().slice(-2);
                let month = ('0' + (now.getMonth() + 1)).slice(-2);
                let day = ('0' + now.getDate()).slice(-2);
                let randomDigits = Math.floor(1000 + Math.random() * 9000);
                return `INV-${year}${month}${day}-${randomDigits}`;
            }

            // Click button to generate/refresh random invoice number
            $(document).on('click', '#btn_generate_invoice_number', function(e) {
                e.preventDefault();
                $('#invoice_number_create').val(generateSupplierInvoiceNumber()).trigger('input');
            });

            // Automatically pre-fill random invoice number when opening modal
            $('#createStoreSupplierInvoiceModal').on('show.bs.modal', function () {
                if (!$('#invoice_number_create').val()) {
                    $('#invoice_number_create').val(generateSupplierInvoiceNumber());
                }
            });

            // Fetch suppliers by store on change
            $('#store_id_dept_create').on('change', function() {
                let store_id = $(this).val();
                let supplierSelect = $('#store_supplier_id_create');
                
                supplierSelect.empty().append('<option value="" disabled selected>{!! __('general.select_from_list') !!}</option>');
                
                if (store_id) {
                    $.ajax({
                        url: "{!! route('dashboard.store-suppliers.by-store') !!}",
                        type: 'GET',
                        data: { store_id: store_id },
                        success: function(data) {
                            $.each(data, function(key, supplier) {
                                let mobileText = supplier.mobile ? ' - ' + supplier.mobile : '';
                                let newOption = new Option(supplier.name + mobileText, supplier.id, false, false);
                                supplierSelect.append(newOption);
                            });
                            supplierSelect.prop('disabled', false).trigger('change.select2');
                        },
                        error: function() {
                            supplierSelect.prop('disabled', false).trigger('change.select2');
                        }
                    });
                } else {
                    supplierSelect.prop('disabled', true).trigger('change.select2');
                }
            });
        });
    </script>
@endpush
