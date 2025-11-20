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
        'header_note',
        'footer_note',
        'finding_description',
        'finding_categories',
        'findings',
        'is_finding_print',
        'pathology_id',
        'radiology_id',
        'date',
        'notification_to',
        'prescribed_by',
    ];

    // Casts for automatic data conversion
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * 🔗 Relation: Each prescription belongs to one OPD.
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

}