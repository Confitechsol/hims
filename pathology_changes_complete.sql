-- =====================================================
-- Pathology Section Database Changes - Complete Script
-- This script removes charge table connections and adds IPD/OPD charge support
-- =====================================================
-- IMPORTANT: Backup your database before running this script!
-- =====================================================

-- =====================================================
-- PART 1: Update Pathology Table
-- =====================================================

-- Step 1: Drop foreign key constraints
-- Find and drop charge_category_id foreign key
SET @constraint_name = (
    SELECT CONSTRAINT_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'pathology' 
    AND COLUMN_NAME = 'charge_category_id' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);

SET @sql = IF(@constraint_name IS NOT NULL, 
    CONCAT('ALTER TABLE pathology DROP FOREIGN KEY ', @constraint_name), 
    'SELECT "Foreign key for charge_category_id not found or already dropped" AS message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Find and drop charge_id foreign key
SET @constraint_name = (
    SELECT CONSTRAINT_NAME 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'pathology' 
    AND COLUMN_NAME = 'charge_id' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);

SET @sql = IF(@constraint_name IS NOT NULL, 
    CONCAT('ALTER TABLE pathology DROP FOREIGN KEY ', @constraint_name), 
    'SELECT "Foreign key for charge_id not found or already dropped" AS message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 2: Drop indexes
DROP INDEX IF EXISTS pathology_charge_category_id_index ON pathology;
DROP INDEX IF EXISTS charge_category_id ON pathology;
DROP INDEX IF EXISTS pathology_charge_id_index ON pathology;
DROP INDEX IF EXISTS charge_id ON pathology;

-- Step 3: Drop old columns
ALTER TABLE pathology 
DROP COLUMN IF EXISTS charge_category_id,
DROP COLUMN IF EXISTS charge_id;

-- Step 4: Add new columns for IPD and OPD charges
ALTER TABLE pathology 
ADD COLUMN standard_charge_ipd DECIMAL(10, 2) NULL AFTER method,
ADD COLUMN standard_charge_opd DECIMAL(10, 2) NULL AFTER standard_charge_ipd;

-- Step 5: Add indexes on new columns for better query performance
CREATE INDEX idx_pathology_standard_charge_ipd ON pathology(standard_charge_ipd);
CREATE INDEX idx_pathology_standard_charge_opd ON pathology(standard_charge_opd);

-- =====================================================
-- PART 2: Update Organisations Charges Table
-- =====================================================

-- Step 1: Add pathology_id column
ALTER TABLE organisations_charges 
ADD COLUMN pathology_id BIGINT UNSIGNED NULL AFTER charge_id;

-- Step 2: Add charge_type enum column (IPD or OPD)
ALTER TABLE organisations_charges 
ADD COLUMN charge_type ENUM('IPD', 'OPD') NULL AFTER pathology_id;

-- Step 3: Add index on pathology_id for better query performance
CREATE INDEX idx_organisations_charges_pathology_id ON organisations_charges(pathology_id);



-- =====================================================
-- PART 3: Optional Data Migration
-- =====================================================

-- If you have existing standard_charge values and want to copy them to IPD/OPD:
-- Uncomment the following lines if needed:

-- UPDATE pathology 
-- SET standard_charge_ipd = COALESCE(standard_charge, 0),
--     standard_charge_opd = COALESCE(standard_charge, 0)
-- WHERE (standard_charge_ipd IS NULL OR standard_charge_opd IS NULL)
-- AND standard_charge IS NOT NULL;

-- =====================================================
-- Migration Complete!
-- =====================================================
-- Verify the changes by running:
-- DESCRIBE pathology;
-- DESCRIBE organisations_charges;
-- =====================================================

