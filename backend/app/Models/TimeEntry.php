<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'datetime',
        'direction',
        'source',
        'raw_data',
        'is_processed',
        'processing_notes',
        'correction_applied',
        'correction_type',
        'original_datetime',
        'confidence_score',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'datetime' => 'datetime',
        'original_datetime' => 'datetime',
        'is_processed' => 'boolean',
        'correction_applied' => 'boolean',
        'confidence_score' => 'decimal:2',
        'raw_data' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the employee that owns this time entry
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // ===== SCOPES =====

    /**
     * Scope to get unprocessed entries
     */
    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    /**
     * Scope to get entries for a specific employee
     */
    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to get entries for a date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('datetime', [$startDate, $endDate]);
    }
}
