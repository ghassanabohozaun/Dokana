<?php

use App\Http\Middleware\SetLangMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            // ->prefix('dashboard')
            // ->name('dashboard')

            // web
            Route::middleware('web')->group(base_path('routes/dashboard.php'));


        },
    )
    ->withMiddleware(function (Middleware $middleware) {

        // redirect if not auth
        $middleware->redirectGuestsTo(function () {
            $isCasher = request()->is('*/casher*') || str_contains(request()->header('referer', ''), '/casher');
            
            if ($isCasher) {
                return route('website.casher.login');
            } elseif (request()->is('*/dashboard/*')) {
                return route('dashboard.get.login');
            } elseif (request()->is('*/users/*')) {
                return route('users.get.login');
            } else {
                // Default fallback to login if hitting any other protected route
                return route('dashboard.get.login');
            }
        });

        // redirect if auth
        $middleware->redirectUsersTo(function () {
            if (Auth::guard('casher')->check() && request()->is('*/casher*')) {
                return route('website.casher.notebook');
            } elseif (Auth::guard('admin')->check() || Auth::guard('web')->check()) {
                return route('dashboard.index');
            } else {
                return route('home');
            }
        });

        $middleware->alias([
            /**** OTHER MIDDLEWARE ALIASES ****/
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,

            'checkLockScreen' => \App\Http\Middleware\CheckLockScreen::class,
            //'setLanguage' => SetLangMiddleware::class,
        ]);

        $middleware->api(prepend: [SetLangMiddleware::class]);
        $middleware->web(append: [\App\Http\Middleware\TenantMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
