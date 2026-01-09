# Pathology Section Database Changes

This document contains SQL scripts to update the Pathology section to remove connections with charge category and charge name tables, and add IPD/OPD charge support.

## Changes Overview

1. **Pathology Table**: Remove `charge_category_id` and `charge_id`, add `standard_charge_ipd` and `standard_charge_opd`
2. **Organisations Charges Table**: Add `pathology_id` and `charge_type` (IPD/OPD) for TPA charges

## SQL Files

### 1. `pathology_changes_step1_update_pathology_table.sql`
- Dynamic version that automatically finds and drops foreign key constraints
- Removes charge_category_id and charge_id columns
- Adds standard_charge_ipd and standard_charge_opd columns

### 2. `pathology_changes_step2_update_organisations_charges.sql`
- Adds pathology_id and charge_type columns to organisations_charges table
- Creates foreign key relationship with pathology table

### 3. `pathology_changes_simple.sql`
- Simplified version with manual constraint names
- Use this if the dynamic version doesn't work
- You may need to adjust foreign key constraint names

### 4. `pathology_changes_rollback.sql`
- Rollback script to revert all changes if needed

## Execution Order

Execute the SQL files in this order:

1. First, run `pathology_changes_step1_update_pathology_table.sql`
2. Then, run `pathology_changes_step2_update_organisations_charges.sql`

Or use the combined simple version:

1. Run `pathology_changes_simple.sql` (all changes in one file)

## Important Notes

### Before Running:
1. **Backup your database** - Always backup before running migrations
2. **Check foreign key names** - If using the simple version, verify the constraint names match your database
3. **Test in development** - Test these changes in a development environment first

### Foreign Key Constraint Names:
Laravel typically names foreign keys as: `{table}_{column}_foreign`

Common names you might see:
- `pathology_charge_category_id_foreign`
- `pathology_charge_id_foreign`

To find your actual constraint names, run:
```sql
SELECT CONSTRAINT_NAME 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'pathology' 
AND COLUMN_NAME IN ('charge_category_id', 'charge_id')
AND REFERENCED_TABLE_NAME IS NOT NULL;
```

### Data Migration:
If you have existing pathology records with charge_id values, you may want to migrate that data:

```sql
-- Example: Copy existing standard_charge to both IPD and OPD (if standard_charge exists)
UPDATE pathology 
SET standard_charge_ipd = COALESCE(standard_charge, 0),
    standard_charge_opd = COALESCE(standard_charge, 0)
WHERE standard_charge_ipd IS NULL OR standard_charge_opd IS NULL;
```

### TPA Charges Migration:
If you have existing TPA charges linked via charge_id that need to be migrated to pathology_id:

```sql
-- Example: Migrate TPA charges from charge_id to pathology_id
-- This assumes you want to set charge_type based on some logic
-- Adjust according to your business rules

UPDATE organisations_charges oc
INNER JOIN pathology p ON oc.charge_id = p.charge_id
SET oc.pathology_id = p.id,
    oc.charge_type = 'OPD'  -- or 'IPD' based on your logic
WHERE oc.charge_id IS NOT NULL 
AND oc.pathology_id IS NULL;
```

## Verification Queries

After running the migrations, verify the changes:

```sql
-- Check pathology table structure
DESCRIBE pathology;

-- Check organisations_charges table structure
DESCRIBE organisations_charges;

-- Verify foreign keys
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('pathology', 'organisations_charges')
AND REFERENCED_TABLE_NAME IS NOT NULL;
```

## Rollback

If you need to rollback the changes, run:
```sql
-- Run pathology_changes_rollback.sql
```

## Support

If you encounter issues:
1. Check the error messages for constraint names
2. Verify table and column names match your database
3. Ensure you have proper permissions
4. Check for any data dependencies before dropping columns

