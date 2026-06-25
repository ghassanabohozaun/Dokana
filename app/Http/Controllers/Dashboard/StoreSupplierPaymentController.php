<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreSupplierPaymentRequest;
use App\Services\Dashboard\StoreSupplierPaymentService;
use App\Services\Dashboard\StoreSupplierService;
use App\Services\Dashboard\StoreBankAccountService;
use App\Services\TenantService;
use Illuminate\Http\Request;

class StoreSupplierPaymentController extends Controller
{
    protected $service;
    protected $supplierService;
    protected $bankAccountService;
    protected $tenantService;

    public function __construct(
        StoreSupplierPaymentService $service,
        StoreSupplierService $supplierService,
        StoreBankAccountService $bankAccountService,
        TenantService $tenantService
    ) {
        $this->service = $service;
        $this->supplierService = $supplierService;
        $this->bankAccountService = $bankAccountService;
        $this->tenantService = $tenantService;
    }

    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $supplierId = $request->input('store_supplier_id');
        $invoiceId = $request->input('store_supplier_invoice_id');
        $storeBankAccountId = $request->input('store_bank_account_id');
        $specificDate = $request->input('specific_date');

        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $storeId = $request->input('store_id') ?? null;
        } else {
            $storeId = user()->store_id;
        }

        $payments = $this->service->getAll($keyword, $storeId, $supplierId, $invoiceId, $storeBankAccountId, $specificDate);
        $title = __('store_supplier_payments.store_supplier_payments');

        $stores = null;
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $stores = app(\App\Services\Dashboard\StoreService::class)->getActiveStoresForDropdown();
        }

        $bankAccounts = collect();
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            if ($storeId) {
                $bankAccounts = \App\Models\StoreBankAccount::where('store_id', $storeId)
                    ->with(['paymentEntity', 'store'])->get();
            } else {
                $bankAccounts = \App\Models\StoreBankAccount::with(['paymentEntity', 'store'])->get();
            }
        } else {
            $bankAccounts = \App\Models\StoreBankAccount::where('store_id', user()->store_id)
                ->with('paymentEntity')->get();
        }

        $suppliers = $this->supplierService->getActiveList($storeId);

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.store_supplier_payments.partials._table', compact('payments', 'stores', 'suppliers'))->render();
        }

        return view('dashboard.store_supplier_payments.index', compact('payments', 'title', 'bankAccounts', 'stores', 'suppliers'));
    }

    public function store(StoreSupplierPaymentRequest $request)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_payments_create');
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

    public function update(StoreSupplierPaymentRequest $request, $id)
    {
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_payments_update');
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
        \Illuminate\Support\Facades\Gate::authorize('store_supplier_payments_delete');
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
}
