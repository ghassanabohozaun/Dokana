<input type="hidden" id="store_customers-total-count" value="{!! $store_customers->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.phone') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.total_debts') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.total_payments') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.current_balance') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('general.status') !!}</th>
                @can('store_customers_update')
                <th class="text-center align-middle py-3 border-top-0">{!! __('general.manage_status') !!}</th>
                @endcan
                @if (auth()->user()->can('store_customers_update') || auth()->user()->can('store_customers_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">
                        {!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($store_customers as $key=>$store_customer)
                <tr id="row{{ $store_customer->id }}">
                    <!-- Mobile Details Control -->
                    <td class="text-center align-middle d-lg-none">
                        <span class="details-control pointer">
                            <i class="fas fa-plus-circle text-primary" style="font-size: 22px;"></i>
                        </span>

                        <!-- Hidden Row Details for AJAX Modal -->
                        <div class="row-details d-none">
                            <div class="modal-details-card">
                                <!-- Header Gradient -->
                                <div class="premium-modal-header"></div>

                                <div class="text-center">
                                    <div class="modal-profile-wrapper">
                                        <div
                                            class="avatar-circle avatar-size-100 d-inline-flex align-items-center justify-content-center text-white text-uppercase shadow-sm bg-indigo-alt">
                                            <i class="fas fa-briefcase font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $store_customer->name !!}</h4>
                                    <span class="modal-role-badge">{!! __('store_customers.store_customer') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $store_customer->id !!}</span>
                                        </div>
                                    </div>

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    @if ($store_customer->store_id)
                                                        <span
                                                            class="badge badge-light-primary border-0">{!! optional($store_customer->store)->name !!}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-phone"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_customers.phone') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                {!! $store_customer->phone ?? '---' !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-hand-holding-usd text-danger"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_customers.total_debts') !!}</span>
                                            <span class="detail-info-value">
                                                <span class="premium-store-badge store-badge-debt">
                                                    <i class="fas fa-arrow-down"></i> {!! $store_customer->total_debts ?? 0 !!}
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-money-check-alt text-success"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_customers.total_payments') !!}</span>
                                            <span class="detail-info-value">
                                                <span class="premium-store-badge store-badge-payment">
                                                    <i class="fas fa-arrow-up"></i> {!! $store_customer->total_payments ?? 0 !!}
                                                </span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-wallet text-primary"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_customers.current_balance') !!}</span>
                                            <span class="detail-info-value">
                                                @if($store_customer->calculated_balance > 0)
                                                    <span class="premium-store-badge store-badge-balance-debt">
                                                        <i class="fas fa-exclamation-circle"></i> {!! $store_customer->calculated_balance !!}
                                                    </span>
                                                @elseif($store_customer->calculated_balance < 0)
                                                    <span class="premium-store-badge store-badge-balance-payment">
                                                        <i class="fas fa-check-circle"></i> {!! abs($store_customer->calculated_balance) !!}
                                                    </span>
                                                @else
                                                    <span class="premium-store-badge store-badge-balance-zero">
                                                        <i class="fas fa-minus"></i> 0
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($store_customers->currentPage() - 1) * $store_customers->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            @if ($store_customer->store_id)
                                <a href="javascript:void(0)" class="store-chip">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {!! optional($store_customer->store)->name !!}
                                </a>
                            @else
                                <span class="badge badge-light-warning border-0">
                                    <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Name -->
                    <td class="text-center align-middle font-weight-bold text-primary">{!! $store_customer->name !!}</td>

                    <!-- Phone -->
                    <td class="text-center align-middle">{!! $store_customer->phone ?? '---' !!}</td>

                    <!-- Total Debts -->
                    <td class="text-center align-middle">
                        <span class="premium-store-badge store-badge-debt">
                            <i class="fas fa-arrow-down"></i> {!! $store_customer->total_debts ?? 0 !!}
                        </span>
                    </td>

                    <!-- Total Payments -->
                    <td class="text-center align-middle">
                        <span class="premium-store-badge store-badge-payment">
                            <i class="fas fa-arrow-up"></i> {!! $store_customer->total_payments ?? 0 !!}
                        </span>
                    </td>

                    <!-- Current Balance -->
                    <td class="text-center align-middle">
                        @if($store_customer->calculated_balance > 0)
                            <span class="premium-store-badge store-badge-balance-debt">
                                <i class="fas fa-exclamation-circle"></i> {!! $store_customer->calculated_balance !!}
                            </span>
                        @elseif($store_customer->calculated_balance < 0)
                            <span class="premium-store-badge store-badge-balance-payment">
                                <i class="fas fa-check-circle"></i> {!! abs($store_customer->calculated_balance) !!}
                            </span>
                        @else
                            <span class="premium-store-badge store-badge-balance-zero">
                                <i class="fas fa-minus"></i> 0
                            </span>
                        @endif
                    </td>

                    <!-- Status -->
                    <td class="text-center align-middle">
                        @include('dashboard.store_customers.parts.status')
                    </td>

                    <!-- Manage Status -->
                    @can('store_customers_update')
                    <td class="text-center align-middle">
                        @include('dashboard.store_customers.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Actions -->
                    @if (auth()->user()->can('store_customers_update') || auth()->user()->can('store_customers_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.store_customers.parts.actions')
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('store_customers.no_store_customers_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $store_customers->links() !!}
    </div>
</div>
