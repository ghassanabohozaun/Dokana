<footer class="border-t border-slate-200/80 dark:border-slate-800/80 bg-white/60 dark:bg-slate-900/60 py-4 px-6 text-xs text-slate-500 dark:text-slate-400 mt-auto transition-colors">
    <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>
            {!! __('dashboard.copyright') !!} &copy; {!! date('Y') !!}
            <span class="font-bold text-slate-700 dark:text-slate-200">
                {!! auth()->user()->store->name ?? setting()->site_name !!}
            </span>. 
            {!! __('dashboard.all_rights_reserved') !!}.
        </p>
        <div class="flex items-center gap-3 text-[11px] text-slate-400">
            <span class="inline-flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                v2.0 Enterprise
            </span>
        </div>
    </div>
</footer>
