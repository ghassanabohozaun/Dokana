<input type="hidden" id="users-total-count" value="{!! $users->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th> <!-- For Details Control -->
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0" style="width: 50px;">#</th>
                @if(isset($stores))
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">{!! __('users.photo') !!}</th>
                <th class="align-middle py-3 border-top-0 property-info-td">{!! __('users.users') !!}</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">{!! __('users.mobile') !!}</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">{!! __('users.role_id') !!}</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">{!! __('users.created_by') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('users.status') !!}</th>
                @can('users_update')
                <th class="text-center align-middle py-3 border-top-0" style="min-width: 120px;">{!! __('users.manage_status') !!}
                </th>
                @endcan
                @if(auth()->user()->can('users_update') || auth()->user()->can('users_delete'))
                <th class="text-center align-middle py-3 border-top-0 sticky-actions" style="min-width: 150px;">{!! __('general.actions') !!}
                </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $key=>$user)
                <tr id="row{{ $user->id }}">
                    <td class="text-center d-lg-none align-middle">
                        <span class="details-control pointer">
                            <i class="fas fa-plus-circle text-primary" style="font-size: 22px;"></i>
                        </span>
                        <!-- Hidden Row Details -->
                        <div class="row-details d-none">
                            <div class="modal-details-card">
                                <!-- Header Gradient -->
                                <div class="premium-modal-header"></div>

                                <div class="text-center">
                                    <!-- Simple & Clean Profile Image -->
                                    <div class="modal-profile-wrapper">
                                        @include('dashboard.users.parts.photo', ['size' => 100])
                                    </div>

                                    <h4 class="modal-name-title font-weight-bold">{!! $user->name !!}</h4>
                                    <span class="modal-role-badge">{!! optional($user->role)->name !!}</span>

                                    <div class="modal-member-since-box">
                                        <i class="fas fa-calendar-alt small mr-1"></i>
                                        {!! __('general.created_at') !!}: {!! is_string($user->created_at) ? $user->created_at : $user->created_at->format('Y-m-d') !!}
                                    </div>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $user->id !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-envelope"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('users.email') !!}</span>
                                            <span class="detail-info-value">{!! $user->email !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-phone"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('users.mobile') !!}</span>
                                            <span class="detail-info-value" dir="ltr">{!! $user->mobile ?? '---' !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-shield-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('users.role_id') !!}</span>
                                            <span
                                                class="detail-info-value text-primary font-weight-bold">{!! optional($user->role)->name !!}</span>
                                        </div>
                                    </div>

                                    @if(isset($stores))
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                            <span class="detail-info-value">{!! optional($user->store)->name !!}</span>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-check-circle"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('users.status') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                @if ($user->status == 1)
                                                    <span
                                                        class="badge badge-success badge-glow badge-pill px-2">{!! __('general.enable') !!}</span>
                                                @else
                                                    <span
                                                        class="badge badge-danger badge-glow badge-pill px-2">{!! __('general.disabled') !!}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user-plus"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('users.created_by') !!}</span>
                                            <span class="detail-info-value">{!! $user->creator->name ?? '---' !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($users->currentPage() - 1) * $users->perPage() !!}
                        </span>
                    </td>
                    @if(isset($stores))
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <a href="javascript:void(0)" class="store-chip">
                            <i class="fas fa-briefcase mr-1"></i>
                            {!! optional($user->store)->name ?? __('general.all_stores') !!}
                        </a>
                    </td>
                    @endif
                    <td class="text-center d-none d-lg-table-cell align-middle">
                        <div class="d-flex justify-content-center">
                            @include('dashboard.users.parts.photo')
                        </div>
                    </td>
                    <td class="align-middle property-info-td">
                        <div class="user-info-cell">
                            <span class="user-name-text font-weight-bold">{!! $user->name !!}</span>
                            <span class="user-email-text">{!! $user->email !!}</span>
                        </div>
                    </td>
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted font-weight-bold" dir="ltr">{!! $user->mobile ?? '---' !!}</span>
                    </td>
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span
                            class="badge badge-pill badge-glow bg-light-primary text-primary font-weight-bold px-3 py-1">
                            {!! optional($user->role)->name !!}
                        </span>
                    </td>
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted small">{!! $user->creator->name ?? '---' !!}</span>
                    </td>
                    <td class="text-center align-middle">
                        @include('dashboard.users.parts.status')
                    </td>
                    @can('users_update')
                    <td class="text-center align-middle">
                        @include('dashboard.users.parts.manage_status')
                    </td>
                    @endcan
                    @if(auth()->user()->can('users_update') || auth()->user()->can('users_delete'))
                    <td class="text-center align-middle sticky-actions">
                        @include('dashboard.users.parts.actions')
                    </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('users.no_users_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="float-right custom-pagination mt-2">
    {!! $users->links() !!}
</div>
</div>



