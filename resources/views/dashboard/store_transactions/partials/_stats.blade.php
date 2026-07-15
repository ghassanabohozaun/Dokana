
<div class="row mb-4" id="transactions-stats-container">
    
    <!-- Total Payments -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-payments">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_transactions.total_payments') ?? 'إجمالي التحصيلات' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_total_payments">{!! number_format($metrics['total_payments'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Total Debts -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-debts">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_transactions.total_debts') ?? 'إجمالي الديون' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_total_debts">{!! number_format($metrics['total_debts'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Net Balance -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-balance">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_transactions.net_balance') ?? 'الرصيد الصافي' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_net_balance">{!! number_format($metrics['net_balance'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Transactions Count -->
    <div class="col-md-3 col-sm-6">
        <div class="stat-card-premium sc-count">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_transactions.total_transactions_count') ?? 'عدد الحركات' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('store_transactions.transaction') ?? 'حركة' !!}</span>
                    <span id="ui_stats_total_count">{!! number_format($metrics['total_transactions_count'] ?? 0, 0) !!}</span>
                </h4>
            </div>
        </div>
    </div>
</div>
