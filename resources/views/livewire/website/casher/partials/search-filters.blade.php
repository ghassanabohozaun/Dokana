    <!-- Search & Filters -->
    <div class="px-4 mb-4">
        <div class="relative group mb-3">
            <i class="ph ph-magnifying-glass absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg group-focus-within:text-primary transition-colors"></i>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('notebook.search_customer') }}" class="w-full bg-white dark:bg-darkCard border border-gray-200 dark:border-gray-800 rounded-2xl py-3.5 pr-11 pl-4 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all text-gray-800 dark:text-gray-100 shadow-sm placeholder-gray-400">
        </div>
        <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
            <button wire:click="setFilter('all')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold {{ $filter === 'all' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard' }}">{{ __('notebook.filter_all') }}</button>
            <button wire:click="setFilter('debt')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold {{ $filter === 'debt' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard' }}">{{ __('notebook.filter_has_debt') }}</button>
            <button wire:click="setFilter('paid')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold {{ $filter === 'paid' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard' }}">{{ __('notebook.filter_paid') }}</button>
        </div>
    </div>
