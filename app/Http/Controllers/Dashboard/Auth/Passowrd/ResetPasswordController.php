<?php

namespace App\Http\Controllers\Dashboard\Auth\Passowrd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ResetPasswordRequest;
use App\Services\Auth\PasswordService;
use Illuminate\Support\Facades\Session;

class ResetPasswordController extends Controller
{
    protected $passwordService;
    //__construct
    public function __construct(PasswordService $passwordService)
    {
        $this->passwordService = $passwordService;
    }

    // show Reset From
    public function showResetFrom($email)
    {
        return view('dashboard.auth.password.reset', ['email' => $email]);
    }

    // reset Pasword
    public function resetPasword(ResetPasswordRequest $request)
    {
        $admin = $this->passwordService->resetPasword($request->email, $request->password);
        if (!$admin) {
            Session::flash('error', __('auth.try_again_later'));
            return redirect()->back();
        }

        return redirect()
            ->route('dashboard.get.login')
            ->with(['success' => __('auth.your_password_updated_successfully')]);
    }
}
