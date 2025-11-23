<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class BloodDonor extends Model
{
    use HasFactory;

    protected $table = 'blood_donor';

    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = [
        'hospital_id',
        'branch_id',
        'donor_name',
        'date_of_birth',
        'blood_bank_product_id',
        'gender',
        'father_name',
        'address',
        'contact_no',
        'created_at',
    ];

    public $timestamps = false; // Only created_at exists, no updated_at

    /**
     * Relationship: A donor belongs to a blood bank product (blood group/type).
     */
    public function bloodBankProduct()
    {
        return $this->belongsTo(BloodBankProduct::class, 'blood_bank_product_id');
    }
}
