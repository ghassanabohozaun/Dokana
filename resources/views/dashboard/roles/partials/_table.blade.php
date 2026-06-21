<input type="hidden" id="roles-total-count" value="{!! $roles->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th> <!-- For Details Control -->
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('roles.role_name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('roles.created_by') !!}
                </th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('roles.description') !!}
                </th>
                @if (auth()->user()->can('roles_update') || auth()->user()->can('roles_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">{!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $key=>$role)
                <tr id="row{{ $role->id }}">
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
                                            class="avatar-circle avatar-size-100 d-inline-flex align-items-center justify-content-center text-white text-uppercase shadow-sm bg-premium-gradient">
                                            <i class="fas fa-shield-alt font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">{!! $role->name !!}</h4>
                                    <span class="modal-role-badge">{!! __('roles.role') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $role->id !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern mt-1">
                                        <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                            <span class="detail-info-value">
                                                @if ($role->store_id)
                                                    <span
                                                        class="badge badge-light-primary border-0">{!! optional($role->store)->name !!}</span>
                                                @else
                                                    <span
                                                        class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern mt-1">
                                        <div class="icon-circle"><i class="fas fa-info-circle"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('roles.description') !!}</span>
                                            <span
                                                class="detail-info-value text-muted small">{!! $role->description ?? '---' !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern mt-1">
                                        <div class="icon-circle"><i class="fas fa-user-plus"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('roles.created_by') !!}</span>
                                            <span class="detail-info-value">{!! $role->creator->name ?? '---' !!}</span>
                                        </div>
                                    </div>
                                    <div class="detail-item-modern mt-1">
                                        <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.created_at') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $role->created_at->format('Y-m-d') !!}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration !!}
                        </span>
                    </td>

                    <!-- Store -->
                    <td class="text-center align-middle">
                        @if ($role->store_id)
                            <a href="javascript:void(0)" class="store-chip">
                                <i class="fas fa-briefcase mr-1"></i>
                                {!! optional($role->store)->name !!}
                            </a>
                        @else
                            <span class="badge badge-light-warning border-0">
                                <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                            </span>
                        @endif
                    </td>

                    <!-- Name -->
                    <td class="text-center align-middle font-weight-bold text-primary">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-shield-alt mr-1 d-none d-md-inline"></i>
                            {!! $role->name !!}
                        </div>
                    </td>

                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted">{!! $role->creator->name ?? '---' !!}</span>
                    </td>

                    <!-- Description (Desktop Only) -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        @if ($role->description)
                            <span class="text-muted">{!! Str::limit($role->description, 30) !!}</span>
                        @else
                            <span class="text-muted">---</span>
                        @endif
                    </td>

                    <!-- Actions -->
                    @if (auth()->user()->can('roles_update') || auth()->user()->can('roles_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.roles.parts.actions')
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('roles.no_roles_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $roles->links() !!}
    </div>
</div>

