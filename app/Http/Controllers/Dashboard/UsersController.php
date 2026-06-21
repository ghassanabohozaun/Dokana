<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\UserRequest;
use App\Services\Dashboard\UserService;
use App\Services\Dashboard\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Dashboard\StoreService;
use App\Exceptions\DeleteRestrictionException;

class UsersController extends Controller
{
    protected $userService, $roleService, $storeService;

    public function __construct(UserService $userService, RoleService $roleService, StoreService $storeService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        Gate::authorize('users_read');
        
        $title = __('users.users');
        $users = $this->userService->getUsers($request);
        $roles = $this->roleService->getAllRoles();
        $stores = null;

        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        $stats = $this->userService->getStats();

        if ($request->ajax()) {
            return view('dashboard.users.partials._table', compact('users', 'roles', 'stores'))->render();
        }

        return view('dashboard.users.index', compact('title', 'users', 'roles', 'stores', 'stats'));
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('users_create');
        
        try {
            $user = $this->userService->storeUser($request);
            return response()->json([
                'status' => true, 
                'message' => __('general.add_success_message'),
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.add_error_message')
            ], 500);
        }
    }

    public function update(UserRequest $request, string $id)
    {
        Gate::authorize('users_update');
        
        try {
            $this->userService->updateUser($request, $id);
            $user = $this->userService->getUser($id);
            $photoUrl = $user->userPhoto();

            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message'),
                'data' => $user,
                'photo_url' => $photoUrl
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.update_error_message')
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('users_delete');
        
        if ($request->ajax()) {
            try {
                $this->userService->destroyUser($request->id);
                return response()->json([
                    'status' => true,
                    'message' => __('general.delete_success_message')
                ], 200);
            } catch (DeleteRestrictionException $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ], 422);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.delete_error_message')
                ], 500);
            }
        }
    }

    public function changeStatus(Request $request)
    {
        Gate::authorize('users_update');
        
        try {
            $this->userService->changeStatusUser($request->id, $request->statusSwitch);
            $user = $this->userService->getUser($request->id);
            return response()->json([
                'status' => true, 
                'message' => __('general.change_status_success_message'),
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.change_status_error_message')
            ], 500);
        }
    }
}
