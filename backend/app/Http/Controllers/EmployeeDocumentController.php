<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmployeeDocument;
use App\Models\Employee; // To ensure employee exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Auth::user()->can('view employee documents');

        $query = EmployeeDocument::query();

        if ($request->has('employeeId')) {
            $query->where('employee_id', $request->employeeId);
        }

        return response()->json($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Auth::user()->can('create employee documents');

        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|uuid|exists:employees,id',
                'document_type' => 'required|in:Work Permit,Resident Permit,Permanent Resident Certificate,Naturalization Certificate,BOTC Passport,Status Card',
                'card_number' => 'nullable|string|max:255',
                'expiration_date' => 'nullable|date',
                'first_receipt_number' => 'nullable|string|max:255',
                'first_receipt_exp_date' => 'nullable|date',
                'second_receipt_number' => 'nullable|string|max:255',
                'second_receipt_exp_date' => 'nullable|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $document = EmployeeDocument::create($validatedData);

        return response()->json($document, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Auth::user()->can('view employee documents');

        $document = EmployeeDocument::findOrFail($id);
        return response()->json($document);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Auth::user()->can('edit employee documents');

        $document = EmployeeDocument::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'employee_id' => 'sometimes|required|uuid|exists:employees,id',
                'document_type' => 'sometimes|required|in:Work Permit,Resident Permit,Permanent Resident Certificate,Naturalization Certificate,BOTC Passport,Status Card',
                'card_number' => 'nullable|string|max:255',
                'expiration_date' => 'nullable|date',
                'first_receipt_number' => 'nullable|string|max:255',
                'first_receipt_exp_date' => 'nullable|date',
                'second_receipt_number' => 'nullable|string|max:255',
                'second_receipt_exp_date' => 'nullable|date',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $document->update($validatedData);

        return response()->json($document);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::user()->can('delete employee documents');

        $document = EmployeeDocument::findOrFail($id);
        $document->delete();

        return response()->json(null, 204);
    }
}
