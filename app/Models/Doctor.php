<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
    protected $table    = 'doctor';
    protected $primaryKey = 'id';
    public $timestamps  = false;
    protected $fillable = [
        'hospital_id',
        'branch_id',
        'doctor_id',
        'registration_no',
        'role_id',
        'name',
        'surname',
        'email',
        'contact_no',
        'emergency_contact_no',
        'department_id',
        'designation',
        'qualification',
        'work_exp',
        'specialization',
        'specialist',
        'dob',
        'gender',
        'blood_group',
        'local_address',
        'permanent_address',
        'marital_status',
        'consultation_fee',
        'ipd_visit_fee',
        'date_of_joining',
        'date_of_leaving',
        'pan_number',
        'identification_number',
        'signature',
        'resume',
        'joining_letter',
        'other_document_name',
        'other_document_file',
        'account_title',
        'bank_account_no',
        'bank_name',
        'ifsc_code',
        'bank_branch',
        'user_id',
        'password',
        'is_active',
        'is_staff',
        'verification_code',
        'zoom_api_key',
        'zoom_api_secret',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dob' => 'date',
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

    public function doctorGlobalShifts()
    {
        return $this->hasMany(DoctorGlobalShift::class, 'doctor_id', 'id');
    }
}
