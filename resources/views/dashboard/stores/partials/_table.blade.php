<input type="hidden" id="stores-total-count" value="{!! $stores->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id="myTable">
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('stores.logo') !!}
                </th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store_name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('stores.email') !!}
                </th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('stores.created_by') !!}
                </th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-xl-table-cell">{!! __('stores.phone') !!}
                </th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('stores.status') !!}</th>
                @can('stores_update')
                <th class="text-center align-middle py-3 border-top-0">{!! __('stores.manage_status') !!}</th>
                @endcan
                @if(auth()->user()->can('stores_update') || auth()->user()->can('stores_delete'))
                <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">{!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($stores as $store)
                <tr id="row{{ $store->id }}">
                    <!-- Mobile Details Control -->
                    <td class="text-center align-middle d-lg-none">
                        <span class="details-control pointer">
                            <i class="fas fa-plus-circle text-primary" style="font-size: 22px;"></i>
                        </span>

                        <!-- Hidden Row Details for AJAX Modal -->
                        <div class="row-details d-none">
                            <div class="modal-details-card">
                                <div class="premium-modal-header"></div>
                                <div class="text-center">
                                    <div class="modal-profile-wrapper">
                                        @include('dashboard.stores.parts.logo', ['size' => 100])
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $store->name !!}</h4>
                                    <span class="modal-role-badge">{!! __('stores.plan_' . strtolower($store->subscription_plan)) !!}</span>
                                </div>

                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user-plus"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.created_by') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $store->creator->name ?? '---' !!}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-envelope"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.email') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $store->email ?? '---' !!}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-phone"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.phone') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $store->phone ?? '---' !!}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-map-marker"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.address') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $store->address ?? '---' !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($stores->currentPage() - 1) * $stores->perPage() !!}
                        </span>
                    </td>

                    <!-- Logo -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        @include('dashboard.stores.parts.logo')
                    </td>

                    <!-- Name -->
                    <td class="text-center align-middle">
                        <a href="javascript:void(0)" class="store-chip">
                            <i class="fas fa-briefcase mr-1"></i>
                            {!! $store->name !!}
                        </a>
                    </td>

                    <!-- Email -->
                    <td class="text-center align-middle d-none d-lg-table-cell">{!! $store->email ?? '---' !!}</td>

                    <!-- Created By -->
                    <td class="text-center align-middle d-none d-lg-table-cell">{!! $store->creator->name ?? '---' !!}</td>

                    <!-- Phone (XL and above) -->
                    <td class="text-center align-middle d-none d-xl-table-cell">{!! $store->phone ?? '---' !!}</td>

                    <!-- Status -->
                    <td class="text-center align-middle">
                        @include('dashboard.stores.parts.status')
                    </td>

                    <!-- Manage Status -->
                    @can('stores_update')
                    <td class="text-center align-middle">
                        @include('dashboard.stores.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Actions -->
                    @if(auth()->user()->can('stores_update') || auth()->user()->can('stores_delete'))
                    <td class="text-center align-middle sticky-actions">
                        @include('dashboard.stores.parts.actions')
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('stores.no_stores_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $stores->links() !!}
    </div>
</div>



