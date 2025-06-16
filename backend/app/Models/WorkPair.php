<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class WorkPair extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'work_date',
        'time_in',
        'time_out',
        'break_minutes',
        'net_hours',
        'regular_hours',
        'overtime_hours',
        'holiday_hours',
        'is_holiday',
        'department',
        'notes',
        'approved_by',
        'approved_at',
        'processing_metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'work_date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'break_minutes' => 'integer',
        'net_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'holiday_hours' => 'decimal:2',
        'is_holiday' => 'boolean',
        'approved_at' => 'datetime',
        'processing_metadata' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the employee that owns this work pair
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ===== SCOPES =====

    /**
     * Scope to get work pairs for a specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to get work pairs for a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    /**
     * Scope to get holiday work pairs
     */
    public function scopeHolidays($query)
    {
        return $query->where('is_holiday', true);
    }

    /**
     * Scope to get approved work pairs
     */
    public function scopeApproved($query)
    {
        return $query->whereNotNull('approved_at');
    }

    /**
     * Scope to get work pairs by department
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    // ===== METHODS =====

    /**
     * Calculate total hours worked
     */
    public function getTotalHours(): float
    {
        return (float) ($this->regular_hours + $this->overtime_hours + $this->holiday_hours);
    }

    /**
     * Check if this work pair has overtime
     */
    public function hasOvertime(): bool
    {
        return $this->overtime_hours > 0;
    }

    /**
     * Get the duration in hours and minutes format
     */
    public function getDurationFormatted(): string
    {
        $totalMinutes = $this->net_hours * 60;
        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;
        
        return sprintf('%d:%02d', $hours, $minutes);
    }
}
