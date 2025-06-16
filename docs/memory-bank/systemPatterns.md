# System Patterns and Architecture

## Core Architecture Pattern

### MVC with Service Layer Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Frontend      │    │   Backend       │    │   Database      │
│   (Vue 3 +      │◄──►│   (Laravel 10.5)│◄──►│   (PostgreSQL   │
│   Inertia.js)   │    │                 │    │   15.3)         │
└─────────────────┘    └─────────────────┘    └─────────────────┘
        │                       │                       │
        │              ┌─────────────────┐              │
        │              │  Service Layer  │              │
        │              │  - PayrollSvc   │              │
        │              │  - TimeClockSvc │              │
        │              │  - TipSvc       │              │
        │              │  - ComplianceSvc│              │
        └──────────────┴─────────────────┴──────────────┘
```

### Database Design Pattern: Domain-Driven Design

**Core Entities:**
- **Employee:** Central entity with relationships to all other domains
- **TimeEntry:** Raw punch data with processing metadata  
- **WorkPair:** Processed time pairs with calculated hours
- **PayPeriod:** Temporal boundary for all payroll operations
- **PayrollRecord:** Final calculated payroll data
- **TipDistribution:** Tip pool calculations and allocations

**Relationship Patterns:**
```sql
Employee (1) ←→ (M) TimeEntry
Employee (1) ←→ (M) WorkPair  
Employee (1) ←→ (M) PayrollRecord
PayPeriod (1) ←→ (M) PayrollRecord
PayPeriod (1) ←→ (1) TipDistribution
TipDistribution (1) ←→ (M) EmployeeTipAllocation
```

## Time Processing Pipeline Pattern

### Three-Stage Processing Architecture
```
Raw Time Data → Stage 1: Preprocessing → Stage 2: Smart Interpretation → Stage 3: Validation → Final Work Pairs
```

**Stage 1: Two-Punch Preprocessing**
- **Pattern:** Data Cleansing Pipeline
- **Purpose:** Handle obvious data issues before interpretation
- **Key Rules:**
  - Identical punches (IN/IN or OUT/OUT) beyond threshold
  - OUT/IN sequence swapping (with overnight protection)
  - Duplicate timestamp detection

**Stage 2: Smart Interpretation** 
- **Pattern:** Rule-Based Expert System
- **Purpose:** Interpret ambiguous single punches using employee patterns
- **Algorithm:** Pattern matching with confidence scoring
- **Fallback:** Manual review queue for low-confidence interpretations

**Stage 3: Final Validation**
- **Pattern:** Business Rule Validation
- **Purpose:** Apply business logic and generate final work pairs
- **Validations:** Business hours, break deductions, overtime calculations

## Service Layer Patterns

### PayrollService Pattern
```php
class PayrollService {
    // Dependencies injected for testability
    private TimeClockService $timeService;
    private ComplianceService $complianceService;
    private TipService $tipService;
    
    // Main orchestration method
    public function processPayPeriod(PayPeriod $period): PayrollResult;
    
    // Atomic calculation methods
    private function calculateRegularHours(Employee $emp, array $workPairs): Decimal;
    private function calculateOvertimeHours(Employee $emp, array $workPairs): Decimal;
    private function calculateHolidayPay(Employee $emp, array $workPairs): Decimal;
}
```

**Key Patterns:**
- **Dependency Injection:** All services injected for testing
- **Single Responsibility:** Each method handles one calculation type
- **Immutable Data:** Use value objects for calculations
- **Transaction Boundaries:** Atomic operations for data consistency

### ComplianceService Pattern
```php
class ComplianceService {
    // Government contribution calculations
    public function calculateNIBContribution(PayrollRecord $record): NIBCalculation;
    public function calculateNHIPContribution(PayrollRecord $record): NHIPCalculation;
    
    // Holiday handling
    public function getHolidaysForPeriod(DateRange $period): Collection;
    public function isHoliday(Carbon $date): bool;
    
    // Validation
    public function validateEmployeeEligibility(Employee $employee): EligibilityStatus;
}
```

## Data Processing Patterns

### Excel Import Pattern
```php
class PunchesImport implements ToModel, WithHeadingRow, WithValidation {
    // Transform raw Excel data to domain objects
    public function model(array $row): ?TimeEntry;
    
    // Validate data before processing
    public function rules(): array;
    
    // Handle processing failures
    public function onFailure(Failure ...$failures);
}
```

**Key Features:**
- **Validation First:** Validate all data before any processing
- **Fail Fast:** Stop processing on critical errors
- **Partial Success:** Handle individual row failures gracefully
- **Audit Trail:** Log all import activities and errors

### Repository Pattern for Complex Queries
```php
interface PayrollRepositoryInterface {
    public function getPayrollForPeriod(int $ppNumber): Collection;
    public function getEmployeeHoursForPeriod(int $employeeId, PayPeriod $period): HoursData;
    public function getTipDistributionData(int $ppNumber): TipData;
}

class PayrollRepository implements PayrollRepositoryInterface {
    // Complex queries with proper joins and indexing
    // Cache frequently accessed data
    // Handle large datasets efficiently
}
```

## Security Patterns

### Role-Based Access Control (RBAC)
```php
// Spatie Permissions integration
class User extends Authenticatable {
    use HasRoles;
    
    // Permission-based authorization
    public function canProcessPayroll(): bool {
        return $this->hasPermissionTo('payroll.process');
    }
    
    // Role-based access
    public function isManager(): bool {
        return $this->hasRole('manager');
    }
}
```

**Security Layers:**
1. **Authentication:** Secure login with session management
2. **Authorization:** Role and permission-based access
3. **Data Protection:** Encryption at rest and in transit
4. **Audit Logging:** Complete trail of all financial operations
5. **Input Validation:** Comprehensive sanitization and validation

### Policy Pattern for Authorization
```php
class PayrollPolicy {
    public function view(User $user, PayrollRecord $record): bool {
        return $user->hasRole('admin') || 
               ($user->hasRole('manager') && $this->isInUserDepartment($user, $record));
    }
    
    public function approve(User $user, PayPeriod $period): bool {
        return $user->hasRole('admin') && $period->status === 'processing';
    }
}
```

## Performance Patterns

### Database Optimization
```sql
-- Proper indexing for time-based queries
CREATE INDEX idx_employee_datetime ON time_entries(employee_id, datetime);
CREATE INDEX idx_employee_date ON work_pairs(employee_id, work_date);
CREATE INDEX idx_payperiod_employee ON payroll_records(pp_number, employee_id);

-- Partitioning for large time series data
PARTITION BY RANGE (YEAR(datetime)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026)
);
```

### Caching Strategy
```php
class PayrollService {
    // Cache expensive calculations
    public function getEmployeeHoursSummary(int $employeeId, PayPeriod $period) {
        return Cache::remember(
            "employee_hours_{$employeeId}_{$period->pp_number}",
            3600, // 1 hour
            fn() => $this->calculateEmployeeHours($employeeId, $period)
        );
    }
}
```

## Error Handling Patterns

### Comprehensive Error Handling
```php
class TimeClockService {
    public function processTimeData(array $timeEntries): ProcessingResult {
        try {
            DB::beginTransaction();
            
            $result = $this->processEntries($timeEntries);
            
            if ($result->hasErrors()) {
                DB::rollback();
                return $result;
            }
            
            DB::commit();
            return $result;
            
        } catch (ValidationException $e) {
            DB::rollback();
            Log::error('Time data validation failed', ['error' => $e->getMessage()]);
            throw $e;
            
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Time processing failed', ['error' => $e->getMessage()]);
            throw new TimeProcessingException('Failed to process time data', 0, $e);
        }
    }
}
```

## Testing Patterns

### Service Testing Strategy
```php
class PayrollServiceTest extends TestCase {
    // Use factories for test data
    public function test_calculates_overtime_correctly() {
        $employee = Employee::factory()->hourly()->create(['hourly_rate' => 20]);
        $workPairs = WorkPair::factory()->count(3)->create([
            'employee_id' => $employee->id,
            'net_hours' => 16 // Total 48 hours
        ]);
        
        $result = $this->payrollService->calculatePayroll($employee, $workPairs);
        
        $this->assertEquals(44 * 20, $result->regular_pay); // 44 hours at $20
        $this->assertEquals(4 * 30, $result->overtime_pay); // 4 hours at $30 (1.5x)
    }
}
```

### Integration Testing
```php
class PayrollProcessingTest extends TestCase {
    use RefreshDatabase;
    
    public function test_complete_payroll_processing_flow() {
        // Test the entire flow from time data upload to final payroll
        $this->uploadTimeData()
             ->processPayPeriod()
             ->assertPayrollCalculated()
             ->assertTipsDistributed()
             ->assertComplianceCalculated();
    }
}
```

## Integration Patterns

### QuickBooks Export Pattern
```php
class QuickBooksExporter {
    public function export(PayPeriod $period): ExportResult {
        $entries = $this->formatForQuickBooks(
            $this->payrollRepository->getPayrollForPeriod($period->pp_number)
        );
        
        return $this->createExportFile($entries);
    }
    
    private function formatForQuickBooks(Collection $payrollRecords): array {
        // Transform domain data to QuickBooks format
        // Handle wage and tip entries separately
        // Apply proper account mapping
    }
}
```

### Multi-Bank Payment Pattern
```php
class PaymentDistributor {
    public function distributePayments(PayPeriod $period): DistributionResult {
        $payments = $this->groupPaymentsByBank(
            $this->getPaymentData($period)
        );
        
        foreach ($payments as $bankCode => $bankPayments) {
            $this->generatePaymentFile($bankCode, $bankPayments);
        }
        
        return new DistributionResult($payments);
    }
}
```