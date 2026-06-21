<?php

namespace App\Http\Controllers\Website\Casher;

use App\Http\Controllers\Controller;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
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

        return view('website.casher.notebook.index');
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
            $query->where('balance', '<=', 0);
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
            ->with('createdBy')
            ->latest()
            ->take($perPage)
            ->get();
            
        // Map createdBy to a user_name property for easier access
        $transactions->transform(function ($tx) {
            $tx->cashier_name = $tx->createdBy ? $tx->createdBy->name : null;
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

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'type' => 'required|in:debt,payment',
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

        $tx = StoreTransaction::create([
            'store_id' => $this->getStoreId(),
            'store_customer_id' => $customer->id,
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
            'description' => 'nullable|string|max:255',
            'transaction_date' => 'required|date',
        ]);

        $description = $request->description ?: ($request->type === 'debt' ? __('notebook.debt') : __('notebook.payment'));

        $tx->update([
            'type' => $request->type,
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
        
        $tx->delete();
        $customer->refresh();

        return response()->json([
            'customer' => $customer,
            'message' => __('notebook.transaction_deleted'),
        ]);
    }
}
