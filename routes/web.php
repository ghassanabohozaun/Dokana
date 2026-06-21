<?php
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\Auth\AuthController as CasherAuthController;
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

        ######################################  Cashier isolated access  ##################################################################
        Route::get('/casher/login', [CasherAuthController::class, 'getCashierLogin'])->name('casher.login')->middleware('guest:casher');
        Route::post('/casher/login', [CasherAuthController::class, 'postCashierLogin'])->name('casher.login.submit')->middleware('guest:casher');
        
        Route::group(['middleware' => ['auth:casher']], function () {
            Route::get('/casher/logout', [CasherAuthController::class, 'logoutCashier'])->name('casher.logout');
            
            // Cashier Notebook (Alpine.js + API)
            Route::get('/casher/notebook', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'index'])->name('casher.notebook');
            
            // Cashier APIs
            Route::prefix('casher/api')->name('casher.api.')->group(function () {
                Route::get('customers', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'getCustomers'])->name('customers.index');
                Route::post('customers', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'storeCustomer'])->name('customers.store');
                Route::get('customers/{customer}/transactions', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'getLedger'])->name('customers.ledger');
                Route::post('customers/{customer}/transactions', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'storeTransaction'])->name('transactions.store');
                Route::put('transactions/{transaction}', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'updateTransaction'])->name('transactions.update');
                Route::delete('transactions/{transaction}', [\App\Http\Controllers\Website\Casher\CasherNotebookController::class, 'destroyTransaction'])->name('transactions.destroy');
            });
        });
    },
);
