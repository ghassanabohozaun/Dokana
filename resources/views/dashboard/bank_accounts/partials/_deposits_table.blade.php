<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="bg-white">
            <tr>
                <th class="text-center align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('general.date') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.created_by') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.amount') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_customers.store_customer') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('store_transactions.description') !!}</th>
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
                        {!! $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d h:i A') : '' !!}
                    </td>
                    <td class="text-center align-middle text-muted">
                        <i class="fas fa-user-circle mr-1"></i> {!! optional($transaction->createdBy)->name ?? '---' !!}
                    </td>
                    <td class="text-center align-middle">
                        <span class="premium-store-badge store-badge-payment">
                            <i class="fas fa-arrow-down"></i> {!! number_format($transaction->amount, 2) !!}
                        </span>
                    </td>
                    <td class="text-center align-middle text-primary font-weight-bold">
                        {!! optional($transaction->customer)->name ?? '---' !!}
                    </td>
                    <td class="align-middle">
                        <div class="font-weight-bold">{!! $transaction->description ?: __('general.no_description') !!}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-4 text-muted">
                        <div class="d-flex flex-column align-items-center">
                            <i class="fas fa-inbox font-large-2 mb-2 text-light"></i>
                            <span>{!! __('bank_accounts.no_deposits_yet') !!}</span>
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
