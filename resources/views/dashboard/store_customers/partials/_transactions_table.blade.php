<div class="table-responsive">
    <table class="table table-hover mb-0 text-center" style="min-width: 600px;">
        <thead style="position: sticky; top: 0; z-index: 10; background: #f8fafc; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <tr>
                <th>#</th>
                <th>{!! __('store_transactions.date') !!}</th>
                <th>{!! __('store_transactions.type') !!}</th>
                <th>{!! __('store_transactions.amount') !!}</th>
                <th>{!! __('store_transactions.description') !!}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="align-middle">{!! $index + 1 + ($transactions->currentPage() - 1) * $transactions->perPage() !!}</td>
                    <td class="align-middle">
                        <span class="font-weight-bold text-dark">{!! $transaction->transaction_date !!}</span>
                    </td>
                    <td class="align-middle">
                        @if($transaction->type === 'debt')
                            <span class="premium-store-badge store-badge-debt">
                                <i class="fas fa-arrow-down"></i> {!! __('store_transactions.debt') !!}
                            </span>
                        @else
                            <span class="premium-store-badge store-badge-payment">
                                <i class="fas fa-arrow-up"></i> {!! __('store_transactions.payment') !!}
                            </span>
                        @endif
                    </td>
                    <td class="align-middle">
                        <span class="font-weight-bold {{ $transaction->type === 'debt' ? 'text-danger' : 'text-success' }}" style="font-size: 15px;">
                            {!! number_format($transaction->amount, 2) !!}
                        </span>
                    </td>
                    <td class="align-middle text-muted">
                        {!! $transaction->description ?: '-' !!}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-folder-open fa-3x mb-2 opacity-50"></i>
                            <p class="mb-0">{!! __('general.no_data') !!}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($transactions->hasPages())
<div class="d-flex justify-content-center p-3">
    {!! $transactions->links() !!}
</div>
@endif
