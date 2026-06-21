<?php

namespace App\Http\Controllers\Website\Auth;

use App\Http\Controllers\Controller;
use App\Services\Website\Auth\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    // __construct dependency injection
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Cashier login functions
    public function getCashierLogin()
    {
        return view('website.casher.login');
    }

    public function postCashierLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = $request->only(['mobile', 'password']);
        $remember = $request->has('remember') ? true : false;

        $checkLogin = $this->authService->login($credentials, $remember, 'casher');
        if (!$checkLogin) {
            return redirect()->back()->withErrors(['mobile' => __('auth.login_failed')])->withInput();
        } else {
            // Check if user is disabled
            if (Auth::guard('casher')->user()->status != 1) {
                $this->authService->logout('casher');
                return redirect()->back()->withErrors(['mobile' => __('auth.account_disabled_contact_admin')]);
            }

            session(['is_locked' => false]);
            flash()->success(__('auth.login_success'));

            return redirect()->route('website.casher.notebook');
        }
    }

    public function logoutCashier()
    {
        $this->authService->logout('casher');
        session(['is_locked' => false]);
        return redirect()->route('website.casher.login');
    }
}
