<input type="hidden" id="bank_accounts-total-count" value="{!! $bankAccounts->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.account_details') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.account_info') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.total_deposits') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.total_withdrawals') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('general.balance') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.is_default') !!}</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('departments.created_by') !!}</th>
                @if (auth()->user()->can('bank_accounts_update') || auth()->user()->can('bank_accounts_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">
                        {!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($bankAccounts as $key=>$account)
                <tr id="row{{ $account->id }}">
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
                                            <i class="fas fa-university font-40"></i>
                                        </div>
                                    </div>
                                    <h4 class="modal-name-title font-weight-bold">
                                        @if ($account->account_type == 'wallet')
                                            <i class="fas fa-wallet text-info mr-1"></i>
                                        @else
                                            <i class="fas fa-university text-primary mr-1"></i>
                                        @endif
                                        {!! $account->paymentEntity->name ?? '' !!}
                                    </h4>
                                    <span class="modal-role-badge">{!! $account->account_number !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $account->id !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('bank_accounts.account_holder_name') !!}</span>
                                            <span class="detail-info-value text-muted">{!! $account->account_holder_name !!}</span>
                                        </div>
                                    </div>

                                    @if ($account->iban)
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-barcode"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('bank_accounts.iban') !!}</span>
                                                <span class="detail-info-value text-muted"
                                                    dir="ltr">{!! $account->formatted_iban !!}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    <span
                                                        class="badge badge-light-primary border-0">{!! $account->store->name ?? '---' !!}</span>
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-coins text-success"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.balance') !!}</span>
                                            <div class="mt-1">
                                                @if ($account->current_balance > 0)
                                                    <span class="premium-store-badge store-badge-balance-payment">
                                                        <i class="fas fa-coins mr-1"></i> {!! number_format($account->current_balance, 2) !!}
                                                    </span>
                                                @elseif($account->current_balance < 0)
                                                    <span class="premium-store-badge store-badge-balance-debt">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        {!! number_format(abs($account->current_balance), 2) !!}
                                                    </span>
                                                @else
                                                    <span class="premium-store-badge store-badge-balance-zero">
                                                        <i class="fas fa-minus mr-1"></i> 0.00
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-star"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('bank_accounts.is_default') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                @if ($account->is_default)
                                                    <span
                                                        class="badge badge-success badge-glow badge-pill px-2">{!! __('bank_accounts.is_default') !!}</span>
                                                @else
                                                    <span class="text-muted">---</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user-plus"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('departments.created_by') !!}</span>
                                            <span class="detail-info-value">{!! $account->creator->name ?? '---' !!}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($bankAccounts->currentPage() - 1) * $bankAccounts->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            <a href="javascript:void(0)" class="store-chip">
                                <i class="fas fa-briefcase mr-1"></i>
                                {!! $account->store->name ?? '---' !!}
                            </a>
                        </td>
                    @endif

                    <!-- Account Details -->
                    <td class="text-center align-middle">
                        <div class="d-flex flex-column align-items-center">
                            @if ($account->account_type == 'wallet')
                                <span class="badge badge-light-info badge-pill font-weight-bold px-2 py-1 mb-1"><i class="fas fa-wallet mr-1"></i> {!! __('bank_accounts.type_wallet') !!}</span>
                            @else
                                <span class="badge badge-light-primary badge-pill font-weight-bold px-2 py-1 mb-1"><i class="fas fa-university mr-1"></i> {!! __('bank_accounts.type_bank') !!}</span>
                            @endif
                            <span class="font-weight-bold text-primary">{!! $account->paymentEntity->name ?? '' !!}</span>
                        </div>
                    </td>

                    <!-- Account Info -->
                    <td class="text-center align-middle">
                        <div class="font-weight-bold" dir="ltr">{!! $account->account_number !!}</div>
                        <div class="text-muted small mt-1">{!! $account->account_holder_name !!}</div>
                    </td>

                    <!-- Total Deposits -->
                    <td class="text-center align-middle">
                        <div class="font-weight-bold text-success" title="{!! __('bank_accounts.total_deposits') !!}">
                            <i class="fas fa-arrow-down mr-1"></i> {!! number_format($account->total_deposits, 2) !!}
                        </div>
                    </td>

                    <!-- Total Withdrawals -->
                    <td class="text-center align-middle">
                        <div class="font-weight-bold text-danger" title="{!! __('bank_accounts.total_withdrawals') !!}">
                            <i class="fas fa-arrow-up mr-1"></i> {!! number_format($account->total_withdrawals, 2) !!}
                        </div>
                    </td>

                    <!-- Balance -->
                    <td class="text-center align-middle" dir="ltr">
                        <div class="font-weight-bold {{ $account->current_balance > 0 ? 'text-success' : ($account->current_balance < 0 ? 'text-danger' : 'text-muted') }}" title="{!! __('general.balance') !!}">
                            <i class="fas fa-coins mr-1"></i> {!! number_format($account->current_balance, 2) !!}
                        </div>
                    </td>

                    <!-- Is Default -->
                    <td class="text-center align-middle">
                        @if ($account->is_default)
                            <i class="fas fa-star text-warning font-large-1" title="{!! __('bank_accounts.is_default') !!}"></i>
                        @else
                            <i class="fas fa-star-o text-muted font-large-1"></i>
                        @endif
                    </td>

                    <!-- Created By -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted small">{!! $account->creator->name ?? '---' !!}</span>
                    </td>

                    <!-- Actions -->
                    @if (auth()->user()->can('bank_accounts_update') || auth()->user()->can('bank_accounts_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.bank_accounts.parts.actions')
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('bank_accounts.no_bank_accounts_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $bankAccounts->links() !!}
    </div>
</div>
