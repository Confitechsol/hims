<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpdPrescriptionBasic extends Model
{
    use HasFactory;

    protected $table = 'ipd_prescription_basic';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'ipd_id',
        'visit_details_id',
        'attachment',
        'attachment_name',
        'header_note',
        'footer_note',
        'finding_description',
        'is_finding_print',
        'date',
        'generated_by',
        'prescribe_by',
    ];

    /**
     * Relationships
     */
    public function ipdDetail()
    {
        return $this->belongsTo(IpdDetail::class, 'ipd_id');
    }

    public function visitDetail()
    {
        return $this->belongsTo(VisitDetail::class, 'visit_details_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function prescribedBy()
    {
        return $this->belongsTo(Doctor::class, 'prescribe_by');
    }
}
