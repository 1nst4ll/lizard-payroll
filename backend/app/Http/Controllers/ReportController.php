<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Punch;
use App\Models\PublicHoliday;
use App\Models\BusinessSetting;
use App\Models\PayrollSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReportController extends Controller
{
    /**
     * Generate an hours report for a specified period and employees.
     */
    public function hours(Request $request)
    {
        Auth::user()->can('view hours report');

        $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'employeeIds' => 'nullable|array',
            'employeeIds.*' => 'uuid|exists:employees,id',
            'departmentId' => 'nullable|uuid|exists:departments,id',
        ]);

        $startDate = Carbon::parse($request->startDate)->startOfDay();
        $endDate = Carbon::parse($request->endDate)->endOfDay();

        $employeesQuery = Employee::query();

        if ($request->has('employeeIds')) {
            $employeesQuery->whereIn('id', $request->employeeIds);
        }

        if ($request->has('departmentId')) {
            $employeesQuery->where('department_id', $request->departmentId);
        }

        // Managers should only see their own department's employees if applicable
        if (Auth::user()->hasRole('manager')) {
            // This logic needs to be refined based on how managers are linked to departments
            // For now, assuming 'view hours report' permission handles access control
        }

        $employees = $employeesQuery->get();
        $publicHolidays = PublicHoliday::whereBetween('holiday_date', [$startDate, $endDate])->get()->keyBy('holiday_date');
        $payrollSettings = PayrollSetting::firstOrCreate([]);
        $businessSettings = BusinessSetting::firstOrCreate([]);

        $reportData = [];

        foreach ($employees as $employee) {
            $employeePunches = Punch::where('employee_id', $employee->id)
                ->whereBetween('punch_time', [$startDate, $endDate])
                ->orderBy('punch_time')
                ->get();

            $weeksData = [];
            $period = CarbonPeriod::create($startDate, '1 day', $endDate);

            $currentWeek = [];
            $weekStartDate = null;

            foreach ($period as $date) {
                if ($weekStartDate === null || $date->dayOfWeek === Carbon::MONDAY) { // Assuming week starts on Monday
                    if ($weekStartDate !== null) {
                        $weeksData[] = $this->calculateWeeklySummary($currentWeek, $payrollSettings, $publicHolidays, $employee);
                    }
                    $currentWeek = [];
                    $weekStartDate = $date->copy();
                }

                $dailyPunches = $employeePunches->filter(function ($punch) use ($date) {
                    return Carbon::parse($punch->punch_time)->isSameDay($date);
                })->sortBy('punch_time');

                $dailySummary = $this->calculateDailySummary($dailyPunches, $date, $payrollSettings, $publicHolidays);
                $currentWeek[] = $dailySummary;
            }
            // Add the last week's data
            if (!empty($currentWeek)) {
                $weeksData[] = $this->calculateWeeklySummary($currentWeek, $payrollSettings, $publicHolidays, $employee);
            }

            $reportData[] = [
                'employee_id' => $employee->id,
                'employee_name' => $employee->first_name . ' ' . $employee->last_name,
                'weeks' => $weeksData,
            ];
        }

        return response()->json($reportData);
    }

    /**
     * Export an hours report as CSV or PDF.
     */
    public function exportHours(Request $request)
    {
        Auth::user()->can('export hours report');

        // This is a placeholder. Real implementation would involve:
        // 1. Calling the hours method to get report data
        // 2. Formatting the data into CSV or PDF
        // 3. Returning the file as a download

        return response()->json([
            'message' => 'Report export initiated. (Placeholder: Export logic needed)',
            'format' => $request->format,
        ], 200);
    }

    /**
     * Helper function to calculate daily summary.
     */
    private function calculateDailySummary($dailyPunches, $date, $payrollSettings, $publicHolidays)
    {
        $checkIn = null;
        $checkOut = null;
        $grossHours = '0h 0m';
        $breakHours = '0h 0m';
        $netHours = '0h 0m';

        // Basic pairing of IN/OUT punches
        $inPunch = $dailyPunches->firstWhere('punch_status', 'IN');
        $outPunch = $dailyPunches->lastWhere('punch_status', 'OUT');

        if ($inPunch && $outPunch) {
            $checkIn = Carbon::parse($inPunch->punch_time);
            $checkOut = Carbon::parse($outPunch->punch_time);

            $grossDuration = $checkOut->diff($checkIn);
            $grossHours = sprintf('%dh %dm', $grossDuration->h, $grossDuration->i);

            $netDuration = $grossDuration;

            // Deduct breaks
            if ($payrollSettings->deduct_breaks) {
                // This is a simplified break deduction. Real logic might be more complex.
                // For example, if gross hours exceed break threshold, deduct break duration.
                $grossHoursInMinutes = $grossDuration->h * 60 + $grossDuration->i;
                $breakThresholdMinutes = $this->parseDurationToMinutes($payrollSettings->break_threshold);

                if ($grossHoursInMinutes >= $breakThresholdMinutes) {
                    $breakDurationMinutes = $this->parseDurationToMinutes($payrollSettings->break_duration);
                    $netDuration->subMinutes($breakDurationMinutes);
                    $breakHours = sprintf('%dh %dm', floor($breakDurationMinutes / 60), $breakDurationMinutes % 60);
                }
            }
            $netHours = sprintf('%dh %dm', $netDuration->h, $netDuration->i);
        }

        return [
            'work_date' => $date->toDateString(),
            'day' => $date->format('l'),
            'check_in' => $checkIn ? $checkIn->format('H:i:s A') : null,
            'check_out' => $checkOut ? $checkOut->format('H:i:s A') : null,
            'gross_hours' => $grossHours,
            'break_hours' => $breakHours,
            'net_hours' => $netHours,
            'is_public_holiday' => $publicHolidays->has($date->toDateString()),
        ];
    }

    /**
     * Helper function to calculate weekly summary.
     */
    private function calculateWeeklySummary($dailySummaries, $payrollSettings, $publicHolidays, $employee)
    {
        $totalNetMinutes = 0;
        $overtimeMinutes = 0;
        $publicHolidayMinutes = 0;

        foreach ($dailySummaries as $day) {
            $netMinutes = $this->parseDurationToMinutes($day['net_hours']);
            $totalNetMinutes += $netMinutes;

            if ($day['is_public_holiday'] && $payrollSettings->pay_public_holiday) {
                // Public holiday hours are typically paid at a different rate, not necessarily added to net hours for overtime calculation
                // This logic needs to be refined based on specific payroll rules
                $publicHolidayMinutes += $netMinutes;
            }
        }

        // Overtime calculation (simplified)
        if ($payrollSettings->pay_overtime) {
            $overtimeThresholdMinutes = $this->parseDurationToMinutes($payrollSettings->overtime_threshold);
            if ($totalNetMinutes > $overtimeThresholdMinutes) {
                $overtimeMinutes = $totalNetMinutes - $overtimeThresholdMinutes;
            }
        }

        return [
            'week_start_date' => $dailySummaries[0]['work_date'], // Assuming first day of week is start
            'week_end_date' => end($dailySummaries)['work_date'], // Assuming last day of week is end
            'daily_punches' => $dailySummaries,
            'weekly_summary' => [
                'total_net_hours' => sprintf('%dh %dm', floor($totalNetMinutes / 60), $totalNetMinutes % 60),
                'overtime_hours' => sprintf('%dh %dm', floor($overtimeMinutes / 60), $overtimeMinutes % 60),
                'public_holiday_hours' => sprintf('%dh %dm', floor($publicHolidayMinutes / 60), $publicHolidayMinutes % 60),
            ],
        ];
    }

    /**
     * Helper function to parse duration string (e.g., "4h 30m", "30min", "44h") to minutes.
     */
    private function parseDurationToMinutes($durationString)
    {
        $minutes = 0;
        if (preg_match('/(\d+)h/', $durationString, $matches)) {
            $minutes += (int)$matches[1] * 60;
        }
        if (preg_match('/(\d+)m/', $durationString, $matches)) {
            $minutes += (int)$matches[1];
        }
        if (preg_match('/(\d+)min/', $durationString, $matches)) {
            $minutes += (int)$matches[1];
        }
        return $minutes;
    }
}
