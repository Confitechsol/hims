<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;

    protected $table = 'custom_fields';

    protected $fillable = [
        'hospital_id',
        'branch_id',
        'name',
        'belong_to',
        'type',
        'bs_column',
        'validation',
        'field_values',
        'visible_on_print',
        'visible_on_report',
        'visible_on_table',
        'visible_on_patient_panel',
        'weight',
        'is_active',
    ];

    /**
     * A custom field can have many values across records
     */
    public function values()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id');
    }
}
