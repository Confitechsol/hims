<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AntenatalExamine extends Model
{
    use HasFactory;

    protected $table = 'antenatal_examine';

    protected $fillable = [
        'primary_examine_id',
        'visit_details_id',
        'ipdid',
        'uter_size',
        'uterus_size',
        'presentation_position',
        'brim_presentation',
        'foeta_heart',
        'blood_pressure',
        'antenatal_oedema',
        'antenatal_weight',
        'urine_sugar',
        'urine',
        'remark',
        'next_visit',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function primaryExamine(): BelongsTo
    {
        return $this->belongsTo(PrimaryExamine::class, 'primary_examine_id');
    }

    public function visitDetails(): BelongsTo
    {
        return $this->belongsTo(VisitDetail::class, 'visit_details_id');
    }

    public function ipd(): BelongsTo
    {
        return $this->belongsTo(Ipd::class, 'ipdid');
    }
}
