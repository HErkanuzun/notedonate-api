<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index()
    {
        $settings = Setting::all();
        return SettingResource::collection($settings);
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

        return new SettingResource($setting);
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

    /**
     * Get theme settings
     */
    public function getThemeSettings()
    {
        $themeSettings = Setting::where('group', 'theme')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $themeSettings
        ]);
    }

    /**
     * Update theme settings
     */
    public function updateThemeSettings(Request $request)
    {
        $validated = $request->validate([
            'theme.mode' => 'nullable|string|in:light,dark',
            'theme.primary_color' => 'nullable|string',
            'theme.secondary_color' => 'nullable|string',
            'theme.font_family' => 'nullable|string'
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tema ayarları güncellendi'
        ]);
    }

    /**
     * Get UI settings
     */
    public function getUiSettings()
    {
        $uiSettings = Setting::where('group', 'ui')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $uiSettings
        ]);
    }

    /**
     * Update UI settings
     */
    public function updateUiSettings(Request $request)
    {
        $validated = $request->validate([
            'ui.sidebar_position' => 'nullable|string|in:left,right',
            'ui.show_breadcrumbs' => 'nullable|boolean',
            'ui.items_per_page' => 'nullable|integer|min:5|max:100'
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Arayüz ayarları güncellendi'
        ]);
    }

    /**
     * Get social media settings
     */
    public function getSocialSettings()
    {
        $socialSettings = Setting::where('group', 'social')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $socialSettings
        ]);
    }

    /**
     * Update social media settings
     */
    public function updateSocialSettings(Request $request)
    {
        $validated = $request->validate([
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url'
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sosyal medya ayarları güncellendi'
        ]);
    }

    /**
     * Get SEO settings
     */
    public function getSeoSettings()
    {
        $seoSettings = Setting::where('group', 'seo')
            ->where('is_public', true)
            ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $seoSettings
        ]);
    }

    /**
     * Update SEO settings
     */
    public function updateSeoSettings(Request $request)
    {
        $validated = $request->validate([
            'seo.meta_keywords' => 'nullable|string',
            'seo.meta_description' => 'nullable|string',
            'seo.google_analytics_id' => 'nullable|string'
        ]);

        foreach ($validated as $key => $value) {
            if ($value !== null) {
                Setting::where('key', $key)->update(['value' => $value]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'SEO ayarları güncellendi'
        ]);
    }
}
