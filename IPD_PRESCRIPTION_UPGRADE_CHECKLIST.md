# IPD Prescription Upgrade - Quick Implementation Checklist

## Pre-Implementation
- [ ] Backup database
- [ ] Create feature branch in Git
- [ ] Review Hospital system functionality
- [ ] Set up development environment

## Phase 1: Database (2-3 hours)
- [ ] Create `ipd_prescription_test` migration
- [ ] Create migration to update `ipd_prescription` table
  - [ ] Remove `pathology_id` string column
  - [ ] Remove `radiology_id` string column
  - [ ] Add `prescribe_by` column
  - [ ] Add `attachment` column
  - [ ] Add `attachment_name` column
  - [ ] Add `visit_details_id` column
- [ ] Create data migration script
- [ ] Test migration on development database
- [ ] Run migration on development
- [ ] Verify data integrity

## Phase 2: Models (1-2 hours)
- [ ] Create `IpdPrescriptionTest` model
  - [ ] Add relationships (prescription, pathology, radiology)
  - [ ] Add fillable fields
- [ ] Update `IpdPrescription` model
  - [ ] Add `tests()` relationship
  - [ ] Add `pathologyTests()` relationship
  - [ ] Add `radiologyTests()` relationship
  - [ ] Add `prescribedBy()` relationship
  - [ ] Update fillable array
- [ ] Test model relationships

## Phase 3: Controller - Store Method (2-3 hours)
- [ ] Update `storePrescription()` validation
  - [ ] Add `prescribe_by` validation
  - [ ] Add file upload validation
- [ ] Add file upload handling
- [ ] Update test storage logic
  - [ ] Remove comma-separated string logic
  - [ ] Add loop to create `IpdPrescriptionTest` records
- [ ] Update prescription creation
  - [ ] Add `prescribe_by` field
  - [ ] Add `attachment` fields
- [ ] Test prescription creation

## Phase 4: Controller - Edit/Update (2-3 hours)
- [ ] Create `editPrescription()` method
  - [ ] Load prescription with relationships
  - [ ] Return JSON response
- [ ] Create `updatePrescription()` method
  - [ ] Handle file upload (update or new)
  - [ ] Update prescription record
  - [ ] Delete old tests
  - [ ] Insert new tests
  - [ ] Update medicines
- [ ] Test edit/update flow

## Phase 5: Controller - View/Delete/Print (2-3 hours)
- [ ] Create `showPrescription()` method
- [ ] Create `deletePrescription()` method
  - [ ] Cascade delete tests
  - [ ] Delete attachment file
- [ ] Create `printPrescription()` method
- [ ] Create `downloadAttachment()` method
- [ ] Test all methods

## Phase 6: Routes (1 hour)
- [ ] Add edit route
- [ ] Add update route
- [ ] Add delete route
- [ ] Add show route
- [ ] Add print route
- [ ] Add download route
- [ ] Test all routes

## Phase 7: Views - Add Modal Updates (2-3 hours)
- [ ] Add "Prescribe By" dropdown
  - [ ] Load doctors list
  - [ ] Add to form
- [ ] Add file upload field
  - [ ] Add validation message
  - [ ] Add file size limit info
- [ ] Add hidden fields for edit mode
  - [ ] `prescription_id`
  - [ ] `action`
- [ ] Update form action logic
- [ ] Test form submission

## Phase 8: Views - Edit Modal (2-3 hours)
- [ ] Create edit modal OR update add modal for edit mode
- [ ] Add JavaScript to load prescription data
- [ ] Populate form fields
- [ ] Load selected tests
- [ ] Load medicines
- [ ] Handle form submission for update
- [ ] Test edit functionality

## Phase 9: Views - Show Modal (1-2 hours)
- [ ] Create show prescription modal
- [ ] Display all prescription details
- [ ] Display medicines list
- [ ] Display tests list
- [ ] Add download attachment button
- [ ] Add print button
- [ ] Test view functionality

## Phase 10: Views - Print View (2-3 hours)
- [ ] Create print blade template
- [ ] Add header with logo
- [ ] Add patient information
- [ ] Add prescription details
- [ ] Add medicines table
- [ ] Add tests table
- [ ] Add findings section
- [ ] Add footer with signature
- [ ] Add print CSS
- [ ] Test print output

## Phase 11: Views - IPD View Updates (1-2 hours)
- [ ] Add prescription list section
- [ ] Add "Add Prescription" button
- [ ] Add edit button for each prescription
- [ ] Add delete button for each prescription
- [ ] Add view button for each prescription
- [ ] Add print button for each prescription
- [ ] Test all buttons

## Phase 12: JavaScript Enhancements (3-4 hours)
- [ ] Add edit mode detection
- [ ] Add load prescription function
- [ ] Add form submission handler
- [ ] Add delete confirmation
- [ ] Add file preview (optional)
- [ ] Update finding filter (if implementing)
- [ ] Test all JavaScript

## Phase 13: Finding Enhancement (2-3 hours) - Optional
- [ ] Add filter input field
- [ ] Add filter JavaScript
- [ ] Test filter functionality

## Phase 14: Notification Integration (4-6 hours) - Optional
- [ ] Check if notification system exists
- [ ] Create notification service
- [ ] Integrate in controller
- [ ] Test notifications

## Phase 15: Testing (4-6 hours)
- [ ] Test create prescription
- [ ] Test edit prescription
- [ ] Test update prescription
- [ ] Test delete prescription
- [ ] Test view prescription
- [ ] Test print prescription
- [ ] Test file upload
- [ ] Test file download
- [ ] Test prescribe by selection
- [ ] Test medicine management
- [ ] Test pathology selection
- [ ] Test radiology selection
- [ ] Test finding management
- [ ] Test notification (if implemented)
- [ ] Test data migration
- [ ] Test with existing data
- [ ] Performance testing

## Phase 16: Documentation (2-3 hours)
- [ ] Update API documentation
- [ ] Update user guide
- [ ] Document new features
- [ ] Create migration guide

## Phase 17: Deployment
- [ ] Code review
- [ ] Final testing on staging
- [ ] Backup production database
- [ ] Run migrations on staging
- [ ] Test on staging
- [ ] Deploy to production
- [ ] Run migrations on production
- [ ] Monitor for issues
- [ ] Verify data integrity

---

## Quick Reference: Key Changes

### Database
- New table: `ipd_prescription_test`
- Updated: `ipd_prescription` (remove string columns, add new fields)

### Models
- New: `IpdPrescriptionTest`
- Updated: `IpdPrescription` (new relationships)

### Controllers
- Updated: `storePrescription()` - normalized test storage
- New: `editPrescription()`, `updatePrescription()`, `showPrescription()`, `deletePrescription()`, `printPrescription()`

### Views
- Updated: Add prescription modal
- New: Edit modal, Show modal, Print view

### Routes
- New: 6 routes for CRUD operations

---

## Testing Priority Order

1. **Critical**: Create prescription (must work)
2. **Critical**: Data migration (no data loss)
3. **High**: Edit/Update prescription
4. **High**: View prescription
5. **Medium**: Delete prescription
6. **Medium**: Print prescription
7. **Low**: File attachments
8. **Low**: Notifications

---

## Rollback Steps (if needed)

1. Restore database backup
2. Revert code changes via Git
3. Clear cache: `php artisan cache:clear`
4. Clear config: `php artisan config:clear`
5. Verify system works

---

## Notes
- Work incrementally, test after each phase
- Keep database backups at each major step
- Document any issues encountered
- Update this checklist as you progress

