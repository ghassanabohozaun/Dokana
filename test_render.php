<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    $t = App\Models\StoreBankAccountAdjustment::with('createdBy')->first();
    $transactions = App\Models\StoreBankAccountAdjustment::paginate(10);
    $account = App\Models\StoreBankAccount::find($t->store_bank_account_id);
    
    echo view('dashboard.bank_accounts.partials._adjustments_table', compact('transactions', 'account'))->render();
} catch (\Exception $e) {
    echo $e->getMessage();
}
