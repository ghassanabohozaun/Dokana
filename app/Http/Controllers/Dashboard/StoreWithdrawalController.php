<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Dashboard\StoreWithdrawalService;
use App\Http\Requests\Dashboard\StoreWithdrawalRequest;
use Illuminate\Support\Facades\Gate;

class StoreWithdrawalController extends Controller
{
    protected $service;

    public function __construct(StoreWithdrawalService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        Gate::authorize('store_withdrawals_read');

        $withdrawals = $this->service->getAll($request);
        $title = __('store_withdrawals.store_withdrawals');

        $stores = null;
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $stores = app(\App\Services\Dashboard\StoreService::class)->getActiveStoresForDropdown();
        }

        // Need bank accounts for the create modal and filters
        $bankAccounts = collect();
        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $bankAccounts = \App\Models\StoreBankAccount::with(['paymentEntity', 'store'])->get();
        } else {
            $bankAccounts = \App\Models\StoreBankAccount::where('store_id', user()->store_id)
                ->with('paymentEntity')->get();
        }

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.store_withdrawals.partials._table', compact('withdrawals', 'stores'))->render();
        }

        return view('dashboard.store_withdrawals.index', compact('withdrawals', 'title', 'bankAccounts', 'stores'));
    }

    public function store(StoreWithdrawalRequest $request)
    {
        Gate::authorize('store_withdrawals_create');
        
        try {
            $this->service->store($request->validated());
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

    public function update(StoreWithdrawalRequest $request, $id)
    {
        Gate::authorize('store_withdrawals_update');
        
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
        Gate::authorize('store_withdrawals_delete');
        
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
