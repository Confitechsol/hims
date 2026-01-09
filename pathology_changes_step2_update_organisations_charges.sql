-- =====================================================
-- Organisations Charges Table Changes - Step 2
-- Add pathology_id and charge_type for Pathology TPA Charges
-- =====================================================

-- Step 1: Add pathology_id column
ALTER TABLE organisations_charges 
ADD COLUMN pathology_id BIGINT UNSIGNED NULL AFTER charge_id;

-- Step 2: Add charge_type enum column (IPD or OPD)
ALTER TABLE organisations_charges 
ADD COLUMN charge_type ENUM('IPD', 'OPD') NULL AFTER pathology_id;

-- Step 3: Add index on pathology_id for better query performance
CREATE INDEX idx_organisations_charges_pathology_id ON organisations_charges(pathology_id);

-- Step 4: Add foreign key constraint for pathology_id
ALTER TABLE organisations_charges 
ADD CONSTRAINT fk_organisations_charges_pathology_id 
FOREIGN KEY (pathology_id) 
REFERENCES pathology(id) 
ON DELETE CASCADE;

-- =====================================================
-- Migration Complete
-- =====================================================

