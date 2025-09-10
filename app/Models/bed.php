<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bed extends Model
{
    use HasFactory;

    protected $table = 'bed';

    public $timestamps = false; // since only created_at exists and no updated_at

    protected $fillable = [
        'hospital_id',
        'name',
        'bed_type_id',
        'bed_group_id',
        'is_active',
    ];

    protected $casts = [
        'bed_type_id' => 'integer',
        'bed_group_id' => 'integer',
    ];

    // Relationships
    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class, 'bed_type_id');
    }

    public function bedGroup(): BelongsTo
    {
        return $this->belongsTo(BedGroup::class, 'bed_group_id');
    }
}
