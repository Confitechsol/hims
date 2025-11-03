<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural form)
    protected $table = 'staff';

    // Fillable fields
    protected $fillable = [
        'hospital_id',
         'branch_id',
        'employee_id',
        'lang_id',
        'department_id',
        'staff_designation_id',
        'specialist',
        'qualification',
        'work_exp',
        'specialization',
        'name',
        'surname',
        'father_name',
        'mother_name',
        'contact_no',
        'emergency_contact_no',
        'email',
        'dob',
        'marital_status',
        'date_of_joining',
        'date_of_leaving',
        'local_address',
        'permanent_address',
        'note',
        'image',
        'password',
        'gender',
        'blood_group',
        'account_title',
        'bank_account_no',
        'bank_name',
        'ifsc_code',
        'bank_branch',
        'payscale',
        'basic_salary',
        'epf_no',
        'contract_type',
        'shift',
        'location',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'resume',
        'joining_letter',
        'resignation_letter',
        'other_document_name',
        'other_document_file',
        'user_id',
        'is_active',
        'verification_code',
        'zoom_api_key',
        'zoom_api_secret',
        'pan_number',
        'identification_number',
        'local_identification_number',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id', 'user_id');
    //     // 'user_id' in staff table -> 'user_id' in users table
    // }

    // If you want to disable default Laravel timestamps (created_at/updated_at)
    public $timestamps = false;
}
