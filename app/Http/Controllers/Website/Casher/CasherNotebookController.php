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

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($filter === 'debt') {
            $query->where('balance', '>', 0);
        } elseif ($filter === 'paid') {
            $query->where('balance', '=', 0);
        } elseif ($filter === 'credit') {
            $query->where('balance', '<', 0);
        } elseif ($filter === 'disabled') {
            $query->where('status', 0);
        }

        $totalCustomers = $query->count();
        $customers = $query->latest()->take($perPage)->get();

        $totalDebt = StoreCustomer::where('store_id', $this->getStoreId())->where('balance', '>', 0)->sum('balance');
        $todayCollections = StoreTransaction::where('store_id', $this->getStoreId())
            ->where('type', 'payment')
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        return response()->json([
            'customers' => $customers,
            'totalCustomers' => $totalCustomers,
            'totalDebt' => $totalDebt,
            'todayCollections' => $todayCollections,
        ]);
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
        ]);

        $customer = StoreCustomer::create([
            'store_id' => $this->getStoreId(),
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return response()->json(['customer' => $customer, 'message' => __('notebook.customer_added') . ' ' . $customer->name]);
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
            ->latest()
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
            'totalTransactions' => $totalTransactions,
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

        if ($customer->status == 0) {
            return response()->json(['message' => __('notebook.customer_disabled_cannot_add_transaction') ?? 'العميل معطل، لا يمكن إضافة حركات مالية له.'], 403);
        }

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'store_bank_account_id' => 'nullable|required_if:type,payment|exists:store_bank_accounts,id',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        if ($request->type === 'debt' && !$customer->bypass_debt_limit && $customer->debt_age !== null && $customer->debt_age > 10) {
            return response()->json([
                'message' => __('notebook.debt_age_exceeded_limit', ['days' => $customer->debt_age]) ?? "لا يمكن تسجيل دين جديد لهذا العميل لوجود دين مستحق منذ أكثر من 10 أيام (عمر الدين الحالي: {$customer->debt_age} يوماً)."
            ], 422);
        }

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
