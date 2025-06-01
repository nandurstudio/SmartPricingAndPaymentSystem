-- Backup dari migration_v1.sql per 2025-05-25
-- Struktur dan seeder sesuai file migration_v1.sql

-- ...existing code dari migration_v1.sql...
-- Drop tables if exist (to avoid error on re-run)
DROP TABLE IF EXISTS `tr_audit_log`;

DROP TABLE IF EXISTS `tr_service_custom_values`;

DROP TABLE IF EXISTS `tr_bookings`;

DROP TABLE IF EXISTS `m_service_type_attributes`;

DROP TABLE IF EXISTS `m_schedules`;

DROP TABLE IF EXISTS `m_services`;

DROP TABLE IF EXISTS `m_role_menu`;

DROP TABLE IF EXISTS `m_menu`;

DROP TABLE IF EXISTS `m_user`;

DROP TABLE IF EXISTS `m_service_types`;

DROP TABLE IF EXISTS `m_tenants`;

DROP TABLE IF EXISTS `m_role`;

-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS db_smartpricingandpaymentsystem;

USE db_smartpricingandpaymentsystem;

-- m_tenants table
CREATE TABLE `m_tenants` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `domain` VARCHAR(255) DEFAULT NULL,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `owner_id` INT(11) UNSIGNED NOT NULL,
    `subscription_plan` ENUM(
        'free',
        'basic',
        'premium',
        'enterprise'
    ) DEFAULT 'free',
    `status` ENUM(
        'active',
        'inactive',
        'suspended',
        'pending'
    ) DEFAULT 'pending',
    `settings` JSON DEFAULT NULL,
    `payment_settings` JSON DEFAULT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `service_type_id` (`service_type_id`),
    KEY `owner_id` (`owner_id`),
    KEY `slug` (`slug`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_service_types table
CREATE TABLE `m_service_types` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `guid` VARCHAR(36) UNIQUE,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
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
    UNIQUE KEY `guid` (`guid`),
    UNIQUE KEY `slug` (`slug`),
    KEY `category` (`category`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_user table
CREATE TABLE `m_user` (
    `intUserID` INT(10) NOT NULL AUTO_INCREMENT,
    `intRoleID` INT(10) NOT NULL DEFAULT '5',
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

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
    `txtGUID` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`intRoleID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

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
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(''),
    `icon` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`intProductID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

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
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(''),
    PRIMARY KEY (`intCategoryID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_order table
CREATE TABLE `m_order` (
    `intOrderID` INT(10) NOT NULL AUTO_INCREMENT,
    `intUserID` INT(10) NOT NULL,
    `txtOrderStatus` VARCHAR(50) NOT NULL,
    `txtPaymentStatus` VARCHAR(50) NOT NULL,
    `dtmOrderDate` DATETIME NULL DEFAULT NULL,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intOrderID`),
    FOREIGN KEY (`intUserID`) REFERENCES `m_user` (`intUserID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- tr_transaction table
CREATE TABLE `tr_transaction` (
    `intTransactionID` INT(10) NOT NULL AUTO_INCREMENT,
    `intUserID` INT(10) NOT NULL,
    `intProductID` INT(10) NOT NULL,
    `txtTransactionStatus` VARCHAR(50) NOT NULL,
    `bitStatus` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intTransactionID`),
    FOREIGN KEY (`intUserID`) REFERENCES `m_user` (`intUserID`),
    FOREIGN KEY (`intProductID`) REFERENCES `m_product` (`intProductID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- tr_transactiondetail table
CREATE TABLE `tr_transactiondetail` (
    `intTransactionDetailID` INT(10) NOT NULL AUTO_INCREMENT,
    `intTransactionID` INT(10) NOT NULL,
    `intProductID` INT(10) NOT NULL,
    `intQuantity` INT(11) NOT NULL,
    `txtSubtotal` DECIMAL(10, 2) NOT NULL,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intTransactionDetailID`),
    FOREIGN KEY (`intTransactionID`) REFERENCES `tr_transaction` (`intTransactionID`),
    FOREIGN KEY (`intProductID`) REFERENCES `m_product` (`intProductID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_productcategory (Many to Many Relationship between Products and Categories)
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
    FOREIGN KEY (`intProductID`) REFERENCES `m_product` (`intProductID`),
    FOREIGN KEY (`intCategoryID`) REFERENCES `m_category` (`intCategoryID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_payment table (optional if you want to manage payment methods)
CREATE TABLE `m_payment` (
    `intPaymentID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtPaymentMethod` VARCHAR(50) NOT NULL,
    `txtPaymentDetails` TEXT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intPaymentID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_shipping table (optional for shipping methods)
CREATE TABLE `m_shipping` (
    `intShippingID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtShippingMethod` VARCHAR(50) NOT NULL,
    `txtShippingDetails` TEXT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intShippingID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_orderstatus table
CREATE TABLE `m_orderstatus` (
    `intOrderStatusID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtOrderStatusName` VARCHAR(50) NOT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT NULL,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intOrderStatusID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_menu (master menu)
CREATE TABLE `m_menu` (
    `intMenuID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtMenuName` VARCHAR(100) NOT NULL,
    `txtMenuLink` VARCHAR(255) NOT NULL,
    `txtIcon` VARCHAR(50) DEFAULT NULL,
    `intParentID` INT(10) DEFAULT NULL,
    `intSortOrder` INT(10) DEFAULT 0,
    `bitActive` TINYINT(1) DEFAULT 1,
    PRIMARY KEY (`intMenuID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_role_menu (relasi role ke menu)
CREATE TABLE `m_role_menu` (
    `intRoleMenuID` INT(10) NOT NULL AUTO_INCREMENT,
    `intRoleID` INT(10) NOT NULL,
    `intMenuID` INT(10) NOT NULL,
    PRIMARY KEY (`intRoleMenuID`),
    FOREIGN KEY (`intRoleID`) REFERENCES `m_role` (`intRoleID`),
    FOREIGN KEY (`intMenuID`) REFERENCES `m_menu` (`intMenuID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_services table
CREATE TABLE `m_services` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `price` DECIMAL(12, 2) DEFAULT 0.00,
    `duration` INT(11) DEFAULT 60,
    `settings` JSON DEFAULT NULL,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_schedules table
CREATE TABLE `m_schedules` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `slot_date` DATE NOT NULL,
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `interval` INT(11) DEFAULT 1,
    `duration` INT(11) DEFAULT 60,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tenant_id` (`tenant_id`),
    KEY `service_id` (`service_id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_service_type_attributes table
CREATE TABLE `m_service_type_attributes` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `service_type_id` INT(11) UNSIGNED NOT NULL,
    `attribute_name` VARCHAR(255) NOT NULL,
    `attribute_label` VARCHAR(255) NOT NULL,
    `attribute_type` VARCHAR(50) NOT NULL,
    `attribute_options` JSON DEFAULT NULL,
    `is_required` BOOLEAN DEFAULT FALSE,
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
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- tr_bookings table
CREATE TABLE `tr_bookings` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `schedule_id` INT(11) UNSIGNED NOT NULL,
    `status` ENUM(
        'pending',
        'confirmed',
        'canceled',
        'completed'
    ) DEFAULT 'pending',
    `booking_date` DATETIME NOT NULL,
    `custom_values` JSON DEFAULT NULL,
    `payment_status` ENUM(
        'unpaid',
        'paid',
        'failed',
        'refunded'
    ) DEFAULT 'unpaid',
    `payment_info` JSON DEFAULT NULL,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    `updated_date` DATETIME DEFAULT NULL,
    `updated_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tenant_id` (`tenant_id`),
    KEY `user_id` (`user_id`),
    KEY `service_id` (`service_id`),
    KEY `schedule_id` (`schedule_id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `m_user` (`intUserID`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`),
    FOREIGN KEY (`schedule_id`) REFERENCES `m_schedules` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- tr_service_custom_values table
CREATE TABLE `tr_service_custom_values` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `booking_id` INT(11) UNSIGNED NOT NULL,
    `service_id` INT(11) UNSIGNED NOT NULL,
    `attribute_name` VARCHAR(255) NOT NULL,
    `attribute_value` TEXT DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `booking_id` (`booking_id`),
    KEY `service_id` (`service_id`),
    FOREIGN KEY (`booking_id`) REFERENCES `tr_bookings` (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `m_services` (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- tr_audit_log table
CREATE TABLE `tr_audit_log` (
    `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `tenant_id` INT(11) UNSIGNED NOT NULL,
    `user_id` INT(11) UNSIGNED NOT NULL,
    `action` ENUM('insert', 'update', 'delete') DEFAULT 'insert',
    `table_name` VARCHAR(255) NOT NULL,
    `record_id` INT(11) UNSIGNED NOT NULL,
    `old_values` JSON DEFAULT NULL,
    `new_values` JSON DEFAULT NULL,
    `created_date` DATETIME DEFAULT NULL,
    `created_by` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tenant_id` (`tenant_id`),
    KEY `user_id` (`user_id`),
    FOREIGN KEY (`tenant_id`) REFERENCES `m_tenants` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `m_user` (`intUserID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Seeder data for m_role
INSERT INTO
    m_role (
        txtRoleName,
        txtRoleDesc,
        txtRoleNote,
        bitStatus,
        txtGUID
    )
VALUES (
        'Administrator',
        'Full access to the system',
        'Initial system administrator role',
        1,
        'role_admin'
    ),
    (
        'User',
        'Standard user role',
        'Default user permissions',
        1,
        'role_user'
    );

-- Seeder data for m_service_types
INSERT INTO
    m_service_types (
        guid,
        name,
        slug,
        description,
        icon,
        category,
        is_system,
        is_approved,
        default_attributes,
        is_active,
        created_date,
        created_by
    )
VALUES (
        'guid_futsal',
        'Futsal',
        'futsal',
        'Futsal court booking and management service',
        'fas fa-futbol',
        'sports',
        1,
        1,
        '{"court_size":{"type":"select","label":"Court Size","options":["small","medium","large"],"required":true},"duration":{"type":"number","label":"Duration (hours)","min":1,"max":4,"required":true}}',
        1,
        NOW(),
        'system'
    ),
    (
        'guid_villa',
        'Villa Rental',
        'villa-rental',
        'Villa and accommodation booking service',
        'fas fa-home',
        'accommodation',
        1,
        1,
        '{"room_count":{"type":"number","label":"Number of Rooms","min":1,"max":20,"required":true},"guest_count":{"type":"number","label":"Number of Guests","min":1,"max":50,"required":true}}',
        1,
        NOW(),
        'system'
    );

-- Seeder data for m_tenants
INSERT INTO
    m_tenants (
        guid,
        name,
        slug,
        service_type_id,
        owner_id,
        subscription_plan,
        status,
        settings,
        is_active,
        created_date,
        created_by
    )
VALUES (
        'guid_alpha',
        'Alpha Futsal Center',
        'alpha-futsal',
        1,
        1,
        'basic',
        'active',
        '{"currency":"IDR"}',
        1,
        NOW(),
        'system'
    ),
    (
        'guid_omega',
        'Omega Villa Resort',
        'omega-villa',
        2,
        2,
        'premium',
        'active',
        '{"check_out_time":"12:00"}',
        1,
        NOW(),
        'system'
    );

-- Seeder data for m_user
INSERT INTO
    m_user (
        intRoleID,
        txtUserName,
        txtFullName,
        txtEmail,
        txtPassword,
        bitActive,
        txtCreatedBy,
        dtmCreatedDate,
        txtUpdatedBy,
        dtmUpdatedDate,
        txtGUID,
        txtPhoto,
        dtmJoinDate
    )
VALUES (
        3,
        'futsal.owner',
        'Futsal Owner',
        'futsal@example.com',
        '$2y$10$examplehash',
        1,
        'system',
        NOW(),
        'system',
        NOW(),
        'user_futsal',
        'default.png',
        NOW()
    ),
    (
        3,
        'villa.owner',
        'Villa Resort Owner',
        'villa@example.com',
        '$2y$10$examplehash',
        1,
        'system',
        NOW(),
        'system',
        NOW(),
        'user_villa',
        'default.png',
        NOW()
    );

-- Seeder data for m_menu
INSERT INTO
    m_menu (
        txtMenuName,
        txtMenuLink,
        txtIcon,
        intParentID,
        intSortOrder,
        bitActive
    )
VALUES (
        'Dashboard',
        '/dashboard',
        'fas fa-tachometer-alt',
        NULL,
        1,
        1
    ),
    (
        'Bookings',
        '/bookings',
        'fas fa-calendar-check',
        NULL,
        2,
        1
    );

-- Seeder data for m_role_menu
INSERT INTO
    m_role_menu (intRoleID, intMenuID)
VALUES (1, 1),
    (1, 2),
    (2, 1);