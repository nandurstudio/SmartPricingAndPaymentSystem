# VS Code Terminal Commands for SmartPricingAndPaymentSystem

This document provides commands that can be executed directly in VS Code's integrated terminal to manage database migrations and seeding.

## Database Migration Commands

### Run All Migrations

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate
```

### Rollback Last Batch of Migrations

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate:rollback
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate:rollback
```

### Refresh Database (Rollback All + Migrate Again)

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate:refresh
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate:refresh
```

### Reset Database (Rollback All)

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate:reset
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate:reset
```

### View Migration Status

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate:status
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate:status
```

## Seeding Commands

### Run MroleSeeder

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:seed MroleSeeder
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark db:seed MroleSeeder
```

### Run MultiTenantSeeder

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:seed MultiTenantSeeder
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark db:seed MultiTenantSeeder
```

## Complete Workflow Commands

### Full Migration + Seeding

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate && php spark db:seed MroleSeeder && php spark db:seed MultiTenantSeeder
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate && php spark db:seed MroleSeeder && php spark db:seed MultiTenantSeeder
```

### Full Refresh + Seeding

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migrate:refresh && php spark db:seed MroleSeeder && php spark db:seed MultiTenantSeeder
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migrate:refresh && php spark db:seed MroleSeeder && php spark db:seed MultiTenantSeeder
```

### Custom Migration Command

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark migration migrate
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark migration migrate
```

## Note

Ensure your CodeIgniter environment is properly configured before running these commands. You can find more information in the CodeIgniter documentation at https://codeigniter.com/user_guide/dbmgmt/migration.html.
