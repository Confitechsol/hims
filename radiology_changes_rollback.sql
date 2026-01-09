-- =====================================================
-- RADIOLOGY CHANGES - ROLLBACK SCRIPT
-- =====================================================
-- This script reverts all radiology changes:
-- 1. Removes standard_charge_ipd and standard_charge_opd from radio table
-- 2. Adds back charge_id column to radio table
-- 3. Removes radiology_id from organisations_charges table
-- =====================================================
-- WARNING: This will delete data in standard_charge_ipd and standard_charge_opd columns!
-- =====================================================

-- STEP 1: Remove radiology_id from organisations_charges
-- =====================================================

-- Drop foreign key constraint on radiology_id if it exists
SET @constraint_name = (
    SELECT CONSTRAINT_NAME 
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'organisations_charges' 
    AND COLUMN_NAME = 'radiology_id' 
    AND REFERENCED_TABLE_NAME IS NOT NULL
    LIMIT 1
);

SET @sql = IF(@constraint_name IS NOT NULL, 
    CONCAT('ALTER TABLE organisations_charges DROP FOREIGN KEY ', @constraint_name), 
    'SELECT "No foreign key constraint found on radiology_id" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop index on radiology_id if it exists
SET @index_name = (
    SELECT INDEX_NAME 
    FROM information_schema.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'organisations_charges' 
    AND COLUMN_NAME = 'radiology_id'
    LIMIT 1
);

SET @sql = IF(@index_name IS NOT NULL, 
    CONCAT('ALTER TABLE organisations_charges DROP INDEX ', @index_name), 
    'SELECT "No index found on radiology_id" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop radiology_id column
ALTER TABLE organisations_charges DROP COLUMN IF EXISTS radiology_id;

-- STEP 2: Revert radio table changes
-- =====================================================

-- Drop standard_charge_ipd and standard_charge_opd columns
ALTER TABLE radio DROP COLUMN IF EXISTS standard_charge_ipd;
ALTER TABLE radio DROP COLUMN IF EXISTS standard_charge_opd;

-- Add back charge_id column
ALTER TABLE radio 
ADD COLUMN charge_id BIGINT UNSIGNED NULL AFTER report_days;

-- Add index on charge_id
CREATE INDEX idx_radio_charge_id ON radio(charge_id);

-- Add foreign key constraint on charge_id
ALTER TABLE radio 
ADD CONSTRAINT fk_radio_charge_id 
FOREIGN KEY (charge_id) REFERENCES charges(id) ON DELETE SET NULL;

-- Verify rollback
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'radio' 
AND COLUMN_NAME IN ('charge_id', 'standard_charge_ipd', 'standard_charge_opd')
ORDER BY ORDINAL_POSITION;

SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'organisations_charges' 
AND COLUMN_NAME = 'radiology_id'
ORDER BY ORDINAL_POSITION;
