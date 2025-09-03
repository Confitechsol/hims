<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $table = 'custom_field_values';

    protected $fillable = [
        'hospital_id',
        'belong_table_id',
        'custom_field_id',
        'field_value',
    ];

    /**
     * Each value belongs to a custom field definition
     */
    public function customField()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }

    /**
     * Generic relation: the record this value belongs to
     * (depends on how you map `belong_table_id`)
     */
    public function parent()
    {
        // This could be polymorphic in a real app
        return $this->morphTo();
    }
}
