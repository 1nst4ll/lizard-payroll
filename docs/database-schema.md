# Payroll Web App: Database Schema

*   **Last Updated:** 2025-06-13 02:00 AM (America/New_York)

This document outlines the proposed database schema for the Payroll Web Application. It details the tables, their columns, data types, relationships, and key constraints. The schema is designed to support the features outlined in [`docs/project-overview.md`](./project-overview.md) and will be implemented using PostgreSQL.

For a high-level overview of the project features, refer to [`docs/project-overview.md`](./project-overview.md).
For UI/UX design guidelines, refer to [`docs/ui_ux_guideline.md`](./ui_ux_guideline.md).
For the chosen technology stack, refer to [`docs/tech-stack.md`](./tech-stack.md).
For API specifications, refer to [`docs/api-spec.md`](./api-spec.md).
For development environment setup, refer to [`docs/dev-environment-setup.md`](./dev-environment-setup.md).
For deployment instructions, refer to [`docs/deployment-guide.md`](./deployment-guide.md).
For testing strategy, refer to [`docs/testing-strategy.md`](./testing-strategy.md).
For security guidelines, refer to [`docs/security-guidelines.md`](./security-guidelines.md).

## Entity-Relationship Diagram (Conceptual)

```mermaid
erDiagram
    BUSINESS_SETTINGS ||--o{ PAYROLL_SETTINGS : "configures"
    BUSINESS_SETTINGS ||--o{ PUBLIC_HOLIDAYS : "defines"
    BUSINESS_SETTINGS ||--o{ DEPARTMENTS : "manages"
    DEPARTMENTS ||--o{ JOB_ROLES : "contains"
    DEPARTMENTS ||--o{ EMPLOYEES : "employs"
    JOB_ROLES ||--o{ EMPLOYEES : "assigns"
    EMPLOYEES ||--o{ PUNCHES : "records"
    EMPLOYEES ||--o{ EMPLOYEE_DOCUMENTS : "has"
    EMPLOYEES ||--o{ PAYROLL_REPORTS : "generates"
    USERS ||--o{ EMPLOYEES : "manages"
    USERS ||--o{ BUSINESS_SETTINGS : "administers"

    BUSINESS_SETTINGS {
        UUID id PK
        VARCHAR business_name
        VARCHAR business_phone
        VARCHAR business_email
        TEXT business_address
        VARCHAR time_zone
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PAYROLL_SETTINGS {
        UUID id PK
        UUID business_id FK
        VARCHAR payroll_period ENUM("Weekly", "Bi-weekly", "Monthly")
        VARCHAR week_run_from ENUM("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday")
        BOOLEAN deduct_breaks
        INTERVAL break_threshold
        INTERVAL break_duration
        BOOLEAN pay_overtime
        INTERVAL overtime_threshold
        DECIMAL overtime_ratio
        BOOLEAN pay_public_holiday
        DECIMAL public_holiday_ratio
        BOOLEAN record_lieu_days
        BOOLEAN pay_sick_days
        INTEGER sick_days_paid_per_year
        BOOLEAN deduct_days_for_salary_employee
        INTEGER expected_number_of_days
        BOOLEAN deduct_nib
        BOOLEAN deduct_nhib
        BOOLEAN add_tips
        BOOLEAN add_service_charge
        DECIMAL service_charge_distribution_percentage
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PUBLIC_HOLIDAYS {
        UUID id PK
        UUID business_id FK
        VARCHAR holiday_name
        DATE holiday_date
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    DEPARTMENTS {
        UUID id PK
        UUID business_id FK
        VARCHAR name
        TEXT description
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    JOB_ROLES {
        UUID id PK
        UUID department_id FK
        VARCHAR title
        TEXT description
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    EMPLOYEES {
        UUID id PK
        VARCHAR employee_id UNIQUE
        VARCHAR first_name
        VARCHAR last_name
        VARCHAR nickname
        VARCHAR gender ENUM("Male", "Female", "Other")
        DATE dob
        VARCHAR phone_number
        VARCHAR email_address UNIQUE
        TEXT address
        VARCHAR passport_number
        VARCHAR status ENUM("Work Permit Holder", "Resident", "Citizen", "Belonger")
        VARCHAR nib_number
        BOOLEAN nib_deduction_override
        VARCHAR nhib_number
        BOOLEAN nhib_deduction_override
        VARCHAR payment_method ENUM("CIBC", "Scotiabank", "RBC", "Check")
        DATE starting_date
        VARCHAR contract_type ENUM("Hourly", "Salary")
        DECIMAL rate
        BOOLEAN contract_signed
        VARCHAR uniform_size
        UUID department_id FK
        UUID job_role_id FK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    EMPLOYEE_DOCUMENTS {
        UUID id PK
        UUID employee_id FK
        VARCHAR document_type ENUM("Work Permit", "Resident Permit", "Permanent Resident Certificate", "Naturalization Certificate", "BOTC Passport", "Status Card")
        VARCHAR card_number
        DATE expiration_date
        VARCHAR first_receipt_number
        DATE first_receipt_exp_date
        VARCHAR second_receipt_number
        DATE second_receipt_exp_date
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PUNCHES {
        UUID id PK
        UUID employee_id FK
        TIMESTAMP punch_time
        VARCHAR punch_status ENUM("IN", "OUT")
        BOOLEAN is_manual_edit DEFAULT FALSE
        TEXT original_punch_data
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PAYROLL_REPORTS {
        UUID id PK
        UUID employee_id FK
        DATE report_start_date
        DATE report_end_date
        JSONB report_data
        TIMESTAMP generated_at
    }

    USERS {
        UUID id PK
        VARCHAR username UNIQUE
        VARCHAR password_hash
        VARCHAR email UNIQUE
        UUID employee_id FK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    ROLES {
        UUID id PK
        VARCHAR name UNIQUE
        VARCHAR guard_name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    PERMISSIONS {
        UUID id PK
        VARCHAR name UNIQUE
        VARCHAR guard_name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }
    MODEL_HAS_ROLES {
        UUID role_id FK
        VARCHAR model_type
        UUID model_id PK FK
    }
    ROLE_HAS_PERMISSIONS {
        UUID permission_id FK
        UUID role_id FK
    }
```

## Table Definitions

### `BUSINESS_SETTINGS`

Stores global business configuration.

| Column             | Type      | Constraints           | Description                               |
| :----------------- | :-------- | :-------------------- | :---------------------------------------- |
| `id`               | `UUID`    | `PRIMARY KEY`         | Unique identifier for the business settings |
| `business_name`    | `VARCHAR` | `NOT NULL`            | Name of the business                      |
| `business_phone`   | `VARCHAR` |                       | Business phone number                     |
| `business_email`   | `VARCHAR` |                       | Business email address                    |
| `business_address` | `TEXT`    |                       | Full business address                     |
| `time_zone`        | `VARCHAR` | `NOT NULL`            | Time zone of the business (IANA format)   |
| `created_at`       | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of creation                     |
| `updated_at`       | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of last update                  |

### `PAYROLL_SETTINGS`

Stores global payroll rules specific to a business.

| Column                               | Type        | Constraints                               | Description                                       |
| :----------------------------------- | :---------- | :---------------------------------------- | :------------------------------------------------ |
| `id`                                 | `UUID`      | `PRIMARY KEY`                             | Unique identifier for payroll settings            |
| `business_id`                        | `UUID`      | `FOREIGN KEY (BUSINESS_SETTINGS.id)`      | Links to the business settings                    |
| `payroll_period`                     | `VARCHAR`   | `NOT NULL`, `ENUM`                        | Weekly, Bi-weekly, or Monthly                     |
| `week_run_from`                      | `VARCHAR`   | `NOT NULL`, `ENUM`                        | Day of the week payroll period starts             |
| `deduct_breaks`                      | `BOOLEAN`   | `NOT NULL`                                | Whether breaks are deducted                       |
| `break_threshold`                    | `INTERVAL`  |                                           | Minimum work duration to trigger a break deduction |
| `break_duration`                     | `INTERVAL`  |                                           | Duration of the deducted break                    |
| `pay_overtime`                       | `BOOLEAN`   | `NOT NULL`                                | Whether overtime is paid                          |
| `overtime_threshold`                 | `INTERVAL`  |                                           | Hours after which overtime applies                |
| `overtime_ratio`                     | `DECIMAL`   |                                           | Overtime pay multiplier (e.g., 1.5)               |
| `pay_public_holiday`                 | `BOOLEAN`   | `NOT NULL`                                | Whether public holidays are paid                  |
| `public_holiday_ratio`               | `DECIMAL`   |                                           | Public holiday pay multiplier (e.g., 2.0)         |
| `record_lieu_days`                   | `BOOLEAN`   | `NOT NULL`                                | Record lieu days for salary employees on holidays |
| `pay_sick_days`                      | `BOOLEAN`   | `NOT NULL`                                | Whether sick days are paid                        |
| `sick_days_paid_per_year`            | `INTEGER`   |                                           | Number of sick days paid per year                 |
| `deduct_days_for_salary_employee`    | `BOOLEAN`   | `NOT NULL`                                | Deduct days for salary employees                  |
| `expected_number_of_days`            | `INTEGER`   |                                           | Expected work days for salary employees           |
| `deduct_nib`                         | `BOOLEAN`   | `NOT NULL`                                | Whether NIB is deducted                           |
| `deduct_nhib`                        | `BOOLEAN`   | `NOT NULL`                                | Whether NHIB is deducted                          |
| `add_tips`                           | `BOOLEAN`   | `NOT NULL`                                | Whether tips are added                            |
| `add_service_charge`                 | `BOOLEAN`   | `NOT NULL`                                | Whether service charge is added                   |
| `service_charge_distribution_percentage` | `DECIMAL` |                                           | Percentage of service charge for distribution     |
| `created_at`                         | `TIMESTAMP` | `DEFAULT NOW()`                           | Timestamp of creation                             |
| `updated_at`                         | `TIMESTAMP` | `DEFAULT NOW()`                           | Timestamp of last update                          |

### `PUBLIC_HOLIDAYS`

Stores a list of public holidays.

| Column         | Type      | Constraints                          | Description                       |
| :------------- | :-------- | :----------------------------------- | :-------------------------------- |
| `id`           | `UUID`    | `PRIMARY KEY`                        | Unique identifier for the holiday |
| `business_id`  | `UUID`    | `FOREIGN KEY (BUSINESS_SETTINGS.id)` | Links to the business settings    |
| `holiday_name` | `VARCHAR` | `NOT NULL`                           | Name of the public holiday        |
| `holiday_date` | `DATE`    | `NOT NULL`                           | Date of the public holiday        |
| `created_at`   | `TIMESTAMP` | `DEFAULT NOW()`                      | Timestamp of creation             |
| `updated_at`   | `TIMESTAMP` | `DEFAULT NOW()`                      | Timestamp of last update          |

### `DEPARTMENTS`

Stores organizational departments.

| Column      | Type      | Constraints                          | Description                 |
| :---------- | :-------- | :----------------------------------- | :-------------------------- |
| `id`        | `UUID`    | `PRIMARY KEY`                        | Unique identifier for department |
| `business_id` | `UUID`    | `FOREIGN KEY (BUSINESS_SETTINGS.id)` | Links to the business settings |
| `name`      | `VARCHAR` | `NOT NULL`, `UNIQUE`                 | Name of the department      |
| `description` | `TEXT`    |                                      | Description of the department |
| `created_at`  | `TIMESTAMP` | `DEFAULT NOW()`                      | Timestamp of creation       |
| `updated_at`  | `TIMESTAMP` | `DEFAULT NOW()`                      | Timestamp of last update    |

### `JOB_ROLES`

Stores job roles within departments.

| Column        | Type      | Constraints                        | Description               |
| :------------ | :-------- | :--------------------------------- | :------------------------ |
| `id`          | `UUID`    | `PRIMARY KEY`                      | Unique identifier for job role |
| `department_id` | `UUID`    | `FOREIGN KEY (DEPARTMENTS.id)`     | Links to the department   |
| `title`       | `VARCHAR` | `NOT NULL`                         | Title of the job role     |
| `description` | `TEXT`    |                                    | Description of the job role |
| `created_at`  | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of creation     |
| `updated_at`  | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of last update  |

### `EMPLOYEES`

Stores employee details.

| Column                 | Type        | Constraints                               | Description                                   |
| :--------------------- | :---------- | :---------------------------------------- | :-------------------------------------------- |
| `id`                   | `UUID`      | `PRIMARY KEY`                             | Unique identifier for the employee            |
| `employee_id`          | `VARCHAR`   | `NOT NULL`, `UNIQUE`                      | Employee's unique ID (auto-generated/manual)  |
| `first_name`           | `VARCHAR`   | `NOT NULL`                                | Employee's first name                         |
| `last_name`            | `VARCHAR`   | `NOT NULL`                                | Employee's last name                          |
| `nickname`             | `VARCHAR`   |                                           | Employee's nickname                           |
| `gender`               | `VARCHAR`   | `ENUM`                                    | Employee's gender                             |
| `dob`                  | `DATE`      |                                           | Date of birth                                 |
| `phone_number`         | `VARCHAR`   |                                           | Employee's phone number                       |
| `email_address`        | `VARCHAR`   | `UNIQUE`                                  | Employee's email address                      |
| `address`              | `TEXT`      |                                           | Employee's address                            |
| `passport_number`      | `VARCHAR`   |                                           | Employee's passport number                    |
| `status`               | `VARCHAR`   | `NOT NULL`, `ENUM`                        | Employee's legal status                       |
| `nib_number`           | `VARCHAR`   |                                           | National Insurance Board number               |
| `nib_deduction_override` | `BOOLEAN`   | `DEFAULT FALSE`                           | Override global NIB deduction setting         |
| `nhib_number`          | `VARCHAR`   |                                           | National Health Insurance Board number        |
| `nhib_deduction_override` | `BOOLEAN`   | `DEFAULT FALSE`                           | Override global NHIB deduction setting        |
| `payment_method`       | `VARCHAR`   | `NOT NULL`, `ENUM`                        | Preferred payment method                      |
| `starting_date`        | `DATE`      | `NOT NULL`                                | Employee's start date                         |
| `contract_type`        | `VARCHAR`   | `NOT NULL`, `ENUM`                        | Hourly or Salary contract                     |
| `rate`                 | `DECIMAL`   | `NOT NULL`                                | Hourly rate or weekly/monthly salary          |
| `contract_signed`      | `BOOLEAN`   | `NOT NULL`                                | Whether contract is signed                    |
| `uniform_size`         | `VARCHAR`   |                                           | Employee's uniform size                       |
| `department_id`        | `UUID`      | `FOREIGN KEY (DEPARTMENTS.id)`            | Links to the employee's department            |
| `job_role_id`          | `UUID`      | `FOREIGN KEY (JOB_ROLES.id)`              | Links to the employee's job role              |
| `created_at`           | `TIMESTAMP` | `DEFAULT NOW()`                           | Timestamp of creation                         |
| `updated_at`           | `TIMESTAMP` | `DEFAULT NOW()`                           | Timestamp of last update                      |

### `EMPLOYEE_DOCUMENTS`

Stores details of employee status documents.

| Column                     | Type      | Constraints                        | Description                                   |
| :------------------------- | :-------- | :--------------------------------- | :-------------------------------------------- |
| `id`                       | `UUID`    | `PRIMARY KEY`                      | Unique identifier for the document            |
| `employee_id`              | `UUID`    | `FOREIGN KEY (EMPLOYEES.id)`       | Links to the employee                         |
| `document_type`            | `VARCHAR` | `NOT NULL`, `ENUM`                 | Type of status document                       |
| `card_number`              | `VARCHAR` |                                    | Card or document number                       |
| `expiration_date`          | `DATE`    |                                    | Expiration date of the document               |
| `first_receipt_number`     | `VARCHAR` |                                    | First receipt number (for Work Permit)        |
| `first_receipt_exp_date`   | `DATE`    |                                    | Expiration date of first receipt              |
| `second_receipt_number`    | `VARCHAR` |                                    | Second receipt number (for Work Permit)       |
| `second_receipt_exp_date`  | `DATE`    |                                    | Expiration date of second receipt             |
| `created_at`               | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of creation                         |
| `updated_at`               | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of last update                      |

### `PUNCHES`

Stores employee time punches.

| Column             | Type        | Constraints                        | Description                                   |
| :----------------- | :---------- | :--------------------------------- | :-------------------------------------------- |
| `id`               | `UUID`      | `PRIMARY KEY`                      | Unique identifier for the punch               |
| `employee_id`      | `UUID`      | `FOREIGN KEY (EMPLOYEES.id)`       | Links to the employee                         |
| `punch_time`       | `TIMESTAMP` | `NOT NULL`                         | Timestamp of the punch                        |
| `punch_status`     | `VARCHAR`   | `NOT NULL`, `ENUM`                 | IN or OUT punch                               |
| `is_manual_edit`   | `BOOLEAN`   | `NOT NULL`, `DEFAULT FALSE`        | True if punch was manually edited             |
| `original_punch_data` | `TEXT`    |                                    | Stores original data if manually edited       |
| `created_at`       | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of creation                         |
| `updated_at`       | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of last update                      |

### `PAYROLL_REPORTS`

Stores generated payroll reports.

| Column           | Type      | Constraints                        | Description                                   |
| :--------------- | :-------- | :--------------------------------- | :-------------------------------------------- |
| `id`             | `UUID`    | `PRIMARY KEY`                      | Unique identifier for the report              |
| `employee_id`    | `UUID`    | `FOREIGN KEY (EMPLOYEES.id)`       | Links to the employee the report is for       |
| `report_start_date` | `DATE`    | `NOT NULL`                         | Start date of the report period               |
| `report_end_date` | `DATE`    | `NOT NULL`                         | End date of the report period                 |
| `report_data`    | `JSONB`   | `NOT NULL`                         | JSON object containing the full report data   |
| `generated_at`   | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp when the report was generated       |

### `USERS`

Stores user authentication details. User roles and permissions are managed via the `spatie/laravel-permission` package.

| Column          | Type      | Constraints                        | Description                                   |
| :-------------- | :-------- | :--------------------------------- | :-------------------------------------------- |
| `id`            | `UUID`    | `PRIMARY KEY`                      | Unique identifier for the user                |
| `username`      | `VARCHAR` | `NOT NULL`, `UNIQUE`               | User's login username                         |
| `password_hash` | `VARCHAR` | `NOT NULL`                         | Hashed password                               |
| `email`         | `VARCHAR` | `NOT NULL`, `UNIQUE`               | User's email address                          |
| `employee_id`   | `UUID`    | `FOREIGN KEY (EMPLOYEES.id)`       | Links to the employee if the user is an employee |
| `created_at`    | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of creation                         |
| `updated_at`    | `TIMESTAMP` | `DEFAULT NOW()`                    | Timestamp of last update                      |

### `ROLES` (Managed by Spatie Permission)

Stores user roles.

| Column       | Type      | Constraints           | Description             |
| :----------- | :-------- | :-------------------- | :---------------------- |
| `id`         | `UUID`    | `PRIMARY KEY`         | Unique identifier for the role |
| `name`       | `VARCHAR` | `NOT NULL`, `UNIQUE`  | Name of the role (e.g., 'admin', 'manager') |
| `guard_name` | `VARCHAR` | `NOT NULL`            | Authentication guard name |
| `created_at` | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of creation   |
| `updated_at` | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of last update |

### `PERMISSIONS` (Managed by Spatie Permission)

Stores individual permissions.

| Column       | Type      | Constraints           | Description             |
| :----------- | :-------- | :-------------------- | :---------------------- |
| `id`         | `UUID`    | `PRIMARY KEY`         | Unique identifier for the permission |
| `name`       | `VARCHAR` | `NOT NULL`, `UNIQUE`  | Name of the permission (e.g., 'edit employees') |
| `guard_name` | `VARCHAR` | `NOT NULL`            | Authentication guard name |
| `created_at` | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of creation   |
| `updated_at` | `TIMESTAMP` | `DEFAULT NOW()`       | Timestamp of last update |

### `MODEL_HAS_ROLES` (Managed by Spatie Permission)

Pivot table linking users (models) to roles.

| Column       | Type      | Constraints           | Description             |
| :----------- | :-------- | :-------------------- | :---------------------- |
| `role_id`    | `UUID`    | `FOREIGN KEY (ROLES.id)` | ID of the role |
| `model_type` | `VARCHAR` | `NOT NULL`            | Fully qualified class name of the model (e.g., 'App\Models\User') |
| `model_id`   | `UUID`    | `PRIMARY KEY`, `FOREIGN KEY (USERS.id)` | ID of the user (model) |

### `ROLE_HAS_PERMISSIONS` (Managed by Spatie Permission)

Pivot table linking roles to permissions.

| Column          | Type      | Constraints           | Description             |
| :-------------- | :-------- | :-------------------- | :---------------------- |
| `permission_id` | `UUID`    | `FOREIGN KEY (PERMISSIONS.id)` | ID of the permission |
| `role_id`       | `UUID`    | `FOREIGN KEY (ROLES.id)` | ID of the role |
