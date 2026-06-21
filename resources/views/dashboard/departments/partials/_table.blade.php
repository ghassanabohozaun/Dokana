<input type="hidden" id="departments-total-count" value="{!! $departments->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if(isset($stores))
                <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('departments.created_by') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.status') !!}</th>
                @can('departments_update')
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.manage_status') !!}</th>
                @endcan
                @if(auth()->user()->can('departments_update') || auth()->user()->can('departments_delete'))
                <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">{!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($departments as $key=>$department)
                <tr id="row{{ $department->id }}">
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
                                        <div class="avatar-circle avatar-size-100 d-inline-flex align-items-center justify-content-center text-white text-uppercase shadow-sm bg-indigo-alt">
                                            <i class="fas fa-briefcase font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $department->name !!}</h4>
                                    <span class="modal-role-badge">{!! __('departments.department') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $department->id !!}</span>
                                        </div>
                                    </div>

                                    @if(isset($stores))
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                            <span class="detail-info-value text-muted small">
                                                @if($department->store_id)
                                                    <span class="badge badge-light-primary border-0">{!! optional($department->store)->name !!}</span>
                                                @else
                                                    <span class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-check-circle"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('departments.status') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                @if ($department->status == 1)
                                                    <span class="badge badge-success badge-glow badge-pill px-2">{!! __('general.enable') !!}</span>
                                                @else
                                                    <span class="badge badge-danger badge-glow badge-pill px-2">{!! __('general.disabled') !!}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user-plus"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('departments.created_by') !!}</span>
                                            <span class="detail-info-value">{!! $department->creator->name ?? '---' !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($departments->currentPage() - 1) * $departments->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if(isset($stores))
                    <td class="text-center align-middle">
                        @if($department->store_id)
                            <a href="javascript:void(0)" class="store-chip">
                                <i class="fas fa-briefcase mr-1"></i>
                                {!! optional($department->store)->name !!}
                            </a>
                        @else
                            <span class="badge badge-light-warning border-0">
                                <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                            </span>
                        @endif
                    </td>
                    @endif

                    <!-- Name -->
                    <td class="text-center align-middle font-weight-bold text-primary">{!! $department->name !!}</td>

                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted small">{!! $department->creator->name ?? '---' !!}</span>
                    </td>

                    <!-- Status -->
                    <td class="text-center align-middle">
                        @include('dashboard.departments.parts.status')
                    </td>

                    <!-- Manage Status -->
                    @can('departments_update')
                    <td class="text-center align-middle">
                        @include('dashboard.departments.parts.manage_status')
                    </td>
                    @endcan

                    <!-- Actions -->
                    @if(auth()->user()->can('departments_update') || auth()->user()->can('departments_delete'))
                    <td class="text-center align-middle sticky-actions">
                        @include('dashboard.departments.parts.actions')
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('departments.no_departments_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $departments->links() !!}
    </div>
</div>



