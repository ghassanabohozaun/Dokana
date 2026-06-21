<?php

namespace App\Services\Auth;

use App\Notifications\sendOTPNotify;
use App\Repositories\Auth\PasswordRepository;

class PasswordService
{
    /**
     * Create a new class instance.
     */

    protected $passwordRepository;
    // __construct
    public function __construct(PasswordRepository $passwordRepository)
    {
        $this->passwordRepository = $passwordRepository;
    }

    public function getAdminByEmail($email)
    {
        $admin = $this->passwordRepository->getAdminByEmail($email);
        if (!$admin) {
            return false;
        }
        $admin->notify(new sendOTPNotify());
        return $admin;
    }

    public function verifyOTP($data)
    {
        $otp = $this->passwordRepository->verifyOTP($data);
        return $otp->status;
    }

    public function resetPasword($email,$password) {
        return $this->passwordRepository->resetPasword($email,$password);
    }
}
