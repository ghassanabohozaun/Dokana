@extends('layouts.dashboard.app')

@section('title')
    {!! $title !!}
@endsection

@section('content')
<div class="space-y-6">
    <!-- 1. Welcome Enterprise Banner -->
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 md:p-8 text-white shadow-xl border border-slate-800/80">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -end-24 w-72 h-72 rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -start-24 w-72 h-72 rounded-full bg-blue-500/15 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/15 text-white font-extrabold text-xl shadow-inner">
                    {{ mb_substr(user()->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl md:text-2xl font-bold tracking-tight">
                        {!! greeting() !!} <span class="text-amber-400 font-extrabold">{!! user()->name !!}</span>! 👋
                    </h2>
                    <p class="text-xs md:text-sm text-slate-300 mt-0.5">
                        {{ auth()->user()->store ? auth()->user()->store->name : (setting()->site_name ?? __('dashboard.dashboard')) }}
                    </p>
                </div>
            </div>

            <!-- Date Badge -->
            <div class="inline-flex items-center gap-2 self-start md:self-auto rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 px-4 py-2 text-xs font-semibold text-slate-200">
                <i class="fas fa-calendar-alt text-indigo-400"></i>
                <span>{{ date('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- 2. Clean Metric KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
        @if ($isSuperAdmin)
            <!-- Super Admin Card 1: Stores Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.stores_count') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['stores_count'] !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-store"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 2: Users Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.system_users') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['users_count'] !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 3: Customers Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.system_customers') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['customers_count'] !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tag"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-80"></div>
            </div>

            <!-- Super Admin Card 4: Total Debts -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.total_debts') }}</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">{!! number_format($stats['total_debt']) !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500 opacity-80"></div>
            </div>
        @else
            <!-- Store Admin Card 1: Today Collections -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.today_collections') }}</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-1">{!! number_format($stats['today_collections']) !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-80"></div>
            </div>

            <!-- Store Admin Card 2: Total Debt -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.total_debts') }}</p>
                        <h3 class="text-2xl font-extrabold text-rose-600 dark:text-rose-400 mt-1">{!! number_format($stats['total_debt']) !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-rose-500 to-amber-500 opacity-80"></div>
            </div>

            <!-- Store Admin Card 3: Customers Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.customers') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['customers_count'] !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-80"></div>
            </div>

            <!-- Store Admin Card 4: Employees Count -->
            <div class="dash-card p-5 relative overflow-hidden group hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">{{ __('dashboard.employees') }}</p>
                        <h3 class="text-2xl font-extrabold text-slate-800 dark:text-white mt-1">{!! $stats['users_count'] ?? 0 !!}</h3>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 text-lg shadow-sm group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <div class="absolute bottom-0 inset-x-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-500 opacity-80"></div>
            </div>
        @endif
    </div>

    <!-- 3. Tables 3-Column Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @if ($isSuperAdmin)
            <!-- Table 1: Late Debts Customers -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users-slash text-rose-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.late_debts_customers') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-danger">{{ $topDebtors->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('store_customers.name') }}</th>
                                <th>{{ __('store_customers.balance') }}</th>
                                <th>{{ __('store_customers.max_debt_limit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDebtors as $customer)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $customer->name }}</p>
                                        @if($customer->store)
                                            <span class="text-[10px] text-indigo-500 block">
                                                <i class="fas fa-store text-[9px] me-1"></i>{{ $customer->store->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="font-bold text-xs text-rose-600 dark:text-rose-400">{{ number_format($customer->balance) }}</span>
                                    </td>
                                    <td>
                                        @if($customer->max_debt_limit !== null)
                                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $customer->max_debt_limit }}</span>
                                        @else
                                            <span class="text-[11px] text-slate-400">{{ __('general.unlimited') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-xs text-slate-400">{{ __('dashboard.no_debts') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 2: Recent Stores -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-store text-blue-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.recent_stores') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-info">{{ $recentStores->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('stores.store_name') }}</th>
                                <th>{{ __('stores.subscription_plan') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentStores as $store)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $store->name }}</p>
                                    </td>
                                    <td>
                                        <span class="badge-pill {{ strtolower($store->subscription_plan) == 'premium' ? 'badge-pill-success' : 'badge-pill-info' }}">
                                            {{ __('stores.plan_'.strtolower($store->subscription_plan)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-6 text-xs text-slate-400">{{ __('stores.no_stores_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Table 3: Recent Users -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-purple-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.recent_users') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-neutral">{{ $recentUsers->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('users.name') }}</th>
                                <th>{{ __('users.email') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $user->name }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs text-slate-400">{{ $user->email }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-6 text-xs text-slate-400">{{ __('users.no_users_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <!-- Store Admin Table 1: Late Debts Customers -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users-slash text-rose-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.late_debts_customers') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-danger">{{ $topDebtors->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('store_customers.name') }}</th>
                                <th>{{ __('store_customers.balance') }}</th>
                                <th>{{ __('store_customers.max_debt_limit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDebtors as $customer)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $customer->name }}</p>
                                    </td>
                                    <td>
                                        <span class="font-bold text-xs text-rose-600 dark:text-rose-400">{{ number_format($customer->balance) }}</span>
                                    </td>
                                    <td>
                                        @if($customer->max_debt_limit !== null)
                                            <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">{{ $customer->max_debt_limit }}</span>
                                        @else
                                            <span class="text-[11px] text-slate-400">{{ __('general.unlimited') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-6 text-xs text-slate-400">{{ __('dashboard.no_debts') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Store Admin Table 2: Recent Transactions -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exchange-alt text-blue-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.recent_transactions') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-info">{{ $recentTransactions->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('dashboard.tx_number') }}</th>
                                <th>{{ __('store_customers.balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $tx)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $tx->customer->name ?? 'غير معروف' }}</p>
                                        <span class="text-[10px] text-slate-400">#{{ $tx->id }}</span>
                                    </td>
                                    <td>
                                        @if($tx->type == 'payment')
                                            <span class="font-bold text-xs text-emerald-600 dark:text-emerald-400">+{{ number_format($tx->amount) }}</span>
                                        @else
                                            <span class="font-bold text-xs text-rose-600 dark:text-rose-400">-{{ number_format($tx->amount) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-6 text-xs text-slate-400">{{ __('dashboard.no_recent_transactions') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Store Admin Table 3: Recent Customers -->
            <div class="dash-card flex flex-col overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user-plus text-emerald-500"></i>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.recent_customers') }}</h4>
                    </div>
                    <span class="badge-pill badge-pill-success">{{ $recentCustomers->count() }}</span>
                </div>
                <div class="flex-1 overflow-y-auto max-h-80 custom-scrollbar">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>{{ __('store_customers.name') }}</th>
                                <th>{{ __('store_customers.phone') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCustomers as $customer)
                                <tr>
                                    <td>
                                        <p class="font-bold text-xs text-slate-800 dark:text-slate-200">{{ $customer->name }}</p>
                                    </td>
                                    <td>
                                        <span class="text-xs text-slate-400">{{ $customer->phone ?? '-' }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-6 text-xs text-slate-400">{{ __('store_customers.no_store_customers_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <!-- 4. Financial Trend Chart Card -->
    <div class="dash-card p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-chart-line text-indigo-500"></i>
                <h4 class="text-sm font-bold text-slate-800 dark:text-white">{{ __('dashboard.financial_trend') }}</h4>
            </div>
        </div>
        <div id="dashboard-trend-chart" class="w-full" style="min-height: 350px;"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var isDarkMode = document.documentElement.classList.contains('dark');
            var textColor = isDarkMode ? '#94a3b8' : '#64748b';
            var gridColor = isDarkMode ? '#1e293b' : '#f1f5f9';

            var options = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Tajawal, Manrope, sans-serif'
                },
                series: [
                    {
                        name: "{{ __('dashboard.total_debts') }}",
                        data: @json($chartDebts)
                    },
                    {
                        name: "{{ __('dashboard.today_collections') }}",
                        data: @json($chartPayments)
                    }
                ],
                colors: ['#f43f5e', '#10b981'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: @json($chartDates),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: textColor,
                            fontSize: '12px',
                            fontFamily: 'Tajawal, Manrope, sans-serif'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: textColor,
                            fontSize: '12px',
                            fontFamily: 'Tajawal, Manrope, sans-serif'
                        }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontFamily: 'Tajawal, Manrope, sans-serif',
                    fontSize: '13px',
                    labels: {
                        colors: textColor
                    }
                },
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light'
                }
            };

            var chartEl = document.querySelector("#dashboard-trend-chart");
            if (chartEl) {
                var chart = new ApexCharts(chartEl, options);
                chart.render();
            }
        });
    </script>
@endpush
