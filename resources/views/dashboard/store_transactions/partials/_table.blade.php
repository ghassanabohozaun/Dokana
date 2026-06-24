<input type="hidden" id="store_transactions-total-count" value="{!! $store_transactions->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center d-lg-none align-middle py-3 border-top-0">#</th>
                <th class="text-center d-none d-lg-table-cell align-middle py-3 border-top-0">#</th>
                @if (isset($stores))
                    <th class="text-center align-middle py-3 border-top-0">{!! __('stores.store') !!}</th>
                @endif
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.store_customer') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.type') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.description') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.date') !!}</th>
                @if (auth()->user()->can('store_transactions_update') || auth()->user()->can('store_transactions_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">
                        {!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($store_transactions as $key=>$store_transaction)
                <tr id="row{{ $store_transaction->id }}">
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
                                    <h4 class="modal-name-title font-weight-bold">{!! optional($store_transaction->customer)->name ?? '---' !!}</h4>
                                    <span class="modal-role-badge">{!! __('store_transactions.store_transaction') !!}</span>
                                </div>

                                <!-- Detail Items List -->
                                <div class="modal-info-list mt-2">
                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-fingerprint"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('general.system_id') !!}</span>
                                            <span class="detail-info-value text-muted"># {!! $store_transaction->id !!}</span>
                                        </div>
                                    </div>

                                    @if (isset($stores))
                                        <div class="detail-item-modern">
                                            <div class="icon-circle"><i class="fas fa-briefcase"></i></div>
                                            <div class="detail-info-box text-left">
                                                <span class="detail-info-label">{!! __('stores.store') !!}</span>
                                                <span class="detail-info-value text-muted small">
                                                    @if ($store_transaction->store_id)
                                                        <span
                                                            class="badge badge-light-primary border-0">{!! optional($store_transaction->store)->name !!}</span>
                                                    @else
                                                        <span
                                                            class="badge badge-light-warning border-0">{!! __('roles.global_role') !!}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-user"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_customers.store_customer') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                {!! optional($store_transaction->customer)->name ?? '---' !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-exchange-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_transactions.type') !!}</span>
                                            <div class="detail-info-value mt-1">
                                                @if($store_transaction->type == 'debt')
                                                    <span class="badge badge-danger">{!! __('store_transactions.debt') !!}</span>
                                                @else
                                                    <span class="badge badge-success">{!! __('store_transactions.payment') !!}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-money-bill"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_transactions.amount') !!}</span>
                                            <span class="detail-info-value">{!! $store_transaction->amount !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-align-left"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_transactions.description') !!}</span>
                                            <span class="detail-info-value">{!! $store_transaction->description ?? '---' !!}</span>
                                        </div>
                                    </div>

                                    <div class="detail-item-modern">
                                        <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                                        <div class="detail-info-box text-left">
                                            <span class="detail-info-label">{!! __('store_transactions.date') !!}</span>
                                            <span class="text-secondary font-weight-bold" dir="ltr">{{ $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d h:i A') : $store_transaction->created_at->format('Y-m-d h:i A') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

                    <!-- Desktop ID Badge -->
                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($store_transactions->currentPage() - 1) * $store_transactions->perPage() !!}
                        </span>
                    </td>

                    <!-- Store -->
                    @if (isset($stores))
                        <td class="text-center align-middle">
                            @if ($store_transaction->store_id)
                                <a href="javascript:void(0)" class="store-chip">
                                    <i class="fas fa-briefcase mr-1"></i>
                                    {!! optional($store_transaction->store)->name !!}
                                </a>
                            @else
                                <span class="badge badge-light-warning border-0">
                                    <i class="fas fa-globe mr-1"></i> {!! __('roles.global_role') !!}
                                </span>
                            @endif
                        </td>
                    @endif

                    <!-- Customer Name -->
                    <td class="text-center align-middle font-weight-bold text-primary">{!! optional($store_transaction->customer)->name ?? '---' !!}</td>

                    <!-- Type -->
                    <td class="text-center align-middle">
                        @if($store_transaction->type == 'debt')
                            <span class="badge badge-danger">{!! __('store_transactions.debt') !!}</span>
                        @else
                            <span class="badge badge-success">{!! __('store_transactions.payment') !!}</span>
                        @endif
                    </td>

                    <!-- Amount -->
                    <td class="text-center align-middle">{!! $store_transaction->amount !!}</td>

                    <!-- Description -->
                    <td class="text-center align-middle">
                        <div class="font-weight-bold">{!! $store_transaction->description ?? '---' !!}</div>
                        @if($store_transaction->type == 'payment' && $store_transaction->store_bank_account_id && $store_transaction->bankAccount)
                            @php
                                $entityName = optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($store_transaction->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                                $accountName = $store_transaction->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $store_transaction->bankAccount->account_number;
                            @endphp
                            <div class="mt-1">
                                <span class="badge badge-light-success border-0"><i class="fas fa-university mr-1"></i>{{ $accountName }}</span>
                            </div>
                        @endif
                    </td>

                    <!-- Date -->
                    <td class="text-center align-middle" dir="ltr">{!! $store_transaction->transaction_date ? $store_transaction->transaction_date->format('Y-m-d h:i A') : $store_transaction->created_at->format('Y-m-d h:i A') !!}</td>

                    <!-- Actions -->
                    @if (auth()->user()->can('store_transactions_update') || auth()->user()->can('store_transactions_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.store_transactions.parts.actions')
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('store_transactions.no_store_transactions_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $store_transactions->links() !!}
    </div>
</div>
