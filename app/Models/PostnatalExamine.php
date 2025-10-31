<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostnatalExamine extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'postnatal_examine';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'patient_id',
        'labor_time',
        'delivery_time',
        'routine_question',
        'general_remark',
    ];

    /**
     * Get the patient associated with this postnatal examination.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
