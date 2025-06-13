# Payroll Web Application

*   **Last Updated:** 2025-06-13 02:26 AM (America/New_York)

This is a comprehensive web application designed to streamline payroll processing for businesses. It manages employee data, tracks hours, calculates wages, and generates various reports, handling different pay types, deductions, and public holidays. The application also offers flexibility for manual adjustments and automated fixes for punch data.

## Features

*   **Core Application Settings:** Configure business details, global payroll rules (e.g., payroll period, overtime, breaks, sick days), and manage public holidays.
*   **Organizational Structure:** Define and manage departments and job roles within the organization.
*   **Employee Management:** Comprehensive employee profiles including personal details, employment information, status documents, NIB/NHIB details, payment methods, and contract types.
*   **Punch Management:** Import raw time punches from various sources, display and process punch data, with features for auto-fixing and manual editing of punches.
*   **Reporting:** Generate detailed hours reports with breakdowns of gross, break, net, overtime, and public holiday hours.
*   **User Authentication and Roles:** Secure access control with distinct roles for Admin, Manager, and Employee.
*   **Security:** Designed with data encryption, secure connections (HTTPS), and access controls.
*   **Scalability:** Built to handle a growing number of employees and historical data efficiently.
*   **Audit Trails:** Logs for all significant actions within the application.

## Tech Stack

The application is built using a modern and robust tech stack:

*   **Backend:** PHP with Laravel (v12)
*   **Frontend:** Vue.js with TypeScript and Vite (integrated with Laravel Jetstream)
*   **Database:** PostgreSQL
*   **ORM:** Eloquent (Laravel)
*   **Authentication & Authorization:** Laravel Jetstream (Inertia Stack), Laravel Socialite (Google OAuth), Spatie Laravel Permission (RBAC)
*   **UI Library:** shadcn/ui (with Tailwind CSS)
*   **Deployment:** Render

For more detailed information on the tech stack, please refer to `docs/tech-stack.md`.

## Getting Started

Detailed instructions on setting up the development environment, running the application locally, and deployment procedures can be found in the `docs/` directory.

### Current Development Status

This project is actively under development. The following phases have been completed or are in progress:

**Phase 1: Project Setup and Backend Foundation (Completed)**
*   **Verify Development Environment:** Confirmed PHP, Composer, Node.js, and Laravel Installer are installed.
*   **Backend Project Initialization:** Created Laravel project in `backend/` directory with Jetstream (Inertia Stack) and teams support.
*   **Database Integration:** Configured `.env` file with PostgreSQL database credentials and ran initial migrations. Create admin user admin/admin123!@#
*   **Authentication & Authorization Setup:** Integrated Laravel Socialite and Spatie Laravel Permission for Google OAuth and RBAC.

**Phase 2: Frontend Setup and Core UI (Completed)**
*   **Frontend Project Initialization:** Vue.js frontend is integrated with the Laravel backend via Jetstream (Inertia Stack).
*   **UI/UX Implementation:** Tailwind CSS is configured, and `shadcn/ui` integration is prepared.
*   **Authentication UI:** Jetstream provides pre-built login, registration, and dashboard views.

**Phase 3: Feature Development - Public Holidays, Departments, Job Roles (Pending)**
*   **Public Holidays:** Implementation of CRUD APIs for public holidays is pending.
*   **Departments:** Implementation of CRUD APIs for departments is pending.
*   **Job Roles:** Implementation of CRUD APIs for job roles is pending.

Next steps involve continuing with feature development as outlined in the development plan.

## Documentation

All project documentation is located in the `docs/` folder. Please refer to these documents for detailed information:

*   [`docs/project-overview.md`](docs/project-overview.md): Detailed overview of application features and structure.
*   [`docs/ui_ux_guideline.md`](docs/ui_ux_guideline.md): Comprehensive UI/UX design proposal and style guide.
*   [`docs/tech-stack.md`](docs/tech-stack.md): Overview of the chosen technology stack.
*   [`docs/database-schema.md`](docs/database-schema.md): Detailed database schema.
*   [`docs/api-spec.md`](docs/api-spec.md): Backend API documentation.
*   [`docs/dev-environment-setup.md`](docs/dev-environment-setup.md): Local development environment setup guide.
*   [`docs/deployment-guide.md`](docs/deployment-guide.md): Deployment instructions for Render.
*   [`docs/testing-strategy.md`](docs/testing-strategy.md): Application testing strategy.
*   [`docs/security-guidelines.md`](docs/security-guidelines.md): Security considerations and best practices.
