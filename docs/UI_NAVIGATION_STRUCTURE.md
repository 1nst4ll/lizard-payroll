# Lizard Payroll - Complete UI & Navigation Structure

## Project Overview
**System:** Turks Kebab Restaurant Management System  
**Purpose:** Comprehensive payroll, time tracking, tip distribution, and compliance management  
**Technology:** Laravel 10.5 + Vue 3 + Inertia.js + ShadCN-Vue Components + Tailwind CSS v4  

## Primary Navigation Structure

### Main Navigation Bar (Top Level)
```
┌─────────────────────────────────────────────────────────────────────────┐
│ 🦎 Lizard Payroll    Dashboard  Employees  Time Clock  Payroll  Reports  Settings │
│                                                              👤 User Menu ▼ │
└─────────────────────────────────────────────────────────────────────────┘
```

**Navigation Items:**
1. **Dashboard** - Main overview and KPIs
2. **Employees** - Employee management and profiles
3. **Time Clock** - Time tracking data processing
4. **Payroll** - Payroll calculation and processing
5. **Reports** - All reporting and exports
6. **Settings** - System configuration

## 1. Dashboard Page (`/dashboard`)

### Layout Description
**Header Section:**
- Welcome message with current date and pay period status
- Quick stats cards showing: Active Employees, Current Pay Period Progress, Pending Corrections, Last Payroll Date

**Main Content Grid (3-column layout):**

**Left Column (30%):**
- **Current Pay Period Card**
  - Period dates (e.g., "Dec 1-14, 2025")
  - Progress bar showing completion percentage
  - Days remaining until payroll deadline
  - Status badge (Collecting, Processing, Complete)

- **Quick Actions Card**
  - "Upload Time Clock Data" button (large, primary)
  - "Process Payroll" button
  - "View Last Pay Stubs" link
  - "Generate Reports" link

**Center Column (40%):**
- **Recent Activity Timeline**
  - Time-stamped entries of recent actions
  - Color-coded by action type (uploads, approvals, corrections)
  - Scrollable list with "View All" link

- **Alerts & Notifications**
  - System notifications requiring attention
  - Error alerts for failed processing
  - Compliance reminders (NIB/NHIP deadlines)

**Right Column (30%):**
- **Key Metrics Cards**
  - Total employees: FOH/BOH breakdown
  - Average hours per employee this period
  - Total tip pool amount
  - Government contributions summary

- **System Status**
  - Last backup time
  - System health indicators
  - Database connectivity status

### Visual Design Details
- **Background:** Clean white with subtle gray card backgrounds
- **Typography:** Inter font, clear hierarchy with card titles in text-lg
- **Color Scheme:** Primary blue for action buttons, green for positive metrics, amber for warnings
- **Cards:** Rounded corners (rounded-lg), subtle shadow, padding p-6
- **Responsive:** Stacks to single column on mobile devices

---

## 2. Employees Section (`/employees`)

### 2.1 Employee List Page (`/employees`)

**Header:**
- Page title "Employee Management"
- "Add New Employee" button (top right, primary button)
- Search bar with placeholder "Search employees..."
- Filter dropdown: "All", "FOH", "BOH", "Active", "Inactive"

**Main Data Table:**
- **Columns:** Photo, Name, Employee ID, Department, Position, Hire Date, Status, Actions
- **Photo Column:** Small circular avatar with initials fallback
- **Name Column:** Full name with email address below in smaller text
- **Department:** Badge showing "FOH" (blue) or "BOH" (green)
- **Status:** Active (green badge) or Inactive (gray badge)
- **Actions:** Edit icon, View details icon, Deactivate/Activate toggle

**Table Features:**
- Sortable columns with sort indicators
- Pagination at bottom (10, 25, 50 per page options)
- Row hover effects with subtle background change
- Export button for CSV/Excel download

**Visual Design:**
- Header with search and filters in sticky position
- Table with alternating row colors (white/gray-50)
- Action buttons as icon buttons with tooltips
- Mobile responsive with horizontal scroll for table

### 2.2 Employee Detail/Edit Page (`/employees/{id}`)

**Layout: Two-column form**

**Left Column (60%):**
- **Personal Information Card**
  - Profile photo upload area (large circular with upload icon)
  - Full name input (required)
  - Employee ID (auto-generated, read-only)
  - Email address input
  - Phone number input
  - Date of birth (date picker)
  - Address fields (multi-line)

- **Employment Details Card**
  - Hire date (date picker)
  - Department selection (FOH/BOH radio buttons)
  - Position/Title input
  - Regular hourly rate (currency input with $ prefix)
  - Overtime multiplier (default 1.5, editable)
  - Employment status toggle (Active/Inactive)

**Right Column (40%):**
- **Government Compliance Card**
  - NIB number input
  - NHIP enrollment status (checkbox)
  - Work permit status (dropdown: Not Required, Valid, Expired)
  - Work permit expiry date (conditional date picker)

- **Banking Information Card**
  - Bank selection (dropdown: FCIB, Scotiabank, RBC, Other)
  - Account number input (masked for security)
  - Account type (Checking/Savings radio buttons)
  - Account holder name verification

- **Access Control Card** (Management only)
  - System access checkbox
  - Role selection (Employee, Supervisor, Manager, Admin)
  - Permissions preview (read-only list)

**Form Actions:**
- Save button (primary, bottom right)
- Cancel button (secondary)
- Delete Employee button (destructive, requires confirmation)

**Visual Design:**
- Form cards with consistent padding and spacing
- Required fields marked with red asterisk
- Validation errors appear inline below fields
- Success toast notification on save
- Confirmation dialog for destructive actions

---

## 3. Time Clock Section (`/time-clock`)

### 3.1 Time Clock Main Page (`/time-clock`)

**Workflow Tabs:**
```
┌─────────────┬─────────────┬─────────────┬─────────────┬─────────────┐
│  📁 Upload  │ 👁️ Review   │ ✏️ Correct  │ ✅ Approve │ ⚡ Process │
└─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘
```

### 3.2 Upload Tab (`/time-clock/upload`)

**Main Upload Area:**
- Large drag-and-drop zone with dashed border
- Upload icon and "Drop time clock file here or click to browse"
- Supported formats: "CSV, TXT, XLS files accepted"
- File size limit indicator
- Progress bar during upload

**Upload History:**
- Table showing recent uploads with columns:
  - Filename, Upload Date, Records Count, Status (Processing/Complete/Error), Actions
- Status badges with appropriate colors
- "Download Sample Format" link below table

**Visual Design:**
- Prominent upload area with hover effects
- Progress indicator with percentage and estimated time
- Success/error states with clear messaging
- File preview option before processing

### 3.3 Review Tab (`/time-clock/review`)

**Data Preview Table:**
- **Columns:** Employee, Date, Time In, Time Out, Hours, Issues, Status
- **Issues Column:** Warning icons with tooltip explanations
- **Status Column:** Color-coded badges (Valid/Warning/Error)

**Issue Summary Panel (Right Sidebar):**
- Count of different issue types:
  - Identical punches: X records
  - Missing OUT punches: X records
  - Overnight shifts: X records
  - Single punches: X records
- "Auto-correct common issues" button
- "Proceed to manual correction" button

**Filters and Controls:**
- Date range picker
- Employee filter dropdown
- Issue type filter checkboxes
- "Show only problems" toggle

### 3.4 Corrections Tab (`/time-clock/corrections`)

**Split View Layout:**

**Left Panel (50%) - Issues List:**
- Expandable list of employees with issues
- Each employee shows count of issues requiring attention
- Click to expand and show individual punch records
- Color coding for issue severity (red/amber/yellow)

**Right Panel (50%) - Correction Interface:**
- **Selected Record Details**
  - Employee name and date
  - Original punch times
  - Detected issue description
  - Suggested correction with confidence level

- **Correction Actions**
  - "Accept Suggestion" button (green)
  - "Manual Edit" option with time pickers
  - "Delete Record" option (destructive)
  - "Skip for Now" button (gray)

**Batch Operations:**
- "Apply All Suggestions" button (with confirmation)
- "Reset All Changes" button
- Progress indicator showing X of Y records processed

### 3.5 Approval Tab (`/time-clock/approval`)

**Summary Dashboard:**
- **Overview Cards:**
  - Total employees processed
  - Total hours calculated
  - Issues resolved
  - Issues remaining

**Final Review Table:**
- Employee summary with calculated hours
- Columns: Name, Regular Hours, Overtime Hours, Total Hours, Issues Resolved
- Expandable rows to show detailed time pairs
- Sort and filter capabilities

**Approval Actions:**
- "Generate Work Pairs" button (large, primary)
- "Export Summary" button
- "Return to Corrections" link if issues found

---

## 4. Payroll Section (`/payroll`)

### 4.1 Payroll Dashboard (`/payroll`)

**Pay Period Selection:**
- Date range picker showing current period
- Previous/Next period navigation arrows
- "Create New Period" button for management

**Processing Workflow Tabs:**
```
┌─────────────┬─────────────┬─────────────┬─────────────┬─────────────┐
│ 📊 Calculate│ 👁️ Review   │ 💰 Tips     │ ✅ Approve │ 📤 Export  │
└─────────────┴─────────────┴─────────────┴─────────────┴─────────────┘
```

### 4.2 Calculate Tab (`/payroll/calculate`)

**Calculation Controls:**
- "Run Payroll Calculation" button (large, primary)
- Calculation parameters panel:
  - Regular rate overrides
  - Holiday pay multiplier (default 2.0x)
  - Overtime threshold (default 44 hours)
  - Include tip pool checkbox

**Progress Monitoring:**
- Real-time progress bar during calculation
- Step-by-step progress indicators:
  - ✅ Time pairs loaded
  - ✅ Regular hours calculated
  - ✅ Overtime applied
  - ⏳ Government contributions
  - ⏳ Tip distribution

**Results Preview:**
- Summary cards showing totals:
  - Total gross pay
  - Total overtime pay
  - Total government contributions
  - Net payroll amount

### 4.3 Review Tab (`/payroll/review`)

**Payroll Summary Table:**
- **Columns:** Employee, Regular Hours, OT Hours, Gross Pay, NIB, NHIP, Tips, Net Pay
- **Summary Row:** Totals for each column with bold formatting
- **Detail Expansion:** Click row to see calculation breakdown

**Review Controls:**
- Filter by department (FOH/BOH/All)
- Sort by any column
- "Flag for Review" checkbox for problematic records
- Override capabilities for management users

**Calculation Details Modal:**
When row is clicked, shows:
- Detailed time breakdown
- Rate calculations
- Government contribution details
- Tip allocation details
- Edit buttons for override (with audit trail)

### 4.4 Tips Tab (`/payroll/tips`)

**Tip Pool Overview:**
- **Input Section:**
  - Total tip pool amount input (currency)
  - Credit card processing fee percentage (default 4%)
  - Surcharge amount (30% service charge)
  - Net tip pool calculation (auto-calculated)

**Distribution Visualization:**
- Pie chart showing FOH (80%) vs BOH (20%) split
- Total amounts for each department
- Per-hour tip rate calculation

**Employee Allocation Table:**
- **Columns:** Employee, Department, Hours Worked, Tip Rate, Total Tips
- **FOH Section:** Sorted by hours, showing individual allocations
- **BOH Section:** Sorted by hours, showing individual allocations
- **Summary:** Total verification matching tip pool

**Distribution Controls:**
- "Recalculate Distribution" button
- "Manual Override" toggles for special cases
- "Apply Tips to Payroll" button (primary action)

### 4.5 Approval Tab (`/payroll/approval`)

**Final Review Dashboard:**
- **Summary Cards:**
  - Employees processed: X
  - Total gross payroll: $X,XXX
  - Total net payroll: $X,XXX
  - Tips distributed: $X,XXX

**Pre-Approval Checklist:**
- ✅ All time records processed
- ✅ Calculations verified
- ✅ Tips distributed
- ✅ Government contributions calculated
- ✅ Bank files can be generated

**Approval Actions:**
- "Approve Payroll" button (large, green, requires confirmation)
- "Generate Pay Stubs" button
- "Export to QuickBooks" button
- "Create Bank Files" button

**Security Features:**
- Digital signature requirement for approval
- Audit trail showing who approved and when
- Lock payroll after approval (prevents changes)

### 4.6 Export Tab (`/payroll/export`)

**Export Options Grid:**

**Pay Stubs Section:**
- "Generate All Pay Stubs" button (PDF)
- "Email Pay Stubs" button (with preview option)
- Individual employee pay stub downloads

**Banking Section:**
- **FCIB Bank File** - Generate and download
- **Scotiabank File** - Generate and download  
- **RBC File** - Generate and download
- "Email to Banks" option

**Accounting Section:**
- "QuickBooks Export" (QBO file download)
- "Excel Summary" (detailed spreadsheet)
- "Government Reports" (NIB/NHIP compliance)

**Archive Section:**
- "Archive Payroll Period" button
- "Download Complete Archive" (ZIP file)

---

## 5. Reports Section (`/reports`)

### 5.1 Reports Dashboard (`/reports`)

**Report Categories Grid (2x3 layout):**

**Employee Reports Card:**
- Employee summary reports
- Time tracking analysis
- Performance metrics
- "Generate Report" button

**Payroll Reports Card:**
- Pay period summaries
- Year-to-date reports
- Comparative analysis
- "Generate Report" button

**Government Reports Card:**
- NIB contribution reports
- NHIP compliance reports
- Tax summary reports
- "Generate Report" button

**Financial Reports Card:**
- Cost analysis
- Department comparisons
- Trend analysis
- "Generate Report" button

**Tip Reports Card:**
- Tip distribution analysis
- FOH/BOH comparisons
- Historical trends
- "Generate Report" button

**Custom Reports Card:**
- Report builder tool
- Saved custom reports
- Scheduled reports
- "Create Report" button

### 5.2 Employee Reports Page (`/reports/employees`)

**Report Parameters Panel:**
- Date range picker (default: current year)
- Employee selection (multi-select dropdown)
- Department filter (All/FOH/BOH)
- Report type selection:
  - Summary Report
  - Detailed Time Analysis
  - Pay History
  - Performance Metrics

**Preview Section:**
- Live preview of report with sample data
- Export format options (PDF, Excel, CSV)
- "Generate Full Report" button

**Generated Reports List:**
- Table of previously generated reports
- Columns: Report Name, Type, Date Generated, Parameters, Download
- Search and filter capabilities

### 5.3 Government Reports Page (`/reports/government`)

**Compliance Dashboard:**
- NIB contributions status
- NHIP enrollment tracking
- Upcoming filing deadlines
- Compliance score indicator

**Report Generation:**
- **NIB Reports**
  - Monthly contribution reports
  - Employee listing with contributions
  - Payment vouchers
  
- **NHIP Reports**
  - Enrollment status reports
  - Premium calculations
  - Compliance verification

**Filing Calendar:**
- Visual calendar showing filing deadlines
- Automated reminders
- Status tracking for submitted reports

---

## 6. Settings Section (`/settings`)

### 6.1 Settings Dashboard (`/settings`)

**Settings Categories (Card Grid):**

**Company Settings Card:**
- Basic company information
- Holiday calendar management
- Pay period configuration
- "Configure" button

**Payroll Settings Card:**
- Overtime rules
- Government contribution rates
- Tip distribution rules
- "Configure" button

**System Settings Card:**
- User management
- Security settings
- Backup configuration
- "Configure" button

**Integration Settings Card:**
- Bank connections
- QuickBooks setup
- API configurations
- "Configure" button

### 6.2 Company Settings (`/settings/company`)

**Company Information Form:**
- Company name, address, phone
- Registration numbers (business license, NIB, NHIP)
- Logo upload area
- Contact information

**Holiday Calendar:**
- List of Turks & Caicos holidays with dates
- "Add Custom Holiday" button
- Holiday pay multiplier settings
- Preview of upcoming holidays

**Pay Period Configuration:**
- Bi-weekly schedule settings
- Pay period start dates
- Payroll processing deadlines
- Automatic period creation settings

### 6.3 Payroll Settings (`/settings/payroll`)

**Calculation Rules:**
- Overtime threshold (hours input, default 44)
- Overtime multiplier (decimal input, default 1.5)
- Holiday pay multiplier (decimal input, default 2.0)
- Minimum wage settings

**Government Compliance:**
- NIB contribution rates (employer/employee percentages)
- NHIP premium amounts
- Tax withholding rules
- Government account numbers

**Tip Distribution:**
- FOH percentage (default 80%)
- BOH percentage (default 20%)
- Processing fee percentage (default 4%)
- Surcharge handling rules

### 6.4 User Management (`/settings/users`)

**User List Table:**
- **Columns:** Name, Email, Role, Last Login, Status, Actions
- "Invite New User" button (top right)
- Search and filter capabilities

**Role Management:**
- **Admin:** Full system access
- **Manager:** Payroll processing and approvals
- **Supervisor:** Time clock and employee management
- **Employee:** Read-only access to personal information

**Access Control:**
- Permission matrix showing role capabilities
- Override permissions for specific users
- Activity logging and audit trail

---

## 7. Employee Self-Service Portal (`/employee`)

### 7.1 Employee Dashboard (`/employee/dashboard`)

**Personal Information Card:**
- Employee photo and basic details
- Current position and department
- Contact information
- "Update Profile" link

**Current Pay Period Card:**
- Period dates and status
- Hours worked so far
- Estimated gross pay
- Tip allocation preview

**Recent Pay Stubs:**
- List of last 3 pay stubs with download links
- "View All Pay History" link

**Time Tracking Summary:**
- Recent punch history
- Hours summary for current period
- Any correction notifications

### 7.2 Pay Stub History (`/employee/pay-stubs`)

**Pay Stub List:**
- Table with columns: Pay Period, Gross Pay, Net Pay, Tips, Date, Download
- Search by date range
- Filter options
- Export options for tax purposes

**Pay Stub Detail Modal:**
When row is clicked:
- Complete pay stub display matching printed version
- Breakdown of all calculations
- Download as PDF option
- Print-friendly version

### 7.3 Time Records (`/employee/time-records`)

**Time Punch History:**
- Calendar view with daily punch records
- Detailed list view with all punches
- Filter by date range
- Any corrections applied highlighted

**Summary View:**
- Weekly hour summaries
- Monthly totals
- Overtime tracking
- Holiday hours worked

---

## Universal Design Elements

### Navigation Components

**Primary Header:**
- Fixed position at top
- Company logo/name on left
- Main navigation in center
- User menu on right with avatar

**Breadcrumb Navigation:**
- Appears below header for deep pages
- Shows current page hierarchy
- Clickable parent pages

**Mobile Navigation:**
- Hamburger menu for mobile devices
- Slide-out drawer with full navigation
- Touch-friendly button sizes

### Common UI Patterns

**Data Tables:**
- Consistent styling across all tables
- Sortable headers with visual indicators
- Pagination with page size options
- Export capabilities
- Loading states with skeleton screens

**Form Patterns:**
- Consistent spacing and alignment
- Required field indicators
- Inline validation with helpful messages
- Clear save/cancel buttons
- Auto-save for long forms

**Status Indicators:**
- Color-coded badges for various statuses
- Consistent icons across the system
- Tooltip explanations where needed

### Responsive Design

**Desktop (1024px+):**
- Full multi-column layouts
- All features visible
- Optimal for data entry and analysis

**Tablet (768px-1023px):**
- Simplified multi-column layouts
- Touch-friendly controls
- Optimized for review and approval

**Mobile (320px-767px):**
- Single column layout
- Essential features prioritized
- Simplified navigation

### Security Features

**Authentication:**
- Login page with company branding
- Password reset functionality
- Two-factor authentication support
- Session timeout warnings

**Data Protection:**
- Sensitive data masking
- Audit trail for all changes
- Role-based access control
- Encrypted data transmission

This comprehensive UI structure provides a complete roadmap for building the Lizard Payroll system with a focus on usability, security, and the complex workflows required for restaurant payroll management in the Turks & Caicos Islands environment.