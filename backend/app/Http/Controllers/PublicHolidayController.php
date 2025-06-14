<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use App\Models\BusinessSetting; // Assuming holidays are tied to a business setting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class PublicHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view public holidays');

        $query = PublicHoliday::query();

        if ($request->has('year')) {
            $query->whereYear('holiday_date', $request->year);
        }

        // This should return an Inertia view, not JSON, for a web route.
        // Assuming you have a 'PublicHolidays/Index' Vue component.
        return inertia('PublicHolidays/Index', [
            'holidays' => $query->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create public holidays');

        return inertia('PublicHolidays/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create public holidays');

        $validatedData = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'holiday_date' => 'required|date',
        ]);

        $businessSetting = BusinessSetting::first();

        if (!$businessSetting) {
            throw ValidationException::withMessages([
                'business_setting' => 'No business setting found. Please configure business settings first.',
            ]);
        }

        $validatedData['business_id'] = $businessSetting->id;

        PublicHoliday::create($validatedData);

        return Redirect::route('holidays.index')->with('message', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PublicHoliday $holiday)
    {
        $this->authorize('view public holidays');

        // This should return an Inertia view, not JSON.
        // Assuming you have a 'PublicHolidays/Show' Vue component.
        return inertia('PublicHolidays/Show', [
            'holiday' => $holiday,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PublicHoliday $holiday)
    {
        $this->authorize('edit public holidays');

        return inertia('PublicHolidays/Edit', [
            'holiday' => $holiday,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PublicHoliday $holiday)
    {
        $this->authorize('edit public holidays');

        $validatedData = $request->validate([
            'holiday_name' => 'sometimes|required|string|max:255',
            'holiday_date' => 'sometimes|required|date',
        ]);

        $holiday->update($validatedData);

        return Redirect::route('holidays.index')->with('message', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PublicHoliday $holiday)
    {
        $this->authorize('delete public holidays');

        $holiday->delete();

        return Redirect::route('holidays.index')->with('message', 'Holiday deleted successfully.');
    }
}
