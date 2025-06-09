-- Subscription Plans Table
CREATE TABLE IF NOT EXISTS `m_subscription_plans` (
    `intPlanID` int unsigned NOT NULL AUTO_INCREMENT,
    `txtGUID` varchar(36) NOT NULL,
    `txtName` varchar(50) NOT NULL,
    `txtCode` varchar(50) NOT NULL,
    `decAmount` decimal(12,2) NOT NULL DEFAULT 0.00,
    `intDuration` int NOT NULL DEFAULT 1 COMMENT 'Duration in months',
    `jsonFeatures` json DEFAULT NULL COMMENT 'Features included in the plan',
    `txtDescription` text,
    `bitActive` tinyint(1) NOT NULL DEFAULT 1,
    `txtCreatedBy` varchar(50) NOT NULL DEFAULT 'system',
    `dtmCreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` varchar(50) DEFAULT NULL,
    `dtmUpdatedDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`intPlanID`),
    UNIQUE KEY `txtGUID` (`txtGUID`),
    UNIQUE KEY `txtCode` (`txtCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscription Features Table
CREATE TABLE IF NOT EXISTS `m_subscription_features` (
    `intFeatureID` int unsigned NOT NULL AUTO_INCREMENT,
    `txtGUID` varchar(36) NOT NULL,
    `txtName` varchar(100) NOT NULL,
    `txtCode` varchar(50) NOT NULL,
    `txtDescription` text,
    `bitActive` tinyint(1) NOT NULL DEFAULT 1,
    `txtCreatedBy` varchar(50) NOT NULL DEFAULT 'system',
    `dtmCreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` varchar(50) DEFAULT NULL,
    `dtmUpdatedDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`intFeatureID`),
    UNIQUE KEY `txtGUID` (`txtGUID`),
    UNIQUE KEY `txtCode` (`txtCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Plan Features Mapping Table
CREATE TABLE IF NOT EXISTS `m_plan_features` (
    `intPlanFeatureID` int unsigned NOT NULL AUTO_INCREMENT,
    `intPlanID` int unsigned NOT NULL,
    `intFeatureID` int unsigned NOT NULL,
    `jsonLimits` json DEFAULT NULL COMMENT 'Feature limits/quotas specific to this plan',
    `bitActive` tinyint(1) NOT NULL DEFAULT 1,
    `txtCreatedBy` varchar(50) NOT NULL DEFAULT 'system',
    `dtmCreatedDate` datetime DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` varchar(50) DEFAULT NULL,
    `dtmUpdatedDate` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`intPlanFeatureID`),
    KEY `fk_plan_features_plan` (`intPlanID`),
    KEY `fk_plan_features_feature` (`intFeatureID`),
    CONSTRAINT `fk_plan_features_feature` FOREIGN KEY (`intFeatureID`) REFERENCES `m_subscription_features` (`intFeatureID`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_plan_features_plan` FOREIGN KEY (`intPlanID`) REFERENCES `m_subscription_plans` (`intPlanID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default subscription plans
INSERT INTO `m_subscription_plans` (`txtGUID`, `txtName`, `txtCode`, `decAmount`, `intDuration`, `jsonFeatures`, `txtDescription`) VALUES
(UUID(), 'Free Plan', 'free', 0.00, 1, '{"max_services": 5, "max_staff": 1, "max_bookings_per_month": 50}', 'Basic features for small businesses'),
(UUID(), 'Basic Plan', 'basic', 99000.00, 1, '{"max_services": 20, "max_staff": 5, "max_bookings_per_month": 200}', 'Essential features for growing businesses'),
(UUID(), 'Premium Plan', 'premium', 199000.00, 3, '{"max_services": 50, "max_staff": 15, "max_bookings_per_month": 500}', 'Advanced features for established businesses'),
(UUID(), 'Enterprise Plan', 'enterprise', 499000.00, 12, '{"max_services": -1, "max_staff": -1, "max_bookings_per_month": -1}', 'Unlimited features for large businesses');

-- Insert default features
INSERT INTO `m_subscription_features` (`txtGUID`, `txtName`, `txtCode`, `txtDescription`) VALUES
(UUID(), 'Service Management', 'service_management', 'Manage services and their details'),
(UUID(), 'Staff Management', 'staff_management', 'Manage staff and their schedules'),
(UUID(), 'Booking Management', 'booking_management', 'Manage bookings and appointments'),
(UUID(), 'Payment Processing', 'payment_processing', 'Process payments through Midtrans'),
(UUID(), 'Analytics & Reports', 'analytics', 'View business analytics and reports'),
(UUID(), 'API Access', 'api_access', 'Access to API endpoints'),
(UUID(), 'Custom Branding', 'custom_branding', 'Customize branding and theme'),
(UUID(), 'Email Notifications', 'email_notifications', 'Send email notifications'),
(UUID(), 'WhatsApp Notifications', 'whatsapp_notifications', 'Send WhatsApp notifications');

-- Map features to plans
-- Free Plan Features
INSERT INTO `m_plan_features` (`intPlanID`, `intFeatureID`, `jsonLimits`)
SELECT 
    (SELECT intPlanID FROM m_subscription_plans WHERE txtCode = 'free'),
    intFeatureID,
    '{"enabled": true, "quota": null}'
FROM m_subscription_features 
WHERE txtCode IN ('service_management', 'booking_management', 'email_notifications');

-- Basic Plan Features
INSERT INTO `m_plan_features` (`intPlanID`, `intFeatureID`, `jsonLimits`)
SELECT 
    (SELECT intPlanID FROM m_subscription_plans WHERE txtCode = 'basic'),
    intFeatureID,
    '{"enabled": true, "quota": null}'
FROM m_subscription_features 
WHERE txtCode IN ('service_management', 'booking_management', 'payment_processing', 'email_notifications', 'staff_management', 'analytics');

-- Premium Plan Features
INSERT INTO `m_plan_features` (`intPlanID`, `intFeatureID`, `jsonLimits`)
SELECT 
    (SELECT intPlanID FROM m_subscription_plans WHERE txtCode = 'premium'),
    intFeatureID,
    '{"enabled": true, "quota": null}'
FROM m_subscription_features 
WHERE txtCode IN ('service_management', 'booking_management', 'payment_processing', 'email_notifications', 'staff_management', 'analytics', 'custom_branding', 'whatsapp_notifications');

-- Enterprise Plan Features (All Features)
INSERT INTO `m_plan_features` (`intPlanID`, `intFeatureID`, `jsonLimits`)
SELECT 
    (SELECT intPlanID FROM m_subscription_plans WHERE txtCode = 'enterprise'),
    intFeatureID,
    '{"enabled": true, "quota": null}'
FROM m_subscription_features;
