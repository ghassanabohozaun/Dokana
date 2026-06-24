<input type="hidden" id="payment_entities-total-count" value="{!! $entities->total() !!}">
<div class="table-responsive">
    <table class="table table-hover mb-0" id='myTable'>
        <thead class="bg-white">
            <tr>
                <th class="text-center align-middle py-3 border-top-0">#</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('payment_entities.name') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('payment_entities.type') !!}</th>
                <th class="text-center align-middle py-3 border-top-0 d-none d-lg-table-cell">{!! __('departments.created_by') !!}</th>
                <th class="text-center align-middle py-3 border-top-0">{!! __('payment_entities.status') !!}</th>
                @can('payment_entities_update')
                <th class="text-center align-middle py-3 border-top-0">{!! __('departments.manage_status') !!}</th>
                @endcan
                @if (auth()->user()->can('payment_entities_update') || auth()->user()->can('payment_entities_delete'))
                    <th class="text-center align-middle py-3 border-top-0 min-w-140 sticky-actions">{!! __('general.actions') !!}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($entities as $key => $entity)
                <tr id="row{{ $entity->id }}">
                    <td class="text-center align-middle">
                        <span class="badge badge-info badge-pill badge-glow premium-badge-circle">
                            {!! $loop->iteration + ($entities->currentPage() - 1) * $entities->perPage() !!}
                        </span>
                    </td>

                    <td class="text-center align-middle font-weight-bold text-primary">
                        @if($entity->type == 'wallet')
                            <i class="fas fa-wallet mr-1 text-info"></i>
                        @else
                            <i class="fas fa-university mr-1 text-primary"></i>
                        @endif
                        {!! $entity->name !!}
                    </td>

                    <td class="text-center align-middle">
                        @if($entity->type == 'wallet')
                            <span class="badge badge-light-info badge-pill font-weight-bold px-2 py-1"><i class="fas fa-wallet mr-1"></i> {!! __('payment_entities.type_wallet') !!}</span>
                        @else
                            <span class="badge badge-light-primary badge-pill font-weight-bold px-2 py-1"><i class="fas fa-university mr-1"></i> {!! __('payment_entities.type_bank') !!}</span>
                        @endif
                    </td>

                    <td class="text-center align-middle d-none d-lg-table-cell">
                        <span class="text-muted small">{!! $entity->creator->name ?? '---' !!}</span>
                    </td>

                    <!-- Status -->
                    <td class="text-center align-middle">
                        @include('dashboard.payment_entities.parts.status')
                    </td>

                    <!-- Manage Status -->
                    @can('payment_entities_update')
                    <td class="text-center align-middle">
                        @include('dashboard.payment_entities.parts.manage_status')
                    </td>
                    @endcan

                    @if (auth()->user()->can('payment_entities_update') || auth()->user()->can('payment_entities_delete'))
                        <td class="text-center align-middle sticky-actions">
                            @include('dashboard.payment_entities.parts.actions')
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100%" class="text-center p-3 text-muted">
                        <i class="ft-info mr-1"></i> {!! __('payment_entities.no_payment_entities_found') !!}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="float-right mt-2 custom-pagination">
        {!! $entities->links() !!}
    </div>
</div>
