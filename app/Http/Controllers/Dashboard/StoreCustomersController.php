<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreCustomerRequest;
use App\Models\Store;
use App\Models\StoreCustomer;
use App\Models\StoreTransaction;
use App\Services\Dashboard\StoreCustomerService;
use App\Services\Dashboard\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StoreCustomersController extends Controller
{
    protected $storeCustomerService, $storeService;

    public function __construct(StoreCustomerService $storeCustomerService, StoreService $storeService)
    {
        $this->storeCustomerService = $storeCustomerService;
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        Gate::authorize('store_customers_read');

        $title = __('store_customers.store_customers');
        $store_customers = $this->storeCustomerService->getAll($request->keyword, $request->store_id);

        $stores = null;
        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        if ($request->ajax()) {
            return view('dashboard.store_customers.partials._table', compact('store_customers', 'stores'))->render();
        }

        return view('dashboard.store_customers.index', compact('title', 'store_customers', 'stores'));
    }

    public function store(StoreCustomerRequest $request)
    {
        Gate::authorize('store_customers_create');

        try {
            $data = $request->only(['name', 'phone', 'store_id']);
            $this->storeCustomerService->create($data);
            return response()->json([
                'status' => true,
                'message' => __('general.add_success_message')
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.add_error_message')
            ], 500);
        }
    }

    public function show(string $id, Request $request)
    {
        Gate::authorize('store_customers_read');

        $store_customer = $this->storeCustomerService->getOne($id);
        if (!$store_customer) {
            abort(404);
        }

        $title = $store_customer->name . ' - ' . __('store_customers.store_customers');
        
        // Fetch paginated transactions for this customer
        $transactions = StoreTransaction::where('store_customer_id', $id)
            ->with(['store'])
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50);

        if ($request->ajax()) {
            return view('dashboard.store_customers.partials._transactions_table', compact('transactions', 'store_customer'))->render();
        }

        return view('dashboard.store_customers.show', compact('title', 'store_customer', 'transactions'));
    }

    public function update(StoreCustomerRequest $request, string $id)
    {
        Gate::authorize('store_customers_update');

        try {
            $data = $request->only(['id', 'name', 'phone', 'store_id']);
            $this->storeCustomerService->update($data);
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

    public function destroy(Request $request)
    {
        Gate::authorize('store_customers_delete');

        if ($request->ajax()) {
            try {
                $this->storeCustomerService->destroy($request->id);
                return response()->json([
                    'status' => true,
                    'message' => __('general.delete_success_message')
                ], 200);
            } catch (\App\Exceptions\DeleteRestrictionException $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.delete_error_message')
                ], 500);
            }
        }
    }

    public function changeStatus(Request $request)
    {
        Gate::authorize('store_customers_update');

        if ($request->ajax()) {
            try {
                $this->storeCustomerService->changeStatus($request->id, $request->statusSwitch);
                $status = $this->storeCustomerService->getOne($request->id);
                return response()->json([
                    'status' => true,
                    'message' => __('general.change_status_success_message'),
                    'data' => $status
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.change_status_error_message')
                ], 500);
            }
        }
    }

    public function getByStore(Request $request)
    {
        Gate::authorize('store_customers_read');

        if ($request->ajax()) {
            $customers = StoreCustomer::active()
                ->where('store_id', $request->store_id)
                ->select('id', 'name', 'phone')
                ->get();
                
            return response()->json($customers);
        }
    }
}
