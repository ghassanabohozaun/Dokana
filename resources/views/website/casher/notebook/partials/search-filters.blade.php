    <!-- Search & Filters -->
    <div class="px-4 mb-4">
        <div class="relative group mb-3 flex items-center">
            <div class="absolute inset-y-0 {{ app()->getLocale() == 'ar' ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none text-gray-400 group-focus-within:text-primary transition-colors">
                <i class="ph-bold ph-magnifying-glass text-lg"></i>
            </div>
            <input x-model="search" type="text" placeholder="{{ __('notebook.search_customer') }}" class="w-full bg-white dark:bg-darkCard border border-gray-200 dark:border-gray-800 rounded-2xl py-3.5 px-11 rtl:pl-20 ltr:pr-20 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all text-gray-800 dark:text-gray-100 shadow-sm placeholder-gray-400">
            <div class="absolute {{ app()->getLocale() == 'ar' ? 'left-2' : 'right-2' }} flex items-center gap-1.5">
                <button x-show="search.length > 0" @click="search = ''" style="display: none;" 
                    x-transition
                    class="text-gray-400 hover:text-red-500 transition-colors w-8 h-8 flex items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
                <button type="button" @click.prevent="startVoiceSearch()" 
                    class="w-9 h-9 flex items-center justify-center rounded-full transition-all"
                    :class="isListening ? 'bg-red-50 text-red-500 shadow-[0_0_0_4px_rgba(239,68,68,0.2)] animate-pulse' : 'bg-blue-50 text-blue-500 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400'">
                    <i class="ph-bold ph-microphone" :class="isListening ? 'text-lg' : 'text-base'"></i>
                </button>
            </div>
        </div>
        <div class="flex gap-2 overflow-x-auto hide-scrollbar whitespace-nowrap pb-1">
            <button @click="setFilter('all')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'all' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_all') }}</button>
            <button @click="setFilter('debt')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'debt' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_has_debt') }}</button>
            <button @click="setFilter('highest_debt')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'highest_debt' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_highest_debt') ?? 'الأعلى ديوناً' }}</button>
            <button @click="setFilter('paid')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'paid' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_paid') }}</button>
            <button @click="setFilter('credit')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'credit' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_credit') ?? 'لهم رصيد' }}</button>
            <button @click="setFilter('disabled')" class="whitespace-nowrap px-4 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-sm font-bold" :class="filter === 'disabled' ? 'bg-primary text-white' : 'text-gray-600 dark:text-gray-300 bg-white dark:bg-darkCard'">{{ __('notebook.filter_disabled') ?? 'معطل' }}</button>
        </div>
    </div>
