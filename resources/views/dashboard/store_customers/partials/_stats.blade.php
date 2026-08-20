<div class="space-y-4" id="customers-stats-container">
    
    <!-- Primary Real-Time Balance Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- 1. Total Customers Count -->
        <div class="dash-card p-4 flex items-center gap-3.5 relative overflow-hidden group hover:border-indigo-200 dark:hover:border-indigo-800">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 text-lg shadow-sm">
                <i class="fas fa-users"></i>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block truncate">
                    {!! __('store_customers.total_customers_count') ?? 'إجمالي العملاء' !!}
                </span>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-lg font-black text-slate-800 dark:text-white font-mono tracking-tight" id="ui_stats_total_customers_count">
                        {!! number_format($metrics['total_customers_count'] ?? 0, 0) !!}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400">{!! __('store_customers.customer') ?? 'عميل' !!}</span>
                </div>
            </div>
        </div>

        <!-- 2. Creditor Balances (For Customers) -->
        <div class="dash-card p-4 flex items-center gap-3.5 relative overflow-hidden group hover:border-emerald-200 dark:hover:border-emerald-800">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-lg shadow-sm">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block truncate">
                    {!! __('store_customers.total_creditor_balances') ?? 'أرصدة دائنة (لهم)' !!}
                </span>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-lg font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight" id="ui_stats_total_creditor_balances">
                        {!! number_format($metrics['total_creditor_balances'] ?? 0, 2) !!}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400">{!! __('general.currency') ?? 'شيكل' !!}</span>
                </div>
            </div>
        </div>

        <!-- 3. Outstanding Debts (On Customers) -->
        <div class="dash-card p-4 flex items-center gap-3.5 relative overflow-hidden group hover:border-rose-200 dark:hover:border-rose-800">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 text-lg shadow-sm">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block truncate">
                    {!! __('store_customers.total_outstanding_debts') ?? 'الديون المستحقة (عليهم)' !!}
                </span>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-lg font-black text-rose-600 dark:text-rose-400 font-mono tracking-tight" id="ui_stats_total_debts">
                        {!! number_format($metrics['total_debts'] ?? 0, 2) !!}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400">{!! __('general.currency') ?? 'شيكل' !!}</span>
                </div>
            </div>
        </div>

        <!-- 4. Net Balance -->
        <div class="dash-card p-4 flex items-center gap-3.5 relative overflow-hidden group hover:border-sky-200 dark:hover:border-sky-800">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 text-lg shadow-sm">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="min-w-0">
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 block truncate">
                    {!! __('store_customers.net_balance') ?? 'الرصيد الصافي' !!}
                </span>
                <div class="flex items-baseline gap-1.5 mt-0.5">
                    <span class="text-lg font-black font-mono tracking-tight {{ ($metrics['net_balance'] ?? 0) >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}" id="ui_stats_net_balance">
                        {!! number_format($metrics['net_balance'] ?? 0, 2) !!}
                    </span>
                    <span class="text-[11px] font-semibold text-slate-400">{!! __('general.currency') ?? 'شيكل' !!}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Lifetime Summary Strip -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Lifetime Debts -->
        <div class="dash-card p-3.5 px-4 flex items-center justify-between bg-gradient-to-r from-slate-900 to-slate-800 text-white dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-slate-200 text-sm">
                    <i class="fas fa-history"></i>
                </div>
                <span class="text-xs font-bold text-slate-300">
                    {!! __('store_customers.total_lifetime_debts') ?? 'حجم الديون (تاريخياً)' !!}
                </span>
            </div>
            <div class="font-mono font-black text-sm text-white">
                <span id="ui_stats_total_lifetime_debts">{!! number_format($metrics['total_lifetime_debts'] ?? 0, 2) !!}</span>
                <span class="text-xs font-normal text-slate-400 ms-1">{!! __('general.currency') ?? 'شيكل' !!}</span>
            </div>
        </div>

        <!-- Lifetime Collections -->
        <div class="dash-card p-3.5 px-4 flex items-center justify-between bg-gradient-to-r from-slate-900 to-indigo-950 text-white dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-indigo-200 text-sm">
                    <i class="fas fa-archive"></i>
                </div>
                <span class="text-xs font-bold text-slate-300">
                    {!! __('store_customers.total_lifetime_payments') ?? 'حجم التحصيلات (تاريخياً)' !!}
                </span>
            </div>
            <div class="font-mono font-black text-sm text-emerald-400">
                <span id="ui_stats_total_lifetime_payments">{!! number_format($metrics['total_lifetime_payments'] ?? 0, 2) !!}</span>
                <span class="text-xs font-normal text-slate-400 ms-1">{!! __('general.currency') ?? 'شيكل' !!}</span>
            </div>
        </div>
    </div>

</div>
