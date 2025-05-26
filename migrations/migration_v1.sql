-- Drop tables if exist (to avoid error on re-run)
DROP TABLE IF EXISTS `tr_audit_log`;
DROP TABLE IF EXISTS `tr_service_custom_values`;
DROP TABLE IF EXISTS `tr_bookings`;
DROP TABLE IF EXISTS `m_service_type_attributes`;
DROP TABLE IF EXISTS `m_schedules`;
DROP TABLE IF EXISTS `m_services`;
DROP TABLE IF EXISTS `tr_transaction`;
DROP TABLE IF EXISTS `tr_transactiondetail`;
DROP TABLE IF EXISTS `m_productcategory`;
DROP TABLE IF EXISTS `m_order`;
DROP TABLE IF EXISTS `m_role_menu`;
DROP TABLE IF EXISTS `m_menu`;
DROP TABLE IF EXISTS `m_user`;
DROP TABLE IF EXISTS `m_product`;
DROP TABLE IF EXISTS `m_category`;
DROP TABLE IF EXISTS `m_payment`;
DROP TABLE IF EXISTS `m_shipping`;
DROP TABLE IF EXISTS `m_orderstatus`;
DROP TABLE IF EXISTS `m_service_types`;
DROP TABLE IF EXISTS `m_tenants`;
DROP TABLE IF EXISTS `m_role`;

-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS db_smartpricingandpaymentsystem;

USE db_smartpricingandpaymentsystem;

-- m_role table
CREATE TABLE `m_role` (
    `intRoleID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `txtRoleName` VARCHAR(50) NOT NULL,
    `txtRoleDesc` TEXT DEFAULT NULL,
    `txtRoleNote` TEXT DEFAULT NULL,
    `bitStatus` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intRoleID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_service_types table
CREATE TABLE `m_service_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(255) DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `is_system` BOOLEAN DEFAULT FALSE,
    `is_approved` BOOLEAN DEFAULT FALSE,
    `requested_by` INT(11) UNSIGNED DEFAULT NULL,
    `approved_by` INT(11) UNSIGNED DEFAULT NULL,
    `approved_date` DATETIME DEFAULT NULL,
    `default_attributes` JSON DEFAULT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_service_types_guid` (`guid`),
    UNIQUE KEY `uk_service_types_slug` (`slug`),
    KEY `idx_service_types_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_tenants table
CREATE TABLE `m_tenants` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `domain` VARCHAR(255) DEFAULT NULL,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `owner_id` INT(11) UNSIGNED NOT NULL,
    `subscription_plan` ENUM('free','basic','premium','enterprise') DEFAULT 'free',
    `status` ENUM('active','inactive','suspended','pending') DEFAULT 'pending',
    `settings` JSON DEFAULT NULL,
    `payment_settings` JSON DEFAULT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_tenants_guid` (`guid`),
    UNIQUE KEY `uk_tenants_slug` (`slug`),
    KEY `idx_tenants_service_type` (`service_type_id`),
    KEY `idx_tenants_owner` (`owner_id`),
    CONSTRAINT `fk_tenants_service_type` FOREIGN KEY (`service_type_id`) REFERENCES `m_service_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_user table
CREATE TABLE `m_user` (
    `intUserID` INT(10) NOT NULL AUTO_INCREMENT,
    `intRoleID` INT(10) UNSIGNED NOT NULL DEFAULT '5',
    `tenant_id` INT(11) UNSIGNED DEFAULT NULL,
    `txtUserName` VARCHAR(50) NOT NULL DEFAULT 'dummy.nick',
    `txtFullName` VARCHAR(100) NOT NULL,
    `txtEmail` VARCHAR(100) NOT NULL DEFAULT 'dummy@email.com',
    `txtPassword` VARCHAR(255) NOT NULL,
    `bitActive` TINYINT(1) NULL DEFAULT '1',
    `bitOnlineStatus` TINYINT(1) NULL DEFAULT '0',
    `dtmLastLogin` DATETIME NULL DEFAULT NULL,
    `txtCreatedBy` VARCHAR(50) NULL DEFAULT 'system',
    `dtmCreatedDate` DATETIME NULL DEFAULT NULL,
    `txtUpdatedBy` VARCHAR(50) NULL DEFAULT 'system',
    `dtmUpdatedDate` DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    `reset_token` VARCHAR(100) NULL DEFAULT NULL,
    `token_created_at` DATETIME NULL DEFAULT NULL,
    `google_auth_token` VARCHAR(255) NULL DEFAULT NULL,
    `txtPhoto` VARCHAR(255) NULL DEFAULT 'default.png',
    `dtmJoinDate` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`intUserID`),
    FOREIGN KEY (`intRoleID`) REFERENCES `m_role` (`intRoleID`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_menu table
CREATE TABLE `m_menu` (
    `intMenuID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `txtMenuName` VARCHAR(100) NOT NULL,
    `txtMenuLink` VARCHAR(255) NULL,
    `txtIcon` VARCHAR(50) NULL,
    `intParentID` INT(10) UNSIGNED NULL,
    `intSortOrder` INT(10) DEFAULT 0,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP NULL DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`intMenuID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_role_menu table
CREATE TABLE `m_role_menu` (
    `intRoleMenuID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `intRoleID` INT(10) UNSIGNED NOT NULL,
    `intMenuID` INT(10) UNSIGNED NOT NULL,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP NULL DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`intRoleMenuID`),
    KEY `idx_role_menu_role` (`intRoleID`),
    KEY `idx_role_menu_menu` (`intMenuID`),
    CONSTRAINT `fk_role_menu_role` FOREIGN KEY (`intRoleID`) REFERENCES `m_role` (`intRoleID`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_role_menu_menu` FOREIGN KEY (`intMenuID`) REFERENCES `m_menu` (`intMenuID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_category table
CREATE TABLE `m_category` (
    `intCategoryID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtCategoryName` VARCHAR(100) NOT NULL,
    `txtDesc` TEXT NULL,
    `icon` VARCHAR(255) DEFAULT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `service_type_id` INT(11) UNSIGNED DEFAULT NULL,
    `tenant_id` INT(11) UNSIGNED DEFAULT NULL,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` DATETIME DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` DATETIME DEFAULT NULL,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intCategoryID`),
    FOREIGN KEY (`service_type_id`) REFERENCES `m_service_types` (`id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_product table
CREATE TABLE `m_product` (
    `intProductID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtProductName` VARCHAR(255) NOT NULL,
    `txtProductDescription` TEXT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` DATETIME DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` DATETIME DEFAULT NULL,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    `icon` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`intProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_productcategory table
CREATE TABLE `m_productcategory` (
    `intProductCategoryID` INT(10) NOT NULL AUTO_INCREMENT,
    `intProductID` INT(10) NOT NULL,
    `intCategoryID` INT(10) NOT NULL,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intProductCategoryID`),
    KEY `intProductID` (`intProductID`),
    KEY `intCategoryID` (`intCategoryID`),
    FOREIGN KEY (`intProductID`) REFERENCES `m_product` (`intProductID`),
    FOREIGN KEY (`intCategoryID`) REFERENCES `m_category` (`intCategoryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_services table
CREATE TABLE `m_services` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) UNIQUE,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `duration` INT UNSIGNED DEFAULT 60,
    `max_capacity` INT UNSIGNED DEFAULT 1,
    `custom_attributes` JSON DEFAULT NULL,
    `settings` JSON DEFAULT NULL,
    `status` ENUM('active','inactive','maintenance') DEFAULT 'active',
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tenant_id` (`tenant_id`),
    KEY `service_type_id` (`service_type_id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`),
    FOREIGN KEY (`service_type_id`) REFERENCES `m_service_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_schedules table
CREATE TABLE `m_schedules` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) UNIQUE,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NOT NULL,
    `is_available` BOOLEAN DEFAULT TRUE,
    `max_bookings` INT UNSIGNED DEFAULT 1,
    `current_bookings` INT UNSIGNED DEFAULT 0,
    `price_override` DECIMAL(10,2) DEFAULT NULL,
    `notes` TEXT,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- m_service_type_attributes table
CREATE TABLE `m_service_type_attributes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(100) NOT NULL,
    `label` VARCHAR(255) NOT NULL,
    `type` ENUM('text','number','boolean','select','date','time','datetime') NOT NULL,
    `options` JSON DEFAULT NULL,
    `is_required` BOOLEAN DEFAULT FALSE,
    `validation_rules` JSON DEFAULT NULL,
    `default_value` VARCHAR(255) DEFAULT NULL,
    `sort_order` INT UNSIGNED DEFAULT 0,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `service_type_id` (`service_type_id`),
    FOREIGN KEY (`service_type_id`) REFERENCES `m_service_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tr_bookings table
CREATE TABLE `tr_bookings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) UNIQUE,
    `user_id` INT(10) NOT NULL,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `schedule_id` INT(11) UNSIGNED NOT NULL,
    `booking_date` DATETIME NOT NULL,
    `status` ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
    `payment_status` ENUM('unpaid','paid','refunded') DEFAULT 'unpaid',
    `quantity` INT UNSIGNED DEFAULT 1,
    `price` DECIMAL(10,2) NOT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `notes` TEXT,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `service_id` (`service_id`),
    KEY `schedule_id` (`schedule_id`),
    FOREIGN KEY (`user_id`) REFERENCES `m_user` (`intUserID`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`),
    FOREIGN KEY (`schedule_id`) REFERENCES `m_schedules` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tr_service_custom_values table
CREATE TABLE `tr_service_custom_values` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `attribute_id` INT(11) UNSIGNED NOT NULL,
    `value` TEXT,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `service_id` (`service_id`),
    KEY `attribute_id` (`attribute_id`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`),
    FOREIGN KEY (`attribute_id`) REFERENCES `m_service_type_attributes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- tr_audit_log table
CREATE TABLE `tr_audit_log` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT(10) NOT NULL,
    `action` VARCHAR(50) NOT NULL,
    `table_name` VARCHAR(50) NOT NULL,
    `record_id` VARCHAR(36) NOT NULL,
    `old_values` JSON DEFAULT NULL,
    `new_values` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT,
    `created_date` DATETIME DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`user_id`) REFERENCES `m_user` (`intUserID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeder data for m_role
INSERT INTO m_role (intRoleID, txtRoleName, txtRoleDesc, txtRoleNote, bitStatus, txtGUID) VALUES
(1, 'Super Administrator', 'Full access to all system features including multi-tenant management', 'Highest level system administrator', 1, UUID()),
(2, 'Administrator', 'System administrator with limited tenant management access', 'System administrator for specific operations', 1, UUID()),
(3, 'Tenant Owner', 'Business owner with full access to their tenant services', 'Can manage their own tenant services and bookings', 1, UUID()),
(4, 'Tenant Staff', 'Staff member of a tenant with limited access', 'Can manage bookings and basic service operations', 1, UUID()),
(5, 'Customer', 'End user who can make bookings', 'Regular user with booking capabilities', 1, UUID()),
(6, 'Guest', 'Unregistered user with view-only access', 'Limited to viewing public information', 1, UUID());

-- Seeder data untuk m_service_types
INSERT INTO m_service_types (guid, name, slug, description, icon, category, is_system, is_approved, default_attributes, is_active, created_date, created_by) VALUES
('ST001', 'Futsal', 'futsal', 'Futsal court booking service', 'fas fa-futbol', 'sports', 1, 1, '{"court_size":{"type":"select","options":["small","medium","large"]}}', 1, NOW(), 'system'),
('ST002', 'Villa', 'villa', 'Villa booking service', 'fas fa-home', 'accommodation', 1, 1, '{"rooms":{"type":"number","min":1,"max":10}}', 1, NOW(), 'system');

-- Menu structure seeder - parent menus
INSERT INTO m_menu (intMenuID, txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive) VALUES
(1, 'Dashboard', '/dashboard', 'activity', NULL, 1, 1),
(2, 'Master Data', '#', 'database', NULL, 2, 1),
(3, 'Tenant', '#', 'home', NULL, 3, 1),
(4, 'Services', '#', 'package', NULL, 4, 1),
(5, 'Bookings', '#', 'book-open', NULL, 5, 1),
(6, 'Reports', '#', 'bar-chart-2', NULL, 6, 1),
(7, 'Settings', '#', 'settings', NULL, 7, 1);

-- Now add the self-referencing foreign key
ALTER TABLE `m_menu` 
ADD CONSTRAINT `fk_menu_parent` 
FOREIGN KEY (`intParentID`) REFERENCES `m_menu` (`intMenuID`) 
ON DELETE SET NULL;

-- Menu structure seeder - level 2 (child menus)
INSERT INTO m_menu (txtMenuName, txtMenuLink, txtIcon, intParentID, intSortOrder, bitActive) VALUES
-- Master Data children
('Users', '/users', 'users', 2, 1, 1),
('Roles', '/roles', 'shield', 2, 2, 1),
('Service Types', '/service-types', 'grid', 2, 3, 1),
('Categories', '/categories', 'folder', 2, 4, 1),
('Tenant List', '/tenants', 'list', 3, 1, 1),-- Tenant Management children
('Tenant Services', '/tenant-services', 'briefcase', 3, 2, 1),
('Service List', '/services', 'list', 4, 1, 1),-- Service Management children
('Schedules', '/schedules', 'calendar', 4, 2, 1),
('Service Attributes', '/service-attributes', 'sliders', 4, 3, 1),
('Booking List', '/bookings', 'bookmark', 5, 1, 1),-- Booking Management children
('Calendar View', '/booking-calendar', 'calendar', 5, 2, 1),
('Booking Reports', '/reports/bookings', 'file-text', 6, 1, 1),-- Reports children
('Revenue Reports', '/reports/revenue', 'dollar-sign', 6, 2, 1),
('Usage Reports', '/reports/usage', 'trending-up', 6, 3, 1),
('System Settings', '/settings/system', 'tool', 7, 1, 1),-- Settings children
('User Profile', '/settings/profile', 'user', 7, 2, 1);

-- Role Menu Access
-- Super Administrator (Full Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 1, intMenuID FROM m_menu;

-- Administrator (Limited Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 2, intMenuID FROM m_menu 
WHERE txtMenuLink IN ('/dashboard', '/bookings', '/booking-calendar', '/settings/profile');

-- Tenant Owner (Business Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 3, intMenuID FROM m_menu 
WHERE txtMenuLink IN (
    '/dashboard',
    '/tenant-services',
    '/services',
    '/schedules',
    '/service-attributes',
    '/bookings',
    '/booking-calendar',
    '/reports/bookings',
    '/reports/revenue',
    '/reports/usage',
    '/settings/profile'
);

-- Tenant Staff (Operational Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 4, intMenuID FROM m_menu 
WHERE txtMenuLink IN (
    '/dashboard',
    '/services',
    '/schedules',
    '/bookings',
    '/booking-calendar',
    '/settings/profile'
);

-- Customer (Limited Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 5, intMenuID FROM m_menu 
WHERE txtMenuLink IN (
    '/dashboard',
    '/bookings',
    '/booking-calendar',
    '/settings/profile'
);

-- Guest (View Only Access)
INSERT INTO m_role_menu (intRoleID, intMenuID) 
SELECT 6, intMenuID FROM m_menu 
WHERE txtMenuLink IN (
    '/dashboard'
);