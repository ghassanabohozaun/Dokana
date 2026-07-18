<input type="hidden" id="store_supplier_invoices-total-count" value="{!! $invoices->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.supplier') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.invoice_number') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.total_amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.paid_amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.remaining_amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.status') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_invoices.date') !!}</th>
                <!-- Actions Column Removed for Bottom Action Bar -->
            </tr>
        </thead>
        <tbody>
            @forelse ($invoices as $invoice)
                <tr id="row{{ $invoice->id }}" class="premium-table-row pointer" data-row-title="{!! $invoice->invoice_number !!} - {!! optional($invoice->supplier)->name !!}">
                    <!-- Mobile Details Control -->
                    <td class="text-center align-middle d-lg-none">
                        <span class="details-control pointer">
                            <i class="fas fa-plus-circle text-primary" style="font-size: 22px;"></i>
                        </span>

                        <!-- Hidden Row Details for AJAX Modal -->
                        <div class="row-details d-none">
                            <div class="modal-details-card">
                                <div class="premium-modal-header bg-danger"></div>

                                <div class="text-center">
                                    <div class="modal-profile-wrapper">
                                        <div class="avatar-circle avatar-size-100 d-inline-flex align-items-center justify-content-center text-white text-uppercase shadow-sm bg-danger">
                                            <i class="fas fa-file-invoice-dollar font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $invoice->invoice_number !!}</h4>
                                    <span class="modal-role-badge badge-danger">{!! __('store_supplier_invoices.store_supplier_invoice') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $invoice->id !!}</span>
                                        </div>
                                    </div>

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    @if ($invoice->store_id)
                                                        <span class="badge badge-light-primary border-0">{!! optional($invoice->store)->name !!}</span>
                                                    @else
                                                        <span class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.supplier') !!}</span>
                                            <span class="detail-info-value text-primary font-weight-bold">{!! optional($invoice->supplier)->name !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-file-invoice"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.invoice_number') !!}</span>
                                            <span class="detail-info-value text-dark font-weight-bold">{!! $invoice->invoice_number !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-money-bill-wave"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.total_amount') !!}</span>
                                            <span class="detail-info-value text-danger font-weight-bold">{!! number_format($invoice->total_amount, 2) !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-check-circle"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.paid_amount') !!}</span>
                                            <span class="detail-info-value text-success font-weight-bold">{!! number_format($invoice->paid_amount, 2) !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-clock"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.remaining_amount') !!}</span>
                                            <span class="detail-info-value text-warning font-weight-bold">{!! number_format($invoice->remaining_amount, 2) !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-info-circle"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.status') !!}</span>
                                            <span class="detail-info-value">
                                                @if($invoice->status === 'paid')
                                                    <span class="badge badge-success">{!! __('store_supplier_invoices.paid') !!}</span>
                                                @elseif($invoice->status === 'partially_paid')
                                                    <span class="badge badge-warning">{!! __('store_supplier_invoices.partially_paid') !!}</span>
                                                @else
                                                    <span class="badge badge-danger">{!! __('store_supplier_invoices.unpaid') !!}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_invoices.date') !!}</span>
                                            <span class="text-secondary font-weight-bold">{{ $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : $invoice->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            @if ($invoice->store_id)
                                <a href="javascript:void(0)" class="store-chip">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {!! optional($invoice->store)->name !!}
                                </a>
                            @else
                                <span class="badge badge-light-warning border-0">
                                    <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Supplier -->
                    <td class="text-center align-middle">
                        <!-- Hidden Actions for Bottom Bar -->
                        <div class="row-actions-html d-none">
                            @include('dashboard.store_supplier_invoices.parts.actions', ['invoice' => $invoice])
                        </div>

                        <!-- Hidden Subtitle for Bottom Bar -->
                        <div class="row-subtitle-html d-none">
                            <span class="badge badge-secondary"><i class="fas fa-file-invoice mr-25"></i> {!! $invoice->invoice_number !!}</span>
                            <span class="badge badge-light-danger"><i class="fas fa-money-bill-wave mr-25"></i> {!! number_format($invoice->total_amount, 2) !!}</span>
                            <span class="badge badge-light-warning"><i class="fas fa-clock mr-25"></i> {!! number_format($invoice->remaining_amount, 2) !!}</span>
                            @if (isset($stores) && $invoice->store_id)
                                <span class="badge badge-light-primary"><i class="fas fa-briefcase mr-25"></i> {!! optional($invoice->store)->name !!}</span>
                            @endif
                        </div>

                        <span class="font-weight-bold text-primary">{!! optional($invoice->supplier)->name !!}</span>
                    </td>

                    <!-- Invoice Number -->
                    <td class="text-center align-middle font-weight-bold text-dark">{!! $invoice->invoice_number !!}</td>

                    <!-- Total Amount -->
                    <td class="text-center align-middle">
                        <span class="text-danger font-weight-bold">{!! number_format($invoice->total_amount, 2) !!}</span>
                    </td>

                    <!-- Paid Amount -->
                    <td class="text-center align-middle font-weight-bold text-success">{!! number_format($invoice->paid_amount, 2) !!}</td>

                    <!-- Remaining Amount -->
                    <td class="text-center align-middle font-weight-bold text-warning">{!! number_format($invoice->remaining_amount, 2) !!}</td>

                    <!-- Status -->
                    <td class="text-center align-middle">
                        @if($invoice->status === 'paid')
                            <span class="badge badge-success">{!! __('store_supplier_invoices.paid') !!}</span>
                        @elseif($invoice->status === 'partially_paid')
                            <span class="badge badge-warning">{!! __('store_supplier_invoices.partially_paid') !!}</span>
                        @else
                            <span class="badge badge-danger">{!! __('store_supplier_invoices.unpaid') !!}</span>
                        @endif
                    </td>

                    <!-- Date -->
                    <td class="text-center align-middle">{!! $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : $invoice->created_at->format('Y-m-d') !!}</td>

                    <!-- Actions Column Removed -->
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('store_supplier_invoices.no_store_supplier_invoices_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $invoices->links() !!}
    </div>
</div>
