<?php

use App\Http\Controllers\Dashboard\Auth\AuthController;
use App\Http\Controllers\Dashboard\Auth\Passowrd\ForgetPasswordController;
use App\Http\Controllers\Dashboard\Auth\Passowrd\ResetPasswordController;
use App\Http\Controllers\Dashboard\StoresController;
use App\Http\Controllers\Dashboard\StoreBankAccountController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\DepartmentsController;
use App\Http\Controllers\Dashboard\StoreCustomersController;
use App\Http\Controllers\Dashboard\StoreTransactionsController;
use App\Http\Controllers\Dashboard\RolesController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\UsersController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale().'/dashboard',
        'as' => 'dashboard.',
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        // ########################################## Auth  #################################################################################
        Route::get('login', [AuthController::class, 'getLogin'])->name('get.login');
        Route::post('login', [AuthController::class, 'postLogin'])->name('post.login');
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        // ########################################## reset password  #######################################################################

        Route::group(['prefix' => 'password', 'as' => 'password.'], function () {
            Route::controller(ForgetPasswordController::class)->group(function () {
                Route::get('email', 'showEmailForm')->name('get.email');
                Route::post('email', 'sendOTP')->name('post.email');
                Route::get('verify/{email?}', 'showVerifyOTPForm')->name('verify');
                Route::post('verify', 'verifyOTP')->name('post.verify');
            });

            Route::controller(ResetPasswordController::class)->group(function () {
                Route::get('reset/{email?}', 'showResetFrom')->name('reset');
                Route::post('reset', 'resetPasword')->name('post.reset');
            });
        });

        // ########################################## protected routes  #####################################################################
        Route::group(['middleware' => ['auth:web', 'checkLockScreen']], function () {
            // ########################################## Auth Protected ####################################################################
            Route::get('lock-screen', [AuthController::class, 'lockScreen'])->name('lock.screen');
            Route::post('unlock-screen', [AuthController::class, 'unlock'])->name('unlock.screen');
            Route::get('unlock-screen', function () {
                return redirect()->route('dashboard.lock.screen');
            });
            Route::get('keep-alive', function () {
                return response()->json(['status' => 'alive', 'time' => now()]);
            })->name('keep.alive');
            // ########################################## welcome  ##########################################################################
            Route::get('/welcome', [DashboardController::class, 'index'])->name('index');
            // ########################################## roles routes ######################################################################
            Route::group(['middleware' => 'can:roles_read'], function () {
                Route::resource('roles', RolesController::class);
                Route::post('/roles/destroy', [RolesController::class, 'destroy'])->name('roles.destroy');
            });
            // ########################################## users routes  #####################################################################
            Route::group(['middleware' => 'can:users_read'], function () {
                Route::resource('users', UsersController::class);
                Route::post('/users/destroy', [UsersController::class, 'destroy'])->name('users.destroy');
                Route::post('/users/status', [UsersController::class, 'changeStatus'])->name('users.change.status');
            });
            // ########################################## settings routes  ###################################################################
            Route::group(['middleware' => 'can:settings_read'], function () {
                Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
                Route::put('/settings/{id?}', [SettingsController::class, 'update'])->name('settings.update');
            });
            // ########################################## employee routes  #################################################################
            Livewire::setUpdateRoute(function ($handle) {
                return Route::match(['get', 'post'], '/livewire/update', $handle);
            });
            // ########################################## departments routes  ##############################################################
            Route::group(['middleware' => 'can:departments_read'], function () {
                Route::resource('departments', DepartmentsController::class);
                Route::post('/departments/destroy', [DepartmentsController::class, 'destroy'])->name('departments.destroy');
                Route::post('/departments/status', [DepartmentsController::class, 'changeStatus'])->name('departments.change.status');
            });
            // ########################################## store customers routes  ##############################################################
            Route::group(['middleware' => 'can:store_customers_read'], function () {
                Route::get('/store-customers/by-store', [StoreCustomersController::class, 'getByStore'])->name('store-customers.by-store');
                Route::resource('store-customers', StoreCustomersController::class);
                Route::post('/store-customers/destroy', [StoreCustomersController::class, 'destroy'])->name('store-customers.destroy');
                Route::post('/store-customers/status', [StoreCustomersController::class, 'changeStatus'])->name('store-customers.change.status');
            });
            // ########################################## store transactions routes  ##############################################################
            Route::group(['middleware' => 'can:store_transactions_read'], function () {
                Route::resource('store-transactions', StoreTransactionsController::class);
                Route::post('/store-transactions/destroy', [StoreTransactionsController::class, 'destroy'])->name('store-transactions.destroy');
            });
            // ########################################## stores routes  ##############################################################
            Route::group(['middleware' => 'can:stores_read'], function () {
                Route::resource('stores', StoresController::class);
                Route::post('/stores/destroy', [StoresController::class, 'destroy'])->name('stores.destroy');
                Route::post('/stores/status', [StoresController::class, 'updateStatus'])->name('stores.status');
                Route::get('/stores-autocomplete', [StoresController::class, 'autocomplete'])->name('stores.autocomplete');
            });
            // ########################################## bank accounts routes #############################################################
            Route::group(['middleware' => 'can:bank_accounts_read'], function () {
                Route::resource('bank-accounts', StoreBankAccountController::class);
                Route::post('/bank-accounts/destroy', [StoreBankAccountController::class, 'destroy'])->name('bank-accounts.destroy');
            });

            // ########################################## notifications #############################################################
            Route::group(['middleware' => 'can:notifications_read'], function () {
                Route::get('/notifications', \App\Livewire\NotificationCenter::class)->name('notifications');
                Route::get('/notifications/{id}/redirect', [\App\Http\Controllers\Dashboard\NotificationController::class, 'redirect'])->name('notifications.redirect');
            });
        });
    },
);
