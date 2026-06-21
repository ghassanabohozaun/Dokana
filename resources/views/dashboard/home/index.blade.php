@extends('layouts.dashboard.app')
@section('title')
    {!! $title !!}
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/dashbaord/css/dashboard-home.css') }}">
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-wrapper mt-n2 dashboard-revolution-wrapper">
            <div class="content-body">

                <!-- 1. Header Banner -->
                <div class="row mt-n1 mb-2">
                    <div class="col-12">
                        <div class="card dokana-welcome-banner">
                            <div class="card-body p-3 d-flex justify-content-between align-items-center flex-wrap" style="position: relative; z-index: 2;">
                                <!-- Right side text (First in RTL) -->
                                <div class="d-flex align-items-center text-white">
                                    <div class="avatar bg-rgba-white-20 p-2 m-0" style="border-radius: 8px; display:flex; align-items:center; justify-content:center; width:45px; height:45px; margin-inline-end: 12px !important;">
                                        <h3 class="mb-0 text-white font-weight-bolder">{{ mb_substr(user()->name, 0, 1) }}</h3>
                                    </div>
                                    <div>
                                        <h3 class="dokana-welcome-title mb-0">{!! greeting() !!} <span class="text-warning">{!! user()->name !!}</span>! 👋</h3>
                                        <p class="dokana-welcome-subtitle mb-0">{{ auth()->user()->store ? auth()->user()->store->name : (setting()->site_name ?? __('dashboard.dashboard')) }}</p>
                                    </div>
                                </div>
                                
                                <!-- Left side date (Second in RTL) -->
                                <div class="dokana-welcome-date d-flex align-items-center text-white mt-2 mt-md-0">
                                    <i class="fas fa-calendar-alt dokana-icon-spacing"></i>
                                    <span style="font-size: 0.95rem;">{{ date('l, d F Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Clean Stats Cards -->
                <div class="row d-flex align-items-stretch">
                    @if ($isSuperAdmin)
                        <!-- Super Admin Cards -->
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-stores h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value">{!! $stats['stores_count'] !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.stores_count') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-users h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value">{!! $stats['users_count'] !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.system_users') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-customers h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value">{!! $stats['customers_count'] !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.system_customers') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-debts h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value text-danger">{!! number_format($stats['total_debt']) !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.total_debts') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Store Admin Cards -->
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-collections h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value text-success">{!! number_format($stats['today_collections']) !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.today_collections') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-debts h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value text-danger">{!! number_format($stats['total_debt']) !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.total_debts') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-customers h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value">{!! $stats['customers_count'] !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.customers') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 col-12 mb-3">
                            <div class="card dokana-glass-card card-users h-100">
                                <div class="card-body d-flex justify-content-between align-items-center p-0">
                                    <div>
                                        <h2 class="dokana-stat-value">{!! $stats['users_count'] ?? 0 !!}</h2>
                                        <h6 class="dokana-stat-title">{{ __('dashboard.employees') }}</h6>
                                    </div>
                                    <div class="dokana-avatar-wrapper">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 3. Chart Section -->
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card dokana-table-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 font-weight-bolder text-dark">
                                    <i class="fas fa-chart-line text-info dokana-icon-spacing"></i>{{ __('dashboard.financial_trend') }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div id="dashboard-trend-chart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Tables Section -->
                <div class="row mt-2">
                    @if($isSuperAdmin)
                        <!-- Super Admin Tables (3-column layout) -->
                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-store text-primary dokana-icon-spacing"></i>{{ __('dashboard.recent_stores') }}
                                    </h5>
                                    <div class="badge badge-light-primary badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $recentStores->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('stores.store_name') }}</th>
                                                        <th>{{ __('stores.subscription_plan') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($recentStores as $store)
                                                        <tr>
                                                            <td><h6 class="mb-0 font-weight-bold">{{ $store->name }}</h6></td>
                                                            <td>
                                                                <span class="badge @if(strtolower($store->subscription_plan) == 'premium') badge-light-success @else badge-light-primary @endif">
                                                                    {{ __('stores.plan_'.strtolower($store->subscription_plan)) }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('stores.no_stores_found') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-users text-info dokana-icon-spacing"></i>{{ __('dashboard.recent_users') }}
                                    </h5>
                                    <div class="badge badge-light-info badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $recentUsers->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('users.name') }}</th>
                                                        <th>{{ __('users.email') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($recentUsers as $user)
                                                        <tr>
                                                            <td><h6 class="mb-0 font-weight-bold">{{ $user->name }}</h6></td>
                                                            <td><small class="text-muted">{{ $user->email }}</small></td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('users.no_users_found') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-user-tag text-warning dokana-icon-spacing"></i>{{ __('dashboard.recent_customers') }}
                                    </h5>
                                    <div class="badge badge-light-warning badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $recentCustomers->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('store_customers.name') }}</th>
                                                        <th>{{ __('store_customers.balance') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($recentCustomers as $customer)
                                                        <tr>
                                                            <td><h6 class="mb-0 font-weight-bold">{{ $customer->name }}</h6></td>
                                                            <td>
                                                                @if($customer->balance > 0)
                                                                    <span class="text-danger font-weight-bold">{{ number_format($customer->balance) }}</span>
                                                                @else
                                                                    <span class="text-success font-weight-bold">{{ number_format($customer->balance) }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('store_customers.no_store_customers_found') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Store Admin Tables (3-column layout) -->
                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-users-slash text-danger dokana-icon-spacing"></i>{{ __('dashboard.late_debts_customers') }}
                                    </h5>
                                    <div class="badge badge-light-danger badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $topDebtors->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('store_customers.name') }}</th>
                                                        <th>{{ __('store_customers.balance') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($topDebtors as $customer)
                                                        <tr>
                                                            <td><h6 class="mb-0 font-weight-bold">{{ $customer->name }}</h6></td>
                                                            <td><span class="text-danger font-weight-bold">{{ number_format($customer->balance) }}</span></td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('dashboard.no_debts') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-exchange-alt text-primary dokana-icon-spacing"></i>{{ __('dashboard.recent_transactions') }}
                                    </h5>
                                    <div class="badge badge-light-primary badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $recentTransactions->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
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
                                                                <h6 class="mb-0 font-weight-bold">{{ $tx->customer->name ?? 'غير معروف' }}</h6>
                                                                <small class="text-muted">#{{ $tx->id }}</small>
                                                            </td>
                                                            <td>
                                                                @if($tx->type == 'payment')
                                                                    <span class="text-success font-weight-bold">+{{ number_format($tx->amount) }}</span>
                                                                @else
                                                                    <span class="text-danger font-weight-bold">-{{ number_format($tx->amount) }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('dashboard.no_recent_transactions') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="card dokana-table-card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 font-weight-bolder text-dark">
                                        <i class="fas fa-user-plus text-success dokana-icon-spacing"></i>{{ __('dashboard.recent_customers') }}
                                    </h5>
                                    <div class="badge badge-light-success badge-pill font-weight-bold px-2 py-1" style="font-size: 0.9rem;">{{ $recentCustomers->count() }}</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="dokana-scroll-container">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('store_customers.name') }}</th>
                                                        <th>{{ __('store_customers.phone') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($recentCustomers as $customer)
                                                        <tr>
                                                            <td><h6 class="mb-0 font-weight-bold">{{ $customer->name }}</h6></td>
                                                            <td><small class="text-muted">{{ $customer->phone ?? '-' }}</small></td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="2" class="text-center text-muted p-4">{{ __('store_customers.no_store_customers_found') }}</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/dashbaord/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            var options = {
                chart: {
                    type: 'area',
                    height: 350,
                    toolbar: { show: false },
                    zoom: { enabled: false }
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
                colors: ['#ef4444', '#10b981'],
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
                            colors: '#64748b',
                            fontSize: '12px',
                            fontFamily: 'Tajawal, sans-serif'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#64748b',
                            fontSize: '12px',
                            fontFamily: 'Tajawal, sans-serif'
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontFamily: 'Tajawal, sans-serif',
                    fontSize: '13px'
                },
                tooltip: {
                    x: { format: 'dd MMM' }
                }
            };

            var chart = new ApexCharts(document.querySelector("#dashboard-trend-chart"), options);
            chart.render();
        });
    </script>
@endpush
