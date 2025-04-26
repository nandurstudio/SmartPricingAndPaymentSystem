CREATE TABLE `trAssessment` (
    `intAssessmentID` INT AUTO_INCREMENT PRIMARY KEY,
    `intUserID` INT NOT NULL,
    `intLineID` INT NOT NULL,
    `intJobTitleID` INT NOT NULL,
    `intCompetencyID` INT NOT NULL,
    `intIndicatorID` INT NOT NULL,
    `bitResult` TINYINT(1) NOT NULL,
    `dtmAssessedDate` DATETIME NOT NULL,
    `txtAssessedBy` VARCHAR(50) NOT NULL,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtInsertedBy` VARCHAR(50) DEFAULT 'system',
    `dtmInsertedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmUpdatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL,
    FOREIGN KEY (`intUserID`) REFERENCES `mUser` (`intUserID`),
    FOREIGN KEY (`intLineID`) REFERENCES `mLine` (`intLineID`),
    FOREIGN KEY (`intJobTitleID`) REFERENCES `mJobTitle` (`intJobTitleID`),
    FOREIGN KEY (`intCompetencyID`) REFERENCES `mCompetencies` (`intCompetencyID`),
    FOREIGN KEY (`intIndicatorID`) REFERENCES `mIndicators` (`intIndicatorID`)
);

CREATE TABLE `trCompetencyProgress` (
    `intProgressID` INT AUTO_INCREMENT PRIMARY KEY,
    `intUserID` INT NOT NULL,
    `intCompetencyID` INT NOT NULL,
    `fltProgress` FLOAT NOT NULL,
    `dtmLastUpdated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `bitActive` TINYINT(1) DEFAULT 1,
    `txtInsertedBy` VARCHAR(50) DEFAULT 'system',
    `dtmInsertedDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `txtUpdatedBy` VARCHAR(50) DEFAULT 'system',
    `dtmUpdatedDate` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `txtGUID` VARCHAR(50) NOT NULL,
    FOREIGN KEY (`intUserID`) REFERENCES `mUser` (`intUserID`),
    FOREIGN KEY (`intCompetencyID`) REFERENCES `mCompetencies` (`intCompetencyID`)
);