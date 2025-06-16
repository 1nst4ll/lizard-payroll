# 🔒 .gitignore Configuration Guide - Lizard Payroll System

## Overview

This document explains the comprehensive .gitignore configuration for the Lizard Payroll System, designed for Laravel 12 with Jetstream, Inertia.js, and sensitive financial data handling.

---

## 📁 File Structure

### Root .gitignore (`/.gitignore`)
- **Purpose:** Handles project-wide exclusions and high-level directory structure
- **Scope:** Entire project including backend, documentation, and exports

### Backend .gitignore (`/backend/.gitignore`)
- **Purpose:** Laravel-specific exclusions with payroll system customizations
- **Scope:** Laravel application directory with enhanced security for financial data

---

## 🛡️ Security-First Approach

### Critical Security Exclusions

#### **Financial Data (NEVER COMMIT)**
```gitignore
# Payroll System Specific
/exports/
/payroll-exports/
/bank-files/
/payment-files/
/nib-exports/
/nhip-exports/
/quickbooks-exports/
/financial-reports/
```

#### **Employee Sensitive Data**
```gitignore
/employee-imports/
/timeclock-data/
/salary-adjustments/
```

#### **Security Credentials**
```gitignore
*.pem
*.key
*.crt
*.cert
*.p12
*.pfx
.env
.env.*
!.env.example
```

---

## 📋 Laravel 12 Framework Exclusions

### Core Laravel Framework
```gitignore
# Laravel Framework
/bootstrap/compiled.php
/bootstrap/cache/*
!/bootstrap/cache/.gitignore
/storage/app/*
!/storage/app/.gitignore
!/storage/app/public
/storage/framework/cache/*
/storage/framework/sessions/*
/storage/framework/testing/*
/storage/framework/views/*
/storage/logs/*
/storage/*.key
/storage/pail
```

### Jetstream & Inertia.js Specific
```gitignore
# Jetstream & Inertia specific
/bootstrap/ssr
/public/js/ssr.js
auth.json
```

---

## 🔧 Development Tools & Dependencies

### Package Managers
```gitignore
# Dependencies
/vendor
/node_modules
package-lock.json
npm-debug.log*
yarn-debug.log*
yarn-error.log*
pnpm-debug.log*
```

### Build Artifacts
```gitignore
# Build artifacts
/public/build
/public/hot
/public/storage
/public/mix-manifest.json
```

### IDE & Editor Files
```gitignore
# IDE and Editor files
/.fleet
/.idea
/.nova
/.phpunit.cache
/.vscode
/.zed
.phpactor.json
```

---

## 🧪 Testing & Quality Assurance

### Test Files & Coverage
```gitignore
# Testing
.phpunit.result.cache
/.phpunit.cache
/phpunit.xml
/coverage/
/tests/coverage/
```

### Logs & Debugging
```gitignore
# Logs
*.log
/storage/logs/*.log
```

---

## 💼 Payroll System Specific Exclusions

### Financial Export Directories
These directories contain sensitive financial data and must NEVER be committed:

#### **Government Compliance Exports**
- `/nib-exports/` - National Insurance Board files
- `/nhip-exports/` - National Health Insurance Plan files

#### **Banking Integration Files**
- `/bank-files/` - FCIB, Scotiabank, RBC payment files
- `/payment-files/` - Generated payment instructions
- `/quickbooks-exports/` - QuickBooks integration files

#### **Payroll Processing**
- `/payroll-exports/` - Generated payroll reports
- `/financial-reports/` - Management and audit reports
- `/employee-imports/` - Employee data import files
- `/timeclock-data/` - Time tracking raw data

### Backup & Temporary Data
```gitignore
# Configuration backups
/config/backups/
/database/backups/

# Temporary processing
/temp-uploads/
/backup-files/
*.tmp
*.temp
*.bak
*.backup
```

---

## 🖥️ Cross-Platform Compatibility

### Operating System Files
```gitignore
# macOS
.DS_Store
.AppleDouble
.LSOverride
.Spotlight-V100
.Trashes

# Windows
Thumbs.db
ehthumbs.db
Desktop.ini

# Linux
*~
```

### Editor Temporary Files
```gitignore
# Temporary files
*.swp
*.swo
*.orig
*.old
```

---

## ⚙️ Environment Configuration

### Environment Files Strategy
```gitignore
# Environment files
.env
.env.*
!.env.example
.env.backup
.env.production
.env.local
.env.testing
```

**Key Points:**
- ✅ **Include:** `.env.example` (template file)
- ❌ **Exclude:** All actual `.env` files with real credentials
- ❌ **Exclude:** Environment backups and production configs

---

## 🚨 Critical Security Reminders

### ⚠️ NEVER COMMIT THESE FILES
1. **Employee personal data** (SSNs, addresses, salaries)
2. **Bank account information** and routing numbers
3. **Government ID numbers** and tax information
4. **Database credentials** and API keys
5. **Payment processing tokens** and certificates
6. **Backup files** containing sensitive data

### 🔍 Regular Security Audits
```bash
# Check for accidentally committed secrets
git log --all --full-history -- "**/.env"
git log --all --full-history -- "**/config/database.php"

# Search for potential sensitive data in commit history
git log --all --source --full-history -S "password"
git log --all --source --full-history -S "secret"
```

---

## 📋 Maintenance Checklist

### Weekly Security Review
- [ ] Verify no sensitive files in recent commits
- [ ] Check export directories are properly excluded
- [ ] Review new file patterns for security implications
- [ ] Audit environment file changes

### Before Each Release
- [ ] Run security scan on repository
- [ ] Verify all financial data directories excluded
- [ ] Check for hardcoded credentials in code
- [ ] Validate .gitignore patterns are working

### Emergency Response
If sensitive data is accidentally committed:
1. **Immediately** rotate all exposed credentials
2. Use `git filter-branch` or BFG to remove from history
3. Force push to all remotes (coordinate with team)
4. Audit access logs for potential exposure
5. Update incident response documentation

---

## 🔧 Customization Guidelines

### Adding New Exclusions
When adding new exclusions:

1. **Categorize appropriately** (Security, Framework, Development, etc.)
2. **Add comments** explaining why files are excluded
3. **Use specific patterns** rather than overly broad wildcards
4. **Test patterns** before committing changes

### Pattern Examples
```gitignore
# Good - Specific and documented
/exports/payroll-*.csv    # Payroll export files

# Bad - Too broad
*.csv                     # Excludes ALL CSV files

# Good - Security-focused
/config/keys/*.pem        # SSL certificates

# Bad - Unclear purpose
/config/*                 # Excludes all config files
```

---

## 📊 Validation Commands

### Test .gitignore Patterns
```bash
# Check if file would be ignored
git check-ignore /path/to/file

# List all ignored files
git status --ignored

# Dry run to see what would be added
git add --dry-run .

# Check specific patterns
git ls-files --others --ignored --exclude-standard
```

### Security Validation
```bash
# Ensure no sensitive files are tracked
find . -name "*.env" -not -path "./backend/.env.example"
find . -path "*/exports/*" -type f
find . -path "*/bank-files/*" -type f

# Verify critical directories are excluded
ls -la exports/ 2>/dev/null || echo "Correctly excluded"
ls -la backend/storage/logs/ 2>/dev/null || echo "Correctly excluded"
```

---

## 📚 Best Practices Summary

### ✅ Do's
- **Be specific** with exclusion patterns
- **Document** security-related exclusions
- **Test** .gitignore changes before committing
- **Review** regularly for new file types
- **Use** separate patterns for different concerns

### ❌ Don'ts
- **Never commit** financial or personal data
- **Avoid** overly broad patterns that exclude needed files
- **Don't ignore** the need for regular security audits
- **Never** commit environment files with real credentials
- **Avoid** platform-specific exclusions in shared repositories

---

## 🎯 Payroll System Compliance

### Regulatory Requirements
The .gitignore configuration supports compliance with:

- **Turks & Caicos Data Protection Laws**
- **Financial institution security requirements**
- **Employment data privacy regulations**
- **Audit trail and record keeping standards**

### Audit Trail Considerations
While excluding sensitive data files, ensure:
- Configuration changes are tracked
- System logs (non-sensitive) are preserved
- Documentation updates are committed
- Compliance artifacts are properly managed

---

**Updated:** December 15, 2025
**Next Review:** January 15, 2026
**Security Level:** HIGH - Financial Data System
**Compliance:** TC Data Protection, Banking Security Standards

This .gitignore configuration provides enterprise-level security for the Lizard Payroll System while maintaining development efficiency and regulatory compliance.
