<div id="app" class="max-w-md mx-auto min-h-screen relative pb-24 shadow-2xl bg-gray-50/50 dark:bg-[#0b1121] transition-colors duration-300">
    @include('livewire.website.casher.partials.header')
    @include('livewire.website.casher.partials.metrics')
    @include('livewire.website.casher.partials.search-filters')
    @include('livewire.website.casher.partials.customer-list')
    @include('livewire.website.casher.partials.fab')

    <!-- Modals -->
    @include('livewire.website.casher.partials.modals.add-customer')
    @include('livewire.website.casher.partials.modals.ledger')
    @include('livewire.website.casher.partials.modals.transaction')
</div>

@push('css')
    <!-- jQuery in Head so it is available to Alpine/Livewire during initialization -->
    <script src="{{ asset('assets/dashbaord/vendors/js/tables/jquery-1.12.3.js') }}"></script>
    
    <!-- Bootstrap Datepicker CSS & JS -->
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/vendors/css/pickers/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <script src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    
    @if (app()->getLocale() == 'ar')
        <script src="{{ asset('assets/dashbaord/vendors/js/pickers/bootstrap-datepicker/locales/bootstrap-datepicker.ar.min.js') }}"></script>
    @endif
    
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/store.css') }}">
@endpush
