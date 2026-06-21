<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreTransactionRequest;
use App\Models\Store;
use App\Models\StoreCustomer;
use App\Services\Dashboard\StoreService;
use App\Services\Dashboard\StoreTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoreTransactionsController extends Controller
{
    protected $storeTransactionService;

    public function __construct(StoreTransactionService $storeTransactionService)
    {
        $this->storeTransactionService = $storeTransactionService;
    }

    public function index(Request $request)
    {
        Gate::authorize('store_transactions_read');

        $store_transactions = $this->storeTransactionService->getAll($request);
        $title = __('store_transactions.store_transactions');

        $stores = null;
        if (user()->role_id == 1 || user()->id == 1) {
            $stores = app(StoreService::class)->getActiveStoresForDropdown();
        }

        // Get customers for the dropdowns
        $customers = collect();
        if (user()->role_id != 1 && user()->id != 1) {
            $customers = StoreCustomer::active()->where('store_id', user()->store_id)->get();
        }

        if ($request->ajax()) {
            return view('dashboard.store_transactions.partials._table', compact('store_transactions', 'stores', 'customers'))->render();
        }

        return view('dashboard.store_transactions.index', compact('store_transactions', 'title', 'stores', 'customers'));
    }

    public function store(StoreTransactionRequest $request)
    {
        Gate::authorize('store_transactions_create');

        if ($request->ajax()) {
            try {
                $this->storeTransactionService->store($request->validated());
                return response()->json([
                    'status' => true,
                    'message' => __('general.add_success_message')
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.add_error_message')
                ], 500);
            }
        }
    }

    public function update(StoreTransactionRequest $request, $id)
    {
        Gate::authorize('store_transactions_update');

        if ($request->ajax()) {
            try {
                $this->storeTransactionService->update($id, $request->validated());
                return response()->json([
                    'status' => true,
                    'message' => __('general.update_success_message')
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.update_error_message')
                ], 500);
            }
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('store_transactions_delete');

        if ($request->ajax()) {
            try {
                $this->storeTransactionService->destroy($request->id);
                return response()->json([
                    'status' => true,
                    'message' => __('general.delete_success_message')
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.delete_error_message')
                ], 500);
            }
        }
    }
}
