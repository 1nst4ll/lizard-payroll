<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use App\Models\BusinessSetting; // Assuming holidays are tied to a business setting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PublicHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('view public holidays');

        $query = PublicHoliday::query();

        if ($request->has('year')) {
            $query->whereYear('holiday_date', $request->year);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->can('create public holidays');

        try {
            $validatedData = $request->validate([
                'holiday_name' => 'required|string|max:255',
                'holiday_date' => 'required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        // Associate with the first business setting found, or create one
        $businessSetting = BusinessSetting::firstOrCreate([]);
        $validatedData['business_id'] = $businessSetting->id;

        $holiday = PublicHoliday::create($validatedData);

        return response()->json($holiday, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Auth::user()->can('view public holidays');

        $holiday = PublicHoliday::findOrFail($id);
        return response()->json($holiday);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Auth::user()->can('edit public holidays');

        $holiday = PublicHoliday::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'holiday_name' => 'sometimes|required|string|max:255',
                'holiday_date' => 'sometimes|required|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $holiday->update($validatedData);

        return response()->json($holiday);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::user()->can('delete public holidays');

        $holiday = PublicHoliday::findOrFail($id);
        $holiday->delete();

        return response()->json(null, 204);
    }
}
