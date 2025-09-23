<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table    = 'doctor';
    public $timestamps  = false;
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'doctor_id',
        'name',
        'surname',
        'email',
        'contact_no',
        'department_id',
        'designation',
        'qualification',
        'work_exp',
        'specialization',
        'dob',
        'gender',
        'blood_group',
        'user_id',
        'is_active',
    ];

    /**
     * Relationships
     */

    // Relation with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relation with Department
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    // Relation with Designation
    public function designation()
    {
        return $this->belongsTo(StaffDesignation::class, 'designation_id');
    }
}
