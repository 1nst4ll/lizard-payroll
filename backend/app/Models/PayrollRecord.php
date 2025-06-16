<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PayrollRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'pp_number',
        'pay_period_start',
        'pay_period_end',
        'regular_hours',
        'overtime_hours',
        'holiday_hours',
        'total_hours',
        'regular_pay',
        'overtime_pay',
        'holiday_pay',
        'tips_received',
        'gross_pay',
        'nib_contribution',
        'nhib_contribution',
        'other_deductions',
        'total_deductions',
        'net_pay',
        'payment_method',
        'payment_reference',
        'paid_date',
        'status',
        'processed_by',
        'processed_at',
        'notes',
        'calculation_metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'holiday_hours' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'regular_pay' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'holiday_pay' => 'decimal:2',
        'tips_received' => 'decimal:2',
        'gross_pay' => 'decimal:2',
        'nib_contribution' => 'decimal:2',
        'nhib_contribution' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_pay' => 'decimal:2',
        'paid_date' => 'date',
        'processed_at' => 'datetime',
        'calculation_metadata' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the employee that owns this payroll record
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ===== SCOPES =====

    /**
     * Scope to get payroll records for a specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to get payroll records for a specific pay period
     */
    public function scopeForPayPeriod($query, $ppNumber)
    {
        return $query->where('pp_number', $ppNumber);
    }

    /**
     * Scope to get paid records
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to get pending records
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get records by payment method
     */
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // ===== METHODS =====

    /**
     * Calculate gross pay total
     */
    public function calculateGrossPay(): float
    {
        return (float) ($this->regular_pay + $this->overtime_pay + $this->holiday_pay + $this->tips_received);
    }

    /**
     * Calculate total deductions
     */
    public function calculateTotalDeductions(): float
    {
        return (float) ($this->nib_contribution + $this->nhib_contribution + $this->other_deductions);
    }

    /**
     * Calculate net pay
     */
    public function calculateNetPay(): float
    {
        return $this->calculateGrossPay() - $this->calculateTotalDeductions();
    }

    /**
     * Check if record has tips
     */
    public function hasTips(): bool
    {
        return $this->tips_received > 0;
    }

    /**
     * Check if record has overtime
     */
    public function hasOvertime(): bool
    {
        return $this->overtime_hours > 0;
    }

    /**
     * Check if record has holiday pay
     */
    public function hasHolidayPay(): bool
    {
        return $this->holiday_hours > 0;
    }

    /**
     * Get pay period description
     */
    public function getPayPeriodDescription(): string
    {
        return sprintf(
            'PP %d: %s to %s',
            $this->pp_number,
            $this->pay_period_start->format('M d, Y'),
            $this->pay_period_end->format('M d, Y')
        );
    }

    /**
     * Format for pay stub display
     */
    public function toPayStub(): array
    {
        return [
            'employee_name' => $this->employee->full_name,
            'employee_id' => $this->employee->employee_id,
            'pay_period' => $this->getPayPeriodDescription(),
            'earnings' => [
                'regular' => [
                    'hours' => $this->regular_hours,
                    'rate' => $this->employee->getCurrentHourlyRate(),
                    'amount' => $this->regular_pay,
                ],
                'overtime' => [
                    'hours' => $this->overtime_hours,
                    'rate' => $this->employee->getOvertimeRate(),
                    'amount' => $this->overtime_pay,
                ],
                'holiday' => [
                    'hours' => $this->holiday_hours,
                    'rate' => $this->employee->getHolidayRate(),
                    'amount' => $this->holiday_pay,
                ],
                'tips' => $this->tips_received,
                'gross_total' => $this->gross_pay,
            ],
            'deductions' => [
                'nib' => $this->nib_contribution,
                'nhib' => $this->nhib_contribution,
                'other' => $this->other_deductions,
                'total' => $this->total_deductions,
            ],
            'net_pay' => $this->net_pay,
            'payment_info' => [
                'method' => $this->payment_method,
                'reference' => $this->payment_reference,
                'date' => $this->paid_date?->format('M d, Y'),
            ],
        ];
    }
}
