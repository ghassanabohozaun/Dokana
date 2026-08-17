<?php

namespace App\Http\Controllers\Website\Casher;

use App\Http\Controllers\Controller;
use App\Models\StoreWithdrawal;
use App\Models\StoreBankAccount;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CasherWithdrawalController extends Controller
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
     * API: Get withdrawals for the current casher's store for today.
     */
    public function index(Request $request)
    {
        if (!$this->isAuthorized('notebook_read')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $perPage = (int) $request->query('per_page', 10);
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $query = StoreWithdrawal::with(['creator', 'bankAccount.paymentEntity'])
            ->where('store_id', $storeId)
            ->whereBetween('withdrawal_date', [$todayStart, $todayEnd]);

        $totalWithdrawalsCount = $query->count();
        $totalWithdrawalsAmount = $query->sum('amount');
        $withdrawals = $query->latest()->take($perPage)->get();

        $withdrawals->transform(function ($w) {
            $w->cashier_name = $w->creator ? $w->creator->name : null;
            if ($w->store_bank_account_id && $w->bankAccount) {
                $entityName = optional($w->bankAccount->paymentEntity)->getTranslation('name', app()->getLocale()) ?: optional($w->bankAccount->paymentEntity)->getTranslation('name', 'ar');
                $w->bank_account_name = $w->bankAccount->account_type === 'cash' ? $entityName : $entityName . ' - ' . $w->bankAccount->account_number;
            } else {
                $w->bank_account_name = null;
            }
            return $w;
        });

        $bankAccounts = StoreBankAccount::where('store_id', $storeId)->get(['id', 'current_balance']);

        return response()->json([
            'withdrawals' => $withdrawals,
            'totalCount' => $totalWithdrawalsCount,
            'totalAmount' => $totalWithdrawalsAmount,
            'bankBalances' => $bankAccounts->pluck('current_balance', 'id')
        ]);
    }

    /**
     * API: Store a new withdrawal.
     */
    public function store(Request $request)
    {
        if (!$this->isAuthorized('notebook_create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'reason' => 'required|string|max:255',
            'store_bank_account_id' => [
                'required',
                Rule::exists('store_bank_accounts', 'id')->where('store_id', $storeId)
            ],
            'withdrawal_date' => 'required|date',
        ]);

        $bankAccount = StoreBankAccount::where('store_id', $storeId)->find($request->store_bank_account_id);
        if ($bankAccount) {
            $availableBalance = (float) $bankAccount->current_balance;
            if ((float) $request->amount > $availableBalance) {
                return response()->json([
                    'message' => __('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)]),
                    'errors' => [
                        'amount' => [__('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)])]
                    ]
                ], 422);
            }
        }

        return DB::transaction(function() use ($request, $storeId) {
            $withdrawal = StoreWithdrawal::create([
                'store_id' => $storeId,
                'store_bank_account_id' => $request->store_bank_account_id,
                'amount' => $request->amount,
                'reason' => $request->reason,
                'withdrawal_date' => $request->withdrawal_date,
                'created_by' => Auth::guard('casher')->id(),
            ]);

            return response()->json([
                'withdrawal' => $withdrawal,
                'message' => __('notebook.withdrawal_added'),
            ]);
        });
    }

    /**
     * API: Update an existing withdrawal.
     */
    public function update(Request $request, $id)
    {
        if (!$this->isAuthorized('notebook_update')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $storeId = $this->getStoreId();
        $withdrawal = StoreWithdrawal::where('store_id', $storeId)->findOrFail($id);

        $request->validate([
            'amount' => 'required|numeric|min:0.1',
            'reason' => 'required|string|max:255',
            'store_bank_account_id' => [
                'required',
                Rule::exists('store_bank_accounts', 'id')->where('store_id', $storeId)
            ],
            'withdrawal_date' => 'required|date',
        ]);

        $bankAccount = StoreBankAccount::where('store_id', $storeId)->find($request->store_bank_account_id);
        if ($bankAccount) {
            $availableBalance = (float) $bankAccount->current_balance;
            if ($withdrawal->store_bank_account_id == $request->store_bank_account_id) {
                $availableBalance += (float) $withdrawal->amount;
            }
            
            if ((float) $request->amount > $availableBalance) {
                return response()->json([
                    'message' => __('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)]),
                    'errors' => [
                        'amount' => [__('store_withdrawals.insufficient_balance', ['balance' => number_format($availableBalance, 2)])]
                    ]
                ], 422);
            }
        }

        return DB::transaction(function() use ($request, $withdrawal) {
            $withdrawal->update([
                'store_bank_account_id' => $request->store_bank_account_id,
                'amount' => $request->amount,
                'reason' => $request->reason,
                'withdrawal_date' => $request->withdrawal_date,
            ]);

            return response()->json([
                'withdrawal' => $withdrawal,
                'message' => __('notebook.transaction_updated') ?? 'تم التعديل بنجاح',
            ]);
        });
    }

    /**
     * API: Delete a withdrawal.
     */
    public function destroy($id)
    {
        if (!$this->isAuthorized('notebook_delete')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $withdrawal = StoreWithdrawal::where('store_id', $this->getStoreId())->findOrFail($id);

        return DB::transaction(function() use ($withdrawal) {
            $withdrawal->forceDelete();

            return response()->json([
                'message' => __('notebook.withdrawal_deleted'),
            ]);
        });
    }
}
