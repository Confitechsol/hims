<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpdVisits extends Model
{
    use HasFactory;

    protected $table = 'opd_visits';

    protected $fillable = [
        'visit_id',
        'patient_id',
        'opd_id',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function opd()
    {
        return $this->belongsTo(OpdDetail::class);
    }
}
