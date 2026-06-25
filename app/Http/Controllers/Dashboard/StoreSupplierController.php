<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreSupplierRequest;
use App\Services\Dashboard\StoreSupplierService;
use App\Services\TenantService;
use App\Models\StoreBankAccount;
use App\Services\Dashboard\StoreService;
use Illuminate\Http\Request;

class StoreSupplierController extends Controller
{
    protected $service;
    protected $tenantService;

    public function __construct(StoreSupplierService $service, TenantService $tenantService)
    {
        $this->service = $service;
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = $request->input('store_id') ?? null;
        } else {
            $storeId = user()->store_id;
        }

        $suppliers = $this->service->getAll($keyword, $storeId);
        
        if ($request->wantsJson() || $request->input('format') === 'json') {
            return response()->json($suppliers->items());
        }

        $title = __('store_suppliers.store_suppliers');

        $stores = null;
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $stores = app(\App\Services\Dashboard\StoreService::class)->getActiveStoresForDropdown();
        }

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.store_suppliers.partials._table', compact('suppliers', 'stores'))->render();
        }

        return view('dashboard.store_suppliers.index', compact('suppliers', 'title', 'stores'));
    }

    public function store(StoreSupplierRequest $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_suppliers_create');
        try {
            $this->service->create($request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.add_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_message'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(StoreSupplierRequest $request, $id)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_suppliers_update');
        try {
            $this->service->update($id, $request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_message'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_suppliers_delete');
        try {
            $this->service->delete($request->id);
            return response()->json([
                'status' => true,
                'message' => __('general.delete_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getByStore(Request $request)
    {
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = $request->input('store_id');
        } else {
            $storeId = user()->store_id;
        }
        $suppliers = $this->service->getActiveList($storeId);
        return response()->json($suppliers);
    }
}
