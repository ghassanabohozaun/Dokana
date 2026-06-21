<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBankAccountRequest;
use App\Models\Store;
use App\Services\Dashboard\StoreBankAccountService;
use App\Services\Dashboard\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\DeleteRestrictionException;

class StoreBankAccountController extends Controller
{
    protected $service;

    public function __construct(StoreBankAccountService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        Gate::authorize('bank_accounts_read'); 

        $bankAccounts = $this->service->getAll($request);
        $title = __('bank_accounts.bank_accounts');
        $stores = null;

        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $stores = app(StoreService::class)->getActiveStoresForDropdown();
        }

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.bank_accounts.partials._table', compact('bankAccounts', 'stores'))->render();
        }

        return view('dashboard.bank_accounts.index', compact('bankAccounts', 'title', 'stores'));
    }

    public function store(StoreBankAccountRequest $request)
    {
        Gate::authorize('bank_accounts_create');
        
        try {
            $this->service->store($request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.add_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.add_error_message')
            ], 500);
        }
    }

    public function update(StoreBankAccountRequest $request, $id)
    {
        Gate::authorize('bank_accounts_update');
        
        try {
            $this->service->update($id, $request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.update_error_message')
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('bank_accounts_delete');
        
        try {
            $this->service->delete($request->id);
            return response()->json([
                'status' => true,
                'message' => __('general.delete_success_message')
            ]);
        } catch (DeleteRestrictionException $e) {
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
