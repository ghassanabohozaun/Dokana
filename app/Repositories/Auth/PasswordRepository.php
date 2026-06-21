<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;

class PasswordRepository
{
    /**
     * Create a new class instance.
     */
    protected $otp;
    public function __construct()
    {
        $this->otp = new Otp();
    }

    public function getAdminByEmail($email)
    {
        $admin = User::where('email', $email)->first();
        return $admin;
    }
    public function verifyOTP($data)
    {
        $otp = $this->otp->validate($data['email'], $data['code']);
        return $otp;
    }

    public function resetPasword($email, $password)
    {
        $admin = User::where('email', $email)->first();
        $adminUpdate = $admin->update([
            'password' => bcrypt($password),
        ]);

        return $adminUpdate;
    }
}
