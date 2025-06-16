# Technical Context: Technology Stack and Implementation Details

## Core Technology Stack

### Backend Framework
**Laravel 12.x (Latest)**
- **Why:** Latest PHP framework with cutting-edge features and performance
- **Key Features:** Enhanced Eloquent ORM, Improved Artisan CLI, Advanced Queue system
- **Extensions:** Laravel Excel, Spatie Permissions, Laravel Jetstream
- **PHP Version:** 8.3+ required for latest performance and security features

### Database System  
**PostgreSQL 16+ (Latest)**
- **Why:** Superior handling of complex queries and financial calculations
- **Key Features:** ACID compliance, Enhanced JSON support, Advanced indexing, Performance improvements
- **Considerations:** Time-series data optimization, Advanced partitioning for large datasets
- **Backup Strategy:** Point-in-time recovery, Daily automated backups

### Frontend Architecture
**Vue 3 + TypeScript + Inertia.js (Latest)**
- **Why:** Server-side rendered SPA with seamless Laravel integration and type safety
- **Benefits:** No API layer needed, SEO friendly, Fast development, Compile-time error checking
- **TypeScript:** Full type safety across the entire frontend, Better IDE support, Enhanced refactoring
- **State Management:** Composition API with built-in reactivity for complex state, Pinia for global state
- **Build Tool:** Vite (default in Laravel 12) for lightning-fast development and optimized builds
- **Routing:** Ziggy for Laravel routes in JavaScript - seamless route sharing with TypeScript definitions

### UI Component System
**ShadCN-Vue (Latest)**
- **Why:** Modern, accessible UI component library designed for Vue 3
- **Key Features:** Radix-based primitives, Full accessibility (WCAG 2.1 AA), Highly customizable
- **Benefits:** Copy-paste components, Consistent design system, Built-in accessibility
- **Integration:** Perfect Tailwind CSS integration, TypeScript support

### CSS Framework
**Tailwind CSS v4 (Latest)**
- **Why:** CSS-first approach with enhanced performance and developer experience
- **Key Features:** Lightning-fast compilation, Enhanced JIT engine, Better tooling
- **Benefits:** Smaller bundle sizes, Improved build times, Advanced CSS features
- **Integration:** Native CSS imports, Better IDE support, Enhanced customization

### Authentication System
**Laravel Jetstream with Inertia & Vue**
- **Why:** Advanced, production-ready authentication scaffolding
- **Key Features:** Two-factor authentication, API tokens, Team management, Session management
- **Benefits:** Security best practices built-in, Profile management, Device tracking
- **Integration:** Perfect Inertia.js integration, Vue 3 components included

### Build and Development Tools
**Vite (Default in Laravel 12)**
- **Why:** Lightning-fast development server and optimized production builds
- **Key Features:** HMR (Hot Module Replacement), ESM-based, Rollup for production
- **Benefits:** Sub-second cold starts, Instant HMR, Optimized bundling
- **Integration:** Laravel 12 default, Perfect Vue 3 support

### JavaScript Routing
**Ziggy (Latest)**
- **Why:** Use Laravel routes in JavaScript with full feature parity
- **Key Features:** Route parameters, Route model binding, Middleware awareness
- **Benefits:** Type-safe routing, No route duplication, Laravel ecosystem integration
- **Integration:** Perfect Inertia.js companion, Vue 3 composables

### PDF Generation
**DomPDF 1.2.2 / Snappy 1.0.7**
- **Primary:** DomPDF for simple pay stubs (pure PHP)
- **Alternative:** Snappy for complex layouts (requires wkhtmltopdf)
- **Templates:** Blade-based with CSS styling
- **Performance:** Background generation for batch processing

### Date/Time Processing
**Carbon 2.65**
- **Purpose:** Complex date calculations and timezone handling
- **Key Features:** Date arithmetic, Timezone conversion, Formatting
- **Integration:** Deep Laravel integration for models and validation

### Decimal Mathematics
**Decimal Math 1.2**
- **Critical Need:** Exact financial calculations (no floating-point errors)
- **Use Cases:** Payroll calculations, Tip distributions, Tax calculations
- **Precision:** Configurable precision for different calculation types

### Authentication & Authorization
**Laravel Jetstream (Latest) + Spatie Permissions 5.8**
- **Jetstream Features:** Complete authentication scaffolding, Team management, Profile management, Two-factor authentication
- **Spatie Permissions:** Role-based access control (RBAC), Permission inheritance, Dynamic role assignment
- **Integration:** Laravel Gates and Policies, Inertia.js frontend components
- **Security:** Built-in 2FA, Session management, API token authentication
- **UI Components:** Pre-built ShadCN-Vue authentication forms and layouts

## Development Environment

### Local Development Setup
**XAMPP Stack**
- **PHP:** 8.2+ with required extensions
- **Apache:** 2.4+ with mod_rewrite enabled
- **MySQL/PostgreSQL:** Both supported for flexibility
- **Tools:** Composer, Node.js 18+, npm

### Required PHP Extensions
```php
// Critical extensions for functionality
ext-pdo_pgsql    // PostgreSQL connection
ext-gd           // Image processing
ext-curl         // HTTP requests
ext-mbstring     // String handling
ext-zip          // File compression
ext-xml          // XML processing
ext-bcmath       // Precise decimal calculations
ext-intl         // Internationalization
```

### PHP Testing Dependencies - ACTUALLY INSTALLED
```json
// composer.json testing dependencies
{
  "require": {
    "php": "^8.2",
    "barryvdh/laravel-dompdf": "^3.1",
    "brick/math": "^0.13.1",
    "inertiajs/inertia-laravel": "^2.0",
    "laravel/framework": "^12.0",
    "laravel/jetstream": "^5.3",
    "laravel/sanctum": "^4.0",
    "laravel/tinker": "^2.10.1",
    "phpoffice/phpspreadsheet": "^4.3",
    "spatie/laravel-permission": "^6.20",
    "tightenco/ziggy": "^2.0"
  },
  "require-dev": {
    "fakerphp/faker": "^1.23",
    "laravel/pail": "^1.2.2",
    "laravel/pint": "^1.13",
    "laravel/sail": "^1.41",
    "mockery/mockery": "^1.6",
    "nunomaduro/collision": "^8.6",
    "pestphp/pest": "^3.8",
    "pestphp/pest-plugin-laravel": "^3.2"
  }
}
```

### Node.js Dependencies - ACTUALLY INSTALLED
```json
{
  "devDependencies": {
    "@inertiajs/vue3": "^2.0",
    "@tailwindcss/forms": "^0.5.7",
    "@tailwindcss/typography": "^0.5.10", 
    "@tailwindcss/vite": "^4.0.0",
    "@types/node": "^24.0.1",
    "@types/ziggy-js": "^1.3.3",
    "@vitejs/plugin-vue": "^5.0.0",
    "@vue/server-renderer": "^3.3.13",
    "autoprefixer": "^10.4.16",
    "axios": "^1.8.2",
    "concurrently": "^9.0.1",
    "laravel-vite-plugin": "^1.2.0",
    "postcss": "^8.4.32",
    "tailwindcss": "^3.4.0",
    "typescript": "^5.8.3",
    "vite": "^6.2.4",
    "vue": "^3.3.13",
    "vue-tsc": "^2.2.10"
  },
  "dependencies": {
    "@vee-validate/zod": "^4.15.1",
    "@vueuse/core": "^13.3.0",
    "class-variance-authority": "^0.7.1",
    "clsx": "^2.1.1",
    "lucide-vue-next": "^0.515.0",
    "reka-ui": "^2.3.1",
    "tailwind-merge": "^3.3.1",
    "tailwindcss-animate": "^1.0.7",
    "vee-validate": "^4.15.1",
    "zod": "^3.25.64"
  }
}
```

## Database Architecture

### Connection Configuration
```php
// .env database settings
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lizard_payroll
DB_USERNAME=postgres
DB_PASSWORD=secure_password

// Connection pooling for performance
DB_POOL_MIN=5
DB_POOL_MAX=50
```

### Performance Optimizations
**Indexing Strategy:**
- Employee-based queries: Composite indexes on (employee_id, date)
- Time-series data: Partitioning by year/quarter
- Financial calculations: Covering indexes for complex joins

**Caching Layers:**
- Query result caching for employee data
- Calculated payroll caching (invalidated on corrections)
- Static data caching (holidays, rates, bank info)

### Backup and Recovery
**Automated Backups:**
- Daily full database backups
- Transaction log shipping for point-in-time recovery
- Encrypted backup storage
- Automated restore testing

## Security Implementation

### Data Protection
**Encryption:**
- Database encryption at rest (PostgreSQL built-in)
- Application-level encryption for sensitive fields
- HTTPS/TLS 1.3 for all communications
- API token encryption

**Input Validation:**
```php
// Laravel validation rules
'employee_id' => 'required|integer|exists:employees,id',
'punch_time' => 'required|date|after:1970-01-01|before:tomorrow',
'pay_rate' => 'required|decimal:2|min:0|max:1000'
```

### Authentication Security
- Strong password policies (Laravel default + custom rules)
- Session security (secure, httpOnly, sameSite)
- CSRF protection on all forms
- Rate limiting on authentication endpoints

### Authorization Layers
```php
// Role hierarchy
Admin > Manager > Supervisor > Employee

// Permission examples
'payroll.process'   // Process payroll for pay period
'employee.manage'   // Create/edit employee records
'timeclock.correct' // Apply time clock corrections
'reports.export'    // Export financial reports
```

## Performance Requirements

### Response Time Targets
- **Page Loads:** <2 seconds (including complex calculations)
- **API Responses:** <500ms for standard operations
- **File Processing:** <30 seconds for 500 employee time entries
- **Report Generation:** <10 seconds for bi-weekly reports

### Scalability Considerations
- **Employee Capacity:** 100+ employees (current: ~50)
- **Concurrent Users:** 10+ simultaneous users
- **Data Volume:** 5+ years of historical payroll data
- **File Upload:** 50MB+ time clock files

### Optimization Strategies
**Frontend:**
- Component lazy loading
- Image optimization and compression
- JavaScript code splitting
- Service worker for offline capability

**Backend:**
- Database query optimization
- Background job processing
- Redis caching for calculated data
- CDN for static assets

## Testing Infrastructure

### Testing Stack
**Backend Testing:**
- **Pest (Latest):** Primary testing framework with elegant syntax for unit and integration tests
- **Laravel Dusk 10.5:** Browser automation tests

**Frontend Testing:**
- **Jest 29.5:** JavaScript unit tests
- **Vue Test Utils:** Component testing
- **Cypress:** End-to-end testing

### Testing Standards
```php
// Required test coverage
Unit Tests:        95%+ for services and models
Integration Tests: 90%+ for controllers and APIs
Feature Tests:     100% for critical financial calculations
Browser Tests:     100% for user workflows

// Pest testing examples (clean, expressive syntax)
it('calculates overtime correctly', function () {
    $employee = Employee::factory()->hourly()->create(['hourly_rate' => 20]);
    $workPairs = WorkPair::factory()->count(3)->create([
        'employee_id' => $employee->id,
        'net_hours' => 16 // Total 48 hours
    ]);
    
    $result = $this->payrollService->calculatePayroll($employee, $workPairs);
    
    expect($result->regular_pay)->toBe(44 * 20) // 44 hours at $20
        ->and($result->overtime_pay)->toBe(4 * 30); // 4 hours at $30 (1.5x)
});

// Pest feature testing with chained expectations
it('processes complete payroll flow correctly')
    ->expect(fn() => $this->uploadTimeData())
    ->and(fn() => $this->processPayPeriod())
    ->and(fn() => $this->assertPayrollCalculated())
    ->and(fn() => $this->assertTipsDistributed())
    ->and(fn() => $this->assertComplianceCalculated());
```

### Pest Configuration
```php
// tests/Pest.php configuration
<?php

uses(Tests\TestCase::class)->in('Feature');
uses(Tests\TestCase::class)->in('Unit');

// Custom expectations for financial calculations
expect()->extend('toBeValidCurrency', function () {
    return $this->toMatch('/^\d+\.\d{2}$/');
});

expect()->extend('toBePositiveAmount', function () {
    return $this->toBeGreaterThan(0);
});

// Global helper functions for payroll testing
function createEmployeeWithRate(float $rate): Employee {
    return Employee::factory()->create(['hourly_rate' => $rate]);
}

function createWorkPairsForHours(Employee $employee, float $hours): Collection {
    return WorkPair::factory()->create([
        'employee_id' => $employee->id,
        'net_hours' => $hours
    ]);
}
```

## Deployment Configuration

### Production Environment
**Server Requirements:**
- **OS:** Ubuntu 22.04 LTS or CentOS 8+
- **PHP:** 8.2+ with OPcache enabled
- **Database:** PostgreSQL 15+ with performance tuning
- **Web Server:** Nginx 1.20+ with SSL/TLS 1.3
- **Memory:** 8GB+ RAM (4GB minimum)
- **Storage:** SSD with 100GB+ available space

### DevOps Pipeline
**Deployment Strategy:**
- Git-based deployment with Laravel Forge or similar
- Zero-downtime deployment with database migrations
- Automated testing before production release
- Rollback capability for failed deployments

**Monitoring:**
- Application performance monitoring (APM)
- Database performance monitoring
- Error tracking and alerting
- Uptime monitoring with 99.9% target

## Integration Requirements

### QuickBooks Integration
**Export Format:** IIF (Intuit Interchange Format)
**Data Mapping:**
- Employee wages → Expense accounts by position
- Tips → Tip Suspense account
- Government contributions → Liability accounts
- Bank payments → Checking account transactions

### Multi-Bank File Formats
**FCIB:** ACH format with specific field requirements
**Scotiabank:** CSV with bank-specific column mapping
**RBC:** Fixed-width format with validation checksums

### Government Reporting
**NIB Reporting:** Monthly contribution reports
**NHIP Reporting:** Quarterly health insurance reports
**Format:** PDF and CSV exports with official templates

## Development Workflow

### Version Control
**Git Strategy:** GitFlow with feature branches
**Branching:** main → develop → feature/bugfix branches
**Code Review:** Required for all production code
**Documentation:** Commit messages following conventional commits

### Development Standards
**Code Style:** PSR-12 for PHP, Prettier for JavaScript
**Documentation:** PhpDoc for all public methods
**Security:** Automated security scanning with tools
**Quality:** Static analysis with PHPStan/Psalm

### Local Development Setup
```bash
# Clone and setup
git clone [repository]
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# Install Pest testing framework
composer require pestphp/pest --dev --with-all-dependencies
composer require pestphp/pest-plugin-laravel --dev
./vendor/bin/pest --init

# Development server
php artisan serve
npm run dev

# Run tests
./vendor/bin/pest
```
