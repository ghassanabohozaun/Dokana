<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DepartmentRequest;
use App\Services\Dashboard\DepartmentService;
use App\Services\Dashboard\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\DeleteRestrictionException;

class DepartmentsController extends Controller
{
    protected $departmentService, $storeService;

    public function __construct(DepartmentService $departmentService, StoreService $storeService)
    {
        $this->departmentService = $departmentService;
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        Gate::authorize('departments_read');

        $title = __('departments.departments');
        $departments = $this->departmentService->getAll($request->keyword, $request->store_id);

        $stores = null;
        if (user()->role_id == 1 || user()->id == 1) {
            $stores = $this->storeService->getActiveStoresForDropdown();
        }

        if ($request->ajax()) {
            return view('dashboard.departments.partials._table', compact('departments', 'stores'))->render();
        }

        return view('dashboard.departments.index', compact('title', 'departments', 'stores'));
    }

    public function store(DepartmentRequest $request)
    {
        Gate::authorize('departments_create');

        try {
            $data = $request->only(['name', 'store_id']);
            $this->departmentService->create($data);
            return response()->json(
                [
                    'status' => true,
                    'message' => __('general.add_success_message'),
                ],
                201,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => __('general.add_error_message'),
                ],
                500,
            );
        }
    }

    public function update(DepartmentRequest $request, string $id)
    {
        Gate::authorize('departments_update');

        try {
            $data = $request->only(['id', 'name', 'store_id']);
            $this->departmentService->update($data);
            return response()->json(
                [
                    'status' => true,
                    'message' => __('general.update_success_message'),
                ],
                200,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => false,
                    'message' => __('general.update_error_message'),
                ],
                500,
            );
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('departments_delete');

        if ($request->ajax()) {
            try {
                $this->departmentService->destroy($request->id);
                return response()->json(
                    [
                        'status' => true,
                        'message' => __('general.delete_success_message'),
                    ],
                    200,
                );
            } catch (DeleteRestrictionException $e) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $e->getMessage(),
                    ],
                    422,
                );
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => __('general.delete_error_message'),
                    ],
                    500,
                );
            }
        }
    }

    public function changeStatus(Request $request)
    {
        Gate::authorize('departments_update');

        if ($request->ajax()) {
            try {
                $this->departmentService->changeStatus($request->id, $request->statusSwitch);
                $status = $this->departmentService->getOne($request->id);
                return response()->json(
                    [
                        'status' => true,
                        'message' => __('general.change_status_success_message'),
                        'data' => $status,
                    ],
                    200,
                );
            } catch (\Exception $e) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => __('general.change_status_error_message'),
                    ],
                    500,
                );
            }
        }
    }
}
