<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageRoomRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'bed_group_id',
        'insurer_room_code',
        'label',
        'rate',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function bedGroup()
    {
        return $this->belongsTo(BedGroup::class, 'bed_group_id');
    }
}
