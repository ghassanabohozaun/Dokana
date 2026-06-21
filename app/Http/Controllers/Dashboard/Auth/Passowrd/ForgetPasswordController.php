<?php

namespace App\Http\Controllers\Dashboard\Auth\Passowrd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ForgetPasswordRequest;
use App\Services\Auth\PasswordService;
use Ichtrojan\Otp\Otp;

class ForgetPasswordController extends Controller
{
    protected $otp2;
    protected $passwordService;

    // __construct
    public function __construct(PasswordService $passwordService)
    {
        $this->otp2 = new Otp();
        $this->passwordService = $passwordService;
    }

    // show Email Form
    public function showEmailForm()
    {
        return view('dashboard.auth.password.email');
    }

    // send OTP
    public function sendOTP(ForgetPasswordRequest $request)
    {
        $admin = $this->passwordService->getAdminByEmail($request->email);

        if (!$admin) {
            return redirect()
                ->back()
                ->withErrors(['error' => __('auth.sorry_email_is_not_registerd')]);
        }

        return redirect()->route('dashboard.password.verify', ['email' => $admin->email]);
    }

    // show Verify OTP Form
    public function showVerifyOTPForm($email)
    {
        return view('dashboard.auth.password.verify', ['email' => $email]);
    }

    //verify OTP
    public function verifyOTP(ForgetPasswordRequest $request)
    {
        $data = $request->only(['email', 'code']);

        $otp = $this->passwordService->verifyOTP($data);

        if (!$otp) {
            return redirect()
                ->back()
                ->withErrors(['error' => __('auth.code_is_invalid')]);
        } else {
            return redirect()->route('dashboard.password.reset', ['email' => $request->email]);
        }
    }
}
