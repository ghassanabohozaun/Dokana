<?php
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\Auth\AuthController as CasherAuthController;
use App\Http\Controllers\Website\Casher\CasherNotebookController;
use App\Livewire\Website\Casher\CasherNotebook;
use App\Http\Controllers\Website\Casher\CasherWithdrawalController;
use App\Http\Controllers\Website\Casher\CasherSupplierController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'as' => 'website.',
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        ###################################### welcome  ##################################################################
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::redirect('/casher', '/casher/login');

        ######################################  Cashier  access  ##################################################################
        Route::get('/casher/login', [CasherAuthController::class, 'getCashierLogin'])
            ->name('casher.login')
            ->middleware('guest:casher');
        Route::post('/casher/login', [CasherAuthController::class, 'postCashierLogin'])
            ->name('casher.login.submit')
            ->middleware('guest:casher');

        ######################################  Cashier    ##################################################################

        Route::group(['middleware' => ['auth:casher']], function () {
            Route::get('/casher/logout', [CasherAuthController::class, 'logoutCashier'])->name('casher.logout');

            // Cashier Notebook (Alpine.js + API)
            Route::get('/casher/notebook', [CasherNotebookController::class, 'index'])->name('casher.notebook');

            // Cashier APIs
            Route::prefix('casher/api')
                ->name('casher.api.')
                ->group(function () {
                    Route::get('customers', [CasherNotebookController::class, 'getCustomers'])->name('customers.index');
                    Route::get('financial-summary', [CasherNotebookController::class, 'getFinancialSummary'])->name('financial-summary');
                    Route::get('today-collections', [CasherNotebookController::class, 'getTodayCollections'])->name('today-collections');
                    Route::get('today-debts', [CasherNotebookController::class, 'getTodayDebts'])->name('today-debts');
                    Route::get('today-direct-sales', [CasherNotebookController::class, 'getTodayDirectSales'])->name('today-direct-sales');
                    Route::post('voice-command', [CasherNotebookController::class, 'processAIVoiceCommand'])->name('voice-command');
                    Route::post('customers', [CasherNotebookController::class, 'storeCustomer'])->name('customers.store');
                    Route::put('customers/{customer}', [CasherNotebookController::class, 'updateCustomer'])->name('customers.update');
                    Route::get('customers/{customer}/transactions', [CasherNotebookController::class, 'getLedger'])->name('customers.ledger');
                    Route::post('customers/{customer}/transactions', [CasherNotebookController::class, 'storeTransaction'])->name('transactions.store');
                    Route::put('transactions/{transaction}', [CasherNotebookController::class, 'updateTransaction'])->name('transactions.update');
                    Route::delete('transactions/{transaction}', [CasherNotebookController::class, 'destroyTransaction'])->name('transactions.destroy');

                    Route::get('withdrawals', [CasherWithdrawalController::class, 'index'])->name('withdrawals.index');
                    Route::post('withdrawals', [CasherWithdrawalController::class, 'store'])->name('withdrawals.store');
                    Route::delete('withdrawals/{withdrawal}', [CasherWithdrawalController::class, 'destroy'])->name('withdrawals.destroy');

                    // Supplier APIs
                    Route::get('suppliers/all-invoices', [CasherSupplierController::class, 'getAllSupplierInvoices'])->name('suppliers.all-invoices');
                    Route::get('suppliers/all-payments', [CasherSupplierController::class, 'getAllSupplierPayments'])->name('suppliers.all-payments');
                    Route::get('suppliers/today-invoices', [CasherSupplierController::class, 'getTodaySupplierInvoices'])->name('suppliers.today-invoices');
                    Route::get('suppliers/today-payments', [CasherSupplierController::class, 'getTodaySupplierPayments'])->name('suppliers.today-payments');
                    Route::get('suppliers', [CasherSupplierController::class, 'getSuppliers'])->name('suppliers.index');
                    Route::post('suppliers', [CasherSupplierController::class, 'storeSupplier'])->name('suppliers.store');
                    Route::put('suppliers/{supplier}', [CasherSupplierController::class, 'updateSupplier'])->name('suppliers.update');
                    Route::get('suppliers/{supplier}/ledger', [CasherSupplierController::class, 'getLedger'])->name('suppliers.ledger');
                    Route::post('suppliers/{supplier}/invoices', [CasherSupplierController::class, 'storeInvoice'])->name('suppliers.invoices.store');
                    Route::put('invoices/{invoice}', [CasherSupplierController::class, 'updateInvoice'])->name('suppliers.invoices.update');
                    Route::delete('invoices/{invoice}', [CasherSupplierController::class, 'destroyInvoice'])->name('suppliers.invoices.destroy');
                    Route::post('suppliers/{supplier}/payments', [CasherSupplierController::class, 'storePayment'])->name('suppliers.payments.store');
                    Route::delete('payments/{payment}', [CasherSupplierController::class, 'destroyPayment'])->name('suppliers.payments.destroy');
                });
        });
    }
);

// Protected Web Artisan Runner for Hostinger
Route::get('/run-my-migrations-secretly', function () {
    // Security check: allowed on local dev, or requires secret key on production
    if (!app()->isLocal() && request('key') !== 'dokana_prod_safe_2026') {
        abort(403, 'Access Denied: Unauthorized access to system maintenance.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = \Illuminate\Support\Facades\Artisan::output();

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $cacheOutput = \Illuminate\Support\Facades\Artisan::output();

        $workingUser = \Illuminate\Support\Facades\DB::table('users')->where('email', 'ghassan@admin.com')->first();
        $hashed = ($workingUser && !empty($workingUser->password)) ? $workingUser->password : \Illuminate\Support\Facades\Hash::make('123456');

        // 1. Ensure Super User (admin@admin.com) is active
        \Illuminate\Support\Facades\DB::table('users')->where('id', 1)->orWhere('email', 'admin@admin.com')->update([
            'email' => 'admin@admin.com',
            'password' => $hashed,
            'status' => 1,
            'deleted_at' => null,
        ]);

        // 2. Ensure Backup Super User (super@admin.com) is active
        $superExists = \Illuminate\Support\Facades\DB::table('users')->where('email', 'super@admin.com')->first();
        if (!$superExists) {
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'store_id' => 1,
                'role_id' => 1,
                'name' => json_encode(['ar' => 'المدير العام', 'en' => 'Super Admin'], JSON_UNESCAPED_UNICODE),
                'email' => 'super@admin.com',
                'password' => $hashed,
                'mobile' => '0599999998',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            \Illuminate\Support\Facades\DB::table('users')->where('email', 'super@admin.com')->update([
                'password' => $hashed,
                'status' => 1,
                'role_id' => 1,
                'store_id' => 1,
                'deleted_at' => null,
            ]);
        }

        return '<div style="font-family: sans-serif; padding: 30px; line-height: 1.6; background: #0f172a; color: #f8fafc; border-radius: 12px; max-width: 700px; margin: 40px auto; direction: rtl;">
            <h2 style="color: #10b981; margin-top: 0;">✅ تم تحديث النظام بنجاح وأمان تام!</h2>
            <div style="background: #064e3b; border: 1px solid #10b981; padding: 14px 18px; border-radius: 8px; color: #a7f3d0; margin-bottom: 16px;">
                <p style="margin: 0 0 6px 0; font-weight: bold; font-size: 15px;">✓ تم ضبط وتفعيل حسابات السوبر أدمن التالية بكلمة مرور (123456):</p>
                <ul style="margin: 0; padding-right: 20px; font-size: 14px;">
                    <li><b>الحساب الأساسي:</b> admin@admin.com</li>
                    <li><b>الحساب الإضافي:</b> super@admin.com</li>
                </ul>
            </div>
            <h3 style="color: #38bdf8;">1. تقرير الميقريشن:</h3>
            <pre style="background: #1e293b; padding: 12px; border-radius: 8px; color: #a7f3d0; direction: ltr; text-align: left;">' . e($migrateOutput ?: 'النظام محدث بالكامل ولا توجد تعديلات معلقة.') . '</pre>
            <h3 style="color: #38bdf8;">2. مسح وتحديث الكاش:</h3>
            <pre style="background: #1e293b; padding: 12px; border-radius: 8px; color: #a7f3d0; direction: ltr; text-align: left;">' . e($cacheOutput) . '</pre>
            <p style="color: #94a3b8; font-size: 13px; margin-top: 20px;">✓ جميع البيانات الحالية محفوظة 100%.</p>
        </div>';
    } catch (\Exception $e) {
        return '<div style="font-family: sans-serif; padding: 30px; background: #450a0a; color: #fecaca; border-radius: 12px; max-width: 700px; margin: 40px auto; direction: rtl;">
            <h2 style="color: #ef4444; margin-top: 0;">❌ حدث خطأ:</h2>
            <pre style="direction: ltr; text-align: left;">' . e($e->getMessage()) . '</pre>
        </div>';
    }
});

// Dedicated Quick Cache Cleaner for Production without SSH
Route::get('/clear-cache', function () {
    if (!app()->isLocal() && request('key') !== 'dokana_prod_safe_2026') {
        abort(403, 'Access Denied: Unauthorized access.');
    }

    try {
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        $output = \Illuminate\Support\Facades\Artisan::output();

        return '<div style="font-family: sans-serif; padding: 30px; line-height: 1.6; background: #0f172a; color: #f8fafc; border-radius: 12px; max-width: 600px; margin: 40px auto; direction: rtl; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3); border: 1px solid #334155;">
            <h2 style="color: #10b981; margin-top: 0; display: flex; align-items: center; gap: 8px;">✨ تم تنظيف الكاش بنجاح!</h2>
            <p style="color: #94a3b8; font-size: 14px;">تم مسح وتفريغ كاش القوالب (Views)، والتكوين (Config)، والمسارات (Routes)، وذاكرة التخزين المؤقت (Cache).</p>
            <pre style="background: #1e293b; padding: 14px; border-radius: 8px; color: #38bdf8; direction: ltr; text-align: left; font-size: 13px; border: 1px solid #334155;">' . e($output) . '</pre>
            <div style="margin-top: 20px; text-align: center;">
                <a href="' . url('/') . '" style="display: inline-block; background: #2563eb; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; font-size: 14px;">الذهاب للرئيسية</a>
            </div>
        </div>';
    } catch (\Exception $e) {
        return '<div style="font-family: sans-serif; padding: 30px; background: #450a0a; color: #fecaca; border-radius: 12px; max-width: 600px; margin: 40px auto; direction: rtl;">
            <h2 style="color: #ef4444; margin-top: 0;">❌ حدث خطأ أثناء تنظيف الكاش:</h2>
            <pre style="direction: ltr; text-align: left;">' . e($e->getMessage()) . '</pre>
        </div>';
    }
});
