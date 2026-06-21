@extends('layouts.website.app')

@section('content')
<div x-data="casherNotebook(window.casherConfig)" x-init="init()" class="max-w-md mx-auto min-h-screen relative pb-24 shadow-2xl bg-gray-50 dark:bg-[#0b1121] transition-colors duration-300 font-sans">
    
    @include('website.casher.notebook.partials.header')
    @include('website.casher.notebook.partials.metrics')
    @include('website.casher.notebook.partials.search-filters')
    @include('website.casher.notebook.partials.customer-list')
    @include('website.casher.notebook.partials.fab')

    <!-- Modals -->
    @include('website.casher.notebook.partials.modals.add-customer')
    @include('website.casher.notebook.partials.modals.ledger')
    @include('website.casher.notebook.partials.modals.transaction')

</div>

@push('css')
    <!-- jQuery in Head so it is available -->
    <script src="{{ asset('assets/dashbaord/vendors/js/tables/jquery-1.12.3.js') }}"></script>
    
    <!-- Bootstrap Datepicker CSS & JS -->
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/vendors/css/pickers/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <script src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    
    @if (app()->getLocale() == 'ar')
        <script src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/locales/bootstrap-datepicker.ar.min.js') }}"></script>
    @endif
    
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/store.css') }}">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/website/casher-notebook.css') }}?v={{ time() }}">
@endpush

@push('scripts')
<script>
    window.casherConfig = {
        apiBase: '{{ url(app()->getLocale() . "/casher/api") }}',
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        todayDate: '{{ \Carbon\Carbon::today()->format("Y-m-d") }}',
        translations: {
            success: '{{ __("notebook.success") }}',
            warning: '{{ __("notebook.warning") }}',
            areYouSure: '{{ __("notebook.are_you_sure") }}',
            confirmDeleteTx: '{{ __("notebook.confirm_delete_transaction") }}',
            yesDelete: '{{ __("notebook.yes_delete") }}',
            cancel: '{{ __("notebook.cancel") }}'
        }
    };
</script>
<script src="{{ asset('assets/website/casher-notebook.js') }}?v={{ time() }}"></script>
@endpush
@endsection
