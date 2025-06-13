Here's a breakdown of the payroll web app features and structure, incorporating your detailed requirements:

## Payroll Web App: Feature Overview

*   **Last Updated:** 2025-06-13 02:04 AM (America/New_York)

This web application will streamline payroll processing by managing employee data, tracking hours, calculating wages, and generating comprehensive reports. It will handle various pay types, deductions, and public holidays, while offering flexibility for manual adjustments and automated fixes.

For details on the technology stack, refer to [`docs/tech-stack.md`](./tech-stack.md).
For UI/UX design guidelines, refer to [`docs/ui_ux_guideline.md`](./ui_ux_guideline.md).
For database structure, refer to [`docs/database-schema.md`](./database-schema.md).
For API specifications, refer to [`docs/api-spec.md`](./api-spec.md).
For development environment setup, refer to [`docs/dev-environment-setup.md`](./dev-environment-setup.md).
For deployment instructions, refer to [`docs/deployment-guide.md`](./deployment-guide.md).
For testing strategy, refer to [`docs/testing-strategy.md`](./testing-strategy.md).
For security guidelines, refer to [`docs/security-guidelines.md`](./security-guidelines.md).

## I. Core Application Settings

### A. Business Settings

* **Business Name:** Text field
* **Business Phone:** Text field
* **Business Email:** Text field
* **Business Address:** Text area
* **Time Zone:** Dropdown selection (e.g., "America/Grand_Turk" for Turks and Caicos Islands)

### B. Global Payroll Settings

* **Payroll Period:** Radio buttons: `Weekly` / `Bi-weekly` / `Monthly`
* **Week Run From:** Dropdown selection: `Monday` to `Sunday` (fixed as per requirement)

* **[ ] Deduct Breaks:** Checkbox
    * **Break Threshold (over):** Numerical input (default: `4h 30m`)
    * **Break Duration:** Numerical input (default: `30min`)

* **[ ] Pay Overtime:** Checkbox
    * **Overtime Threshold:** Numerical input (default: `44h`)
    * **Overtime Ratio:** Numerical input (default: `1.5`)

* **[ ] Pay Public Holiday:** Checkbox
    * **Public Holiday Ratio:** Numerical input (default: `2.0`)

* **[ ] Record Lieu days for salary employee for Public Holiday:** Checkbox

* **[ ] Pay Sick Days:** Checkbox
    * **Sick Days Paid Per Year:** Numerical input (default: `3`)

* **[ ] Deduct Days for Salary Paid Employee:** Checkbox
    * **Expected Number of Days:** Numerical input (default: `6`)

* **[ ] Deduct NIB:** Checkbox
* **[ ] Deduct NHIB:** Checkbox
* **[ ] Add Tips:** Checkbox
* **[ ] Add Service Charge:** Checkbox
    * **% of Total Collected for Distribution:** Numerical input

### C. 2025 Public Holiday Calendar

This will be a pre-populated, read-only table within the settings, with an option to add/edit future years.

| Holiday Name        | Date         | Day of Week | Month     |
| :------------------ | :----------- | :---------- | :-------- |
| New Year's Day      | 01/01/2025   | Wednesday   | January   |
| Commonwealth Day    | 03/10/2025   | Monday      | March     |
| Good Friday         | 04/18/2025   | Friday      | April     |
| Easter Monday       | 04/21/2025   | Monday      | April     |
| JAGS McCartney Day  | 05/26/2025   | Monday      | May       |
| King's Birthday     | 06/23/2025   | Monday      | June      |
| Emancipation Day    | 08/01/2025   | Friday      | August    |
| National Youth Day  | 09/26/2025   | Friday      | September |
| National Heritage Day | 10/13/2025   | Monday      | October   |
| Thanksgiving        | 11/28/2025   | Friday      | November  |
| Christmas Day       | 12/25/2025   | Thursday    | December  |
| Boxing Day          | 12/26/2025   | Friday      | December  |

## II. Organizational Structure

### A. Departments

* **Kitchen**
* **Service**

### B. Job Roles

* **Kitchen:**
    * Kitchen Helper
    * Cook
    * Chef
    * Executive Chef
* **Service:**
    * Server
    * Bartender
    * Barback
    * Server / Bartender
    * Hostess
    * Supervisor
    * Manager

*(Note: These should be manageable lists in the UI, allowing for adding, editing, and deleting.)*

## III. Employee Management

A dedicated section to store and manage all employee data.

* **Employee ID:** Auto-generated/Manual input (unique identifier)
* **First Name:** Text field
* **Last Name:** Text field
* **Nickname:** Text field
* **Gender:** Dropdown (Male/Female/Other)
* **DOB:** Date picker
* **Phone Number:** Text field
* **Email Address:** Text field
* **Address:** Text area
* **Passport Number:** Text field
* **Status:** Dropdown:
    * `Work Permit Holder`
    * `Resident`
    * `Citizen`
    * `Belonger`
* **Status Document (Conditional based on Status):**
    * **Work Permit:**
        * Card / Number: Text field
        * Exp Date: Date picker
        * 1st Receipt / Number: Text field
        * 1st Receipt Exp Date: Date picker
        * 2nd Receipt / Number: Text field
        * 2nd Receipt Exp Date: Date picker
    * **Resident Permit:**
        * Number: Text field
        * Exp Date: Date picker
    * **Permanent Resident Certificate:**
        * Number: Text field
    * **Naturalization Certificate:** (No specific fields mentioned, likely just a record of existence)
    * **BOTC Passport:** (No specific fields mentioned)
    * **Status Card:** (No specific fields mentioned)
* **NIB:**
    * Number: Text field
    * Deduction: Checkbox (pre-selected based on global settings but can be overridden)
* **NHIB:**
    * Number: Text field
    * Deduction: Checkbox (pre-selected based on global settings but can be overridden)
* **Payment Method:** Dropdown:
    * `CIBC`
    * `Scotiabank`
    * `RBC`
    * `Check`
* **Starting Date:** Date picker
* **Type of Contract:** Radio buttons: `Hourly` / `Salary`
* **Rate:** Numerical input (conditional: hourly rate for `Hourly`, weekly/monthly salary for `Salary`)
* **Contract Signed:** Checkbox
* **Uniform Size:** Text field

## IV. Punch Management

This is the core functionality for importing and processing time punches.

* **Import Raw Punches:**
    * **File Upload:** Button to upload an Excel file (`.xlsx`, `.xls`, `.csv`).
    * **External Database Access:** (Requires backend integration) Configuration settings to connect to an external time clock database (e.g., ODBC, API).

* **Punch Data Display (Example format after import/retrieval):**

| ID | Name    | Date                | Status |
| :-- | :------ | :------------------ | :----- |
| 3   | Joan    | 2025-06-08 00:41:38 | OUT    |
| 18  | Farar   | 2025-06-07 23:23:30 | IN     |
| 4   | Kevin   | 2025-06-07 23:18:58 | OUT    |
| 23  | Majorie | 2025-06-07 23:18:13 | OUT    |
| 13  | Roseline| 2025-06-07 21:09:21 | OUT    |
| 20  | Rose    | 2025-06-07 17:37:42 | OUT    |
| 18  | Farar   | 2025-06-07 17:32:38 | IN     |
| 2   | Alande  | 2025-06-07 17:32:06 | OUT    |
| 8   | Marianne| 2025-06-07 17:10:19 | OUT    |
| 23  | Majorie | 2025-06-07 16:59:53 | IN     |

* **Punch Processing Features:**
    * **Auto-fix Punches:**
        * Algorithm to detect missing IN/OUT punches and suggest fixes (e.g., pairing, flagging).
        * Option to apply suggested fixes in bulk or individually.
    * **Manual Edit Punches:**
        * Table view of punches with inline editing capabilities for Date, Time, and Status.
        * Ability to add new punches.
        * Ability to delete existing punches.
        * Audit log for manual changes.

## V. Reporting

### Hours Report

This report will provide a detailed breakdown of employee hours for a selected period, split by week.

* **Filters:**
    * **Employee Selection:** Dropdown/Multi-select list of employees.
    * **Pay Period Selection:** Date range picker (pre-filled based on selected payroll period, e.g., "Current Week", "Previous Bi-weekly", "Custom Date Range").

* **Report Output (per week, per employee):**

| Employee  | Work Date  | Day         | Check In     | Check Out    | Gross Hours | Break Hours | Net Hours |
| :-------- | :--------- | :---------- | :----------- | :----------- | :---------- | :---------- | :-------- |
| [Employee Name] | 2025-06-02 | Monday      | 08:00:00 AM  | 05:00:00 PM  | 9h 00m      | 30m         | 8h 30m    |
| [Employee Name] | 2025-06-03 | Tuesday     | 08:05:00 AM  | 04:55:00 PM  | 8h 50m      | 30m         | 8h 20m    |
| ...       | ...        | ...         | ...          | ...          | ...         | ...         | ...       |
| **Weekly Total:** | | | | | | **Total Net Hours:** [XXh XXm] | **Overtime Hours:** [YYh YYm] | **Public Holiday Hours:** [ZZh ZZm] |

* **Calculations in the report:**
    * **Gross Hours:** Time between Check In and Check Out.
    * **Break Hours:** Calculated based on "Deduct Breaks" setting.
    * **Net Hours:** Gross Hours - Break Hours.
    * **Total Net Hours (per week):** Sum of Net Hours for the week.
    * **Overtime Hours (per week):** Calculated based on "Pay Overtime" setting and Overtime Threshold.
    * **Public Holiday Hours (per week):** Hours worked on a public holiday, calculated based on "Pay Public Holiday" setting and Public Holiday Ratio.

## VI. Additional Considerations

* **User Authentication and Roles (via Laravel Jetstream & Spatie Permission):**
    * Admin: Full access to all settings, employee data, and payroll processing.
    * Manager: Access to view employee data for their department, approve punches, view reports.
    * Employee: View their own punches, hours, and pay stubs.
    * Role-Based Access Control (RBAC) will be implemented using Spatie Laravel Permission for fine-grained control over user actions.
* **Security:** Data encryption, secure connections (HTTPS), access controls.
* **Scalability:** Design to handle a growing number of employees and historical data.
* **Audit Trails:** Log all significant actions (e.g., data changes, payroll runs, manual punch edits).
* **Payment Processing Integration (Future):** While "Payment Method" is listed, actual bank transfers would be a future enhancement requiring integration with banking APIs.
* **Pay Stub Generation:** Generate professional pay stubs for employees.
* **Tax Forms:** Generate necessary tax forms (e.g., for NIB/NHIB remittances).

The next steps would involve UI/UX design, database schema design, and then moving into front-end and back-end development.
