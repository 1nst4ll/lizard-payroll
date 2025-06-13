<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Punch;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB; // For transactions

class PunchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Auth::user()->can('view punches');

        $query = Punch::query();

        if ($request->has('employeeId')) {
            $query->where('employee_id', $request->employeeId);
        }

        if ($request->has('startDate')) {
            $query->where('punch_time', '>=', $request->startDate);
        }

        if ($request->has('endDate')) {
            $query->where('punch_time', '<=', $request->endDate . ' 23:59:59'); // Include end of day
        }

        return response()->json($query->with('employee')->orderBy('punch_time')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->can('add punches');

        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|uuid|exists:employees,id',
                'punch_time' => 'required|date',
                'punch_status' => 'required|in:IN,OUT',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $punch = Punch::create($validatedData);

        return response()->json($punch, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Auth::user()->can('view punches');

        $punch = Punch::with('employee')->findOrFail($id);
        return response()->json($punch);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Auth::user()->can('edit punches');

        $punch = Punch::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'punch_time' => 'sometimes|required|date',
                'punch_status' => 'sometimes|required|in:IN,OUT',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $validatedData['is_manual_edit'] = true; // Mark as manually edited
        $validatedData['original_punch_data'] = json_encode($punch->toArray()); // Store original data

        $punch->update($validatedData);

        return response()->json($punch);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::user()->can('delete punches');

        $punch = Punch::findOrFail($id);
        $punch->delete();

        return response()->json(null, 204);
    }

    /**
     * Import raw punch data from a file.
     */
    public function import(Request $request)
    {
        Auth::user()->can('import punches');

        $request->validate([
            'punchFile' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        // This is a placeholder. Real implementation would involve:
        // 1. Parsing the uploaded file (e.g., using Laravel Excel package)
        // 2. Validating each row
        // 3. Creating Punch records in a batch
        // 4. Handling errors and reporting back

        return response()->json([
            'message' => 'Punch data import initiated. (Placeholder: File parsing and processing logic needed)',
            'importedCount' => 0,
            'errors' => [],
        ], 200);
    }

    /**
     * Apply automatic fixes to punch data for a given period.
     */
    public function autofix(Request $request)
    {
        Auth::user()->can('autofix punches');

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'employeeId' => 'nullable|uuid|exists:employees,id',
        ]);

        // This is a placeholder. Real implementation would involve:
        // 1. Fetching punches for the period/employee
        // 2. Implementing auto-fix logic (e.g., pairing IN/OUT, flagging anomalies)
        // 3. Applying fixes and marking punches as is_manual_edit = true
        // 4. Returning a summary of changes

        return response()->json([
            'message' => 'Autofix process initiated. (Placeholder: Logic for fixing punches needed)',
            'summary' => [
                'punches_analyzed' => 0,
                'fixes_suggested' => 0,
                'fixes_applied' => 0,
                'unresolved_issues' => 0,
            ],
            'details' => [],
        ], 200);
    }
}
