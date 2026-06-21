<?php

namespace App\Repositories\Dashboard;

use App\Models\User;
use App\Traits\Dashboard\HandleAjaxPagination;

class UserRepository
{
    use HandleAjaxPagination;

    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // get user by id
    public function getUser($id)
    {
        return $this->model->find($id);
    }

    // get all users by current country
    public function getUsers($request)
    {
        $query = $this->model
            ->with(['creator', 'role'])
            ->filter($request->only(['keyword', 'store_id']), ['name', 'email'], ['store_id'])
            ->orderByDesc('id');

        return $this->applyAjaxPagination($request, $query);
    }

    // store user
    public function storeUser($data)
    {
        return $this->model->create([
            'store_id' => $data['store_id'] ?? null,
            'role_id' => $data['role_id'],
            'name' => [
                'ar' => $data['name']['ar'],
                'en' => $data['name']['en'],
            ],
            'email' => $data['email'],
            'password' => $data['password'],
            'mobile' => $data['mobile'] ?? null,
            'status' => 1,
            'photo' => $data['photo'] ?? null,
        ]);
    }

    // update user
    public function updateUser($data, $user)
    {
        return $user->update([
            'store_id' => $data['store_id'] ?? $user->store_id,
            'role_id' => $data['role_id'],
            'name' => [
                'ar' => $data['name']['ar'],
                'en' => $data['name']['en'],
            ],
            'email' => $data['email'],
            'password' => empty($data['password']) ? $user->password : $data['password'],
            'mobile' => $data['mobile'] ?? $user->mobile,
            'photo' => $data['photo'] ?? $user->photo,
        ]);
    }

    // delete user
    public function destroyUser($user)
    {
        return $user->delete();
    }

    // change user status
    public function changeStatusUser($user, $status)
    {
        return $user->update(['status' => $status]);
    }
    public function getStats()
    {
        $baseQuery = $this->model->query();

        $total_users = $baseQuery->count();
        $active_users = $this->model->where('status', 1)->count();
        $managers = $this->model->where('role_id', 2)->count();
        $super_users = $this->model->where('role_id', 1)->count();

        return [
            'total_users' => $total_users,
            'active_users' => $active_users,
            'managers' => $managers,
            'super_users' => $super_users,
        ];
    }
}
