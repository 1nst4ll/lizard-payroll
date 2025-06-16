<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic Information
        'employee_id',
        'first_name',
        'last_name',
        'nickname',
        'gender',
        'date_of_birth',
        'phone_number',
        'email_address',
        'address',
        'passport_number',
        
        // Legal Status
        'status',
        'status_document_type',
        
        // Work Permit Details
        'work_permit_card_number',
        'work_permit_card_expiry',
        'work_permit_first_receipt_number',
        'work_permit_first_receipt_expiry',
        'work_permit_second_receipt_number',
        'work_permit_second_receipt_expiry',
        
        // Other Document Details
        'resident_permit_number',
        'resident_permit_expiry',
        'permanent_resident_certificate_number',
        'naturalization_certificate_number',
        'botc_passport_number',
        'status_card_number',
        
        // Government Contributions
        'nib_number',
        'nib_deduction',
        'nhib_number',
        'nhib_deduction',
        
        // Payment Information
        'payment_method',
        'bank_account_number',
        'bank_routing_number',
        
        // Employment Details
        'starting_date',
        'contract_type',
        'hourly_rate',
        'salary_amount',
        'contract_signed',
        'uniform_size',
        
        // Department and Role
        'department',
        'position',
        
        // Status and Activity
        'employment_status',
        'termination_date',
        'termination_reason',
        
        // Metadata
        'additional_data',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'work_permit_card_expiry' => 'date',
        'work_permit_first_receipt_expiry' => 'date',
        'work_permit_second_receipt_expiry' => 'date',
        'resident_permit_expiry' => 'date',
        'starting_date' => 'date',
        'termination_date' => 'date',
        'nib_deduction' => 'decimal:2',
        'nhib_deduction' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'salary_amount' => 'decimal:2',
        'contract_signed' => 'boolean',
        'additional_data' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passport_number',
        'bank_account_number',
        'bank_routing_number',
        'nib_number',
        'nhib_number',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get all time entries for this employee
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get all work pairs for this employee
     */
    public function workPairs(): HasMany
    {
        return $this->hasMany(WorkPair::class);
    }

    /**
     * Get all payroll records for this employee
     */
    public function payrollRecords(): HasMany
    {
        return $this->hasMany(PayrollRecord::class);
    }

    // ===== ACCESSORS =====

    /**
     * Get the employee's full name
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name . ' ' . $this->last_name)
        );
    }

    /**
     * Get the employee's display name (nickname or full name)
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nickname ?: $this->full_name
        );
    }

    /**
     * Get the employee's age
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date_of_birth ? $this->date_of_birth->age : null
        );
    }

    /**
     * Get years of service
     */
    protected function yearsOfService(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->starting_date ? $this->starting_date->diffInYears(now()) : null
        );
    }

    /**
     * Check if employee is eligible for NIB
     */
    protected function isNibEligible(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->status, ['citizen', 'belonger', 'resident'])
        );
    }

    /**
     * Check if employee is eligible for NHIB
     */
    protected function isNhibEligible(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->status, ['citizen', 'belonger', 'resident'])
        );
    }

    /**
     * Check if work permit is expiring soon (within 30 days)
     */
    protected function isWorkPermitExpiringSoon(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status !== 'work_permit_holder') {
                    return false;
                }

                $expiryDate = $this->work_permit_card_expiry 
                    ?: $this->work_permit_second_receipt_expiry 
                    ?: $this->work_permit_first_receipt_expiry;

                return $expiryDate && $expiryDate->diffInDays(now()) <= 30;
            }
        );
    }

    /**
     * Check if any documents are expired
     */
    protected function hasExpiredDocuments(): Attribute
    {
        return Attribute::make(
            get: function () {
                $today = now();
                
                return ($this->work_permit_card_expiry && $this->work_permit_card_expiry < $today) ||
                       ($this->work_permit_first_receipt_expiry && $this->work_permit_first_receipt_expiry < $today) ||
                       ($this->work_permit_second_receipt_expiry && $this->work_permit_second_receipt_expiry < $today) ||
                       ($this->resident_permit_expiry && $this->resident_permit_expiry < $today);
            }
        );
    }

    // ===== SCOPES =====

    /**
     * Scope to get only active employees
     */
    public function scopeActive($query)
    {
        return $query->where('employment_status', 'active');
    }

    /**
     * Scope to get employees by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Scope to get employees by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get hourly employees
     */
    public function scopeHourly($query)
    {
        return $query->where('contract_type', 'hourly');
    }

    /**
     * Scope to get salaried employees
     */
    public function scopeSalaried($query)
    {
        return $query->where('contract_type', 'salary');
    }

    /**
     * Scope to get employees with expiring work permits
     */
    public function scopeExpiringWorkPermits($query, $days = 30)
    {
        return $query->where('status', 'work_permit_holder')
                    ->where(function ($q) use ($days) {
                        $q->whereDate('work_permit_card_expiry', '<=', now()->addDays($days))
                          ->orWhereDate('work_permit_first_receipt_expiry', '<=', now()->addDays($days))
                          ->orWhereDate('work_permit_second_receipt_expiry', '<=', now()->addDays($days));
                    });
    }

    /**
     * Scope to get employees eligible for government contributions
     */
    public function scopeGovernmentEligible($query)
    {
        return $query->whereIn('status', ['citizen', 'belonger', 'resident']);
    }

    // ===== BUSINESS LOGIC METHODS =====

    /**
     * Calculate current hourly rate (for both hourly and salaried employees)
     */
    public function getCurrentHourlyRate(): float
    {
        if ($this->contract_type === 'hourly') {
            return (float) $this->hourly_rate;
        }

        if ($this->contract_type === 'salary' && $this->salary_amount) {
            // Assume 2080 hours per year (40 hours/week * 52 weeks)
            return (float) $this->salary_amount / 2080;
        }

        return 0.0;
    }

    /**
     * Calculate overtime rate (1.5x regular rate)
     */
    public function getOvertimeRate(): float
    {
        return $this->getCurrentHourlyRate() * 1.5;
    }

    /**
     * Calculate holiday rate (2x regular rate per TC regulations)
     */
    public function getHolidayRate(): float
    {
        return $this->getCurrentHourlyRate() * 2.0;
    }

    /**
     * Get the employee's current work permit status
     */
    public function getWorkPermitStatus(): array
    {
        if ($this->status !== 'work_permit_holder') {
            return ['status' => 'not_applicable', 'message' => 'Employee is not a work permit holder'];
        }

        $today = now();
        $status = 'unknown';
        $document = null;
        $expiry = null;

        // Check card first
        if ($this->work_permit_card_number && $this->work_permit_card_expiry) {
            $document = 'card';
            $expiry = $this->work_permit_card_expiry;
            $status = $expiry > $today ? 'valid' : 'expired';
        }
        // Then check second receipt
        elseif ($this->work_permit_second_receipt_number && $this->work_permit_second_receipt_expiry) {
            $document = 'second_receipt';
            $expiry = $this->work_permit_second_receipt_expiry;
            $status = $expiry > $today ? 'valid' : 'expired';
        }
        // Finally check first receipt
        elseif ($this->work_permit_first_receipt_number && $this->work_permit_first_receipt_expiry) {
            $document = 'first_receipt';
            $expiry = $this->work_permit_first_receipt_expiry;
            $status = $expiry > $today ? 'valid' : 'expired';
        }

        $daysUntilExpiry = $expiry ? $expiry->diffInDays($today, false) : null;

        return [
            'status' => $status,
            'document' => $document,
            'expiry_date' => $expiry,
            'days_until_expiry' => $daysUntilExpiry,
            'is_expiring_soon' => $daysUntilExpiry !== null && $daysUntilExpiry <= 30 && $daysUntilExpiry >= 0,
            'message' => $this->getWorkPermitStatusMessage($status, $daysUntilExpiry)
        ];
    }

    /**
     * Get work permit status message
     */
    private function getWorkPermitStatusMessage(string $status, ?int $daysUntilExpiry): string
    {
        switch ($status) {
            case 'valid':
                if ($daysUntilExpiry <= 30) {
                    return "Work permit expires in {$daysUntilExpiry} days - renewal required soon";
                }
                return 'Work permit is valid';
            case 'expired':
                return 'Work permit has expired - immediate action required';
            default:
                return 'Work permit status unknown - please update employee records';
        }
    }

    /**
     * Check if employee can work legally
     */
    public function canWorkLegally(): bool
    {
        if (in_array($this->status, ['citizen', 'belonger', 'resident'])) {
            return true;
        }

        if ($this->status === 'work_permit_holder') {
            $permitStatus = $this->getWorkPermitStatus();
            return $permitStatus['status'] === 'valid';
        }

        return false;
    }

    /**
     * Get payment method details
     */
    public function getPaymentMethodDetails(): array
    {
        $details = [
            'method' => $this->payment_method,
            'requires_bank_details' => in_array($this->payment_method, ['cibc', 'scotiabank', 'rbc']),
        ];

        if ($details['requires_bank_details']) {
            $details['bank_name'] = ucfirst($this->payment_method);
            $details['account_number'] = $this->bank_account_number;
            $details['routing_number'] = $this->bank_routing_number;
            $details['is_complete'] = !empty($this->bank_account_number);
        } else {
            $details['is_complete'] = true; // Check payment doesn't require bank details
        }

        return $details;
    }

    /**
     * Format employee for display in lists
     */
    public function toListItem(): array
    {
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'display_name' => $this->display_name,
            'full_name' => $this->full_name,
            'department' => $this->department,
            'position' => $this->position,
            'employment_status' => $this->employment_status,
            'status' => $this->status,
            'contract_type' => $this->contract_type,
            'hourly_rate' => $this->getCurrentHourlyRate(),
            'can_work_legally' => $this->canWorkLegally(),
            'has_expired_documents' => $this->has_expired_documents,
            'is_work_permit_expiring_soon' => $this->is_work_permit_expiring_soon,
        ];
    }

    // ===== VALIDATION METHODS =====

    /**
     * Validate employee data integrity
     */
    public function validateDataIntegrity(): array
    {
        $issues = [];

        // Check required fields
        if (empty($this->employee_id)) $issues[] = 'Employee ID is required';
        if (empty($this->first_name)) $issues[] = 'First name is required';
        if (empty($this->last_name)) $issues[] = 'Last name is required';

        // Check contract type consistency
        if ($this->contract_type === 'hourly' && empty($this->hourly_rate)) {
            $issues[] = 'Hourly rate is required for hourly employees';
        }
        if ($this->contract_type === 'salary' && empty($this->salary_amount)) {
            $issues[] = 'Salary amount is required for salaried employees';
        }

        // Check work permit consistency
        if ($this->status === 'work_permit_holder') {
            $hasWorkPermitInfo = $this->work_permit_card_number || 
                                $this->work_permit_first_receipt_number || 
                                $this->work_permit_second_receipt_number;
            
            if (!$hasWorkPermitInfo) {
                $issues[] = 'Work permit details are required for work permit holders';
            }
        }

        // Check payment method consistency
        if (in_array($this->payment_method, ['cibc', 'scotiabank', 'rbc'])) {
            if (empty($this->bank_account_number)) {
                $issues[] = 'Bank account number is required for bank transfers';
            }
        }

        // Check government eligibility consistency
        if ($this->is_nib_eligible && empty($this->nib_number)) {
            $issues[] = 'NIB number is required for eligible employees';
        }
        if ($this->is_nhib_eligible && empty($this->nhib_number)) {
            $issues[] = 'NHIB number is required for eligible employees';
        }

        return $issues;
    }
}
