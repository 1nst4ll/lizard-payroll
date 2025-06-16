<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        $query = Employee::query();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->byDepartment($request->department);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->filled('contract_type')) {
            $query->where('contract_type', $request->contract_type);
        }

        // Default to active employees only
        if (!$request->filled('show_all')) {
            $query->active();
        }

        $employees = $query->orderBy('last_name')
                          ->orderBy('first_name')
                          ->paginate(20)
                          ->withQueryString();

        // Transform employees for frontend
        $employees->getCollection()->transform(function ($employee) {
            return $employee->toListItem();
        });

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
            'filters' => [
                'search' => $request->search,
                'department' => $request->department,
                'status' => $request->status,
                'employment_status' => $request->employment_status,
                'contract_type' => $request->contract_type,
                'show_all' => $request->boolean('show_all'),
            ],
            'departments' => $this->getDepartments(),
            'statuses' => $this->getStatuses(),
            'employmentStatuses' => $this->getEmploymentStatuses(),
            'contractTypes' => $this->getContractTypes(),
        ]);
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        return Inertia::render('Employees/Create', [
            'departments' => $this->getDepartments(),
            'positions' => $this->getPositions(),
            'statuses' => $this->getStatuses(),
            'paymentMethods' => $this->getPaymentMethods(),
            'banks' => $this->getBanks(),
        ]);
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $validated = $this->validateEmployeeData($request);

        $employee = Employee::create($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee
     */
    public function show(Employee $employee)
    {
        // Load relationships
        $employee->load(['timeEntries' => function ($query) {
            $query->latest()->limit(10);
        }, 'payrollRecords' => function ($query) {
            $query->latest()->limit(5);
        }]);

        // Add computed fields
        $employeeData = $employee->toArray();
        $employeeData['work_permit_status'] = $employee->getWorkPermitStatus();
        $employeeData['payment_method_details'] = $employee->getPaymentMethodDetails();
        $employeeData['data_integrity_issues'] = $employee->validateDataIntegrity();
        $employeeData['can_work_legally'] = $employee->canWorkLegally();

        return Inertia::render('Employees/Show', [
            'employee' => $employeeData,
        ]);
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(Employee $employee)
    {
        return Inertia::render('Employees/Edit', [
            'employee' => $employee,
            'departments' => $this->getDepartments(),
            'positions' => $this->getPositions(),
            'statuses' => $this->getStatuses(),
            'paymentMethods' => $this->getPaymentMethods(),
            'banks' => $this->getBanks(),
        ]);
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validateEmployeeData($request, $employee);

        $employee->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee (soft delete)
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }

    /**
     * Restore a soft-deleted employee
     */
    public function restore($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Employee restored successfully.');
    }

    /**
     * Get employees with expiring work permits
     */
    public function expiringWorkPermits()
    {
        $employees = Employee::expiringWorkPermits(30)
                            ->orderBy('work_permit_card_expiry')
                            ->get()
                            ->map(function ($employee) {
                                $data = $employee->toListItem();
                                $data['work_permit_status'] = $employee->getWorkPermitStatus();
                                return $data;
                            });

        return Inertia::render('Employees/ExpiringWorkPermits', [
            'employees' => $employees,
        ]);
    }

    /**
     * Validate employee data
     */
    private function validateEmployeeData(Request $request, ?Employee $employee = null): array
    {
        $employeeIdRule = $employee 
            ? Rule::unique('employees')->ignore($employee->id)
            : Rule::unique('employees');

        return $request->validate([
            // Basic Information
            'employee_id' => ['required', 'string', 'max:50', $employeeIdRule],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'nickname' => ['nullable', 'string', 'max:50'],
            'gender' => ['required', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'email_address' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'passport_number' => ['nullable', 'string', 'max:20'],

            // Legal Status
            'status' => ['required', 'in:citizen,belonger,resident,work_permit_holder'],
            'status_document_type' => ['nullable', 'string', 'max:100'],

            // Work Permit Details (conditional)
            'work_permit_card_number' => ['nullable', 'string', 'max:50'],
            'work_permit_card_expiry' => ['nullable', 'date', 'after:today'],
            'work_permit_first_receipt_number' => ['nullable', 'string', 'max:50'],
            'work_permit_first_receipt_expiry' => ['nullable', 'date', 'after:today'],
            'work_permit_second_receipt_number' => ['nullable', 'string', 'max:50'],
            'work_permit_second_receipt_expiry' => ['nullable', 'date', 'after:today'],

            // Other Document Details
            'resident_permit_number' => ['nullable', 'string', 'max:50'],
            'resident_permit_expiry' => ['nullable', 'date'],
            'permanent_resident_certificate_number' => ['nullable', 'string', 'max:50'],
            'naturalization_certificate_number' => ['nullable', 'string', 'max:50'],
            'botc_passport_number' => ['nullable', 'string', 'max:20'],
            'status_card_number' => ['nullable', 'string', 'max:50'],

            // Government Contributions
            'nib_number' => ['nullable', 'string', 'max:20'],
            'nib_deduction' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'nhib_number' => ['nullable', 'string', 'max:20'],
            'nhib_deduction' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],

            // Payment Information
            'payment_method' => ['required', 'in:check,cibc,scotiabank,rbc'],
            'bank_account_number' => ['nullable', 'string', 'max:30'],
            'bank_routing_number' => ['nullable', 'string', 'max:20'],

            // Employment Details
            'starting_date' => ['required', 'date'],
            'contract_type' => ['required', 'in:hourly,salary'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'salary_amount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'contract_signed' => ['boolean'],
            'uniform_size' => ['nullable', 'string', 'max:10'],

            // Department and Role
            'department' => ['required', 'in:front_of_house,back_of_house,management,administration'],
            'position' => ['required', 'string', 'max:100'],

            // Status
            'employment_status' => ['required', 'in:active,inactive,terminated,on_leave'],
            'termination_date' => ['nullable', 'date'],
            'termination_reason' => ['nullable', 'string', 'max:500'],

            // Additional data
            'additional_data' => ['nullable', 'array'],
        ]);
    }

    /**
     * Get available departments
     */
    private function getDepartments(): array
    {
        return [
            ['value' => 'front_of_house', 'label' => 'Front of House'],
            ['value' => 'back_of_house', 'label' => 'Back of House'],
            ['value' => 'management', 'label' => 'Management'],
            ['value' => 'administration', 'label' => 'Administration'],
        ];
    }

    /**
     * Get available positions based on department
     */
    private function getPositions(): array
    {
        return [
            'front_of_house' => [
                'Server', 'Host/Hostess', 'Bartender', 'Cashier', 'Food Runner'
            ],
            'back_of_house' => [
                'Chef', 'Line Cook', 'Prep Cook', 'Dishwasher', 'Kitchen Assistant'
            ],
            'management' => [
                'General Manager', 'Assistant Manager', 'Shift Supervisor', 'Kitchen Manager'
            ],
            'administration' => [
                'Accountant', 'Office Assistant', 'HR Representative'
            ],
        ];
    }

    /**
     * Get available legal statuses
     */
    private function getStatuses(): array
    {
        return [
            ['value' => 'citizen', 'label' => 'TC Islander (Citizen)'],
            ['value' => 'belonger', 'label' => 'Belonger'],
            ['value' => 'resident', 'label' => 'Permanent Resident'],
            ['value' => 'work_permit_holder', 'label' => 'Work Permit Holder'],
        ];
    }

    /**
     * Get available employment statuses
     */
    private function getEmploymentStatuses(): array
    {
        return [
            ['value' => 'active', 'label' => 'Active'],
            ['value' => 'inactive', 'label' => 'Inactive'],
            ['value' => 'terminated', 'label' => 'Terminated'],
            ['value' => 'on_leave', 'label' => 'On Leave'],
        ];
    }

    /**
     * Get available contract types
     */
    private function getContractTypes(): array
    {
        return [
            ['value' => 'hourly', 'label' => 'Hourly'],
            ['value' => 'salary', 'label' => 'Salary'],
        ];
    }

    /**
     * Get available payment methods
     */
    private function getPaymentMethods(): array
    {
        return [
            ['value' => 'check', 'label' => 'Check'],
            ['value' => 'cibc', 'label' => 'CIBC Bank Transfer'],
            ['value' => 'scotiabank', 'label' => 'Scotiabank Transfer'],
            ['value' => 'rbc', 'label' => 'RBC Bank Transfer'],
        ];
    }

    /**
     * Get available banks for routing
     */
    private function getBanks(): array
    {
        return [
            'cibc' => ['name' => 'CIBC FirstCaribbean', 'routing' => '900000001'],
            'scotiabank' => ['name' => 'Scotiabank', 'routing' => '900000002'],
            'rbc' => ['name' => 'RBC Royal Bank', 'routing' => '900000003'],
        ];
    }
}
