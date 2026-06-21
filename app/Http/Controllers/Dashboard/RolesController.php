<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\RoleRequest;
use App\Services\Dashboard\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\Dashboard\StoreService;
use App\Exceptions\DeleteRestrictionException;

class RolesController extends Controller
{
    protected $roleService, $storeService;

    public function __construct(RoleService $roleService, StoreService $storeService)
    {
        $this->roleService = $roleService;
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        Gate::authorize('roles_read');

        $title = __('roles.roles');
        $roles = $this->roleService->getRoles($request);
        $stores = null;

        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        if ($request->ajax()) {
            return view('dashboard.roles.partials._table', compact('roles', 'stores'))->render();
        }
        return view('dashboard.roles.index', compact('title', 'roles', 'stores'));
    }

    public function create()
    {
        Gate::authorize('roles_create');

        $title = __('roles.create_new_role');

        $stores = null;
        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        return view('dashboard.roles.create', compact('title', 'stores'));
    }

    public function store(RoleRequest $request)
    {
        Gate::authorize('roles_create');

        try {
            $this->roleService->storeRole($request);

            if ($request->ajax()) {
                return response()->json(['status' => true, 'message' => __('general.add_success_message')], 201);
            }

            flash()->success(__('general.add_success_message'));
            return redirect()->route('dashboard.roles.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => __('general.add_error_message')], 500);
            }
            flash()->error(__('general.add_error_message'));
            return redirect()->back();
        }
    }

    public function edit(string $id)
    {
        Gate::authorize('roles_update');

        $title = __('roles.update_role');
        $role = $this->roleService->getRole($id);

        if (!$role) {
            flash()->error(__('general.no_record_found'));
            return redirect()->route('dashboard.roles.index');
        }

        $this->authorize('update', $role);

        $stores = null;
        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        return view('dashboard.roles.edit', compact('role', 'title', 'stores'));
    }

    public function update(RoleRequest $request, string $id)
    {
        $role = $this->roleService->getRole($id);
        if ($role) {
            $this->authorize('update', $role);
        }

        try {
            $this->roleService->updateRole($request, $id);

            if ($request->ajax()) {
                return response()->json(['status' => true, 'message' => __('general.update_success_message')], 200);
            }

            flash()->success(__('general.update_success_message'));
            return redirect()->route('dashboard.roles.index');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => __('general.update_error_message')], 500);
            }
            flash()->error(__('general.update_error_message'));
            return redirect()->back();
        }
    }

    public function destroy(Request $request)
    {
        $role = $this->roleService->getRole($request->id);
        if ($role) {
            $this->authorize('delete', $role);
        }

        if ($request->ajax()) {
            try {
                $this->roleService->destroyRole($request->id);
                return response()->json(['status' => true, 'message' => __('general.delete_success_message')], 200);
            } catch (DeleteRestrictionException $e) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 422);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => __('general.delete_error_message')], 500);
            }
        }
    }
}
