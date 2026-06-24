<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreBankAccountRequest;
use App\Models\Store;
use App\Services\Dashboard\StoreBankAccountService;
use App\Services\Dashboard\StoreService;
use App\Models\StoreBankAccount;
use App\Models\PaymentEntity;
use App\Models\StoreTransaction;
use App\Models\StoreWithdrawal;
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

        $banks = PaymentEntity::where('type', 'bank')->where('status', 1)->get()->map(function ($bank) {
            return [
                'id' => $bank->id,
                'ar' => $bank->getTranslation('name', 'ar'),
                'en' => $bank->getTranslation('name', 'en'),
            ];
        })->toArray();

        $wallets = PaymentEntity::where('type', 'wallet')->where('status', 1)->get()->map(function ($wallet) {
            return [
                'id' => $wallet->id,
                'ar' => $wallet->getTranslation('name', 'ar'),
                'en' => $wallet->getTranslation('name', 'en'),
            ];
        })->toArray();

        $cashEntities = PaymentEntity::where('type', 'cash')->where('status', 1)->get()->map(function ($cash) {
            return [
                'id' => $cash->id,
                'ar' => $cash->getTranslation('name', 'ar'),
                'en' => $cash->getTranslation('name', 'en'),
            ];
        })->toArray();

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.bank_accounts.partials._table', compact('bankAccounts', 'stores'))->render();
        }

        return view('dashboard.bank_accounts.index', compact('bankAccounts', 'title', 'stores', 'banks', 'wallets', 'cashEntities'));
    }

    public function getByStore(Request $request)
    {
        $bankAccounts = StoreBankAccount::with(['paymentEntity'])
            ->where('store_id', $request->store_id)
            ->get();

        return response()->json($bankAccounts);
    }

    public function getBalance(Request $request)
    {
        $bankAccount = StoreBankAccount::find($request->bank_account_id);
        if ($bankAccount) {
            return response()->json(['balance' => $bankAccount->current_balance]);
        }
        return response()->json(['balance' => 0]);
    }

    public function show(string $id, Request $request)
    {
        Gate::authorize('bank_accounts_read');

        $account = StoreBankAccount::with(['store', 'creator', 'paymentEntity'])->findOrFail($id);
        
        $title = __('bank_accounts.bank_accounts') . ' - ' . ($account->paymentEntity ? $account->paymentEntity->getTranslation('name', app()->getLocale()) : '');

        // Calculate totals
        $totalDeposits = StoreTransaction::where('store_bank_account_id', $id)->where('type', 'payment')->sum('amount');
        $totalWithdrawals = StoreWithdrawal::where('store_bank_account_id', $id)->sum('amount');
        $currentBalance = $totalDeposits - $totalWithdrawals;

        $tab = $request->get('tab', 'deposits');

        if ($tab === 'withdrawals') {
            $transactions = StoreWithdrawal::where('store_bank_account_id', $id)
                ->with(['creator'])
                ->orderBy('withdrawal_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $transactions = StoreTransaction::where('store_bank_account_id', $id)
                ->where('type', 'payment')
                ->with(['createdBy', 'customer'])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        if ($request->ajax()) {
            if ($tab === 'withdrawals') {
                return view('dashboard.bank_accounts.partials._withdrawals_table', compact('transactions', 'account'))->render();
            }
            return view('dashboard.bank_accounts.partials._deposits_table', compact('transactions', 'account'))->render();
        }

        return view('dashboard.bank_accounts.show', compact('title', 'account', 'transactions', 'totalDeposits', 'totalWithdrawals', 'currentBalance', 'tab'));
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
