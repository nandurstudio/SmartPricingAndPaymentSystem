-- Create Database if not exists
CREATE DATABASE IF NOT EXISTS db_smartpricingandpaymentsystem;

USE db_smartpricingandpaymentsystem;

-- m_role table
CREATE TABLE `m_role` (
    `intRoleID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtRoleName` VARCHAR(50) NOT NULL,
    `bitStatus` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intRoleID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_user table
CREATE TABLE `m_user` (
    `intUserID` INT(10) NOT NULL AUTO_INCREMENT,
    `intRoleID` INT(10) NOT NULL DEFAULT '5',
    `txtUserName` VARCHAR(50) NOT NULL DEFAULT 'dummy.nick',
    `txtFullName` VARCHAR(100) NOT NULL,
    `txtEmail` VARCHAR(100) NOT NULL DEFAULT 'dummy@email.com',
    `txtPassword` VARCHAR(255) NOT NULL,
    `bitActive` TINYINT(1) NULL DEFAULT '1',
    `bitOnlineStatus` TINYINT(1) NULL DEFAULT '0',
    `dtmLastLogin` DATETIME NULL DEFAULT NULL,
    `txtCreatedBy` VARCHAR(50) NULL DEFAULT 'system',
    `dtmCreatedDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` VARCHAR(50) NULL DEFAULT 'system',
    `dtmUpdatedDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    `reset_token` VARCHAR(100) NULL DEFAULT NULL,
    `token_created_at` DATETIME NULL DEFAULT NULL,
    `google_auth_token` VARCHAR(255) NULL DEFAULT NULL;
    `txtPhoto` TEXT NULL DEFAULT 'default.jpg',
    `dtmJoinDate` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`intUserID`),
    FOREIGN KEY (`intRoleID`) REFERENCES `m_role` (`intRoleID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_product table
CREATE TABLE `m_product` (
    `intProductID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtProductName` VARCHAR(255) NOT NULL,
    `txtProductDescription` TEXT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intProductID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_category table
CREATE TABLE `m_category` (
    `intCategoryID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtCategoryName` VARCHAR(100) NOT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intCategoryID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_order table
CREATE TABLE `m_order` (
    `intOrderID` INT(10) NOT NULL AUTO_INCREMENT,
    `intUserID` INT(10) NOT NULL,
    `txtOrderStatus` VARCHAR(50) NOT NULL,
    `txtPaymentStatus` VARCHAR(50) NOT NULL,
    `dtmOrderDate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
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
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intShippingID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- m_orderstatus table
CREATE TABLE `m_orderstatus` (
    `intOrderStatusID` INT(10) NOT NULL AUTO_INCREMENT,
    `txtOrderStatusName` VARCHAR(50) NOT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtCreatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmCreatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `txtLastUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmLastUpdatedDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL DEFAULT(UUID()),
    PRIMARY KEY (`intOrderStatusID`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;

-- Ensure proper cleanup of database constraints and indexes, if any