<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use App\Models\StoreSupplier;
use App\Models\StoreSupplierInvoice;
use App\Models\StoreSupplierPayment;
use App\Models\StoreWithdrawal;
use App\Models\StoreBankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = __('dashboard.dashboard');
        $storeId = user()->store_id;
        $isSuperAdmin = $storeId == 1 || user()->role_id == 1 || user()->id == 1;

        $stats = [
            'stores_count'          => 0,
            'active_stores_count'   => 0,
            'users_count'           => 0,
            'customers_count'       => 0,
            'total_debt'            => 0,
            'today_collections'     => 0,
            'today_debts'           => 0,
            'today_withdrawals'     => 0,
            'store_total_balance'   => 0,
            'supplier_debts'        => 0,
            'net_balance'           => 0,
        ];

        $recentTransactions = collect();
        $recentStores = collect();
        $recentUsers = collect();
        $recentCustomers = collect();
        $recentSupplierInvoices = collect();
        $topDebtors = collect();
        
        $chartDates = [];
        $chartDebts = [];
        $chartPayments = [];
        $chartWithdrawals = [];
        
        $liquidityBreakdown = [
            'cash'    => 0,
            'wallet'  => 0,
            'bank'    => 0,
        ];

        // 1. Calculations by Role
        if ($isSuperAdmin) {
            $stats['stores_count'] = Store::count();
            $stats['active_stores_count'] = Store::where('status', true)->count();
            $stats['users_count'] = User::count();
            $stats['customers_count'] = StoreCustomer::count();
            $stats['total_debt'] = StoreCustomer::where('balance', '>', 0)->sum('balance');
            $stats['store_total_balance'] = StoreBankAccount::sum('current_balance');
            
            $stats['today_collections'] = StoreTransaction::where('type', 'payment')
                ->whereDate('transaction_date', Carbon::today())
                ->sum('amount');

            $recentStores = Store::withCount(['customers', 'users'])->latest()->take(6)->get();
            $recentUsers = User::with('store')->latest()->take(6)->get();
            
            $topDebtors = StoreCustomer::with(['store'])
                ->where('balance', '>', 0)
                ->orderByDesc('balance')
                ->take(6)
                ->get();

            // Liquidity for Super Admin
            $bankAccounts = StoreBankAccount::with('paymentEntity')->get();
            foreach ($bankAccounts as $acc) {
                if ($acc->account_type === 'cash') {
                    $liquidityBreakdown['cash'] += $acc->current_balance;
                } elseif (optional($acc->paymentEntity)->type === 'wallet') {
                    $liquidityBreakdown['wallet'] += $acc->current_balance;
                } else {
                    $liquidityBreakdown['bank'] += $acc->current_balance;
                }
            }
        } else {
            $stats['users_count'] = User::where('store_id', $storeId)->count();
            $stats['customers_count'] = StoreCustomer::count();
            $stats['total_debt'] = StoreCustomer::where('balance', '>', 0)->sum('balance');
            
            $stats['today_collections'] = StoreTransaction::where('type', 'payment')
                ->whereDate('transaction_date', Carbon::today())
                ->sum('amount');

            $stats['today_debts'] = StoreTransaction::where('type', 'debt')
                ->whereDate('transaction_date', Carbon::today())
                ->sum('amount');

            $stats['today_withdrawals'] = StoreWithdrawal::whereDate('withdrawal_date', Carbon::today())
                ->sum('amount');

            $stats['store_total_balance'] = StoreBankAccount::sum('current_balance');
            $stats['supplier_debts'] = StoreSupplierInvoice::sum('remaining_amount');
            $stats['net_balance'] = $stats['total_debt'] - $stats['supplier_debts'];

            $recentTransactions = StoreTransaction::with(['customer', 'store'])
                ->latest('transaction_date')
                ->latest('id')
                ->take(6)
                ->get();
                
            $topDebtors = StoreCustomer::where('balance', '>', 0)
                ->orderByDesc('balance')
                ->take(6)
                ->get();

            $recentCustomers = StoreCustomer::latest()->take(6)->get();
            
            $recentSupplierInvoices = StoreSupplierInvoice::with('supplier')
                ->where('remaining_amount', '>', 0)
                ->orderByDesc('invoice_date')
                ->take(6)
                ->get();

            // Liquidity Breakdown for Store
            $bankAccounts = StoreBankAccount::with('paymentEntity')->get();
            foreach ($bankAccounts as $acc) {
                if ($acc->account_type === 'cash') {
                    $liquidityBreakdown['cash'] += $acc->current_balance;
                } elseif (optional($acc->paymentEntity)->type === 'wallet') {
                    $liquidityBreakdown['wallet'] += $acc->current_balance;
                } else {
                    $liquidityBreakdown['bank'] += $acc->current_balance;
                }
            }
        }

        // 2. Multi-Day Financial Trend (Last 7 Days)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d/m');
            
            $chartDebts[] = (float) StoreTransaction::where('type', 'debt')
                ->whereDate('transaction_date', $date)
                ->sum('amount');
                
            $chartPayments[] = (float) StoreTransaction::where('type', 'payment')
                ->whereDate('transaction_date', $date)
                ->sum('amount');

            $chartWithdrawals[] = (float) StoreWithdrawal::whereDate('withdrawal_date', $date)
                ->sum('amount');
        }

        return view('dashboard.home.index', compact(
            'title', 
            'stats', 
            'isSuperAdmin',
            'recentTransactions',
            'recentStores',
            'recentUsers',
            'recentCustomers',
            'recentSupplierInvoices',
            'topDebtors',
            'chartDates',
            'chartDebts',
            'chartPayments',
            'chartWithdrawals',
            'liquidityBreakdown'
        ));
    }
}
