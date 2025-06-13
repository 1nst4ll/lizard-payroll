<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\BusinessSetting; // Assuming departments are tied to a business setting
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('view departments');

        return response()->json(Department::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('create departments');

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:departments',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        // Associate with the first business setting found, or create one with default values
        $businessSetting = BusinessSetting::firstOrCreate(
            [], // Attributes to search for (empty to always create if none exist)
            [
                'business_name' => 'Default Business', // Provide a default value
                'time_zone' => 'UTC', // Provide a default value
            ]
        );
        $validatedData['business_id'] = $businessSetting->id;

        $department = Department::create($validatedData);

        return response()->json($department, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('view departments');

        $department = Department::findOrFail($id);
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('edit departments');

        $department = Department::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|required|string|max:255|unique:departments,name,' . $id,
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $department->update($validatedData);

        return response()->json($department);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }
        Auth::user()->can('delete departments');

        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(null, 204);
    }
}
