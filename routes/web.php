<?php
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\Auth\AuthController as CasherAuthController;
use App\Http\Controllers\Website\Casher\CasherNotebookController;
use App\Livewire\Website\Casher\CasherNotebook;

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
                    Route::post('customers', [CasherNotebookController::class, 'storeCustomer'])->name('customers.store');
                    Route::get('customers/{customer}/transactions', [CasherNotebookController::class, 'getLedger'])->name('customers.ledger');
                    Route::post('customers/{customer}/transactions', [CasherNotebookController::class, 'storeTransaction'])->name('transactions.store');
                    Route::put('transactions/{transaction}', [CasherNotebookController::class, 'updateTransaction'])->name('transactions.update');
                    Route::delete('transactions/{transaction}', [CasherNotebookController::class, 'destroyTransaction'])->name('transactions.destroy');

                    Route::get('withdrawals', [\App\Http\Controllers\Website\Casher\CasherWithdrawalController::class, 'index'])->name('withdrawals.index');
                    Route::post('withdrawals', [\App\Http\Controllers\Website\Casher\CasherWithdrawalController::class, 'store'])->name('withdrawals.store');
                    Route::delete('withdrawals/{withdrawal}', [\App\Http\Controllers\Website\Casher\CasherWithdrawalController::class, 'destroy'])->name('withdrawals.destroy');
                });
        });
    },
);
