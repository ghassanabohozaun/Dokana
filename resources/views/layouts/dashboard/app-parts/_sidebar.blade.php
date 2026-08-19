<!-- Mobile Backdrop Overlay -->
<div id="sidebar-backdrop"
    class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs opacity-0 hidden transition-opacity duration-200 md:hidden">
</div>

<!-- Main Sidebar Container -->
<aside id="app-sidebar"
    class="fixed inset-y-0 start-0 z-50 flex h-full w-72 flex-col bg-[#0f172a] text-slate-200 border-e border-slate-800/80 shadow-2xl transition-transform duration-300 md:translate-x-0 -translate-x-full rtl:translate-x-full rtl:md:translate-x-0">
    
    <!-- Sidebar Header / Brand Area -->
    <div class="flex h-16 items-center justify-between px-5 border-b border-slate-800/80">
        <a href="{!! route('dashboard.index') !!}" class="flex items-center gap-3 group">
            <div class="brand-glow-badge">
                @if (!empty(setting()->logo) && file_exists(public_path('uploads/settings/' . setting()->logo)))
                    <img class="h-7 w-7 object-contain rounded-lg" alt="{{ setting()->site_name ?? 'Dokana' }}" 
                         src="{!! asset('uploads/settings/' . setting()->logo) !!}" 
                         width="28" height="28" loading="eager">
                @else
                    <i class="fas fa-cubes text-base text-indigo-400"></i>
                @endif
            </div>
            <div>
                <span class="text-sm font-black text-white tracking-wide block bg-gradient-to-r from-white via-slate-100 to-indigo-200 bg-clip-text text-transparent">
                    {{ setting()->site_name ?? 'DOKANA' }}
                </span>
                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-widest bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                    Enterprise
                </span>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" data-sidebar-toggle
            class="flex md:hidden h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <i class="fas fa-times text-sm"></i>
        </button>
    </div>

    <!-- Quick Search Menu -->
    <div class="px-4 pt-4 pb-2">
        <div class="relative">
            <i class="fas fa-search absolute start-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
            <input type="text" id="sidebar-menu-search"
                placeholder="{!! __('dashboard.search') !!}..."
                class="w-full rounded-xl bg-slate-800/60 border border-slate-700/60 ps-9 pe-3 py-2 text-xs text-slate-200 placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
        </div>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 overflow-y-auto px-3 py-3 space-y-1 custom-scrollbar" id="main-menu-navigation">
        <!-- 1. Dashboard Home -->
        <a href="{!! route('dashboard.index') !!}"
            class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 @if(Request::is('*welcome*') || Request::is('*/dashboard') || Request::is('*/dashboard/')) bg-indigo-600 text-white shadow-md shadow-indigo-600/20 @else text-slate-300 hover:bg-slate-800/70 hover:text-white @endif">
            <i class="fas fa-home text-sm w-5 text-center"></i>
            <span class="menu-title flex-1">{!! __('dashboard.dashboard') !!}</span>
        </a>

        <!-- 2. Notifications -->
        @can('notifications_read')
        <a href="{!! route('dashboard.notifications') !!}"
            class="sidebar-item flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-all duration-150 @if(Request::routeIs('dashboard.notifications')) bg-indigo-600 text-white shadow-md shadow-indigo-600/20 @else text-slate-300 hover:bg-slate-800/70 hover:text-white @endif">
            <i class="fas fa-bell text-sm w-5 text-center"></i>
            <span class="menu-title flex-1">{!! __('notifications.notifications') !!}</span>
        </a>
        @endcan

        <!-- Divider Label: Core Operations -->
        <div class="px-3 pt-3 pb-1 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
            {!! __('dashboard.main_navigation') !!}
        </div>

        <!-- 3. System Management Group -->
        @if(auth()->user()->can('stores_read') || auth()->user()->can('departments_read') || auth()->user()->can('settings_read'))
        @php
            $isSystemActive = Request::routeIs('dashboard.stores.*') || Request::routeIs('dashboard.departments.*') || Request::routeIs('dashboard.settings.*');
        @endphp
        <div class="nav-group" data-nav-group>
            <button type="button"
                class="group-toggle w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800/70 hover:text-white transition-all @if($isSystemActive) text-white bg-slate-800/50 @endif">
                <div class="flex items-center gap-3">
                    <i class="fas fa-sliders-h text-sm w-5 text-center text-indigo-400"></i>
                    <span class="menu-title">{!! __('dashboard.main_navigation') !!}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 @if($isSystemActive) rotate-180 @endif"></i>
            </button>
            <div class="group-items ps-8 pe-2 pt-1 pb-1 space-y-1 @if(!$isSystemActive) hidden @endif">
                @can('stores_read')
                <a href="{!! route('dashboard.stores.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.stores.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('stores.stores') !!}
                </a>
                @endcan
                @can('departments_read')
                <a href="{!! route('dashboard.departments.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.departments.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('departments.departments') !!}
                </a>
                @endcan
                @can('settings_read')
                <a href="{!! route('dashboard.settings.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.settings.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('settings.settings') !!}
                </a>
                @endcan
            </div>
        </div>
        @endif

        <!-- 4. Financial Management Group -->
        @if(auth()->user()->can('payment_entities_read') || auth()->user()->can('bank_accounts_read') || auth()->user()->can('store_withdrawals_read'))
        @php
            $isFinancialActive = Request::routeIs('dashboard.bank-accounts.*') || Request::routeIs('dashboard.payment-entities.*') || Request::routeIs('dashboard.store-withdrawals.*');
        @endphp
        <div class="nav-group" data-nav-group>
            <button type="button"
                class="group-toggle w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800/70 hover:text-white transition-all @if($isFinancialActive) text-white bg-slate-800/50 @endif">
                <div class="flex items-center gap-3">
                    <i class="fas fa-wallet text-sm w-5 text-center text-emerald-400"></i>
                    <span class="menu-title">{!! __('dashboard.financial_management') !!}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 @if($isFinancialActive) rotate-180 @endif"></i>
            </button>
            <div class="group-items ps-8 pe-2 pt-1 pb-1 space-y-1 @if(!$isFinancialActive) hidden @endif">
                @can('payment_entities_read')
                <a href="{!! route('dashboard.payment-entities.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.payment-entities.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('payment_entities.payment_entities') !!}
                </a>
                @endcan
                @can('bank_accounts_read')
                <a href="{!! route('dashboard.bank-accounts.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.bank-accounts.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('bank_accounts.bank_accounts') !!}
                </a>
                @endcan
                @can('store_withdrawals_read')
                <a href="{!! route('dashboard.store-withdrawals.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-withdrawals.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_withdrawals.store_withdrawals') !!}
                </a>
                @endcan
            </div>
        </div>
        @endif

        <!-- 5. Suppliers Management Group -->
        @if(auth()->user()->can('store_suppliers_read') || auth()->user()->can('store_supplier_invoices_read') || auth()->user()->can('store_supplier_payments_read'))
        @php
            $isSuppliersActive = Request::routeIs('dashboard.store-suppliers.*') || Request::routeIs('dashboard.store-supplier-invoices.*') || Request::routeIs('dashboard.store-supplier-payments.*');
        @endphp
        <div class="nav-group" data-nav-group>
            <button type="button"
                class="group-toggle w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800/70 hover:text-white transition-all @if($isSuppliersActive) text-white bg-slate-800/50 @endif">
                <div class="flex items-center gap-3">
                    <i class="fas fa-truck text-sm w-5 text-center text-amber-400"></i>
                    <span class="menu-title">{!! __('store_suppliers.store_suppliers') !!}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 @if($isSuppliersActive) rotate-180 @endif"></i>
            </button>
            <div class="group-items ps-8 pe-2 pt-1 pb-1 space-y-1 @if(!$isSuppliersActive) hidden @endif">
                @can('store_suppliers_read')
                <a href="{!! route('dashboard.store-suppliers.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-suppliers.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_suppliers.store_suppliers') !!}
                </a>
                @endcan
                @can('store_supplier_invoices_read')
                <a href="{!! route('dashboard.store-supplier-invoices.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-supplier-invoices.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_supplier_invoices.store_supplier_invoices') !!}
                </a>
                @endcan
                @can('store_supplier_payments_read')
                <a href="{!! route('dashboard.store-supplier-payments.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-supplier-payments.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_supplier_payments.store_supplier_payments') !!}
                </a>
                @endcan
            </div>
        </div>
        @endif

        <!-- 6. Customers & Transactions Management Group -->
        @if(auth()->user()->can('store_customers_read') || auth()->user()->can('store_transactions_read'))
        @php
            $isCustomersActive = Request::routeIs('dashboard.store-customers.*') || Request::routeIs('dashboard.store-transactions.*');
        @endphp
        <div class="nav-group" data-nav-group>
            <button type="button"
                class="group-toggle w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800/70 hover:text-white transition-all @if($isCustomersActive) text-white bg-slate-800/50 @endif">
                <div class="flex items-center gap-3">
                    <i class="fas fa-users text-sm w-5 text-center text-blue-400"></i>
                    <span class="menu-title">{!! __('store_customers.store_customers') !!}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 @if($isCustomersActive) rotate-180 @endif"></i>
            </button>
            <div class="group-items ps-8 pe-2 pt-1 pb-1 space-y-1 @if(!$isCustomersActive) hidden @endif">
                @can('store_customers_read')
                <a href="{!! route('dashboard.store-customers.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-customers.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_customers.store_customers') !!}
                </a>
                @endcan
                @can('store_transactions_read')
                <a href="{!! route('dashboard.store-transactions.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.store-transactions.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('store_transactions.store_transactions') !!}
                </a>
                @endcan
            </div>
        </div>
        @endif

        <!-- 7. Users & Roles Management Group -->
        @if(auth()->user()->can('users_read') || auth()->user()->can('roles_read'))
        @php
            $isUsersActive = Request::routeIs('dashboard.users.*') || Request::routeIs('dashboard.roles.*');
        @endphp
        <div class="nav-group" data-nav-group>
            <button type="button"
                class="group-toggle w-full flex items-center justify-between gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-300 hover:bg-slate-800/70 hover:text-white transition-all @if($isUsersActive) text-white bg-slate-800/50 @endif">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-shield text-sm w-5 text-center text-purple-400"></i>
                    <span class="menu-title">{!! __('dashboard.technical_support') !!}</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 @if($isUsersActive) rotate-180 @endif"></i>
            </button>
            <div class="group-items ps-8 pe-2 pt-1 pb-1 space-y-1 @if(!$isUsersActive) hidden @endif">
                @can('users_read')
                <a href="{!! route('dashboard.users.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.users.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('users.users') !!}
                </a>
                @endcan
                @can('roles_read')
                <a href="{!! route('dashboard.roles.index') !!}"
                    class="sidebar-item block px-3 py-2 rounded-lg text-xs font-medium transition-colors @if(Request::routeIs('dashboard.roles.*')) bg-indigo-600/90 text-white font-semibold @else text-slate-400 hover:text-white hover:bg-slate-800/50 @endif">
                    {!! __('roles.roles') !!}
                </a>
                @endcan
            </div>
        </div>
        @endif

    </nav>

    <!-- Sidebar Footer -->
    <div class="p-3 border-t border-slate-800/80">
        <a href="{!! route('dashboard.logout') !!}"
            class="flex items-center justify-center gap-2 w-full py-2 px-3 rounded-xl bg-slate-800/60 hover:bg-rose-600/20 text-slate-400 hover:text-rose-400 text-xs font-semibold transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>{!! __('auth.logout') !!}</span>
        </a>
    </div>
</aside>

<!-- Sidebar Submenus & Search Controller Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Group toggle accordion
        const groupToggles = document.querySelectorAll('[data-nav-group] .group-toggle');
        groupToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const group = this.closest('[data-nav-group]');
                const items = group.querySelector('.group-items');
                const chevron = this.querySelector('.fa-chevron-down');
                
                if (items) {
                    const isHidden = items.classList.contains('hidden');
                    if (isHidden) {
                        items.classList.remove('hidden');
                        if (chevron) chevron.classList.add('rotate-180');
                    } else {
                        items.classList.add('hidden');
                        if (chevron) chevron.classList.remove('rotate-180');
                    }
                }
            });
        });

        // Sidebar search filter
        const searchInput = document.getElementById('sidebar-menu-search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase().trim();
                const items = document.querySelectorAll('#main-menu-navigation .sidebar-item');
                const groups = document.querySelectorAll('#main-menu-navigation [data-nav-group]');

                items.forEach(item => {
                    const text = item.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (filter.length > 0) {
                    groups.forEach(group => {
                        const groupItems = group.querySelector('.group-items');
                        const visibleItems = group.querySelectorAll('.sidebar-item:not(.hidden)');
                        if (visibleItems.length > 0) {
                            group.classList.remove('hidden');
                            if (groupItems) groupItems.classList.remove('hidden');
                        } else {
                            group.classList.add('hidden');
                        }
                    });
                } else {
                    groups.forEach(group => {
                        group.classList.remove('hidden');
                    });
                }
            });
        }
    });
</script>
