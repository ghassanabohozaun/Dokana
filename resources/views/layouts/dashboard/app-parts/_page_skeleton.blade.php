<!-- Global Enterprise SaaS Shimmer Skeleton Overlay -->
<div id="global-page-skeleton" class="global-page-skeleton">
    <div class="w-full max-w-7xl mx-auto space-y-5">
        
        <!-- 1. Header Toolbar Skeleton -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="skeleton-shimmer h-11 w-11 rounded-2xl shrink-0"></div>
                <div class="space-y-1.5">
                    <div class="flex items-center gap-2">
                        <div class="skeleton-shimmer h-5 w-36 rounded-lg"></div>
                        <div class="skeleton-shimmer h-4.5 w-16 rounded-full"></div>
                    </div>
                    <div class="skeleton-shimmer h-3 w-52 rounded-md"></div>
                </div>
            </div>
            <div class="flex items-center gap-2.5 w-full sm:w-auto justify-end">
                <div class="skeleton-shimmer h-10 w-28 rounded-xl"></div>
                <div class="skeleton-shimmer h-10 w-36 rounded-xl"></div>
            </div>
        </div>

        <!-- 2. KPI Metrics Grid Skeleton (4 Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @for ($i = 0; $i < 4; $i++)
                <div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-4 space-y-3 shadow-xs">
                    <div class="flex items-center justify-between">
                        <div class="skeleton-shimmer h-3.5 w-20 rounded"></div>
                        <div class="skeleton-shimmer h-9 w-9 rounded-xl"></div>
                    </div>
                    <div class="skeleton-shimmer h-7 w-28 rounded-lg"></div>
                    <div class="skeleton-shimmer h-2.5 w-36 rounded"></div>
                </div>
            @endfor
        </div>

        <!-- 3. Smart Search & Filter Toolbar Skeleton -->
        <div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-3.5 shadow-xs">
            <div class="flex flex-col md:flex-row items-center gap-3">
                <div class="skeleton-shimmer h-10 flex-1 w-full rounded-xl"></div>
                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <div class="skeleton-shimmer h-10 w-32 rounded-xl"></div>
                    <div class="skeleton-shimmer h-10 w-32 rounded-xl"></div>
                    <div class="skeleton-shimmer h-10 w-24 rounded-xl"></div>
                </div>
            </div>
        </div>

        <!-- 4. Data Table Card Skeleton -->
        <div class="bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl overflow-hidden shadow-xs">
            <!-- Table Header Row Skeleton -->
            <div class="p-3.5 border-b border-slate-200/80 dark:border-slate-800/80 flex items-center justify-between bg-slate-50/60 dark:bg-slate-900/60">
                <div class="flex items-center gap-3">
                    <div class="skeleton-shimmer h-4 w-6 rounded"></div>
                    <div class="skeleton-shimmer h-4 w-28 rounded"></div>
                </div>
                <div class="skeleton-shimmer h-4 w-20 rounded"></div>
                <div class="hidden sm:block skeleton-shimmer h-4 w-24 rounded"></div>
                <div class="skeleton-shimmer h-4 w-16 rounded"></div>
                <div class="skeleton-shimmer h-4 w-20 rounded"></div>
            </div>

            <!-- Table Rows Skeleton (3 Balanced Rows) -->
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                @for ($r = 0; $r < 3; $r++)
                    <div class="p-3.5 flex items-center justify-between gap-4">
                        <!-- Left: # + Avatar + Title -->
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="skeleton-shimmer h-3.5 w-4 rounded shrink-0"></div>
                            <div class="skeleton-shimmer h-9 w-9 rounded-xl shrink-0"></div>
                            <div class="space-y-1.5 flex-1 max-w-[200px]">
                                <div class="skeleton-shimmer h-3.5 w-full rounded"></div>
                                <div class="skeleton-shimmer h-2.5 w-2/3 rounded"></div>
                            </div>
                        </div>

                        <!-- Center Info Data -->
                        <div class="hidden md:block skeleton-shimmer h-3.5 w-28 rounded"></div>

                        <!-- Status Badge Pill -->
                        <div class="skeleton-shimmer h-6 w-16 rounded-full shrink-0"></div>

                        <!-- Switch Pill -->
                        <div class="hidden sm:block skeleton-shimmer h-6 w-11 rounded-full shrink-0"></div>

                        <!-- Actions Icons -->
                        <div class="flex items-center gap-2 shrink-0">
                            <div class="skeleton-shimmer h-8 w-8 rounded-lg"></div>
                            <div class="skeleton-shimmer h-8 w-8 rounded-lg"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

    </div>
</div>
