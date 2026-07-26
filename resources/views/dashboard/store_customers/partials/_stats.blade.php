<div class="row mb-4" id="customers-lifetime-stats-container">
    
    <!-- Total Lifetime Debts -->
    <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-debts opacity-90" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: #fff;">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper" style="background-color: rgba(255,255,255,0.1); color: #cbd5e1;">
                <i class="fas fa-history"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title" style="color: #cbd5e1 !important;">{!! __('store_customers.total_lifetime_debts') ?? 'حجم الديون (تاريخياً)' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end; color: #ffffff !important;">
                    <span class="stat-currency" style="color: #ffffff !important;">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_total_lifetime_debts" style="color: #ffffff !important;">{!! number_format($metrics['total_lifetime_debts'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Total Lifetime Payments -->
    <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-payments opacity-90" style="background: linear-gradient(135deg, #334155, #1e293b); color: #fff;">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper" style="background-color: rgba(255,255,255,0.1); color: #cbd5e1;">
                <i class="fas fa-archive"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title" style="color: #cbd5e1 !important;">{!! __('store_customers.total_lifetime_payments') ?? 'حجم التحصيلات (تاريخياً)' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end; color: #ffffff !important;">
                    <span class="stat-currency" style="color: #ffffff !important;">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_total_lifetime_payments" style="color: #ffffff !important;">{!! number_format($metrics['total_lifetime_payments'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>
</div>

<h5 class="mb-3 text-muted" style="font-weight: bold; font-size: 15px;">الأرصدة الحالية المعلقة:</h5>
<div class="row mb-4" id="customers-stats-container">
    <!-- Total Customers -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-count">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_customers.total_customers_count') ?? 'إجمالي العملاء' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('store_customers.customer') ?? 'عميل' !!}</span>
                    <span id="ui_stats_total_customers_count">{!! number_format($metrics['total_customers_count'] ?? 0, 0) !!}</span>
                </h4>
            </div>
        </div>
    </div>

    <!-- Total Creditor Balances -->
    <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
        <div class="stat-card-premium sc-payments">
            <div class="bg-shape"></div>
            <div class="stat-icon-wrapper">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stat-content text-left">
                <span class="stat-title">{!! __('store_customers.total_creditor_balances') ?? 'أرصدة دائنة (لهم)' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_total_creditor_balances">{!! number_format($metrics['total_creditor_balances'] ?? 0, 2) !!}</span>
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
                <span class="stat-title">{!! __('store_customers.total_outstanding_debts') ?? 'الديون المستحقة (عليهم)' !!}</span>
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
                <span class="stat-title">{!! __('store_customers.net_balance') ?? 'الرصيد الصافي' !!}</span>
                <h4 class="stat-value" style="direction: ltr; justify-content: flex-end;">
                    <span class="stat-currency">{!! __('general.currency') ?? 'شيكل' !!}</span>
                    <span id="ui_stats_net_balance">{!! number_format($metrics['net_balance'] ?? 0, 2) !!}</span>
                </h4>
            </div>
        </div>
    </div>

</div>
