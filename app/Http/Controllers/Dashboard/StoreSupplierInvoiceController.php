<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreSupplierInvoiceRequest;
use App\Services\Dashboard\StoreSupplierInvoiceService;
use App\Services\Dashboard\StoreSupplierService;
use App\Services\TenantService;
use Illuminate\Http\Request;

class StoreSupplierInvoiceController extends Controller
{
    protected $service;
    protected $supplierService;
    protected $tenantService;

    public function __construct(
        StoreSupplierInvoiceService $service,
        StoreSupplierService $supplierService,
        TenantService $tenantService
    ) {
        $this->service = $service;
        $this->supplierService = $supplierService;
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $supplierId = $request->input('store_supplier_id');
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = $request->input('store_id') ?? null;
        } else {
            $storeId = user()->store_id;
        }

        $invoices = $this->service->getAll($keyword, $storeId, $supplierId);
        $title = __('store_supplier_invoices.store_supplier_invoices');

        $stores = null;
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $stores = app(\App\Services\Dashboard\StoreService::class)->getActiveStoresForDropdown();
        }

        $suppliers = $this->supplierService->getActiveList($storeId);

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.store_supplier_invoices.partials._table', compact('invoices', 'stores'))->render();
        }

        return view('dashboard.store_supplier_invoices.index', compact('invoices', 'title', 'suppliers', 'stores'));
    }

    public function store(StoreSupplierInvoiceRequest $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_invoices_create');
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

    public function update(StoreSupplierInvoiceRequest $request, $id)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_invoices_update');
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
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_invoices_delete');
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

    public function getBySupplier(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = null;
        } else {
            $storeId = user()->store_id;
        }

        $invoices = \App\Models\StoreSupplierInvoice::where('store_supplier_id', $supplierId)
            ->when($storeId, function($q) use ($storeId) {
                return $q->where('store_id', $storeId);
            })
            ->whereIn('status', ['unpaid', 'partially_paid'])
            ->get();

        return response()->json($invoices);
    }

    public function show($id)
    {
        $invoice = $this->service->find($id);
        return response()->json($invoice);
    }
}
