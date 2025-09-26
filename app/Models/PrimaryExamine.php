<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimaryExamine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'primary_examine';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'ipdid',
        'visit_details_id',
        'bleeding',
        'headache',
        'pain',
        'constipation',
        'urinary_symptoms',
        'vomiting',
        'cough',
        'vaginal',
        'discharge',
        'oedema',
        'haemoroids',
        'weight',
        'height',
        'date',
        'general_condition',
        'finding_remark',
        'pelvic_examination',
        'sp',
    ];

    /**
     * Relationships can be added if needed, for example:
     * IPD details or visit details, if corresponding models exist.
     */
    public function ipd()
    {
        return $this->belongsTo(IpdDetails::class, 'ipdid');
    }

    public function visitDetails()
    {
        return $this->belongsTo(VisitDetails::class, 'visit_details_id');
    }
}
