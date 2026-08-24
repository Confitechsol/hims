<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

trait HasIpdBillVisibility
{
    public const STAGE_APPROVAL = 'approval';

    public const STAGE_APPROVAL_PREVIEW = 'approval_preview';

    public const STAGE_FINAL_PREVIEW = 'final_preview';

    public const STAGE_FINAL_BILL = 'final_bill';

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

    /**
     * @return array<string, bool>
     */
    public static function billVisibilityFromRequest($request): array
    {
        $parse = static function ($value): bool {
            if ($value === null || $value === '') {
                return true;
            }

            return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
                ?? ((string) $value === '1');
        };

        return [
            'show_on_approval_bill' => $parse($request->input('show_on_approval_bill', 1)),
            'show_on_approval_preview' => $parse($request->input('show_on_approval_preview', 1)),
            'show_on_final_preview' => $parse($request->input('show_on_final_preview', 1)),
            'show_on_final_bill' => $parse($request->input('show_on_final_bill', 1)),
        ];
    }
}
