<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdPrescription extends Model
{
    use HasFactory;

    // Table name (optional if Laravel naming matches)
    protected $table = 'opd_prescription';

    // Mass assignable attributes
    protected $fillable = [
        'opd_id',
        'visit_id',
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
    ];

    // Casts for automatic data conversion
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * 🔗 Relation: Each prescription belongs to one OPD.
     */
    public function opd()
    {
        return $this->belongsTo(OpdDetail::class, 'opd_id');
    }

}