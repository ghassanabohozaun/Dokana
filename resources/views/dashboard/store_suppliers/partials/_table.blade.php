<input type="hidden" id="store_suppliers-total-count" value="{!! $suppliers->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_suppliers.name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_suppliers.mobile') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_suppliers.bank_name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_suppliers.account_number') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_suppliers.date') !!}</th>
                @if (auth()->user()->can('store_suppliers_update') || auth()->user()->can('store_suppliers_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">
                        {!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($suppliers as $supplier)
                <tr id="row{{ $supplier->id }}">
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
                                    <h4 class="modal-name-title font-weight-bold">{!! $supplier->name !!}</h4>
                                    <span class="modal-role-badge badge-danger">{!! __('store_suppliers.store_supplier') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $supplier->id !!}</span>
                                        </div>
                                    </div>

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    @if ($supplier->store_id)
                                                        <span class="badge badge-light-primary border-0">{!! optional($supplier->store)->name !!}</span>
                                                    @else
                                                        <span class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-money-bill"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_suppliers.name') !!}</span>
                                            <span class="detail-info-value text-danger font-weight-bold">{!! $supplier->name !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-mobile-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_suppliers.mobile') !!}</span>
                                            <span class="detail-info-value">{!! $supplier->mobile !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-university"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_suppliers.bank_name') !!}</span>
                                            <span class="detail-info-value">{!! $supplier->bank_name !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-credit-card"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_suppliers.account_number') !!}</span>
                                            <span class="detail-info-value">{!! $supplier->account_number !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_suppliers.date') !!}</span>
                                            <span class="text-secondary font-weight-bold">{{ $supplier->created_at->format('Y-m-d') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            @if ($supplier->store_id)
                                <a href="javascript:void(0)" class="store-chip">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {!! optional($supplier->store)->name !!}
                                </a>
                            @else
                                <span class="badge badge-light-warning border-0">
                                    <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Name -->
                    <td class="text-center align-middle">
                        <span class="text-danger font-weight-bold">{!! $supplier->name !!}</span>
                    </td>

                    <!-- Mobile -->
                    <td class="text-center align-middle">{!! $supplier->mobile !!}</td>

                    <!-- Bank Name -->
                    <td class="text-center align-middle font-weight-bold text-primary">{!! $supplier->bank_name !!}</td>

                    <!-- Account Number -->
                    <td class="text-center align-middle font-weight-bold text-info">{!! $supplier->account_number !!}</td>

                    <!-- Date -->
                    <td class="text-center align-middle">{!! $supplier->created_at->format('Y-m-d') !!}</td>

                    <!-- Actions -->
                    @if (auth()->user()->can('store_suppliers_update') || auth()->user()->can('store_suppliers_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.store_suppliers.parts.actions', ['supplier' => $supplier])
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('store_suppliers.no_store_suppliers_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $suppliers->links() !!}
    </div>
</div>
