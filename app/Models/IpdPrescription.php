<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPrescription extends Model
{
    use HasFactory;

    // Table name (optional if Laravel naming matches)
    protected $table = 'ipd_prescription';

    // Mass assignable attributes
    protected $fillable = [
        'prescription_number',
        'ipd_id',
        'visit_details_id',  // NEW
        'header_note',
        'footer_note',
        'finding_description',
        'finding_categories',
        'findings',
        'is_finding_print',
        'pathology_id',  // Keep for backward compatibility, will be deprecated
        'radiology_id',  // Keep for backward compatibility, will be deprecated
        'date',
        'notification_to',
        'prescribed_by',
        'attachment',      // NEW
        'attachment_name', // NEW
    ];

    // Casts for automatic data conversion
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * 🔗 Relation: Each prescription belongs to one IPD.
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    /**
     * 🔗 Relation: Prescription has many tests (pathology/radiology)
     */
    public function tests()
    {
        return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id');
    }

    /**
     * 🔗 Relation: Prescription has many pathology tests
     */
    public function pathologyTests()
    {
        return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id')
                    ->whereNotNull('pathology_id');
    }

    /**
     * 🔗 Relation: Prescription has many radiology tests
     */
    public function radiologyTests()
    {
        return $this->hasMany(IpdPrescriptionTest::class, 'ipd_prescription_id')
                    ->whereNotNull('radiology_id');
    }

    /**
     * 🔗 Relation: Prescription prescribed by doctor
     */
    public function prescribedBy()
    {
        return $this->belongsTo(Doctor::class, 'prescribed_by');
    }

    /**
     * 🔗 Relation: Prescription has many medicines
     */
    public function medicines()
    {
        return $this->hasMany(IpdMedicine::class, 'prescription_id');
    }

}