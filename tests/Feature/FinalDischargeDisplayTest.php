<?php

namespace Tests\Feature;

use App\Models\IpdDetail;
use App\Services\IpdFinalBillService;
use Tests\TestCase;

class FinalDischargeDisplayTest extends TestCase
{
    public function test_generated_final_bill_shows_the_stored_system_discharge_time(): void
    {
        $ipd = new IpdDetail();
        $ipd->discharged = 'yes';
        $ipd->discharged_date = '2026-10-01 09:00:00';
        $ipd->final_bill_generated_at = '2026-10-06 08:40:00';
        $ipd->final_discharge_at = '2026-10-06 08:41:12';

        $shown = app(IpdFinalBillService::class)->finalDischargeDisplayAt($ipd);

        $this->assertSame('2026-10-06 08:41:12', $shown->format('Y-m-d H:i:s'));
        $this->assertSame('2026-10-01 09:00:00', (string) $ipd->discharged_date);
    }

    public function test_final_bill_preview_keeps_the_user_discharge_time(): void
    {
        $ipd = new IpdDetail();
        $ipd->discharged = 'yes';
        $ipd->discharged_date = '2026-10-01 09:00:00';

        $this->assertNull(app(IpdFinalBillService::class)->finalDischargeDisplayAt($ipd));
    }

    public function test_older_generated_bill_without_the_new_field_uses_the_generation_time(): void
    {
        $ipd = new IpdDetail();
        $ipd->final_bill_generated_at = '2026-10-06 08:40:00';

        $shown = app(IpdFinalBillService::class)->finalDischargeDisplayAt($ipd);

        $this->assertSame('2026-10-06 08:40:00', $shown->format('Y-m-d H:i:s'));
    }
}
