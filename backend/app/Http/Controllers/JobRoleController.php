<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobRole;
use App\Models\Department; // To ensure department exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class JobRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Auth::user()->can('view job roles');

        $query = JobRole::query();

        if ($request->has('departmentId')) {
            $query->where('department_id', $request->departmentId);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->can('create job roles');

        try {
            $validatedData = $request->validate([
                'department_id' => 'required|uuid|exists:departments,id',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $jobRole = JobRole::create($validatedData);

        return response()->json($jobRole, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Auth::user()->can('view job roles');

        $jobRole = JobRole::findOrFail($id);
        return response()->json($jobRole);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Auth::user()->can('edit job roles');

        $jobRole = JobRole::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'department_id' => 'sometimes|required|uuid|exists:departments,id',
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $jobRole->update($validatedData);

        return response()->json($jobRole);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::user()->can('delete job roles');

        $jobRole = JobRole::findOrFail($id);
        $jobRole->delete();

        return response()->json(null, 204);
    }
}
