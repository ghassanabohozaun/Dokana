@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="pagination flex items-center justify-between w-full px-2 py-3">
        <!-- Mobile View (Compact, Single-Line, Clean) -->
        <div class="flex justify-between items-center flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-400 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-not-allowed rounded-xl select-none">
                    <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'right' : 'left' }} text-[10px]"></i>
                    <span>{{ Lang() == 'ar' ? 'السابق' : 'Previous' }}</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors shadow-2xs">
                    <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'right' : 'left' }} text-[10px]"></i>
                    <span>{{ Lang() == 'ar' ? 'السابق' : 'Previous' }}</span>
                </a>
            @endif

            <div class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 text-xs font-bold text-slate-700 dark:text-slate-300">
                <span class="text-indigo-600 dark:text-indigo-400 font-mono">{{ $paginator->currentPage() }}</span>
                <span class="text-slate-400">/</span>
                <span class="font-mono">{{ $paginator->lastPage() }}</span>
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700/80 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors shadow-2xs">
                    <span>{{ Lang() == 'ar' ? 'التالي' : 'Next' }}</span>
                    <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                </a>
            @else
                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-400 dark:text-slate-600 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 cursor-not-allowed rounded-xl select-none">
                    <span>{{ Lang() == 'ar' ? 'التالي' : 'Next' }}</span>
                    <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                </span>
            @endif
        </div>

        <!-- Desktop View (Full Numbers & Record Counter) -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    <span>{{ __('general.showing') ?? 'عرض' }}</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $paginator->firstItem() }}</span>
                    <span>{{ __('general.to') ?? 'إلى' }}</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">{{ $paginator->lastItem() }}</span>
                    <span>{{ __('general.of') ?? 'من إجمالي' }}</span>
                    <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $paginator->total() }}</span>
                    <span>{{ __('general.records') ?? 'سجل' }}</span>
                </p>
            </div>

            <div>
                <div class="inline-flex items-center rounded-xl shadow-2xs overflow-hidden border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="w-9 h-9 flex items-center justify-center bg-slate-50 dark:bg-slate-800/80 text-xs font-medium text-slate-300 dark:text-slate-600 cursor-not-allowed shrink-0 select-none">
                            <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'right' : 'left' }} text-[10px]"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="w-9 h-9 flex items-center justify-center bg-white dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shrink-0" aria-label="{{ __('pagination.previous') }}">
                            <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'right' : 'left' }} text-[10px]"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true" class="min-w-[36px] h-9 px-2 flex items-center justify-center bg-slate-50/80 dark:bg-slate-800/50 text-xs font-medium text-slate-400 dark:text-slate-500 border-s border-slate-200/80 dark:border-slate-700/80 shrink-0 select-none">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="min-w-[36px] h-9 px-3 flex items-center justify-center bg-indigo-600 text-xs font-bold text-white shadow-inner border-s border-indigo-700 shrink-0 select-none">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="min-w-[36px] h-9 px-3 flex items-center justify-center bg-white dark:bg-slate-800 text-xs font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border-s border-slate-200/80 dark:border-slate-700/80 transition-colors shrink-0" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="w-9 h-9 flex items-center justify-center bg-white dark:bg-slate-800 text-xs font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border-s border-slate-200/80 dark:border-slate-700/80 transition-colors shrink-0" aria-label="{{ __('pagination.next') }}">
                            <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="w-9 h-9 flex items-center justify-center bg-slate-50 dark:bg-slate-800/80 text-xs font-medium text-slate-300 dark:text-slate-600 cursor-not-allowed border-s border-slate-200/80 dark:border-slate-700/80 shrink-0 select-none">
                            <i class="fas fa-chevron-{{ Lang() == 'ar' ? 'left' : 'right' }} text-[10px]"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
