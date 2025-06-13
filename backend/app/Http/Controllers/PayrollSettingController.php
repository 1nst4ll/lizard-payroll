<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Models\PayrollSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PayrollSettingController extends Controller
{
    public function show()
    {
        Auth::user()->can('view payroll settings');

        $settings = PayrollSetting::firstOrCreate([]);
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        Auth::user()->can('edit payroll settings');

        try {
            $validatedData = $request->validate([
                'payroll_period' => 'required|in:Weekly,Bi-weekly,Monthly',
                'week_run_from' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'deduct_breaks' => 'required|boolean',
                'break_threshold' => 'nullable|string|max:255',
                'break_duration' => 'nullable|string|max:255',
                'pay_overtime' => 'required|boolean',
                'overtime_threshold' => 'nullable|string|max:255',
                'overtime_ratio' => 'nullable|numeric|min:0',
                'pay_public_holiday' => 'required|boolean',
                'public_holiday_ratio' => 'nullable|numeric|min:0',
                'record_lieu_days' => 'required|boolean',
                'pay_sick_days' => 'required|boolean',
                'sick_days_paid_per_year' => 'nullable|integer|min:0',
                'deduct_days_for_salary_employee' => 'required|boolean',
                'expected_number_of_days' => 'nullable|integer|min:0',
                'deduct_nib' => 'required|boolean',
                'deduct_nhib' => 'required|boolean',
                'add_tips' => 'required|boolean',
                'add_service_charge' => 'required|boolean',
                'service_charge_distribution_percentage' => 'nullable|numeric|min:0|max:100',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        // Ensure business_id is set if it's a new record
        $settings = PayrollSetting::firstOrCreate([]);
        if (!$settings->business_id) {
            // Assuming a default business_id or fetching it from BusinessSetting
            // For now, we'll assume a BusinessSetting record exists and use its ID
            $businessSetting = BusinessSetting::firstOrCreate([]);
            $validatedData['business_id'] = $businessSetting->id;
        }

        $settings->update($validatedData);

        return response()->json($settings);
    }
}
