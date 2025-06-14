<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\JobRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('view employees');

        $query = Employee::query();

        if ($request->has('departmentId')) {
            $query->where('department_id', $request->departmentId);
        }

        if ($request->has('jobRoleId')) {
            $query->where('job_role_id', $request->jobRoleId);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        }

        // Authorization logic should be handled by policies, not in the controller.
        // The 'view employees' permission should correctly scope the query for the user's role.

        return response()->json($query->with(['department', 'jobRole'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create employees');

        try {
            $validatedData = $request->validate([
                'employee_id' => 'required|string|max:255|unique:employees',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'nickname' => 'nullable|string|max:255',
                'gender' => 'nullable|in:Male,Female,Other',
                'dob' => 'nullable|date',
                'phone_number' => 'nullable|string|max:255',
                'email_address' => 'nullable|email|max:255|unique:employees',
                'address' => 'nullable|string',
                'passport_number' => 'nullable|string|max:255',
                'status' => 'required|in:Work Permit Holder,Resident,Citizen,Belonger',
                'nib_number' => 'nullable|string|max:255',
                'nib_deduction_override' => 'boolean',
                'nhib_number' => 'nullable|string|max:255',
                'nhib_deduction_override' => 'boolean',
                'payment_method' => 'required|in:CIBC,Scotiabank,RBC,Check',
                'starting_date' => 'required|date',
                'contract_type' => 'required|in:Hourly,Salary',
                'rate' => 'required|numeric|min:0',
                'contract_signed' => 'required|boolean',
                'uniform_size' => 'nullable|string|max:255',
                'department_id' => 'nullable|uuid|exists:departments,id',
                'job_role_id' => 'nullable|uuid|exists:job_roles,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $employee = Employee::create($validatedData);

        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view employees');

        $employee = Employee::with(['department', 'jobRole', 'documents'])->findOrFail($id);
        return response()->json($employee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->authorize('edit employees');

        $employee = Employee::findOrFail($id);

        try {
            $validatedData = $request->validate([
                'employee_id' => 'sometimes|required|string|max:255|unique:employees,employee_id,' . $employee->id,
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'nickname' => 'nullable|string|max:255',
                'gender' => 'nullable|in:Male,Female,Other',
                'dob' => 'nullable|date',
                'phone_number' => 'nullable|string|max:255',
                'email_address' => 'nullable|email|max:255|unique:employees,email_address,' . $employee->id,
                'address' => 'nullable|string',
                'passport_number' => 'nullable|string|max:255',
                'status' => 'sometimes|required|in:Work Permit Holder,Resident,Citizen,Belonger',
                'nib_number' => 'nullable|string|max:255',
                'nib_deduction_override' => 'boolean',
                'nhib_number' => 'nullable|string|max:255',
                'nhib_deduction_override' => 'boolean',
                'payment_method' => 'sometimes|required|in:CIBC,Scotiabank,RBC,Check',
                'starting_date' => 'sometimes|required|date',
                'contract_type' => 'sometimes|required|in:Hourly,Salary',
                'rate' => 'sometimes|required|numeric|min:0',
                'contract_signed' => 'sometimes|required|boolean',
                'uniform_size' => 'nullable|string|max:255',
                'department_id' => 'nullable|uuid|exists:departments,id',
                'job_role_id' => 'nullable|uuid|exists:job_roles,id',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        $employee->update($validatedData);

        return response()->json($employee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete employees');

        $employee = Employee::findOrFail($id);
        $employee->delete(); // Consider soft delete for historical data integrity

        return response()->json(null, 204);
    }
}
