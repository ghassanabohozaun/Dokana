<input type="hidden" id="store_supplier_payments-total-count" value="{!! $payments->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_payments.supplier') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_payments.invoice') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.bank_account') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_payments.amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_payments.notes') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_supplier_payments.date') !!}</th>
                @if (auth()->user()->can('store_supplier_payments_update') || auth()->user()->can('store_supplier_payments_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">
                        {!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr id="row{{ $payment->id }}">
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
                                            <i class="fas fa-hand-holding-usd font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $payment->amount !!}</h4>
                                    <span class="modal-role-badge badge-danger">{!! __('store_supplier_payments.store_supplier_payment') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $payment->id !!}</span>
                                        </div>
                                    </div>

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    @if ($payment->store_id)
                                                        <span class="badge badge-light-primary border-0">{!! optional($payment->store)->name !!}</span>
                                                    @else
                                                        <span class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-truck"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_payments.supplier') !!}</span>
                                            <span class="detail-info-value font-weight-bold">{!! optional($payment->supplier)->name !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-file-invoice-dollar"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_payments.invoice') !!}</span>
                                            <span class="detail-info-value">{!! optional($payment->invoice)->invoice_number ?? '---' !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-university"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('bank_accounts.bank_account') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                @if($payment->bankAccount)
                                                    @php
                                                        $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                                        $accountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
                                                    @endphp
                                                    <span class="badge badge-light-info border-0">{!! $accountName !!}</span>
                                                @else
                                                    ---
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-money-bill"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_payments.amount') !!}</span>
                                            <span class="detail-info-value text-danger font-weight-bold">{!! $payment->amount !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-align-left"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_payments.notes') !!}</span>
                                            <span class="detail-info-value">{!! $payment->notes ?? '---' !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_supplier_payments.date') !!}</span>
                                            <span class="text-secondary font-weight-bold">{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($payments->currentPage() - 1) * $payments->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            @if ($payment->store_id)
                                <a href="javascript:void(0)" class="store-chip">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {!! optional($payment->store)->name !!}
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
                        <span class="font-weight-bold">{!! optional($payment->supplier)->name !!}</span>
                    </td>

                    <!-- Invoice -->
                    <td class="text-center align-middle">
                        @if($payment->invoice)
                            <span class="badge badge-light-primary border-0">{!! $payment->invoice->invoice_number !!}</span>
                        @else
                            <span class="text-muted">---</span>
                        @endif
                    </td>

                    <!-- Bank Account -->
                    <td class="text-center align-middle font-weight-bold text-primary">
                        @if($payment->bankAccount)
                            @php
                                $entityName = optional($payment->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($payment->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $payment->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $payment->bankAccount->account_number;
                            @endphp
                            {!! $accountName !!}
                        @else
                            ---
                        @endif
                    </td>

                    <!-- Amount -->
                    <td class="text-center align-middle">
                        <span class="text-danger font-weight-bold">{!! $payment->amount !!}</span>
                    </td>

                    <!-- Reason -->
                    <td class="text-center align-middle">{!! $payment->notes ?? '---' !!}</td>

                    <!-- Date -->
                    <td class="text-center align-middle">{!! $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') : $payment->created_at->format('Y-m-d') !!}</td>

                    <!-- Actions -->
                    @if (auth()->user()->can('store_supplier_payments_update') || auth()->user()->can('store_supplier_payments_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.store_supplier_payments.parts.actions', ['payment' => $payment])
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('store_supplier_payments.no_store_supplier_payments_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $payments->links() !!}
    </div>
</div>
