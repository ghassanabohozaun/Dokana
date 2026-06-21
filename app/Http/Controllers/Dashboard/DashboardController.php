<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Store;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $title = __('dashboard.dashboard');
        $storeId = user()->store_id;
        $isSuperAdmin = $storeId == 1;

        // --- 2. Statistics Cards ---
        $stats = [
            'stores_count'      => 0,
            'users_count'       => 0,
            'customers_count'   => 0,
            'total_debt'        => 0,
            'today_collections' => 0,
        ];

        $recentTransactions = collect();
        $recentStores = collect();
        $recentUsers = collect();
        $recentCustomers = collect();
        $chartDates = [];
        $chartDebts = [];
        $chartPayments = [];
        $topDebtors = collect();

        if ($isSuperAdmin) {
            $stats['stores_count'] = Store::count();
            $stats['users_count'] = User::count();
            $stats['customers_count'] = StoreCustomer::count();
            $stats['total_debt'] = StoreCustomer::where('balance', '>', 0)->sum('balance');
            
            $recentStores = Store::latest()->take(10)->get();
            $recentUsers = User::latest()->take(10)->get();
            $recentCustomers = StoreCustomer::latest()->take(10)->get();
        } else {
            $stats['users_count'] = User::where('store_id', $storeId)->count();
            $stats['customers_count'] = StoreCustomer::count();
            $stats['total_debt'] = StoreCustomer::where('balance', '>', 0)->sum('balance');
            $stats['today_collections'] = StoreTransaction::where('type', 'payment')
                ->whereDate('created_at', Carbon::today())
                ->sum('amount');
            
            $recentTransactions = StoreTransaction::with('customer')
                ->latest()
                ->take(10)
                ->get();
                
            $topDebtors = StoreCustomer::where('balance', '>', 0)
                ->orderBy('balance', 'desc')
                ->take(10)
                ->get();

            $recentCustomers = StoreCustomer::latest()->take(10)->get();
        }

        // Chart Data for last 7 days (Available for both Super Admin and Store Admin)
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M');
            
            $chartDebts[] = StoreTransaction::where('type', 'debt')
                ->whereDate('created_at', $date)
                ->sum('amount');
                
            $chartPayments[] = StoreTransaction::where('type', 'payment')
                ->whereDate('created_at', $date)
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
            'topDebtors',
            'chartDates',
            'chartDebts',
            'chartPayments'
        ));
    }
}

