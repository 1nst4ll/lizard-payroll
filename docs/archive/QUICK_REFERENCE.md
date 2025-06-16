# 🚀 Quick Reference Guide: Lizard Payroll System

## 📋 Essential Information At-a-Glance

### Project Overview
**Name:** Lizard Payroll (Turks Kebab Restaurant Management System)  
**Complexity:** High (8/10) - Enterprise-level restaurant management  
**Timeline:** 16-20 weeks across 6 phases  
**ROI:** $18,725 annual value  
**Success Probability:** 85%  

---

## 🛠️ Technology Stack Quick Setup

### Core Technologies
```bash
# Backend
Laravel 10.5 + PHP 8.2+
PostgreSQL 15.3
Redis (caching)
Laravel Jetstream (authentication)

# Frontend  
Vue 3.3 + TypeScript
Inertia.js 1.3
ShadCN-Vue (components)
Tailwind CSS v4
Ziggy (routing)
Vite (build tool)

# Specialized
Laravel Excel 3.1 (file processing)
Decimal Math 1.2 (financial precision)
Spatie Permissions 5.8 (RBAC)
Carbon 2.65 (date/time)
DomPDF 1.2.2 (PDF generation)
```

### Quick Environment Setup
```bash
# 1. Laravel Project with Jetstream
composer create-project laravel/laravel lizard-payroll
cd lizard-payroll
composer require laravel/jetstream
php artisan jetstream:install inertia --teams

# 2. Key Dependencies
composer require maatwebsite/excel spatie/laravel-permission
composer require barryvdh/laravel-dompdf php-decimal/php-decimal
composer require tightenco/ziggy

# 3. Frontend with TypeScript
npm install -D typescript vue-tsc
npm install -D tailwindcss@next @tailwindcss/typography
npm install ziggy-js
npx shadcn-vue@latest init
npx shadcn-vue@latest add button input table card dialog form

# 4. Database
# Create PostgreSQL database: lizard_payroll
# Update .env with database credentials
php artisan migrate
```

---

## 📊 Database Schema Quick Reference

### Core Tables
```sql
employees          -- Employee master data
time_entries       -- Raw punch data
work_pairs         -- Processed time pairs
pay_periods        -- Bi-weekly pay cycles
payroll_records    -- Final payroll calculations
tip_distributions  -- Tip pool and allocations
bank_accounts      -- Multi-bank configuration
```

### Critical Relationships
```sql
Employee (1) ←→ (M) TimeEntry
Employee (1) ←→ (M) WorkPair  
Employee (1) ←→ (M) PayrollRecord
PayPeriod (1) ←→ (M) PayrollRecord
PayPeriod (1) ←→ (1) TipDistribution
```

---

## 🧮 Critical Business Rules

### Time Processing
```php
// Three-stage pipeline
Stage 1: Preprocessing (obvious corrections)
Stage 2: Smart Interpretation (pattern matching)  
Stage 3: Business Logic (final validation)

// Overtime threshold
OVERTIME_THRESHOLD = 44 hours (TC law)
OVERTIME_MULTIPLIER = 1.5x
```

### Tip Distribution
```php
POS Total
├── Less: Credit Card Processing (4%)
├── Plus: Service Surcharge (30%)  
├── Less: Staff Food ($300)
└── Final Tip Pool
    ├── BOH: 20%
    └── FOH: 80%
```

### Government Compliance
```php
// NIB Contributions
EMPLOYER_RATE = 5.5%
EMPLOYEE_RATE = 6.5%

// NHIP Contributions  
EMPLOYER_RATE = 3.0%
EMPLOYEE_RATE = 3.0%

// Holiday Pay
MULTIPLIER = 2.0x (12 TC holidays)
```

---

## 🔒 Security Essentials

### Must-Have Security Features
```php
// 1. Input Validation
'pay_rate' => 'required|decimal:2|min:0|max:1000'

// 2. Data Encryption
protected $casts = [
    'bank_account' => EncryptedCast::class,
];

// 3. Role-Based Access
$user->hasPermissionTo('payroll.process')

// 4. Audit Logging
AuditLog::create([...all financial changes...]);
```

### OWASP Top 10 Checklist
- [ ] A01: Broken Access Control → RBAC implementation
- [ ] A02: Cryptographic Failures → Data encryption
- [ ] A03: Injection → Input validation/sanitization
- [ ] A04: Insecure Design → Security-first architecture
- [ ] A05: Security Misconfiguration → Secure defaults
- [ ] A06: Vulnerable Components → Regular updates
- [ ] A07: Authentication Failures → Strong auth system
- [ ] A08: Software Integrity → Code signing/verification
- [ ] A09: Logging Failures → Comprehensive audit trail
- [ ] A10: SSRF → Request validation

---

## 🎯 Phase Implementation Priorities

### Phase 1: Foundation (Weeks 1-3)
```bash
✓ Development environment setup
✓ Database schema implementation
✓ Authentication and RBAC
✓ Basic employee management
✓ Testing framework setup
```

### Phase 2: Core Payroll (Weeks 4-6)
```bash
✓ Time clock file upload
✓ Time processing pipeline
✓ Basic payroll calculations
✓ Holiday pay processing
✓ Pay stub generation
```

### Phase 3: Advanced Features (Weeks 7-9)
```bash
✓ Tip distribution system
✓ Management overrides
✓ Pattern recognition
✓ Advanced UI components
✓ Audit logging
```

### Phase 4: Compliance (Weeks 10-12)
```bash
✓ NIB/NHIP integration
✓ Government reporting
✓ Holiday calendar
✓ Enhanced security
✓ Compliance dashboard
```

### Phase 5: Integration (Weeks 13-15)
```bash
✓ Multi-bank payment files
✓ QuickBooks integration
✓ Export systems
✓ File management
✓ Integration testing
```

### Phase 6: Production (Weeks 16-18)
```bash
✓ Comprehensive testing
✓ Security audit
✓ Performance optimization
✓ Production deployment
✓ Documentation and training
```

---

## 💰 Financial Calculation Patterns

### Critical: Use Decimal Math
```php
use Decimal\Decimal;

// ✅ CORRECT - Exact calculations
$pay = (new Decimal($hours))->mul(new Decimal($rate));

// ❌ WRONG - Floating point errors
$pay = $hours * $rate; // DON'T DO THIS!
```

### Payroll Calculation Example
```php
class PayrollCalculator {
    public function calculate(Employee $emp, Collection $workPairs): PayrollRecord {
        $totalHours = $workPairs->sum('net_hours');
        $regularHours = min($totalHours, 44);
        $overtimeHours = max(0, $totalHours - 44);
        
        $regularPay = (new Decimal($regularHours))->mul(new Decimal($emp->hourly_rate));
        $overtimePay = (new Decimal($overtimeHours))
            ->mul(new Decimal($emp->hourly_rate))
            ->mul(new Decimal($emp->overtime_multiplier));
            
        return new PayrollRecord([
            'regular_hours' => $regularHours,
            'overtime_hours' => $overtimeHours,
            'regular_pay' => $regularPay,
            'overtime_pay' => $overtimePay,
            'gross_pay' => $regularPay->add($overtimePay)
        ]);
    }
}
```

---

## 🧪 Testing Strategy Quick Reference

### Test Coverage Targets
```php
Unit Tests:        95% (financial calculations)
Integration Tests: 90% (service layer)  
Feature Tests:     100% (critical workflows)
Browser Tests:     100% (user journeys)
```

### Critical Test Categories
```php
// 1. Financial Calculation Tests
test_calculates_overtime_correctly()
test_tip_distribution_accuracy()
test_government_contribution_calculations()

// 2. Time Processing Tests  
test_handles_overnight_shifts()
test_applies_corrections_correctly()
test_pattern_recognition_accuracy()

// 3. Integration Tests
test_complete_payroll_flow()
test_bank_file_generation()
test_quickbooks_export()

// 4. Security Tests
test_authorization_enforcement()
test_input_validation()
test_audit_logging()
```

---

## 🚨 Common Pitfalls to Avoid

### ❌ **Don't Do This**
```php
// Floating point for money
$total = $hours * $rate; 

// Missing authorization checks
public function updatePayroll() { /* no auth check */ }

// Hardcoded business rules
if ($hours > 40) { /* should be configurable */ }

// Missing audit trails
$record->update($data); // no logging

// Weak input validation
$request->input('pay_rate'); // no validation
```

### ✅ **Do This Instead**
```php
// Exact decimal math
$total = (new Decimal($hours))->mul(new Decimal($rate));

// Proper authorization
$this->authorize('update', $payrollRecord);

// Configurable business rules
if ($hours > config('payroll.overtime_threshold')) { }

// Comprehensive audit logging
$this->auditService->logPayrollChange($record, $changes);

// Strong validation
$request->validate(['pay_rate' => 'required|decimal:2|min:0|max:1000']);
```

---

## 📞 Emergency Reference

### Critical File Locations
```bash
Memory Bank:              docs/memory-bank/
Implementation Plan:      docs/IMPLEMENTATION_PLAN.md
Project Analysis:         docs/PROJECT_ANALYSIS.md
Tech Specifications:      docs/memory-bank/techContext.md
UI/UX Guidelines:         docs/memory-bank/uiuxContext.md
```

### Key Configuration Files
```bash
Environment:              .env
Database Config:          config/database.php
Permissions:              config/permission.php
Services:                 app/Services/
Models:                   app/Models/
Controllers:              app/Http/Controllers/
Vue Components:           resources/js/Components/
```

### Quick Commands
```bash
# Development
php artisan serve
npm run dev

# Testing  
php artisan test
npm run test

# Database
php artisan migrate
php artisan db:seed

# Cache
php artisan cache:clear
php artisan config:clear
```

---

## 🎯 Success Metrics Dashboard

### Technical Targets
- [ ] **Performance:** <2s page load times
- [ ] **Uptime:** 99.9% availability  
- [ ] **Test Coverage:** >90% overall
- [ ] **Security:** Zero critical vulnerabilities

### Business Targets
- [ ] **Processing Time:** <30 minutes for payroll
- [ ] **Accuracy:** 100% calculation precision
- [ ] **User Satisfaction:** >95% rating
- [ ] **Error Rate:** <1% requiring manual correction

### Quality Gates
- [ ] **Code Review:** All changes reviewed
- [ ] **Security Scan:** OWASP validation passed
- [ ] **Performance Test:** Load testing completed
- [ ] **User Testing:** Acceptance criteria met

---

**🚀 Ready to build world-class restaurant management software!**

**Remember:** This is enterprise-level complexity. Take time to understand the business domain, follow security best practices, and maintain comprehensive testing throughout development.

**Questions?** Reference the complete documentation package for detailed guidance.
