<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BusinessSettingController extends Controller
{
    public function show()
    {
        Auth::user()->can('view business settings');

        $settings = BusinessSetting::firstOrCreate([]);
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        Auth::user()->can('edit business settings');

        try {
            $validatedData = $request->validate([
                'business_name' => 'required|string|max:255',
                'business_phone' => 'nullable|string|max:255',
                'business_email' => 'nullable|email|max:255',
                'business_address' => 'nullable|string',
                'time_zone' => 'required|string|max:255',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $settings = BusinessSetting::firstOrCreate([]);
        $settings->update($validatedData);

        return response()->json($settings);
    }
}
