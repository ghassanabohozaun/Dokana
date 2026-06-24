    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow expanded" data-scroll-to-active="true">
        <div class="main-menu-content">
            {{-- Search Bar --}}
            <div class="sidebar-search-wrapper">
                <div class="sidebar-search-container">
                    <i class="fas fa-search sidebar-search-icon"></i>
                    <input type="text" class="sidebar-search-input" id="sidebar-menu-search"
                        placeholder="{!! __('dashboard.search') !!}">
                </div>
            </div>

            <ul class="navigation navigation-main mt-1" id="main-menu-navigation" data-menu="menu-navigation">
                <!-- begin: Dashboard -->
                <li class=" nav-item @if (Request::is('*welcome*')) active @endif">
                    <a href="{!! route('dashboard.index') !!}">
                        <i class="{{ config('global.module_icons.dashboard') }}"></i>
                        <span class="menu-title" data-i18n="nav.dash.main">{!! __('dashboard.dashboard') !!}</span>
                    </a>
                </li>
                <!-- end: Dashboard -->

                <!-- begin: Notifications -->
                @can('notifications_read')
                <li class=" nav-item @if (Request::routeIs('dashboard.notifications')) active @endif">
                    <a href="{!! route('dashboard.notifications') !!}">
                        <i class="la la-bell"></i>
                        <span class="menu-title">{!! __('notifications.notifications') !!}</span>
                    </a>
                </li>
                @endcan
                <!-- end: Notifications -->

                {{-- Group 1: System Management --}}
                @if(auth()->user()->can('stores_read') || auth()->user()->can('departments_read') || auth()->user()->can('settings_read'))
                @php
                    $isSystemActive =
                        Request::routeIs('dashboard.stores.*') ||
                        Request::routeIs('dashboard.departments.*') ||
                        Request::routeIs('dashboard.settings.*');
                @endphp
                <li class="nav-item has-sub @if ($isSystemActive) open @endif">
                    <a href="javascript:void(0)">
                        <i class="fas fa-cogs"></i>
                        <span class="menu-title">{!! __('dashboard.main_navigation') !!}</span>
                    </a>
                    <ul class="menu-content">
                        @can('stores_read')
                            <li class="@if (Request::routeIs('dashboard.stores.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.stores.index') !!}">
                                    {!! __('stores.stores') !!}
                                </a>
                            </li>
                        @endcan
                        @can('departments_read')
                            <li class="@if (Request::routeIs('dashboard.departments.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.departments.index') !!}">
                                    {!! __('departments.departments') !!}
                                </a>
                            </li>
                        @endcan
                        @can('settings_read')
                            <li class="@if (Request::routeIs('dashboard.settings.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.settings.index') !!}">
                                    {!! __('settings.settings') !!}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Group 2: Financial Management --}}
                @if(auth()->user()->can('payment_entities_read') || auth()->user()->can('bank_accounts_read') || auth()->user()->can('store_withdrawals_read'))
                @php
                    $isFinancialActive =
                        Request::routeIs('dashboard.bank-accounts.*') ||
                        Request::routeIs('dashboard.payment-entities.*') ||
                        Request::routeIs('dashboard.store-withdrawals.*');
                @endphp
                <li class="nav-item has-sub @if ($isFinancialActive) open @endif">
                    <a href="javascript:void(0)">
                        <i class="fas fa-wallet"></i>
                        <span class="menu-title">{!! __('dashboard.financial_management') !!}</span>
                    </a>
                    <ul class="menu-content">
                        @can('payment_entities_read')
                            <li class="@if (Request::routeIs('dashboard.payment-entities.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.payment-entities.index') !!}">
                                    {!! __('payment_entities.payment_entities') !!}
                                </a>
                            </li>
                        @endcan
                        @can('bank_accounts_read')
                            <li class="@if (Request::routeIs('dashboard.bank-accounts.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.bank-accounts.index') !!}">
                                    {!! __('bank_accounts.bank_accounts') !!}
                                </a>
                            </li>
                        @endcan
                        @can('store_withdrawals_read')
                            <li class="@if (Request::routeIs('dashboard.store-withdrawals.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.store-withdrawals.index') !!}">
                                    {!! __('store_withdrawals.store_withdrawals') !!}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Group 2: Customers Management --}}
                @if(auth()->user()->can('store_customers_read') || auth()->user()->can('store_transactions_read'))
                @php
                    $isCustomersActive = Request::routeIs('dashboard.store-customers.*') || Request::routeIs('dashboard.store-transactions.*');
                @endphp
                <li class="nav-item has-sub @if ($isCustomersActive) open @endif">
                    <a href="javascript:void(0)">
                        <i class="fas fa-users"></i>
                        <span class="menu-title">{!! __('store_customers.store_customers') !!}</span>
                    </a>
                    <ul class="menu-content">
                        @can('store_customers_read')
                            <li class="@if (Request::routeIs('dashboard.store-customers.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.store-customers.index') !!}">
                                    {!! __('store_customers.store_customers') !!}
                                </a>
                            </li>
                        @endcan
                        @can('store_transactions_read')
                            <li class="@if (Request::routeIs('dashboard.store-transactions.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.store-transactions.index') !!}">
                                    {!! __('store_transactions.store_transactions') !!}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif

                {{-- Group 3: Support & Access (Users, Roles) --}}
                @if(auth()->user()->can('users_read') || auth()->user()->can('roles_read'))
                @php
                    $isSupportActive =
                        Request::routeIs('dashboard.users.*') ||
                        Request::routeIs('dashboard.roles.*');
                @endphp
                <li class="nav-item has-sub @if ($isSupportActive) open @endif">
                    <a href="javascript:void(0)">
                        <i class="fas fa-tools"></i>
                        <span class="menu-title">{!! __('dashboard.technical_support') !!}</span>
                    </a>
                    <ul class="menu-content">
                        @can('users_read')
                            <li class="@if (Request::routeIs('dashboard.users.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.users.index') !!}">
                                    {!! __('users.users') !!}
                                </a>
                            </li>
                        @endcan
                        @can('roles_read')
                            <li class="@if (Request::routeIs('dashboard.roles.*')) active @endif">
                                <a class="menu-item" href="{!! route('dashboard.roles.index') !!}">
                                    {!! __('roles.roles') !!}
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @endif


            </ul>
        </div>

        @push('js')
            <script>
                (function() {
                    const searchInput = document.getElementById('sidebar-menu-search');
                    if (!searchInput) return;

                    searchInput.addEventListener('keyup', function() {
                        let filter = this.value.toLowerCase();
                        let menuItems = document.querySelectorAll('#main-menu-navigation li.nav-item');

                        menuItems.forEach(function(item) {
                            let text = item.innerText.toLowerCase();
                            if (text.includes(filter)) {
                                item.style.display = "";
                            } else {
                                item.style.display = "none";
                            }
                        });

                        // Show/hide headers based on search
                        let headers = document.querySelectorAll('#main-menu-navigation li.navigation-header');
                        headers.forEach(h => h.style.display = filter ? "none" : "");
                    });
                })();
            </script>
        @endpush
    </div>
