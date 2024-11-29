<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index(Request $request)
    {
        $query = Setting::query();

        // Grup filtresi
        if ($request->has('group')) {
            $query->where('group', $request->group);
        }

        // Sadece public ayarları göster
        if ($request->boolean('public_only', false)) {
            $query->where('is_public', true);
        }

        $settings = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    /**
     * Store a newly created setting.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|unique:settings,key',
            'value' => 'required',
            'group' => 'required|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $setting = Setting::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Setting created successfully',
            'data' => $setting
        ], 201);
    }

    /**
     * Display the specified setting.
     */
    public function show(Setting $setting)
    {
        // Eğer ayar public değilse ve kullanıcı yetkili değilse erişimi engelle
        if (!$setting->is_public && !auth()->user()?->can('view', $setting)) {
            abort(403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $setting
        ]);
    }

    /**
     * Update the specified setting.
     */
    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'value' => 'sometimes|required',
            'group' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'is_public' => 'boolean'
        ]);

        $setting->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Setting updated successfully',
            'data' => $setting
        ]);
    }

    /**
     * Remove the specified setting.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Setting deleted successfully'
        ]);
    }

    /**
     * Get settings by group.
     */
    public function getGroup($group)
    {
        $settings = Setting::group($group);

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    /**
     * Bulk update settings.
     */
    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string|exists:settings,key',
            'settings.*.value' => 'required'
        ]);

        foreach ($validated['settings'] as $setting) {
            Setting::set($setting['key'], $setting['value']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Settings updated successfully'
        ]);
    }
}
