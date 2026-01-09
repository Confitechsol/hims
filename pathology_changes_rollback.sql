-- =====================================================
-- Rollback Script - Revert Pathology Changes
-- Use this if you need to rollback the changes
-- =====================================================

-- =====================================================
-- Step 1: Rollback organisations_charges table changes
-- =====================================================

-- Drop foreign key constraint
ALTER TABLE organisations_charges 
DROP FOREIGN KEY IF EXISTS fk_organisations_charges_pathology_id;

-- Drop index
DROP INDEX IF EXISTS idx_organisations_charges_pathology_id ON organisations_charges;

-- Drop columns
ALTER TABLE organisations_charges 
DROP COLUMN IF EXISTS pathology_id,
DROP COLUMN IF EXISTS charge_type;

-- =====================================================
-- Step 2: Rollback pathology table changes
-- =====================================================

-- Drop indexes on new columns
DROP INDEX IF EXISTS idx_pathology_standard_charge_ipd ON pathology;
DROP INDEX IF EXISTS idx_pathology_standard_charge_opd ON pathology;

-- Drop new columns
ALTER TABLE pathology 
DROP COLUMN IF EXISTS standard_charge_ipd,
DROP COLUMN IF EXISTS standard_charge_opd;

-- Re-add old columns
ALTER TABLE pathology 
ADD COLUMN charge_category_id BIGINT UNSIGNED NULL AFTER method,
ADD COLUMN charge_id BIGINT UNSIGNED NULL AFTER charge_category_id;

-- Re-add indexes
CREATE INDEX idx_pathology_charge_category_id ON pathology(charge_category_id);
CREATE INDEX idx_pathology_charge_id ON pathology(charge_id);

-- Re-add foreign keys
ALTER TABLE pathology 
ADD CONSTRAINT fk_pathology_charge_category_id 
FOREIGN KEY (charge_category_id) 
REFERENCES charge_categories(id) 
ON DELETE SET NULL;

ALTER TABLE pathology 
ADD CONSTRAINT fk_pathology_charge_id 
FOREIGN KEY (charge_id) 
REFERENCES charges(id) 
ON DELETE SET NULL;

-- =====================================================
-- Rollback Complete
-- =====================================================

