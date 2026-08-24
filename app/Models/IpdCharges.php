<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class IpdCharges extends Model
{
    use HasFactory;

    public const STAGE_APPROVAL = 'approval';

    public const STAGE_APPROVAL_PREVIEW = 'approval_preview';

    public const STAGE_FINAL_PREVIEW = 'final_preview';

    public const STAGE_FINAL_BILL = 'final_bill';

    protected $table = 'ipd_charges';

    protected $fillable = [
        'ipd_id',
        'charge_type_id',
        'charge_category_id',
        'charge_id',
        'standard_charge',
        'tpa_charge',
        'qty',
        'total',
        'discount_percentage',
        'tax',
        'net_amount',
        'charge_note',
        'date',
        'show_on_approval_bill',
        'show_on_approval_preview',
        'show_on_final_preview',
        'show_on_final_bill',
    ];

    protected $casts = [
        'show_on_approval_bill' => 'boolean',
        'show_on_approval_preview' => 'boolean',
        'show_on_final_preview' => 'boolean',
        'show_on_final_bill' => 'boolean',
    ];

    public static function billStageColumn(string $stage): string
    {
        return match ($stage) {
            self::STAGE_APPROVAL => 'show_on_approval_bill',
            self::STAGE_APPROVAL_PREVIEW => 'show_on_approval_preview',
            self::STAGE_FINAL_PREVIEW => 'show_on_final_preview',
            self::STAGE_FINAL_BILL => 'show_on_final_bill',
            default => throw new InvalidArgumentException('Unknown bill stage: ' . $stage),
        };
    }

    public function scopeVisibleForBillStage(Builder $query, ?string $stage): Builder
    {
        if ($stage === null || $stage === '') {
            return $query;
        }

        return $query->where(self::billStageColumn($stage), true);
    }

    public function charge()
    {
        return $this->belongsTo(Charge::class);
    }

    public function ipd()
    {
        return $this->belongsTo(IpdDetail::class);
    }

    public function chargeCategory()
    {
        return $this->belongsTo(ChargeCategory::class, 'charge_category_id', 'id');
    }

    public function chargeType()
    {
        return $this->belongsTo(ChargeTypeMaster::class, 'charge_type_master', 'id');
    }
}
