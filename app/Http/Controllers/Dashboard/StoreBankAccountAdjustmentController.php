<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StoreBankAccount;
use App\Models\StoreBankAccountAdjustment;
use Illuminate\Support\Facades\DB;

class StoreBankAccountAdjustmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'store_bank_account_id' => 'required|exists:store_bank_accounts,id',
            'actual_balance' => 'required|numeric',
            'notes' => 'nullable|string|max:500'
        ]);

        $bankAccount = StoreBankAccount::findOrFail($request->store_bank_account_id);
        
        $oldBalance = $bankAccount->current_balance;
        $newBalance = $request->actual_balance;
        
        if ($oldBalance == $newBalance) {
            return response()->json([
                'status' => false,
                'message' => __('bank_accounts.balance_is_same')
            ], 422);
        }

        $amount = $newBalance - $oldBalance;

        DB::beginTransaction();
        try {
            StoreBankAccountAdjustment::create([
                'store_id' => $bankAccount->store_id,
                'store_bank_account_id' => $bankAccount->id,
                'amount' => $amount,
                'old_balance' => $oldBalance,
                'new_balance' => $newBalance,
                'notes' => $request->notes,
                'created_by' => user()->id,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => __('bank_accounts.reconciliation_successful')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => __('general.error_occurred')
            ], 500);
        }
    }
}
