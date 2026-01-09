-- =====================================================
-- RADIOLOGY CHANGES - STEP 1: Update radio table
-- =====================================================
-- This script removes charge_id connection from the radio table
-- and adds standard_charge_ipd and standard_charge_opd columns.
-- =====================================================

-- Drop foreign key constraint on charge_id if it exists
SET @constraint_name = (
    SELECT CONSTRAINT_NAME 
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'radio' 
    AND COLUMN_NAME = 'charge_id' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);

SET @sql = IF(@constraint_name IS NOT NULL, 
    CONCAT('ALTER TABLE radio DROP FOREIGN KEY ', @constraint_name), 
    'SELECT "No foreign key constraint found on charge_id" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop index on charge_id if it exists
SET @index_name = (
    SELECT INDEX_NAME 
    FROM information_schema.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'radio' 
    AND COLUMN_NAME = 'charge_id'
    LIMIT 1
);

SET @sql = IF(@index_name IS NOT NULL, 
    CONCAT('ALTER TABLE radio DROP INDEX ', @index_name), 
    'SELECT "No index found on charge_id" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop charge_id column
ALTER TABLE radio DROP COLUMN IF EXISTS charge_id;

-- Add new columns for IPD and OPD standard charges
ALTER TABLE radio 
ADD COLUMN standard_charge_ipd DECIMAL(10, 2) NULL AFTER report_days,
ADD COLUMN standard_charge_opd DECIMAL(10, 2) NULL AFTER standard_charge_ipd;

-- Verify changes
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'radio' 
AND COLUMN_NAME IN ('charge_id', 'standard_charge_ipd', 'standard_charge_opd')
ORDER BY ORDINAL_POSITION;
