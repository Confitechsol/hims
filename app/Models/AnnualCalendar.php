<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualCalendar extends Model
{
    use HasFactory;

    protected $table = 'annual_calendar';

    protected $fillable = [
        'hospital_id',
        'holiday_type',
        'from_date',
        'to_date',
        'description',
        'is_active',
        'holiday_color',
        'front_site',
        'created_by',
    ];

    protected $casts = [
        'from_date' => 'datetime',
        'to_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function holidayType()
    {
        return $this->belongsTo(HolidayType::class, 'holiday_type');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
