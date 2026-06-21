<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StoreRequest;
use App\Services\Dashboard\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\DeleteRestrictionException;
use App\Models\Store;

class StoresController extends Controller
{
    protected $service;

    public function __construct(StoreService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        Gate::authorize('stores_read');
        
        $stores = $this->service->getAll($request);
        $all_stores = (user()->id === 1 || user()->role_id === 1) ? Store::active()->orderByDesc('id')->get() : null;
        $title = __('stores.stores');

        if ($request->ajax() || $request->has('_ajax')) {
            return view('dashboard.stores.partials._table', compact('stores'))->render();
        }

        return view('dashboard.stores.index', compact('stores', 'all_stores', 'title'));
    }

    public function store(StoreRequest $request)
    {
        Gate::authorize('stores_create');
        
        try {
            $this->service->store($request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.add_success_message')
            ]);
        } catch (\Exception $e) {
            \Log::error('Store Creation Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => false,
                'message' => __('general.add_error_message')
            ], 500);
        }
    }

    public function update(StoreRequest $request, $id)
    {
        Gate::authorize('stores_update');
        
        try {
            $this->service->update($id, $request->validated());
            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('general.update_error_message')
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('stores_delete');
        
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
                'message' => __('general.delete_error_message')
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        Gate::authorize('stores_update');
        
        try {
            $this->service->updateStatus($request->id, $request->statusSwitch);
            return response()->json([
                'status' => true,
                'message' => __('general.update_success_message'),
                'data' => ['id' => $request->id, 'status' => $request->statusSwitch]
            ]);
        } catch (\Exception $e) {
            \Log::error('Status Update Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'status' => false,
                'message' => __('general.update_error_message')
            ], 500);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = $this->service->autocomplete($request->get('q'));
        return response()->json($data);
    }
}
