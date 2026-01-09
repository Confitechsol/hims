-- =====================================================
-- Pathology Table Changes - Step 1
-- Remove charge_category_id and charge_id, Add IPD/OPD Charges
-- =====================================================

-- Step 1: Drop foreign key constraints
-- Note: Adjust constraint names based on your database naming convention
-- Laravel typically names them as: table_column_foreign

-- Drop foreign key for charge_category_id
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
    'SELECT "Foreign key for charge_category_id not found" AS message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop foreign key for charge_id
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
    'SELECT "Foreign key for charge_id not found" AS message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 2: Drop indexes (if they exist)
-- Drop index on charge_category_id
DROP INDEX IF EXISTS pathology_charge_category_id_index ON pathology;
DROP INDEX IF EXISTS charge_category_id ON pathology;

-- Drop index on charge_id
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

-- Step 5: Add indexes on new columns (optional but recommended for performance)
CREATE INDEX idx_pathology_standard_charge_ipd ON pathology(standard_charge_ipd);
CREATE INDEX idx_pathology_standard_charge_opd ON pathology(standard_charge_opd);

-- =====================================================
-- Migration Complete
-- =====================================================

