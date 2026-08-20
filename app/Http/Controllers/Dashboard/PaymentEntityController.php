<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\PaymentEntityService;
use App\Http\Requests\Dashboard\PaymentEntityRequest;
use App\Services\TenantService;
use App\Exceptions\DeleteRestrictionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\PaymentEntity;

class PaymentEntityController extends Controller
{
    protected $service;

    public function __construct(PaymentEntityService $service)
    {
        $this->service = $service;
    }

    protected function checkSuperAdminAccess(Request $request = null)
    {
        if (!app(TenantService::class)->isSuperAdmin()) {
            if ($request && ($request->ajax() || $request->wantsJson())) {
                response()->json([
                    'status' => false,
                    'message' => __('general.access_denied_msg')
                ], 403)->send();
                exit;
            }
            abort(403, __('general.access_denied_msg'));
        }
    }

    public function index(Request $request)
    {
        Gate::authorize('payment_entities_read');
        $this->checkSuperAdminAccess($request);

        $isSuperAdmin = true;
        $entities = $this->service->getAll($request);
        $title = __('payment_entities.payment_entities');

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.payment_entities.partials._table', compact('entities', 'isSuperAdmin'))->render();
        }

        return view('dashboard.payment_entities.index', compact('entities', 'title', 'isSuperAdmin'));
    }

    public function store(PaymentEntityRequest $request)
    {
        Gate::authorize('payment_entities_create');
        $this->checkSuperAdminAccess($request);
        
        try {
            $this->service->store($request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.add_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_message'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(PaymentEntityRequest $request, $id)
    {
        Gate::authorize('payment_entities_update');
        $this->checkSuperAdminAccess($request);
        
        try {
            $this->service->update($id, $request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.error_message'),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('payment_entities_delete');
        $this->checkSuperAdminAccess($request);
        
        try {
            $this->service->delete($request->id);
            return response()->json([
                'status' => true,
                'message' => __('general.delete_success_message')
            ]);
        } catch (DeleteRestrictionException $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        Gate::authorize('payment_entities_update');
        $this->checkSuperAdminAccess($request);

        if ($request->ajax()) {
            try {
                $this->service->changeStatus($request->id, $request->statusSwitch);
                return response()->json([
                    'status' => true,
                    'message' => __('general.change_status_success_message'),
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => __('general.change_status_error_message'),
                ], 500);
            }
        }
    }
}
