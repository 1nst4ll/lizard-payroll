### UI/UX Design Proposal for Payroll Web App

*   **Last Updated:** 2025-06-13 02:08 AM (America/New_York)

Here is a comprehensive UI/UX design proposal for the payroll web application. The design prioritizes clarity, efficiency, and a user-friendly experience for administrators and managers. It uses a clean, modern aesthetic with a consistent layout to make navigation intuitive.

For a high-level overview of the project features, refer to [`docs/project-overview.md`](./project-overview.md).
For details on the technology stack, refer to [`docs/tech-stack.md`](./tech-stack.md).

-----

### **1. Design Philosophy & Style Guide**

  * **Clarity & Simplicity:** The interface will be uncluttered, with clear labeling and a logical hierarchy. Complex calculations happen in the background, presenting the user with clean, understandable results.
  * **Efficiency:** Workflows are designed to minimize clicks. Features like bulk actions, smart defaults, and autofix suggestions will speed up common tasks like correcting punches and running payroll.
  * **Role-Based Access:** The UI will adapt based on the user's role (e.g., Administrator, Manager). An Admin sees everything, while a Manager might only see their department's employees and reports.
  * **Consistency:** A consistent design language (buttons, forms, modals, tables) will be used throughout the app, making it predictable and easy to learn.
  * **Responsive & Mobile-First:** The design will prioritize a mobile-first approach, ensuring optimal user experience and functionality across various screen sizes, from mobile devices to large desktops. The UI will adapt fluidly to different viewports.
  * **Native App Feel:** Leveraging `shadcn/ui` (with Vue.js implementation) and careful design, the application will aim for a clean, intuitive interface that feels familiar and performant, akin to a native application.

**Color Palette:**

  * **Primary:** `#4A90E2` (A professional and calming blue)
  * **Secondary/Accent:** `#50E3C2` (A vibrant teal for calls-to-action, success states)
  * **Neutral:** `#F4F5F7` (Light gray for backgrounds)
  * **Text:** `#333333` (Dark gray for readability)
  * **Error/Alert:** `#D0021B` (A clear, attention-grabbing red)

**Typography:**

  * **Font:** Inter or Lato - modern, highly readable sans-serif fonts suitable for UI.
    *   **Headings (H1):** `font-size: 2.5rem; font-weight: 700;` (Bold)
    *   **Headings (H2):** `font-size: 2rem; font-weight: 600;` (Semi-bold)
    *   **Headings (H3):** `font-size: 1.75rem; font-weight: 600;` (Semi-bold)
    *   **Body Text:** `font-size: 1rem; font-weight: 400;` (Regular)
    *   **Small Text:** `font-size: 0.875rem; font-weight: 400;` (Regular)

### **Component Library & Styling:**

*   **shadcn/ui:** The primary UI component library. While originally built for React, efforts are underway to provide Vue.js compatibility. Components will be integrated and customized to match the defined design system, potentially requiring manual adaptation for Vue.js.
*   **Tailwind CSS:** Used for utility-first styling, enabling rapid and consistent application of design tokens and responsive layouts.

### **Accessibility:**

*   **Semantic HTML:** Use appropriate HTML5 elements to ensure proper structure and meaning.
*   **Keyboard Navigation:** All interactive elements will be fully navigable and operable via keyboard.
*   **ARIA Attributes:** Implement ARIA (Accessible Rich Internet Applications) attributes where necessary to enhance semantic meaning for assistive technologies.
*   **Color Contrast:** Ensure sufficient color contrast ratios for all text and interactive elements to meet WCAG 2.1 AA standards.
*   **Focus Management:** Clearly indicate focus states for interactive elements to aid keyboard and assistive technology users.
*   **Alt Text:** Provide descriptive `alt` text for all meaningful images.

-----

### **2. Wireframes & Mockups**

Here are the low-fidelity wireframes for the core sections of the application.

#### **2.1 Main Dashboard**

**Objective:** Provide a high-level overview of the current payroll status and quick access to common tasks.

**Elements:**

  * **Top Navigation:** Links to major sections (Dashboard, Employees, Time & Punches, Reports, Settings).
  * **Current Payroll Period:** Clearly displays the current period and a countdown to the next payroll run.
  * **Action Required:** A prominent section highlighting urgent tasks, like "Missing Punches" or "Pending Approvals."
  * **Quick Stats:** Cards showing key metrics like Total Hours Worked (this period), Estimated Payroll Cost, and number of Active Employees.
  * **Upcoming Holidays:** A reminder of the next public holiday.
  * **Quick Links:** Buttons for the most common actions: `Import Punches`, `Run Payroll`, `View Reports`.

-----

#### **2.2 Settings**

**Objective:** Allow administrators to easily configure all business and global payroll rules in one place.

**Elements:**

  * **Tabbed Navigation:** The settings page is divided into logical tabs: `Business`, `Payroll`, `Deductions & Contributions`, and `Holidays`. This prevents an overwhelmingly long page.
  * **Clear Toggles and Fields:** Using checkboxes (`[ ]`) and toggles for on/off settings makes configuration intuitive.
  * **Contextual Inputs:** Fields appear only when needed (e.g., "Break Duration" field is visible only if "Deduct Breaks" is checked).
  * **Save Button:** A sticky "Save Changes" button is always visible at the bottom.

-----

#### **2.3 Employee Management**

**Objective:** Provide a comprehensive view of all employees and allow for easy adding, viewing, and editing of employee profiles.

**A. Employee List View**

**Elements:**

  * **Search and Filter:** Powerful search bar to find employees by name or ID. Filters for Department, Job Role, and Status.
  * **Employee Table:** A clean, scannable table with key information (Name, ID, Department, Job Role, Status).
  * **"Add Employee" Button:** Prominently placed for easy access.
  * **Action Menu:** A "..." menu on each row with options to `View/Edit` or `Deactivate`.

**B. Employee Profile View**

**Elements:**

  * **Profile Header:** Shows the employee's name, photo (optional), and key status (e.g., "Active - Work Permit").
  * **Tabbed Sections:** Organizes the vast amount of employee data into manageable tabs: `Personal`, `Employment`, `Documents`, `Payroll`, `History`.
  * **Clear Data Entry:** Well-labeled fields and logical grouping make data entry straightforward. Conditional fields (like the specific document details) are shown based on the "Status" dropdown.

-----

#### **2.4 Punch Management**

**Objective:** Allow for the import, viewing, and correction of time punches efficiently.

**Elements:**

  * **Import Modal:** A clean pop-up for uploading Excel/CSV files or connecting to an external source.
  * **Date Range Selector:** Allows the user to view punches for a specific payroll period or custom range.
  * **Punch Table:**
      * Displays raw punches with clear IN/OUT status.
      * **Flags/Warnings:** An icon (`⚠️`) highlights potential errors like missing punches or unusually long shifts.
      * **Inline Editing:** Clicking on a date, time, or status allows for instant editing.
  * **"Autofix" Button:** A button that, when clicked, scans for errors and presents a summary of suggested changes (e.g., "Found 3 missing OUT punches. Suggest pairing them with the next day's IN punch?"). The user can then approve these fixes.
  * **"Add Punch" Button:** A manual override for adding a missed punch.

-----

#### **2.5 Reporting**

**Objective:** Generate the specific "Hours Report" as requested, with clear totals and breakdowns.

**Elements:**

  * **Report Filters:**
      * `Report Type:` Dropdown (starts with "Hours Report," can be expanded later).
      * `Pay Period:` Dropdown with presets (`Current Week`, `Last Bi-weekly Period`) and a custom date range option.
      * `Employee:` Multi-select dropdown to choose one or more employees.
      * `Department:` Filter by department.
  * **`Generate Report` Button:** Clear call-to-action.
  * **Report Display:**
      * The report is grouped by **Employee**, then by **Week**.
      * Each row represents a workday, with the columns as specified (`Work Date`, `Day`, `Check In`, `Check Out`, `Gross Hours`, `Break Hours`, `Net Hours`).
      * **Weekly Summary Row:** A bolded summary row at the end of each week calculates `Total Net Hours`, `Overtime Hours`, and `Public Holiday Hours`.
  * **Export Options:** Buttons to `Print` or `Export to CSV/PDF`.

-----

This UI/UX design provides a robust and user-centric foundation for the payroll web app, directly addressing all the specified features and ensuring that complex payroll tasks are made as simple and efficient as possible.

### Recommended shadcn/ui Components

Based on the UI/UX Design Proposal, here's a mapping of required UI elements to suitable `shadcn/ui` components to achieve a responsive, mobile-first design with a native app feel:

#### 1. General / Layout Components

*   **Layout & Navigation:**
    *   **Sidebar:** For main navigation on larger screens.
    *   **Sheet:** For mobile navigation (drawer from the side).
    *   **Navigation Menu:** For top-level navigation items.
    *   **Button:** For various actions and links.
    *   **Separator:** For visual separation in menus or content.
*   **General Display:**
    *   **Card:** For "Quick Stats" and other content blocks on the Dashboard.
    *   **Skeleton:** For loading states, especially for data-intensive sections like reports or employee lists.
    *   **Typography:** For consistent text styling (headings, body text).

#### 2. Core Application Settings (I. Core Application Settings)

*   **Input Fields:**
    *   **Input:** For `Business Name`, `Business Phone`, `Business Email`, `Passport Number`, `NIB Number`, `NHIB Number`, `Employee ID`, `First Name`, `Last Name`, `Nickname`, `Phone Number`, `Email Address`, `Card / Number`, `1st Receipt / Number`, `2nd Receipt / Number`, `Rate`, `Uniform Size`.
    *   **Textarea:** For `Business Address`, `Address`.
*   **Selection & Toggles:**
    *   **Select:** For `Time Zone`, `Gender`, `Status`, `Payment Method`, `Department`, `Job Role`, `Report Type`, `Employee Selection`, `Pay Period Selection`.
    *   **Radio Group:** For `Payroll Period`, `Week Run From`, `Type of Contract`.
    *   **Checkbox:** For `Deduct Breaks`, `Pay Overtime`, `Pay Public Holiday`, `Record Lieu days for salary employee for Public Holiday`, `Sick Days Paid Per Year`, `Deduct Days for Salary Paid Employee`, `Deduct NIB`, `Deduct NHIB`, `Add Tips`, `Add Service Charge`, `Contract Signed`, `NIB Deduction Override`, `NHIB Deduction Override`.
    *   **Switch:** Can be an alternative for simple on/off settings like `Deduct Breaks` if a more modern toggle is preferred over a checkbox.
*   **Date Input:**
    *   **Date Picker:** For `DOB`, `Exp Date`, `1st Receipt Exp Date`, `2nd Receipt Exp Date`, `Starting Date`, `Pay Period Selection` (date range).
*   **Numerical Input:**
    *   **Input** (with type="number" or custom validation): For `Break Threshold`, `Break Duration`, `Overtime Threshold`, `Overtime Ratio`, `Public Holiday Ratio`, `Sick Days Paid Per Year`, `Expected Number of Days`, `% of Total Collected for Distribution`.
*   **Tabbed Navigation:**
    *   **Tabs:** For organizing settings pages (`Business`, `Payroll`, `Deductions & Contributions`, `Holidays`) and Employee Profile views (`Personal`, `Employment`, `Documents`, `Payroll`, `History`).

#### 3. Employee Management (III. Employee Management)

*   **Data Display & Interaction:**
    *   **Data Table:** For `Employee List View` (powerful table with sorting, filtering, pagination).
    *   **Pagination:** For `Employee List View` and `Punch Data Display`.
    *   **Input:** For `Search and Filter` in employee list.
    *   **Dropdown Menu:** For "Action Menu" (`View/Edit`, `Deactivate`) on each employee row.
    *   **Avatar:** For employee photos (optional).

#### 4. Punch Management (IV. Punch Management)

*   **File Upload:**
    *   **Dialog:** For the "Import Modal" (file upload pop-up).
    *   **Input** (type="file"): For file selection within the import modal.
*   **Punch Data Display:**
    *   **Table:** For `Punch Data Display` (raw punches).
    *   **Input** (inline editing): For `Date`, `Time`, `Status` within the punch table.
    *   **Button:** For "Autofix" and "Add Punch".
    *   **Toast / Sonner:** For displaying messages like "Punch data imported successfully" or warnings.

#### 5. Reporting (V. Reporting)

*   **Filters:**
    *   **Select:** For `Report Type`, `Pay Period`, `Employee`, `Department`.
    *   **Date Picker:** For custom date ranges in `Pay Period`.
    *   **Button:** For `Generate Report`.
*   **Report Display:**
    *   **Table:** For displaying the `Hours Report`.
    *   **Chart:** (Optional, for future enhancements) If any visual representation of hours or payroll data is desired.
*   **Export Options:**
    *   **Button:** For `Print` or `Export to CSV/PDF`.
