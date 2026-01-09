-- =====================================================
-- RADIOLOGY CHANGES - STEP 2: Update organisations_charges table
-- =====================================================
-- This script adds radiology_id column to organisations_charges
-- table to support radiology TPA charges with IPD/OPD structure.
-- Note: charge_type column should already exist from pathology changes.
-- =====================================================

-- Add radiology_id column if it doesn't exist
ALTER TABLE organisations_charges 
ADD COLUMN IF NOT EXISTS radiology_id BIGINT UNSIGNED NULL AFTER pathology_id;

-- Add index on radiology_id
CREATE INDEX IF NOT EXISTS idx_organisations_charges_radiology_id 
ON organisations_charges(radiology_id);

-- Add foreign key constraint on radiology_id
-- Note: This assumes the foreign key doesn't already exist
SET @fk_exists = (
    SELECT COUNT(*) 
    FROM information_schema.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'organisations_charges' 
    AND COLUMN_NAME = 'radiology_id' 
    AND REFERENCED_TABLE_NAME = 'radio'
);

SET @sql = IF(@fk_exists = 0, 
    'ALTER TABLE organisations_charges ADD CONSTRAINT fk_organisations_charges_radiology_id FOREIGN KEY (radiology_id) REFERENCES radio(id) ON DELETE CASCADE', 
    'SELECT "Foreign key constraint already exists" AS message'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verify changes
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'organisations_charges' 
AND COLUMN_NAME IN ('radiology_id', 'charge_type')
ORDER BY ORDINAL_POSITION;
