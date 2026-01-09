-- =====================================================
-- Pathology Changes - Simple Version (Manual Constraint Names)
-- Use this if the dynamic version doesn't work
-- Replace constraint names with your actual foreign key names
-- =====================================================

-- =====================================================
-- Step 1: Update Pathology Table
-- =====================================================

-- Drop foreign keys (replace constraint names with your actual names)
-- Common Laravel naming: pathology_charge_category_id_foreign, pathology_charge_id_foreign
ALTER TABLE pathology DROP FOREIGN KEY pathology_charge_category_id_foreign;
ALTER TABLE pathology DROP FOREIGN KEY pathology_charge_id_foreign;

-- Drop indexes
DROP INDEX pathology_charge_category_id_index ON pathology;
DROP INDEX pathology_charge_id_index ON pathology;

-- Drop old columns
ALTER TABLE pathology 
DROP COLUMN charge_category_id,
DROP COLUMN charge_id;

-- Add new columns
ALTER TABLE pathology 
ADD COLUMN standard_charge_ipd DECIMAL(10, 2) NULL AFTER method,
ADD COLUMN standard_charge_opd DECIMAL(10, 2) NULL AFTER standard_charge_ipd;

-- Add indexes
CREATE INDEX idx_pathology_standard_charge_ipd ON pathology(standard_charge_ipd);
CREATE INDEX idx_pathology_standard_charge_opd ON pathology(standard_charge_opd);

-- =====================================================
-- Step 2: Update Organisations Charges Table
-- =====================================================

-- Add new columns
ALTER TABLE organisations_charges 
ADD COLUMN pathology_id BIGINT UNSIGNED NULL AFTER charge_id,
ADD COLUMN charge_type ENUM('IPD', 'OPD') NULL AFTER pathology_id;

-- Add index
CREATE INDEX idx_organisations_charges_pathology_id ON organisations_charges(pathology_id);

-- Add foreign key
ALTER TABLE organisations_charges 
ADD CONSTRAINT fk_organisations_charges_pathology_id 
FOREIGN KEY (pathology_id) 
REFERENCES pathology(id) 
ON DELETE CASCADE;

-- =====================================================
-- Migration Complete
-- =====================================================

