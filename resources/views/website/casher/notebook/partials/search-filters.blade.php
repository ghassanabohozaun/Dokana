    <!-- Search & Filters -->
    <div class="px-4 mb-4">
        <div class="relative group mb-3">
            <i class="ph-bold ph-magnifying-glass absolute {{ app()->getLocale() == 'ar' ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 text-gray-400 text-lg group-focus-within:text-primary transition-colors"></i>
            <input x-model="search" type="text" placeholder="{{ __('notebook.search_customer') }}" class="w-full bg-white dark:bg-darkCard border border-gray-200 dark:border-gray-800 rounded-2xl py-3.5 px-11 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all text-gray-800 dark:text-gray-100 shadow-sm placeholder-gray-400">
            <button x-show="search.length > 0" @click="search = ''" style="display: none;" 
                x-transition:enter="transition-opacity duration-150"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute {{ app()->getLocale() == 'ar' ? 'left-4' : 'right-4' }} top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors w-7 h-7 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                <i class="ph-bold ph-x text-sm"></i>
            </button>
        </div>
        <div class="flex gap-2 flex-wrap pb-1">
            <button @click="setFilter('all')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'all' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_all') }}</button>
            <button @click="setFilter('debt')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'debt' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_has_debt') }}</button>
            <button @click="setFilter('paid')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'paid' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_paid') }}</button>
            <button @click="setFilter('credit')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'credit' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_credit') ?? 'لهم رصيد' }}</button>
            <button @click="setFilter('disabled')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'disabled' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_disabled') ?? 'معطل' }}</button>
        </div>
    </div>
