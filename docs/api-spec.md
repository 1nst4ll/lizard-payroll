# Payroll Web App: API Specification

*   **Last Updated:** 2025-06-13 01:59 AM (America/New_York)

This document details the RESTful API endpoints for the Payroll Web Application backend. It provides information on available routes, HTTP methods, request/response formats, authentication requirements, and error handling.

For a high-level overview of the project features, refer to [`docs/project-overview.md`](./project-overview.md).
For UI/UX design guidelines, refer to [`docs/ui_ux_guideline.md`](./ui_ux_guideline.md).
For the chosen technology stack, refer to [`docs/tech-stack.md`](./tech-stack.md).
For database structure, refer to [`docs/database-schema.md`](./database-schema.md).
For development environment setup, refer to [`docs/dev-environment-setup.md`](./dev-environment-setup.md).
For deployment instructions, refer to [`docs/deployment-guide.md`](./deployment-guide.md).
For testing strategy, refer to [`docs/testing-strategy.md`](./testing-strategy.md).
For security guidelines, refer to [`docs/security-guidelines.md`](./security-guidelines.md).

## Base URL

`[Your Backend API Base URL Here, e.g., https://api.payrollapp.com/v1]`

## Authentication

Authentication for the web application is handled primarily by **Laravel Jetstream** (Inertia Stack), which provides session-based authentication for web routes. For API authentication, **Laravel Sanctum** is used, allowing for token-based authentication. **Laravel Socialite** facilitates OAuth integrations (e.g., Google login).

For API requests requiring authentication (e.g., from a mobile app or a separate frontend not using Inertia), a valid **Sanctum API Token** must be included in the `Authorization` header as a Bearer token.

`Authorization: Bearer [YOUR_SANCTUM_TOKEN]`

## Error Responses

Standard error response format:

```json
{
  "statusCode": 400,
  "message": "Bad Request",
  "error": "Validation failed"
}
```

Common status codes:
*   `200 OK`: Request successful.
*   `201 Created`: Resource successfully created.
*   `204 No Content`: Request successful, no content to return.
*   `400 Bad Request`: Invalid request payload or parameters.
*   `401 Unauthorized`: Authentication required or invalid token.
*   `403 Forbidden`: User does not have necessary permissions.
*   `404 Not Found`: Resource not found.
*   `500 Internal Server Error`: Server-side error.

---

## 1. Authentication Endpoints (Managed by Laravel Jetstream/Sanctum)

For web-based authentication, Laravel Jetstream handles login, registration, and session management. For API-based authentication, Laravel Sanctum provides token-based authentication.

### `POST /login` (Web Authentication - Handled by Jetstream)

*   **Description:** User login for web applications. Typically handled by Jetstream's Inertia routes.
*   **Request Body:** Form data with `email` and `password`.
*   **Response:** Redirects on success, or returns validation errors.

### `POST /oauth/google/redirect` (Google OAuth - Handled by Socialite)

*   **Description:** Redirects user to Google for authentication.
*   **Response:** Redirect to Google.

### `GET /oauth/google/callback` (Google OAuth Callback)

*   **Description:** Handles the callback from Google after successful authentication.
*   **Response:** Redirects to the application dashboard or returns an error.

### `POST /sanctum/token` (API Token Issuance - If not using Jetstream's built-in API tokens)

*   **Description:** Issues a new API token for a user.
*   **Request Body:**
    ```json
    {
      "email": "string",
      "password": "string",
      "device_name": "string"
    }
    ```
*   **Response Body (Success 200):**
    ```json
    {
      "token": "string"
    }
    ```
*   **Response Body (Error 401):**
    ```json
    {
      "message": "Invalid credentials"
    }
    ```

---

## 2. Business Settings Endpoints

### `GET /settings/business`

Retrieves the business settings.

*   **Description:** Get current business settings.
*   **Permissions:** Admin
*   **Response Body (Success 200):**
    ```json
    {
      "id": "uuid",
      "business_name": "string",
      "business_phone": "string",
      "business_email": "string",
      "business_address": "string",
      "time_zone": "string",
      "created_at": "timestamp",
      "updated_at": "timestamp"
    }
    ```

### `PUT /settings/business`

Updates the business settings.

*   **Description:** Update business settings.
*   **Permissions:** Admin
*   **Request Body:** (All fields optional, only provide what needs to be updated)
    ```json
    {
      "business_name": "string",
      "business_phone": "string",
      "business_email": "string",
      "business_address": "string",
      "time_zone": "string"
    }
    ```
*   **Response Body (Success 200):** Returns the updated business settings object.

---

## 3. Global Payroll Settings Endpoints

### `GET /settings/payroll`

Retrieves the global payroll settings.

*   **Description:** Get current global payroll settings.
*   **Permissions:** Admin
*   **Response Body (Success 200):**
    ```json
    {
      "id": "uuid",
      "payroll_period": "Weekly | Bi-weekly | Monthly",
      "week_run_from": "Monday | Tuesday | Wednesday | Thursday | Friday | Saturday | Sunday",
      "deduct_breaks": "boolean",
      "break_threshold": "string (e.g., '4h 30m')",
      "break_duration": "string (e.g., '30min')",
      "pay_overtime": "boolean",
      "overtime_threshold": "string (e.g., '44h')",
      "overtime_ratio": "number",
      "pay_public_holiday": "boolean",
      "public_holiday_ratio": "number",
      "record_lieu_days": "boolean",
      "pay_sick_days": "boolean",
      "sick_days_paid_per_year": "integer",
      "deduct_days_for_salary_employee": "boolean",
      "expected_number_of_days": "integer",
      "deduct_nib": "boolean",
      "deduct_nhib": "boolean",
      "add_tips": "boolean",
      "add_service_charge": "boolean",
      "service_charge_distribution_percentage": "number",
      "created_at": "timestamp",
      "updated_at": "timestamp"
    }
    ```

### `PUT /settings/payroll`

Updates the global payroll settings.

*   **Description:** Update global payroll settings.
*   **Permissions:** Admin
*   **Request Body:** (All fields optional, only provide what needs to be updated)
    ```json
    {
      "payroll_period": "Weekly | Bi-weekly | Monthly",
      "week_run_from": "Monday | Tuesday | Wednesday | Thursday | Friday | Saturday | Sunday",
      "deduct_breaks": "boolean",
      "break_threshold": "string",
      "break_duration": "string",
      "pay_overtime": "boolean",
      "overtime_threshold": "string",
      "overtime_ratio": "number",
      "pay_public_holiday": "boolean",
      "public_holiday_ratio": "number",
      "record_lieu_days": "boolean",
      "pay_sick_days": "boolean",
      "sick_days_paid_per_year": "integer",
      "deduct_days_for_salary_employee": "boolean",
      "expected_number_of_days": "integer",
      "deduct_nib": "boolean",
      "deduct_nhib": "boolean",
      "add_tips": "boolean",
      "add_service_charge": "boolean",
      "service_charge_distribution_percentage": "number"
    }
    ```
*   **Response Body (Success 200):** Returns the updated payroll settings object.

---

## 4. Public Holidays Endpoints

### `GET /holidays`

Retrieves a list of public holidays.

*   **Description:** Get all public holidays.
*   **Permissions:** Admin, Manager
*   **Query Parameters:**
    *   `year` (optional): Filter by year (e.g., `?year=2025`).
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "holiday_name": "string",
        "holiday_date": "date (YYYY-MM-DD)",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `POST /holidays`

Creates a new public holiday.

*   **Description:** Add a new public holiday.
*   **Permissions:** Admin
*   **Request Body:**
    ```json
    {
      "holiday_name": "string",
      "holiday_date": "date (YYYY-MM-DD)"
    }
    ```
*   **Response Body (Success 201):** Returns the created public holiday object.

### `PUT /holidays/:id`

Updates an existing public holiday.

*   **Description:** Update a public holiday by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the public holiday.
*   **Request Body:** (All fields optional)
    ```json
    {
      "holiday_name": "string",
      "holiday_date": "date (YYYY-MM-DD)"
    }
    ```
*   **Response Body (Success 200):** Returns the updated public holiday object.

### `DELETE /holidays/:id`

Deletes a public holiday.

*   **Description:** Delete a public holiday by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the public holiday.
*   **Response Body (Success 204):** No content.

---

## 5. Departments Endpoints

### `GET /departments`

Retrieves a list of departments.

*   **Description:** Get all departments.
*   **Permissions:** Admin, Manager
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "name": "string",
        "description": "string",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `POST /departments`

Creates a new department.

*   **Description:** Add a new department.
*   **Permissions:** Admin
*   **Request Body:**
    ```json
    {
      "name": "string",
      "description": "string"
    }
    ```
*   **Response Body (Success 201):** Returns the created department object.

### `PUT /departments/:id`

Updates an existing department.

*   **Description:** Update a department by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the department.
*   **Request Body:** (All fields optional)
    ```json
    {
      "name": "string",
      "description": "string"
    }
    ```
*   **Response Body (Success 200):** Returns the updated department object.

### `DELETE /departments/:id`

Deletes a department.

*   **Description:** Delete a department by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the department.
*   **Response Body (Success 204):** No content.

---

## 6. Job Roles Endpoints

### `GET /departments/:departmentId/job-roles`

Retrieves job roles for a specific department.

*   **Description:** Get all job roles within a department.
*   **Permissions:** Admin, Manager
*   **Path Parameters:**
    *   `departmentId`: UUID of the department.
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "department_id": "uuid",
        "title": "string",
        "description": "string",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `POST /departments/:departmentId/job-roles`

Creates a new job role within a department.

*   **Description:** Add a new job role to a department.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `departmentId`: UUID of the department.
*   **Request Body:**
    ```json
    {
      "title": "string",
      "description": "string"
    }
    ```
*   **Response Body (Success 201):** Returns the created job role object.

### `PUT /job-roles/:id`

Updates an existing job role.

*   **Description:** Update a job role by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the job role.
*   **Request Body:** (All fields optional)
    ```json
    {
      "title": "string",
      "description": "string",
      "department_id": "uuid"
    }
    ```
*   **Response Body (Success 200):** Returns the updated job role object.

### `DELETE /job-roles/:id`

Deletes a job role.

*   **Description:** Delete a job role by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the job role.
*   **Response Body (Success 204):** No content.

---

## 7. Employee Management Endpoints

### `GET /employees`

Retrieves a list of employees.

*   **Description:** Get all employees.
*   **Permissions:** Admin, Manager (Managers may only see employees in their department)
*   **Query Parameters:**
    *   `departmentId` (optional): Filter by department.
    *   `jobRoleId` (optional): Filter by job role.
    *   `status` (optional): Filter by employee status.
    *   `search` (optional): Search by name or employee ID.
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "employee_id": "string",
        "first_name": "string",
        "last_name": "string",
        "email_address": "string",
        "status": "string",
        "department": { "id": "uuid", "name": "string" },
        "job_role": { "id": "uuid", "title": "string" },
        "contract_type": "Hourly | Salary",
        "rate": "number",
        // ... other relevant employee fields
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `GET /employees/:id`

Retrieves details of a specific employee.

*   **Description:** Get employee details by ID.
*   **Permissions:** Admin, Manager (if employee in their department), Employee (for their own profile)
*   **Path Parameters:**
    *   `id`: UUID of the employee.
*   **Response Body (Success 200):** Returns the full employee object, including nested document details.

### `POST /employees`

Creates a new employee.

*   **Description:** Add a new employee.
*   **Permissions:** Admin
*   **Request Body:**
    ```json
    {
      "employee_id": "string",
      "first_name": "string",
      "last_name": "string",
      "nickname": "string",
      "gender": "Male | Female | Other",
      "dob": "date (YYYY-MM-DD)",
      "phone_number": "string",
      "email_address": "string",
      "address": "string",
      "passport_number": "string",
      "status": "Work Permit Holder | Resident | Citizen | Belonger",
      "nib_number": "string",
      "nib_deduction_override": "boolean",
      "nhib_number": "string",
      "nhib_deduction_override": "boolean",
      "payment_method": "CIBC | Scotiabank | RBC | Check",
      "starting_date": "date (YYYY-MM-DD)",
      "contract_type": "Hourly | Salary",
      "rate": "number",
      "contract_signed": "boolean",
      "uniform_size": "string",
      "department_id": "uuid",
      "job_role_id": "uuid",
      "documents": [ // Optional: can be added later via separate endpoint
        {
          "document_type": "Work Permit | Resident Permit | Permanent Resident Certificate | Naturalization Certificate | BOTC Passport | Status Card",
          "card_number": "string",
          "expiration_date": "date (YYYY-MM-DD)",
          "first_receipt_number": "string",
          "first_receipt_exp_date": "date (YYYY-MM-DD)",
          "second_receipt_number": "string",
          "second_receipt_exp_date": "date (YYYY-MM-DD)"
        }
      ]
    }
    ```
*   **Response Body (Success 201):** Returns the created employee object.

### `PUT /employees/:id`

Updates an existing employee's details.

*   **Description:** Update employee details by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the employee.
*   **Request Body:** (All fields optional, only provide what needs to be updated)
    ```json
    {
      "first_name": "string",
      "last_name": "string",
      // ... other employee fields
      "department_id": "uuid",
      "job_role_id": "uuid"
    }
    ```
*   **Response Body (Success 200):** Returns the updated employee object.

### `DELETE /employees/:id`

Deactivates or deletes an employee.

*   **Description:** Deactivate or delete an employee by ID. (Consider soft delete/deactivation instead of hard delete for historical payroll data integrity).
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the employee.
*   **Response Body (Success 204):** No content.

---

## 8. Employee Documents Endpoints

### `POST /employees/:employeeId/documents`

Adds a new document for an employee.

*   **Description:** Add a new status document for an employee.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `employeeId`: UUID of the employee.
*   **Request Body:**
    ```json
    {
      "document_type": "Work Permit | Resident Permit | Permanent Resident Certificate | Naturalization Certificate | BOTC Passport | Status Card",
      "card_number": "string",
      "expiration_date": "date (YYYY-MM-DD)",
      "first_receipt_number": "string",
      "first_receipt_exp_date": "date (YYYY-MM-DD)",
      "second_receipt_number": "string",
      "second_receipt_exp_date": "date (YYYY-MM-DD)"
    }
    ```
*   **Response Body (Success 201):** Returns the created document object.

### `PUT /documents/:id`

Updates an existing employee document.

*   **Description:** Update an employee document by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the document.
*   **Request Body:** (All fields optional)
    ```json
    {
      "card_number": "string",
      "expiration_date": "date (YYYY-MM-DD)"
    }
    ```
*   **Response Body (Success 200):** Returns the updated document object.

### `DELETE /documents/:id`

Deletes an employee document.

*   **Description:** Delete an employee document by ID.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the document.
*   **Response Body (Success 204):** No content.

---

## 9. Punch Management Endpoints

### `POST /punches/import`

Imports raw punch data from a file.

*   **Description:** Upload an Excel/CSV file containing raw punch data.
*   **Permissions:** Admin, Manager
*   **Request Body:** `multipart/form-data` with a file field (e.g., `punchFile`).
*   **Response Body (Success 200):**
    ```json
    {
      "message": "Punch data imported successfully",
      "importedCount": 150,
      "errors": [] // List of any parsing errors or invalid rows
    }
    ```

### `GET /punches`

Retrieves a list of punches for a given period.

*   **Description:** Get punches for employees within a date range.
*   **Permissions:** Admin, Manager (for their department's employees), Employee (for their own punches)
*   **Query Parameters:**
    *   `employeeId` (optional): Filter by specific employee.
    *   `startDate`: Date (YYYY-MM-DD)
    *   `endDate`: Date (YYYY-MM-DD)
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "employee_id": "uuid",
        "employee_name": "string",
        "punch_time": "timestamp",
        "punch_status": "IN | OUT",
        "is_manual_edit": "boolean",
        "original_punch_data": "string",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `PUT /punches/:id`

Updates an existing punch record.

*   **Description:** Manually edit a punch record.
*   **Permissions:** Admin, Manager
*   **Path Parameters:**
    *   `id`: UUID of the punch.
*   **Request Body:**
    ```json
    {
      "punch_time": "timestamp",
      "punch_status": "IN | OUT"
    }
    ```
*   **Response Body (Success 200):** Returns the updated punch object.

### `POST /punches`

Adds a new manual punch record.

*   **Description:** Manually add a new punch record.
*   **Permissions:** Admin, Manager
*   **Request Body:**
    ```json
    {
      "employee_id": "uuid",
      "punch_time": "timestamp",
      "punch_status": "IN | OUT"
    }
    ```
*   **Response Body (Success 201):** Returns the created punch object.

### `DELETE /punches/:id`

Deletes a punch record.

*   **Description:** Delete a punch record.
*   **Permissions:** Admin, Manager
*   **Path Parameters:**
    *   `id`: UUID of the punch.
*   **Response Body (Success 204):** No content.

### `POST /punches/autofix`

Applies automatic fixes to punch data for a given period.

*   **Description:** Analyze and suggest/apply fixes for missing/unpaired punches.
*   **Permissions:** Admin, Manager
*   **Request Body:**
    ```json
    {
      "startDate": "date (YYYY-MM-DD)",
      "endDate": "date (YYYY-MM-DD)",
      "employeeId": "uuid" // Optional: apply to specific employee
    }
    ```
*   **Response Body (Success 200):**
    ```json
    {
      "message": "Autofix process completed.",
      "summary": {
        "punches_analyzed": 100,
        "fixes_suggested": 5,
        "fixes_applied": 3,
        "unresolved_issues": 2
      },
      "details": [
        // List of applied fixes or unresolved issues
      ]
    }
    ```

---

## 10. Reporting Endpoints

### `GET /reports/hours`

Generates an hours report for a specified period and employees.

*   **Description:** Generate a detailed hours report.
*   **Permissions:** Admin, Manager (for their department's employees), Employee (for their own report)
*   **Query Parameters:**
    *   `startDate`: Date (YYYY-MM-DD)
    *   `endDate`: Date (YYYY-MM-DD)
    *   `employeeIds` (optional): Comma-separated list of employee UUIDs.
    *   `departmentId` (optional): Filter by department.
*   **Response Body (Success 200):**
    ```json
    [
      {
        "employee_id": "uuid",
        "employee_name": "string",
        "weeks": [
          {
            "week_start_date": "date (YYYY-MM-DD)",
            "week_end_date": "date (YYYY-MM-DD)",
            "daily_punches": [
              {
                "work_date": "date (YYYY-MM-DD)",
                "day": "string (e.g., Monday)",
                "check_in": "time (HH:MM:SS)",
                "check_out": "time (HH:MM:SS)",
                "gross_hours": "string (e.g., '9h 00m')",
                "break_hours": "string (e.g., '30m')",
                "net_hours": "string (e.g., '8h 30m')"
              }
            ],
            "weekly_summary": {
              "total_net_hours": "string (e.g., '40h 00m')",
              "overtime_hours": "string (e.g., '4h 00m')",
              "public_holiday_hours": "string (e.g., '8h 00m')"
            }
          }
        ]
      }
    ]
    ```

### `GET /reports/hours/export`

Exports an hours report as CSV or PDF.

*   **Description:** Export a detailed hours report.
*   **Permissions:** Admin, Manager
*   **Query Parameters:**
    *   `startDate`: Date (YYYY-MM-DD)
    *   `endDate`: Date (YYYY-MM-DD)
    *   `employeeIds` (optional): Comma-separated list of employee UUIDs.
    *   `format`: `csv | pdf`
*   **Response:** File download (CSV or PDF).

---

## 11. User Management Endpoints (for Admin)

### `GET /users`

Retrieves a list of all users.

*   **Description:** Get all user accounts.
*   **Permissions:** Admin
*   **Response Body (Success 200):**
    ```json
    [
      {
        "id": "uuid",
        "username": "string",
        "email": "string",
        "role": "Admin | Manager | Employee",
        "employee_id": "uuid | null",
        "created_at": "timestamp",
        "updated_at": "timestamp"
      }
    ]
    ```

### `POST /users`

Creates a new user account.

*   **Description:** Create a new user.
*   **Permissions:** Admin
*   **Request Body:**
    ```json
    {
      "username": "string",
      "password": "string",
      "email": "string",
      "role": "Admin | Manager | Employee",
      "employee_id": "uuid" // Optional: Link to an existing employee
    }
    ```
*   **Response Body (Success 201):** Returns the created user object (without password hash).

### `PUT /users/:id`

Updates an existing user account.

*   **Description:** Update user details or role.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the user.
*   **Request Body:** (All fields optional)
    ```json
    {
      "username": "string",
      "email": "string",
      "role": "Admin | Manager | Employee",
      "employee_id": "uuid" // Can be updated or set to null
    }
    ```
*   **Response Body (Success 200):** Returns the updated user object.

### `DELETE /users/:id`

Deletes a user account.

*   **Description:** Delete a user account.
*   **Permissions:** Admin
*   **Path Parameters:**
    *   `id`: UUID of the user.
*   **Response Body (Success 204):** No content.
