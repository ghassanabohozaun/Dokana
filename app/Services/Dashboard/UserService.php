<?php

namespace App\Services\Dashboard;

use App\Repositories\Dashboard\UserRepository;
use App\Utils\ImageManagerUtils;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $userRepository, $imageManagerUtils;

    public function __construct(UserRepository $userRepository, ImageManagerUtils $imageManagerUtils)
    {
        $this->userRepository = $userRepository;
        $this->imageManagerUtils = $imageManagerUtils;
    }

    // get user by id

    public function getUser($id)
    {
        $user = $this->userRepository->getUser($id);
        return $user;
    }

    // get all users by current country
    public function getUsers($request)
    {
        return $this->userRepository->getUsers($request);
    }

    // store user
    public function storeUser($request)
    {
        $data = $request->except(['photo']);
        if ($request->hasFile('photo')) {
            $file_name = $this->imageManagerUtils->uploadSingleImage('', $request->photo, 'users');
            $data['photo'] = $file_name;
        }

        $user = $this->userRepository->storeUser($data);
        return $user;
    }

    // update user
    public function updateUser($request, $id)
    {
        $user = $this->userRepository->getUser($id);
        if (!$user) {
            return false;
        }

        $data = $request->except(['photo', 'delete_photo']);
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                $this->imageManagerUtils->removeImageFromLocal($user->photo, 'users');
            }
            $file_name = $this->imageManagerUtils->uploadSingleImage('', $request->photo, 'users');
            $data['photo'] = $file_name;
        } elseif (isset($request->delete_photo) && $request->delete_photo == 1) {
            if ($user->photo) {
                $this->imageManagerUtils->removeImageFromLocal($user->photo, 'users');
            }
            $data['photo'] = null;
        }

        return $this->userRepository->updateUser($data, $user);
    }

    // destroy user
    public function destroyUser($id)
    {
        $user = $this->userRepository->getUser($id);
        if (!$user) {
            return false;
        }

        if ($user->photo) {
            $this->imageManagerUtils->removeImageFromLocal($user->photo, 'users');
        }

        if ($this->userRepository->destroyUser($user)) {
            return true;
        }
        return false;
    }

    // change user status
    public function changeStatusUser($id, $status)
    {
        $user = $this->userRepository->getUser($id);
        if (!$user) {
            return false;
        }

        return $this->userRepository->changeStatusUser($user, $status);
    }
    public function getStats()
    {
        return $this->userRepository->getStats();
    }
}
