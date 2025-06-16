# Project Brief: Turks Kebab Restaurant Management System

*This is the foundation document that shapes all other Memory Bank files.*

## Related Documentation
- **Why This Exists:** [productContext.md](productContext.md) - Business problems and solution approach
- **How It Works:** [systemPatterns.md](systemPatterns.md) - Technical architecture and patterns
- **Technology:** [techContext.md](techContext.md) - Implementation stack and tools
- **User Experience:** [uiuxContext.md](uiuxContext.md) - Design system and workflows
- **Current Status:** [activeContext.md](activeContext.md) & [progress.md](progress.md)

## Project Overview
**Project Name:** Lizard Payroll (Turks Kebab Restaurant Management System)  
**Location:** D:\ClaudeMCP\!projects\lizard-payroll  
**Client:** Turks Kebab Restaurant, Turks & Caicos Islands  
**Developer:** Daniel Hoffmann (daniel@hoffmanntci.com)

## Core Mission
Develop a comprehensive web-based restaurant management system that integrates time tracking, payroll processing, tip distribution, multi-bank payment routing, and government compliance into a unified platform designed specifically for the Turks & Caicos Islands business environment.

## Business Context
- **Industry:** Restaurant/Food Service
- **Location:** Turks & Caicos Islands
- **Compliance Requirements:** NIB (National Insurance Board), NHIP (National Health Insurance Plan)
- **Banking:** Multi-bank environment (FCIB, Scotiabank, RBC)
- **Operational Complexity:** Two departments (BOH - Back of House, FOH - Front of House)

## Key Stakeholders
- **Restaurant Owner/Management:** Primary system users
- **Employees:** Time tracking and pay stub access
- **Government Agencies:** NIB/NHIP compliance reporting
- **Banking Partners:** Payment processing integration
- **Accountant/Bookkeeper:** QuickBooks integration needs

## Success Criteria
1. **Accuracy:** 100% accurate payroll calculations including overtime, tips, and government contributions
2. **Compliance:** Full adherence to Turks & Caicos labor laws and tax requirements
3. **Efficiency:** Reduce payroll processing time from hours to minutes
4. **Transparency:** Clear pay stubs and tip distribution visibility
5. **Integration:** Seamless QuickBooks export and multi-bank payment processing
6. **Security:** Protect sensitive employee and financial data
7. **Usability:** Intuitive interface for non-technical users

## Technology Stack Foundation
- **Backend:** Laravel 10.5 + PostgreSQL 15.3
- **Frontend:** Vue 3 + Inertia.js 1.3
- **Time Processing:** Carbon 2.65 + Custom algorithms
- **File Processing:** Laravel Excel 3.1
- **PDF Generation:** DomPDF 1.2.2 / Snappy 1.0.7
- **Security:** Spatie Permissions 5.8
- **Testing:** Pest (Latest) + Jest 29.5 + Dusk 10.5

## Core Functional Requirements
1. **Employee Management:** Complete employee profiles with compliance status
2. **Time Clock Processing:** Smart interpretation of punch data with correction algorithms
3. **Payroll Calculation:** Bi-weekly processing with overtime and holiday pay
4. **Tip Distribution:** Automated allocation based on hours and department
5. **Government Compliance:** NIB/NHIP contribution calculations
6. **Multi-Bank Payments:** Support for multiple banking relationships
7. **Reporting:** Pay stubs, summaries, and QuickBooks export
8. **Access Control:** Role-based permissions for different user types

## Technical Constraints
- **Performance:** Handle 50+ employees with real-time processing
- **Security:** OWASP Top 10 compliance mandatory
- **Accessibility:** WCAG 2.1 AA compliance
- **Browser Support:** Modern browsers (Chrome, Firefox, Safari, Edge)
- **Mobile Responsive:** Full functionality on tablets and phones
- **Backup:** Daily automated backups with disaster recovery
- **Audit Trail:** Complete logging of all financial transactions

## Unique Business Rules
- **Holiday Calendar:** Turks & Caicos specific holidays with 2x pay multiplier
- **Tip Pool:** 80% FOH, 20% BOH distribution
- **Overtime:** 44-hour threshold (TC labor law)
- **Processing Fees:** 4% credit card fee deduction from tips
- **Surcharge Addition:** 30% service charge added to tip pool
- **Bi-weekly Payroll:** Two-week pay periods with specific processing dates

## Risk Considerations
- **Data Loss:** Financial data critical - robust backup strategy required
- **Compliance Violations:** Government penalties for incorrect calculations
- **Security Breaches:** Employee PII and financial data protection
- **System Downtime:** Payroll processing delays impact employee satisfaction
- **Calculation Errors:** Mathematical mistakes can affect employee trust and legal compliance

## Project Phases
1. **Phase 1:** Core infrastructure and employee management
2. **Phase 2:** Time tracking and basic payroll
3. **Phase 3:** Tip distribution and advanced calculations
4. **Phase 4:** Government compliance and reporting
5. **Phase 5:** Multi-bank integration and QuickBooks export
6. **Phase 6:** Testing, security audit, and deployment

## Quality Standards
- **Code Quality:** PSR standards, comprehensive documentation
- **Testing:** 90%+ code coverage requirement
- **Security:** Third-party security audit before production
- **Performance:** <2 second page load times
- **Reliability:** 99.9% uptime requirement
- **Maintainability:** Clear documentation and modular architecture