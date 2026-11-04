<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::all();

        $grouped = [];
        $flat = [];

        foreach ($settings as $setting) {
            $val = match ($setting->type) {
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };

            $grouped[$setting->group][$setting->key] = $val;
            $flat[$setting->key] = $val;
        }

        return response()->json([
            'data' => [
                'grouped' => $grouped,
                'settings' => $flat,
            ],
        ]);
    }
}
