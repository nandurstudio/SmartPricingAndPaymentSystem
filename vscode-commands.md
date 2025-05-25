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

## Database Maintenance Commands

### Database Maintenance Menu

The following command will show an interactive menu for database maintenance tasks:

**Windows:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:maintenance
```

**Linux/Mac:**
```bash
cd /path/to/SmartPricingAndPaymentSystem && php spark db:maintenance
```

This menu provides the following options:

1. **Reset ALL (Drop, Recreate, Seed)**
   - Drops all tables
   - Runs all migrations
   - Seeds the database with initial data:
     - Role data (MroleSeeder)
     - Service type data (ServiceTypeSeeder)
     - Multi-tenant data (MultiTenantSeeder)
     - User data (UserSeeder)
     - Master data (MasterDataSeeder)
     - Transaction data (TransactionSeeder)

2. **Patch Table Names (Standardize)**
   - Standardizes table names according to new naming convention
   - Updates the following tables:
     - mrole -> m_role
     - tusers -> m_user
     - tcategories -> m_category
     - tproducts -> m_product
     - torders -> m_order
     - service_types -> m_service_types
     - tenants -> m_tenants

3. **Cleanup Duplicate Tables**
   - Checks for and removes duplicate tables
   - Migrates data if necessary before dropping old tables
   - Handles the following tables:
     - service_types -> m_service_types
     - tenants -> m_tenants
     - torders -> m_order

4. **Upgrade Structure (Views/References)**
   - Creates views for backward compatibility
   - Sets up views for:
     - tenants -> m_tenants
     - service_types -> m_service_types

### Direct Commands for Maintenance Tasks

If you prefer to run maintenance tasks directly without the menu, you can use these commands:

**Reset ALL:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:maintenance
```
Then select option 1 when prompted.

**Patch Table Names:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:maintenance
```
Then select option 2 when prompted.

**Cleanup Duplicates:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:maintenance
```
Then select option 3 when prompted.

**Upgrade Structure:**
```powershell
cd f:\laragon\www\SmartPricingAndPaymentSystem && php spark db:maintenance
```
Then select option 4 when prompted.

## Note

Ensure your CodeIgniter environment is properly configured before running these commands. You can find more information in the CodeIgniter documentation at https://codeigniter.com/user_guide/dbmgmt/migration.html.
