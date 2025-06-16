# UI/UX Context: Design System and User Experience

## Design Philosophy

### User-Centric Approach
**Primary Users:** Restaurant management staff (non-technical)
**Secondary Users:** Employees viewing pay information
**Tertiary Users:** Accountant/bookkeeper accessing reports

**Core Principle:** Simplicity without sacrificing functionality
- Clear visual hierarchy for complex financial data
- Intuitive workflows for non-technical users
- Immediate feedback for all user actions
- Error prevention through smart defaults and validation

### Accessibility Standards
**WCAG 2.1 AA Compliance Required**
- Keyboard navigation for all interactive elements
- Screen reader compatibility with semantic HTML
- Color contrast ratio minimum 4.5:1
- Alternative text for all images and icons
- Focus indicators for interactive elements

## Visual Design System

### Component Library
**ShadCN-Vue Components with Tailwind CSS v4**
- **Foundation:** Radix-Vue primitives for accessibility
- **Customization:** Copy-paste components with full customization
- **Accessibility:** WCAG 2.1 AA compliant out of the box
- **TypeScript:** Full type safety for all components
- **Integration:** Seamless Tailwind CSS v4 integration

### Color Palette
**Primary Colors (Tailwind CSS v4 Custom Palette):**
- **Brand Blue:** hsl(217, 91%, 60%) `--color-primary` (Professional, trustworthy)
- **Success Green:** hsl(142, 76%, 36%) `--color-success` (Positive actions, completed status)
- **Warning Orange:** hsl(36, 77%, 49%) `--color-warning` (Attention needed, review required)
- **Error Red:** hsl(0, 72%, 51%) `--color-destructive` (Errors, critical issues)
- **Neutral Gray:** hsl(220, 14%, 96%) `--color-muted` (Text, borders, subtle elements)

**Background Colors:**
- **Primary White:** hsl(0, 0%, 100%) `--color-background` (Main content areas)
- **Light Gray:** hsl(220, 14%, 96%) `--color-muted` (Secondary backgrounds)
- **Dark Gray:** hsl(222, 84%, 5%) `--color-card` (Headers, navigation)

**Text Colors:**
- **Primary Text:** hsl(222, 84%, 5%) `--color-foreground` (High contrast readability)
- **Secondary Text:** hsl(215, 16%, 47%) `--color-muted-foreground` (Supporting information)
- **Accent Text:** hsl(217, 91%, 60%) `--color-primary` (Links and interactive elements)

### Typography System
**Font Family:** Inter (Web-safe fallback: system-ui, sans-serif)
- **Excellent readability** for financial data
- **Multiple weights available** (400, 500, 600, 700)
- **Optimized for screens** with good number distinction

**Font Scale:**
```css
text-xs:    0.75rem (12px) // Small labels, metadata
text-sm:    0.875rem (14px) // Table data, form inputs  
text-base:  1rem (16px) // Body text, primary content
text-lg:    1.125rem (18px) // Section headers
text-xl:    1.25rem (20px) // Page titles
text-2xl:   1.5rem (24px) // Main headings
text-3xl:   1.875rem (30px) // Dashboard titles
```

### Spacing System (Tailwind CSS)
**Consistent 4px base unit:**
- **xs:** 0.25rem (4px) - Tight spacing
- **sm:** 0.5rem (8px) - Element padding
- **md:** 1rem (16px) - Standard spacing  
- **lg:** 1.5rem (24px) - Section spacing
- **xl:** 2rem (32px) - Page sections
- **2xl:** 3rem (48px) - Major divisions

## Component Design Patterns

### ShadCN-Vue Component System
**Core Components Used:**
- **Button:** Multiple variants (default, destructive, outline, secondary, ghost)
- **Input:** Form inputs with validation states and proper ARIA labels
- **Table:** Sortable, filterable data tables with pagination
- **Card:** Content containers with consistent spacing and shadows
- **Dialog:** Modal dialogs for confirmations and detailed forms
- **Alert:** Status messages and notifications with icons
- **Badge:** Status indicators and tags
- **Select:** Dropdown menus with search and multi-select capabilities
- **Tabs:** Navigation between related content sections
- **Form:** Form validation with error handling and accessibility

### Data Tables (Critical for Payroll)
**ShadCN-Vue Table Implementation:**
- Sortable columns using `<Table>`, `<TableHeader>`, `<TableBody>` components
- Fixed headers for long scrolling lists with `sticky` positioning
- Zebra striping with built-in `table-row` variants
- Hover states with `hover:bg-muted/50` classes
- Responsive design with horizontal scrolling on mobile

**TypeScript Interfaces:**
```typescript
interface PayrollTableRow {
  id: number;
  employee_name: string;
  regular_hours: number;
  overtime_hours: number;
  gross_pay: number;
  net_pay: number;
  tips_allocated: number;
}
```

**Features:**
- Export functionality (PDF, Excel, CSV) with Button actions
- Filtering with Input components and real-time search
- Pagination using ShadCN-Vue Pagination component
- Row selection with Checkbox components for batch operations
- Inline editing with Dialog modals for corrections

### Form Design Standards
**ShadCN-Vue Form Components:**
- Clear Label components with required field indicators
- Input components with helpful placeholder text and examples
- Real-time validation using Form component with error states
- Smart defaults to reduce user effort
- Consistent FormField wrapper for proper spacing
- Consistent sizing and spacing

**Financial Input Special Requirements:**
- Currency formatting with $ symbols
- Decimal precision controls (2 places for money)
- Thousands separators for readability
- Calculation previews for complex fields
- Range validation with helpful limits

### Navigation Architecture
**Primary Navigation:**
```
Dashboard → Employees → Time Clock → Payroll → Reports → Settings
```

**Secondary Navigation (Contextual):**
- Time Clock: Upload → Review → Corrections → Approve
- Payroll: Calculate → Review → Tips → Approve → Export
- Reports: Pay Stubs → Summaries → Government → QuickBooks

### Dashboard Design
**Key Performance Indicators (KPIs):**
- Current pay period status and progress
- Pending corrections requiring attention
- Employee count and active status
- Recent payroll processing summary

**Visual Elements:**
- Progress bars for pay period completion
- Alert badges for items requiring attention
- Quick action buttons for common tasks
- Recent activity timeline

## User Experience Flows

### Time Clock Processing Flow
```
1. Upload File → 2. Preview Data → 3. Review Corrections → 4. Apply Changes → 5. Generate Work Pairs
   [Upload]      [Validate]      [Manual Review]    [Confirm]     [Complete]
```

**UX Considerations:**
- Drag-and-drop file upload with progress indicators
- Clear preview of data with potential issues highlighted
- Side-by-side comparison for corrections
- Bulk approval options for similar corrections
- Success confirmation with summary statistics

### Payroll Processing Flow
```
1. Select Period → 2. Calculate Hours → 3. Review & Adjust → 4. Process Tips → 5. Final Approval → 6. Generate Outputs
   [Choose PP]    [Auto Calculate] [Manual Review]   [Tip Distribution] [Confirm]    [Export/Print]
```

**UX Considerations:**
- Period selection with clear date ranges
- Real-time calculation updates
- Override capabilities for special circumstances
- Tip distribution visualization
- Multi-format export options

### Employee Self-Service Flow
```
1. Login → 2. View Pay Stub → 3. Time Records → 4. Contact Support
   [Auth]   [Current/History] [Punch History] [Help]
```

**UX Considerations:**
- Simple authentication with password reset
- Mobile-friendly pay stub viewing
- Historical data access with date filters
- Clear contact information for questions

## Responsive Design Strategy

### Breakpoint System
```css
Mobile:    320px - 767px   (Single column, simplified navigation)
Tablet:    768px - 1023px  (Two columns, condensed tables)
Desktop:   1024px+         (Full layout, all features visible)
```

### Mobile-First Considerations
**Critical Mobile Features:**
- Pay stub viewing and downloading
- Basic employee information access
- Time punch history review
- Emergency contact information

**Mobile Optimizations:**
- Touch-friendly button sizes (minimum 44px)
- Simplified navigation with hamburger menu
- Swipe gestures for table navigation
- Optimized forms with appropriate input types

### Progressive Enhancement
**Base Experience:** Core functionality works without JavaScript
**Enhanced Experience:** Rich interactions with Vue.js components
**Premium Experience:** Real-time updates and advanced features

## Interactive Elements

### Button Design System
**Primary Actions:** Blue background, white text, prominent placement
**Secondary Actions:** White background, blue border, blue text
**Destructive Actions:** Red background, white text, confirmation required
**Disabled State:** Gray background, reduced opacity, no interaction

**Button Sizes:**
- **Small:** 32px height for compact spaces
- **Medium:** 40px height for standard forms
- **Large:** 48px height for primary actions

### Form Validation UX
**Real-time Validation:**
- Immediate feedback on blur for critical fields
- Progressive validation during typing for complex rules
- Clear success indicators for valid inputs
- Contextual help text for unclear requirements

**Error Handling:**
- Inline error messages below fields
- Summary of errors at top of form
- Auto-focus to first error field
- Clear instructions for resolution

### Loading States and Feedback
**Loading Indicators:**
- Skeleton screens for table data loading
- Progress bars for file processing
- Spinners for quick operations
- Time estimates for long operations

**Success Feedback:**
- Toast notifications for completed actions
- Inline confirmation messages
- Visual checkmarks for completed steps
- Email confirmations for critical operations

## Data Visualization

### Financial Data Presentation
**Currency Formatting:**
- Consistent decimal places (2 for dollars)
- Thousands separators for readability
- Negative numbers in red with parentheses
- Percentage values with % symbol

**Table Design for Payroll:**
- Fixed-width columns for financial data alignment
- Alternating row colors for readability
- Sortable headers with clear indicators
- Totals and subtotals with emphasized styling

### Charts and Graphs
**Pay Period Trends:**
- Line charts for hours worked over time
- Bar charts for department comparisons
- Pie charts for tip distribution visualization
- Trend indicators for wage changes

**Design Standards:**
- Accessible color combinations
- Clear axis labels and legends
- Interactive tooltips with detailed data
- Export functionality for analysis

## Error Prevention and Recovery

### Smart Defaults
**Form Pre-population:**
- Employee information from previous entries
- Standard work hours based on position
- Default overtime multipliers by role
- Common time corrections based on patterns

### Validation Strategy
**Client-side Validation:**
- Immediate feedback for format errors
- Range checking for reasonable values
- Required field validation before submission
- Cross-field validation for logical consistency

**Server-side Validation:**
- Business rule enforcement
- Database constraint validation
- Security validation for all inputs
- Audit logging for all changes

### Error Recovery
**Graceful Degradation:**
- Offline capability for critical functions
- Auto-save functionality for long forms
- Session timeout warnings with extension option
- Data recovery for browser crashes

## Print and Export Design

### Pay Stub Design
**Professional Layout:**
- Company branding and contact information
- Clear employee identification
- Detailed earnings breakdown
- Government contribution summary
- Net pay prominence with large, bold text

**Print Optimization:**
- Standard 8.5" x 11" paper size
- Adequate margins for hole punching
- High contrast for photocopying
- QR code for digital verification

### Report Layouts
**Summary Reports:**
- Executive summary at top
- Detailed breakdowns by category
- Visual charts for key metrics
- Footer with generation timestamp

**Compliance Reports:**
- Government-required formatting
- Official form layouts where applicable
- Digital signatures and timestamps
- Audit trail information

## Performance Considerations

### Frontend Performance
**Critical Rendering Path:**
- Above-the-fold content loads first
- CSS optimization for initial paint
- JavaScript lazy loading for non-critical features
- Image optimization with WebP format

**User Experience Performance:**
- <100ms response for user interactions
- <1s for page transitions
- <3s for complex calculations
- Progress indicators for operations >1s

### Accessibility Performance
**Screen Reader Optimization:**
- Logical heading hierarchy (H1 → H6)
- Descriptive link text and button labels
- ARIA labels for complex interactions
- Skip links for keyboard navigation

**Cognitive Load Reduction:**
- Consistent navigation patterns
- Clear information architecture
- Minimal required fields in forms
- Contextual help and guidance

This comprehensive UI/UX foundation ensures the system will be both powerful for complex payroll operations and accessible for non-technical restaurant staff.
