<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('store_customers.statement_title') }} - {{ $customer->name }}</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
            margin-top: 14mm;
            margin-bottom: 14mm;
            margin-left: 9mm;
            margin-right: 9mm;
        }

        body {
            font-family: 'tajawal', sans-serif;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Helpers */
        .w-full {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .ltr {
            direction: ltr;
            display: inline-block;
        }

        /* Top Accent Bar */
        .top-accent-bar {
            height: 3px;
            background-color: #4f46e5;
            margin-bottom: 14px;
            border-radius: 2px;
        }

        /* 1. Header Layout */
        .header-table {
            width: 100%;
            margin-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 12px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .store-logo {
            max-height: 60px;
            max-width: 130px;
        }

        .store-name {
            font-size: 17px;
            font-weight: bold;
            color: #0f172a;
            margin: 0 0 2px 0;
        }

        .store-meta {
            font-size: 9.5px;
            color: #64748b;
            margin: 0;
            line-height: 1.4;
        }

        /* Statement Metadata Box */
        .statement-meta-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
            display: inline-block;
        }

        .statement-main-title {
            font-size: 14px;
            font-weight: bold;
            color: #4f46e5;
            margin: 0 0 4px 0;
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
        }

        .meta-line {
            font-size: 9px;
            color: #475569;
            margin: 1px 0;
        }

        /* 2. Split Hero Section (Customer Card & Financial Card) */
        .hero-table {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }

        .hero-card {
            border-radius: 10px;
            padding: 10px 14px;
            vertical-align: top;
        }

        /* Customer Info Card (Left/Right) */
        .customer-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .card-header-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }

        .customer-title-name {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .customer-details-table {
            width: 100%;
            font-size: 9.5px;
        }

        .customer-details-table td {
            padding: 2px 0;
        }

        /* Financial Highlight Card */
        .finance-card {
            background-color: #f8fafc;
            border: 1px solid #cbd5e1;
            text-align: center;
        }

        .balance-hero-amount {
            font-size: 20px;
            font-weight: bold;
            direction: ltr;
            margin: 2px 0;
        }

        .balance-badge-pill {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            margin-bottom: 6px;
        }

        /* Micro 3-box breakdown inside finance card */
        .finance-breakdown-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            margin-top: 6px;
            padding-top: 6px;
        }

        .finance-breakdown-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 0 4px;
        }

        .micro-label {
            font-size: 8px;
            color: #64748b;
            margin-bottom: 1px;
        }

        .micro-val {
            font-size: 10px;
            font-weight: bold;
            direction: ltr;
        }

        /* 3. Modern Transactions Ledger Table */
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5px;
            margin-bottom: 14px;
        }

        .ledger-table th {
            background-color: #0f172a;
            color: #f8fafc;
            font-weight: bold;
            padding: 8px 6px;
            text-align: center;
            border: 1px solid #0f172a;
            font-size: 9.5px;
        }

        .ledger-table td {
            padding: 6px 6px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .ledger-table tr:nth-child(even) td {
            background-color: #fcfdfd;
        }

        /* Previous Balance Row */
        .prev-balance-row td {
            background-color: #fffbeb !important;
            border-top: 1.5px solid #fde047;
            border-bottom: 1.5px solid #fde047;
            font-weight: bold;
            color: #854d0e;
        }

        /* Soft Pills */
        .pill {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8.5px;
            font-weight: bold;
            text-align: center;
        }

        .pill-debt {
            background-color: #ffe4e6;
            color: #e11d48;
        }

        .pill-payment {
            background-color: #d1fae5;
            color: #059669;
        }

        .pill-prev {
            background-color: #fef08a;
            color: #854d0e;
        }

        /* 4. Signatures Block */
        .signatures-table {
            width: 100%;
            margin-top: 14px;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .sig-box {
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            background-color: #fafafa;
            vertical-align: top;
            width: 50%;
        }

        .sig-label {
            font-size: 9.5px;
            font-weight: bold;
            color: #475569;
            margin-bottom: 30px;
        }

        .sig-line {
            border-top: 1px solid #94a3b8;
            width: 70%;
            margin: 0 auto;
            padding-top: 3px;
            font-size: 8px;
            color: #94a3b8;
        }

        /* 5. Footer Table */
        .footer-table {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            font-size: 8.5px;
            color: #94a3b8;
        }
    </style>
</head>

<body>

    <!-- Accent Color Top Bar -->
    <div class="top-accent-bar"></div>

    <!-- Header Section -->
    <table class="header-table">
        <tr>
            <!-- Store Logo & Information -->
            <td style="width: 55%;">
                <table style="width: 100%;">
                    <tr>
                        @if (!empty($storeLogo))
                            <td style="width: 70px; vertical-align: middle;">
                                <img src="{{ $storeLogo }}" class="store-logo" alt="Logo">
                            </td>
                        @endif
                        <td
                            style="vertical-align: middle; {{ !empty($storeLogo) ? (app()->getLocale() == 'ar' ? 'padding-right: 10px;' : 'padding-left: 10px;') : '' }}">
                            <h1 class="store-name">{{ optional($store)->name ?? 'Dokana' }}</h1>
                            @if (optional($store)->address)
                                <p class="store-meta">{{ __('stores.address') }}: {{ $store->address }}</p>
                            @endif
                            @if (optional($store)->phone)
                                <p class="store-meta">{{ __('store_customers.phone') }}: <span
                                        class="ltr">{{ $store->phone }}</span></p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Statement Meta Box -->
            <td style="width: 45%; text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};">
                <div class="statement-meta-box">
                    <div class="statement-main-title">{{ __('store_customers.statement_title') }}</div>
                    <br>
                    <div class="meta-line">
                        <b>{{ __('store_customers.statement_no') }}:</b>
                        <span class="ltr">STMT-#{{ str_pad($customer->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <br>
                    <div class="meta-line">
                        <b>{{ __('store_customers.issue_date') }}:</b>
                        <span class="ltr">{{ $generatedAt->format('Y-m-d H:i') }}</span>
                    </div>
                    <br>
                    <div class="meta-line" style="color: #4f46e5; font-weight: bold; margin-top: 2px;">
                        {{ __('store_customers.last_transactions_count', ['count' => count($transactions)]) }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Split Hero Section: Customer Info & Financial Overview -->
    <table class="hero-table">
        <tr>
            <!-- Customer Card (50%) -->
            <td class="hero-card customer-card" style="width: 50%;">
                <div class="card-header-label">{{ __('store_customers.customer_details') }}</div>
                <div class="customer-title-name">{{ $customer->name }}</div>
                <table class="customer-details-table">
                    <tr>
                        <td style="color: #64748b; width: 40%;">{{ __('store_customers.phone') }}:</td>
                        <td class="font-bold ltr"
                            style="{{ app()->getLocale() == 'ar' ? 'text-align: right;' : 'text-align: left;' }}">
                            {{ $customer->phone ?? '---' }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">{{ __('store_customers.max_debt_limit') }}:</td>
                        <td class="font-bold ltr"
                            style="{{ app()->getLocale() == 'ar' ? 'text-align: right;' : 'text-align: left;' }}">
                            {{ $customer->max_debt_limit ? number_format($customer->max_debt_limit, 2) : __('general.unlimited') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">{{ __('store_customers.account_status') }}:</td>
                        <td>
                            <span style="font-weight: bold; color: {{ $customer->status ? '#059669' : '#dc2626' }};">
                                {{ $customer->status ? __('general.enable') : __('general.disabled') }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>

            <!-- Financial Hero Card (50%) -->
            <td class="hero-card finance-card" style="width: 50%;">
                <div class="card-header-label" style="text-align: center;">
                    {{ __('store_customers.financial_summary_short') }}</div>

                <div class="balance-hero-amount"
                    style="color: {{ $currentBalance > 0 ? '#dc2626' : ($currentBalance < 0 ? '#059669' : '#0f172a') }};">
                    {{ number_format(abs($currentBalance), 2) }}
                    <span
                        style="font-size: 11px; font-weight: normal; color: #64748b;">{{ __('general.currency') ?? 'ILS' }}</span>
                </div>

                <div>
                    <span class="balance-badge-pill"
                        style="background-color: {{ $currentBalance > 0 ? '#fee2e2; color: #dc2626' : ($currentBalance < 0 ? '#dcfce7; color: #15803d' : '#f1f5f9; color: #475569') }};">
                        {{ $currentBalance > 0 ? __('store_customers.status_debtor') : ($currentBalance < 0 ? __('store_customers.status_creditor') : __('store_customers.status_cleared')) }}
                    </span>
                </div>

                <!-- 3 Micro Breakdown Columns -->
                <table class="finance-breakdown-table">
                    <tr>
                        <td>
                            <div class="micro-label">{{ __('store_customers.previous_balance') }}</div>
                            <div class="micro-val" style="color: #475569;">{{ number_format($previousBalance, 2) }}
                            </div>
                        </td>
                        <td style="border-left: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0;">
                            <div class="micro-label">{{ __('store_customers.period_debts') }}</div>
                            <div class="micro-val" style="color: #e11d48;">{{ number_format($recentDebts, 2) }}</div>
                        </td>
                        <td>
                            <div class="micro-label">{{ __('store_customers.period_payments') }}</div>
                            <div class="micro-val" style="color: #059669;">{{ number_format($recentPayments, 2) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Transactions Ledger Table -->
    <table class="ledger-table">
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 14%;">{{ __('store_customers.date_time') }}</th>
                <th style="width: 11%;">{{ __('store_customers.transaction_type') }}</th>
                <th style="width: 32%;">{{ __('store_customers.transaction_description') }}</th>
                <th style="width: 11%;">{{ __('store_customers.debit_amount') }}</th>
                <th style="width: 11%;">{{ __('store_customers.credit_amount') }}</th>
                <th style="width: 11%;">{{ __('store_customers.running_balance') }}</th>
                <th style="width: 6%;">{{ __('store_customers.created_by') }}</th>
            </tr>
        </thead>
        <tbody>
            <!-- Previous Balance Row -->
            <tr class="prev-balance-row">
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center">
                    <span class="pill pill-prev">{{ __('store_customers.previous_balance_badge') }}</span>
                </td>
                <td>{{ __('store_customers.previous_balance_row_desc', ['count' => count($transactions)]) }}</td>
                <td class="text-center">-</td>
                <td class="text-center">-</td>
                <td class="text-center ltr font-bold" style="direction: ltr;">
                    {{ number_format($previousBalance, 2) }}
                </td>
                <td class="text-center">-</td>
            </tr>

            <!-- Transactions Rows -->
            @forelse($transactions as $index => $transaction)
                <tr>
                    <td class="text-center" style="color: #64748b;">{{ $index + 1 }}</td>
                    <td class="text-center" style="direction: ltr;">
                        {{ $transaction->transaction_date ? $transaction->transaction_date->format('Y-m-d H:i') : $transaction->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="text-center">
                        @if ($transaction->type == 'debt')
                            <span class="pill pill-debt">{{ __('store_customers.debt_sales') }}</span>
                        @else
                            <span class="pill pill-payment">{{ __('store_customers.payment_cash') }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $transaction->description ?: ($transaction->type == 'debt' ? __('store_customers.credit_sales_desc') : __('store_customers.cash_payment_desc')) }}
                    </td>
                    <td class="text-center font-bold" style="direction: ltr; color: #e11d48;">
                        {{ $transaction->type == 'debt' ? number_format($transaction->amount, 2) : '-' }}
                    </td>
                    <td class="text-center font-bold" style="direction: ltr; color: #059669;">
                        {{ $transaction->type == 'payment' ? number_format($transaction->amount, 2) : '-' }}
                    </td>
                    <td class="text-center font-bold"
                        style="direction: ltr; color: {{ $transaction->running_balance > 0 ? '#dc2626' : ($transaction->running_balance < 0 ? '#059669' : '#334155') }};">
                        {{ number_format($transaction->running_balance, 2) }}
                    </td>
                    <td class="text-center" style="font-size: 8px; color: #64748b;">
                        {{ optional($transaction->createdBy)->name ?? __('general.system') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 16px; color: #94a3b8;">
                        {{ __('store_customers.no_transactions_recorded') }}
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f1f5f9; font-weight: bold; border-top: 2px solid #cbd5e1;">
                <td colspan="4"
                    style="{{ app()->getLocale() == 'ar' ? 'text-align: left; padding-left: 10px;' : 'text-align: right; padding-right: 10px;' }} font-weight: bold;">
                    {{ __('store_customers.period_totals_and_final') }}
                </td>
                <td class="text-center ltr" style="direction: ltr; color: #e11d48; font-weight: bold;">
                    {{ number_format($recentDebts, 2) }}
                </td>
                <td class="text-center ltr" style="direction: ltr; color: #059669; font-weight: bold;">
                    {{ number_format($recentPayments, 2) }}
                </td>
                <td class="text-center ltr"
                    style="direction: ltr; font-weight: bold; font-size: 11px; color: {{ $currentBalance > 0 ? '#dc2626' : ($currentBalance < 0 ? '#059669' : '#0f172a') }};">
                    {{ number_format($currentBalance, 2) }}
                </td>
                <td class="text-center">-</td>
            </tr>
        </tfoot>
    </table>

    <!-- Signatures & Authorization Section -->
    <table class="signatures-table">
        <tr>
            <td class="sig-box">
                <div class="sig-label">{{ __('store_customers.customer_signature') }}</div>
                <br>
                <div class="sig-line">...........................................................</div>
            </td>
            <td class="sig-box">
                <div class="sig-label">{{ __('store_customers.store_signature') }}</div>
                <br>
                <div class="sig-line">...........................................................</div>
            </td>
        </tr>
    </table>

    <!-- HTML Page Footer -->
    <htmlpagefooter name="page-footer">
        <table class="footer-table">
            <tr>
                <td style="width: 40%; {{ app()->getLocale() == 'ar' ? 'text-align: right;' : 'text-align: left;' }}">
                    {{ __('store_customers.system_footer_text') }} &copy; {{ date('Y') }}
                </td>
                <td style="width: 20%; text-align: center;">
                    {{ __('store_customers.page_x_of_y') }}
                </td>
                <td
                    style="width: 40%; {{ app()->getLocale() == 'ar' ? 'text-align: left;' : 'text-align: right;' }} direction: ltr;">
                    {{ $generatedAt->format('Y-m-d H:i:s') }}
                </td>
            </tr>
        </table>
    </htmlpagefooter>

</body>

</html>
