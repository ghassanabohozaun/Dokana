<?php

namespace App\Http\Controllers\Website\Casher;

use App\Http\Controllers\Controller;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use App\Models\StoreWithdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasherNotebookController extends Controller
{
    /**
     * Check if the casher is authorized for the given ability.
     */
    protected function isAuthorized($ability)
    {
        return Auth::guard('casher')->check() && Auth::guard('casher')->user()->hasAbility($ability);
    }

    /**
     * Get the store ID for the current casher.
     */
    protected function getStoreId()
    {
        return Auth::guard('casher')->user()->store_id;
    }

    /**
     * Render the main notebook view.
     */
    public function index()
    {
        if (!$this->isAuthorized('notebook_read')) {
            abort(403, 'Unauthorized access to the notebook.');
        }

        $storeBankAccounts = \App\Models\StoreBankAccount::with('paymentEntity')
            ->where('store_id', $this->getStoreId())
            ->get();

        return view('website.casher.notebook.index', compact('storeBankAccounts'));
    }

    /**
     * API: Get customers with search, filter, and pagination.
     */
    public function getCustomers(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $search = $request->query('search', '');
        $filter = $request->query('filter', 'all');
        $perPage = (int) $request->query('per_page', 5);

        $query = StoreCustomer::where('store_id', $this->getStoreId());

        if ($search || $filter !== 'all') {
            $query->where(function($q) use ($search, $filter) {
                $q->where(function($subQ) use ($search, $filter) {
                    if ($search) {
                        $subQ->where('name', 'like', '%' . $search . '%');
                    }
                    if ($filter === 'debt' || $filter === 'highest_debt') {
                        $subQ->where('balance', '>', 0);
                    } elseif ($filter === 'paid') {
                        $subQ->where('balance', '=', 0);
                    } elseif ($filter === 'credit') {
                        $subQ->where('balance', '<', 0);
                    } elseif ($filter === 'disabled') {
                        $subQ->where('status', 0);
                    }
                    $subQ->where('is_walk_in', false);
                });

                if ($search) {
                    $q->orWhere('is_walk_in', true);
                }
            });
        }

        // The total will include the walk-in customer regardless of filter/search.
        // We might want to subtract 1 if we don't want it to skew "total customers found" 
        // but it's fine to just count it as a result.
        $totalCustomers = $query->count();
        
        if ($filter === 'highest_debt') {
            $customers = $query->orderByDesc('is_walk_in')->orderByDesc('balance')->latest()->take($perPage)->get();
        } else {
            $customers = $query->orderByDesc('is_walk_in')->latest()->take($perPage)->get();
        }

        $totalDebt = StoreCustomer::where('store_id', $this->getStoreId())->where('balance', '>', 0)->sum('balance');
        $todayCollections = StoreTransaction::where('store_id', $this->getStoreId())
            ->where('type', 'payment')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
        $todayDirectSales = StoreTransaction::where('store_id', $this->getStoreId())
            ->where('type', 'direct_sale')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');
        $todayDebts = StoreTransaction::where('store_id', $this->getStoreId())
            ->where('type', 'debt')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return response()->json([
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'totalDebt' => $totalDebt,
            'todayCollections' => $todayCollections,
            'todayDirectSales' => $todayDirectSales,
            'todayDebts' => $todayDebts,
        ]);
    }

    /**
     * API: Get financial summary for today, week, and month.
     */
    public function getFinancialSummary(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();

        $periods = [
            'today' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
        ];

        $summary = [];

        foreach ($periods as $key => $startDate) {
            $summary[$key] = [
                'collections' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'payment')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'direct_sales' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'direct_sale')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
                'debts' => StoreTransaction::where('store_id', $storeId)
                    ->where('type', 'debt')
                    ->where('created_at', '>=', $startDate)
                    ->sum('amount'),
            ];
        }

        return response()->json(['summary' => $summary]);
    }

    /**
     * API: Add a new customer.
     */
    public function storeCustomer(Request $request)
    {
        if (!$this->isAuthorized('notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'opening_balance' => 'nullable|numeric|min:0',
        ]);

        $customer = StoreCustomer::create([
            'store_id' => $this->getStoreId(),
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        if ($request->filled('opening_balance') && $request->opening_balance > 0) {
            StoreTransaction::create([
                'store_id' => $customer->store_id,
                'store_customer_id' => $customer->id,
                'type' => 'debt',
                'amount' => $request->opening_balance,
                'transaction_date' => now(),
                'description' => __('notebook.opening_balance') ?? 'رصيد افتتاحي',
                'created_by' => Auth::guard('casher')->id(),
            ]);
        }

        return response()->json(['customer' => $customer, 'message' => __('notebook.customer_added') . ' ' . $customer->name]);
    }

    /**
     * API: Update an existing customer.
     */
    public function updateCustomer(Request $request, $id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = StoreCustomer::where('store_id', $this->getStoreId())->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['customer' => $customer, 'message' => __('notebook.customer_updated') ?? 'تم تحديث بيانات الزبون']);
    }

    /**
     * API: Get ledger for a specific customer.
     */
    public function getLedger(Request $request, $customerId)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = StoreCustomer::where('store_id', $this->getStoreId())->findOrFail($customerId);
        $perPage = (int) $request->query('per_page', 5);

        $transactions = $customer->transactions()
            ->with(['createdBy', 'bankAccount.paymentEntity'])
            ->reorder()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take($perPage)
            ->get();
            
        // Map createdBy and bank_account_name properties for easier access
        $transactions->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
            
            if ($tx->type === 'payment' && $tx->store_bank_account_id && $tx->bankAccount) {
                $entityName = optional($tx->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($tx->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $tx->bank_account_name = $tx->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $tx->bankAccount->account_number;
            } else {
                $tx->bank_account_name = null;
            }
            
            return $tx;
        });

        $totalTransactions = $customer->transactions()->count();

        return response()->json([
            'customer' => $customer,
            'transactions' => $transactions,
            'totalLedgerTransactions' => $totalTransactions,
        ]);
    }

    /**
     * API: Store a new transaction.
     */
    public function storeTransaction(Request $request, $customerId)
    {
        if (!$this->isAuthorized('notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $customer = StoreCustomer::where('store_id', $this->getStoreId())->findOrFail($customerId);

        if ($customer->status == 0 && ($request->type === 'debt' || $request->boolean('is_direct_sale'))) {
            return response()->json(['message' => __('notebook.customer_disabled_cannot_add_debt') ?? 'العميل معطل، لا يمكن إضافة ديون أو مبيعات له، يُسمح بتسديد الدفعات فقط.'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => 'nullable|required_if:type,payment|exists:store_bank_accounts,id',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
            'is_direct_sale' => 'nullable|boolean',
        ]);

        if ($request->type === 'debt' && !$customer->bypass_debt_limit && $customer->debt_age !== null && $customer->debt_age > 10) {
            return response()->json([
                'message' => __('notebook.debt_age_exceeded_limit', ['days' => $customer->debt_age]) ?? "لا يمكن تسجيل دين جديد لهذا العميل لوجود دين مستحق منذ أكثر من 10 أيام (عمر الدين الحالي: {$customer->debt_age} يوماً)."
            ], 422);
        }

        if ($request->boolean('is_direct_sale')) {
            // Direct POS sale: record debt then immediate payment
            $debtTx = StoreTransaction::create([
                'store_id' => $this->getStoreId(),
                'store_customer_id' => $customer->id,
                'type' => 'debt',
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'description' => $request->description ?: 'مبيعات',
                'created_by' => Auth::guard('casher')->id(),
            ]);

            $tx = StoreTransaction::create([
                'store_id' => $this->getStoreId(),
                'store_customer_id' => $customer->id,
                'store_bank_account_id' => $request->store_bank_account_id,
                'type' => 'payment',
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'description' => $request->description ?: 'دفع مباشر',
                'linked_transaction_id' => $debtTx->id,
                'created_by' => Auth::guard('casher')->id(),
            ]);

            // Link debt to payment
            $debtTx->updateQuietly(['linked_transaction_id' => $tx->id]);
        } else {
            $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

            $tx = StoreTransaction::create([
                'store_id' => $this->getStoreId(),
                'store_customer_id' => $customer->id,
                'store_bank_account_id' => $request->type === 'payment' ? $request->store_bank_account_id : null,
                'type' => $request->type,
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'description' => $description,
                'created_by' => Auth::guard('casher')->id(),
            ]);
        }

        $customer->refresh();

        return response()->json([
            'transaction' => $tx,
            'customer' => $customer,
            'message' => $request->type === 'debt' ? __('notebook.debt_registered') : __('notebook.payment_registered'),
        ]);
    }

    /**
     * API: Update a transaction.
     */
    public function updateTransaction(Request $request, $id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tx = StoreTransaction::where('store_id', $this->getStoreId())->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => 'nullable|required_if:type,payment|exists:store_bank_accounts,id',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

        $tx->update([
            'type' => $request->type,
            'store_bank_account_id' => $request->type === 'payment' ? $request->store_bank_account_id : null,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'description' => $description,
            // we do not update created_by on update
        ]);

        $customer = $tx->customer()->first();
        $customer->refresh();

        return response()->json([
            'transaction' => $tx,
            'customer' => $customer,
            'message' => __('notebook.transaction_updated'),
        ]);
    }

    /**
     * API: Delete a transaction.
     */
    public function destroyTransaction($id)
    {
        if (!$this->isAuthorized('notebook_delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $tx = StoreTransaction::where('store_id', $this->getStoreId())->findOrFail($id);
        $customer = $tx->customer()->first();
        
        $tx->forceDelete();
        $customer->refresh();

        return response()->json([
            'customer' => $customer,
            'message' => __('notebook.transaction_deleted'),
        ]);
    }
}
