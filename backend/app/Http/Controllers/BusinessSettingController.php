<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BusinessSettingController extends Controller
{
    /**
     * Show the form for editing the business settings.
     */
    public function edit()
    {
        $this->authorize('view business settings');

        // Use firstOrNew to avoid creating an empty record in the database.
        // This will retrieve the first record, or instantiate a new empty model if none exists.
        $settings = BusinessSetting::firstOrNew([]);

        return inertia('BusinessSettings/Edit', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update the business settings in storage.
     */
    public function update(Request $request)
    {
        $this->authorize('edit business settings');

        $validatedData = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'time_zone' => 'required|string|max:255',
        ]);

        // Use updateOrCreate to handle both creation and updates atomically.
        // The first array is the condition to find the record (empty means it will find the first or create a new one).
        // The second array is the data to update or create with.
        BusinessSetting::updateOrCreate([], $validatedData);

        return redirect()->route('settings.business.edit')->with('message', 'Business settings updated successfully.');
    }
}
