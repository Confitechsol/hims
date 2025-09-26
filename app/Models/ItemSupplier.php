<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSupplier extends Model
{
    use HasFactory;

    protected $table = 'item_supplier';

    protected $fillable = [
        'hospital_id',
        'item_supplier',
        'phone',
        'email',
        'address',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_email',
        'description',
        'is_active',
    ];

    /**
     * Relationship: A supplier can supply many item stocks
     */
    public function stocks()
    {
        return $this->hasMany(ItemStock::class, 'supplier_id');
    }
}