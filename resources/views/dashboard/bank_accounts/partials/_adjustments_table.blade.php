<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="bg-white">
            <tr>
                <th class="text-center align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('general.date') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.created_by') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.previous_balance') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.actual_balance_title') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.difference_adjustment') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('bank_accounts.notes') !!}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $key => $transaction)
                <tr>
                    <td class="text-center align-middle">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() !!}
                        </span>
                    </td>
                    <td class="text-center align-middle font-weight-bold" dir="ltr">
                        {!! $transaction->created_at ? $transaction->created_at->format('Y-m-d h:i A') : '' !!}
                    </td>
                    <td class="text-center align-middle text-muted">
                        <i class="fas fa-user-circle mr-1"></i> {!! optional($transaction->creator)->name ?? '---' !!}
                    </td>
                    <td class="text-center align-middle text-muted">
                        {!! number_format($transaction->old_balance, 2) !!}
                    </td>
                    <td class="text-center align-middle text-dark font-weight-bold">
                        {!! number_format($transaction->new_balance, 2) !!}
                    </td>
                    <td class="text-center align-middle">
                        @if($transaction->amount > 0)
                            <span class="premium-store-badge store-badge-payment">
                                <i class="fas fa-arrow-down"></i> {!! number_format(abs($transaction->amount), 2) !!}
                            </span>
                            <br><small class="text-success">{!! __('bank_accounts.surplus') !!}</small>
                        @elseif($transaction->amount < 0)
                            <span class="premium-store-badge store-badge-debt">
                                <i class="fas fa-arrow-up"></i> {!! number_format(abs($transaction->amount), 2) !!}
                            </span>
                            <br><small class="text-danger">{!! __('bank_accounts.deficit') !!}</small>
                        @else
                            <span class="text-muted">0.00</span>
                        @endif
                    </td>
                    <td class="align-middle">
                        <div class="font-weight-bold">{!! $transaction->notes ?: __('general.no_description') !!}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-4 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-inbox font-large-2 mb-2 text-light"></i>
                            <span>{!! __('bank_accounts.no_adjustments_yet') !!}</span>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="float-right mt-3 custom-pagination">
        {!! $transactions->links() !!}
    </div>
</div>
