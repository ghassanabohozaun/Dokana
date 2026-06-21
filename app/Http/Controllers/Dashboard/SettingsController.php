<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\SettingRequest;
use App\Services\Dashboard\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display settings for the current store.
     */
    public function index()
    {
        Gate::authorize('settings_read');
        
        $title = __('settings.settings');
        $settings = $this->settingService->getSetting();
        $currencies = \App\Models\Currency::where('is_active', true)->get();

        return view('dashboard.settings.index', compact('title', 'settings', 'currencies'));
    }

    /**
     * Update settings for the current store.
     */
    public function update(SettingRequest $request)
    {
        Gate::authorize('settings_update');
        
        try {
            $data = $request->except(['_token', '_method']);
            $this->settingService->updateSettings($data);

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
}
