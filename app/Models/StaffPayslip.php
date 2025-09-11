<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayslip extends Model
{
    use HasFactory;

    // Table name (since it's not plural)
    protected $table = 'staff_payslip';

    // Primary key
    protected $primaryKey = 'id';

    // Laravel doesn’t automatically manage timestamps here (only created_at exists)
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'hospital_id',
        'role_id',
        'staff_id',
        'is_active',
        'created_at',
    ];

    // Casts
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
