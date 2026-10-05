<?php

namespace Tests\Feature;

use App\Exceptions\InvalidTemporaryAdmissionException;
use App\Models\IpdDetail;
use App\Services\Billing\TemporaryAdmissionEstimateAddon;
use App\Services\Billing\TemporaryAdmissionEstimateContext;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RuntimeException;
use Tests\TestCase;

class TemporaryAdmissionEstimateAddonTest extends TestCase
{
    public function test_temporary_admission_is_used_only_during_the_callback_and_is_not_stored_on_the_ipd(): void
    {
        $context = new TemporaryAdmissionEstimateContext();
        $addon = new TemporaryAdmissionEstimateAddon($context);
        $ipd = $this->ipd('2026-10-05 08:15:00');
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => '2026-10-04T14:30',
        ]);

        $seen = null;
        $result = $addon->run($request, $ipd, false, function () use ($context, &$seen) {
            $seen = $context->admissionAt();

            return 'calculated';
        });

        $this->assertSame('calculated', $result);
        $this->assertSame('2026-10-04 14:30:00', $seen->format('Y-m-d H:i:s'));
        $this->assertNull($context->admissionAt());
        $this->assertSame('2026-10-05 08:15:00', $ipd->date);
        $this->assertFalse($ipd->exists);
    }

    public function test_same_minute_as_the_real_admission_does_not_activate_the_add_on(): void
    {
        $context = new TemporaryAdmissionEstimateContext();
        $addon = new TemporaryAdmissionEstimateAddon($context);
        $ipd = $this->ipd('2026-10-05 08:15:40');
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => '2026-10-05T08:15',
        ]);

        $called = false;
        $addon->run($request, $ipd, false, function () use ($context, &$called) {
            $called = true;
            $this->assertNull($context->admissionAt());

            return null;
        });

        $this->assertTrue($called);
    }

    public function test_approval_bills_ignore_the_temporary_admission(): void
    {
        $context = new TemporaryAdmissionEstimateContext();
        $addon = new TemporaryAdmissionEstimateAddon($context);
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => 'not-a-date',
        ]);

        $addon->run($request, $this->ipd('2026-10-05 08:15:00'), true, function () use ($context) {
            $this->assertNull($context->admissionAt());

            return null;
        });
    }

    public function test_invalid_temporary_admission_is_rejected(): void
    {
        $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
        $ipd = $this->ipd('2026-10-05 08:15:00');

        $this->expectException(InvalidTemporaryAdmissionException::class);
        $addon->resolve(
            Request::create('/estimate', 'GET', ['temp_admission_at' => 'not-a-date']),
            $ipd,
            false
        );
    }

    public function test_context_is_cleared_when_calculation_fails(): void
    {
        $context = new TemporaryAdmissionEstimateContext();

        try {
            $context->using(Carbon::parse('2026-10-04 14:30:00'), function () {
                throw new RuntimeException('calculation failed');
            });
            $this->fail('The calculation exception should leave the add-on.');
        } catch (RuntimeException $e) {
            $this->assertSame('calculation failed', $e->getMessage());
        }

        $this->assertNull($context->admissionAt());
    }

    private function ipd(string $admission): IpdDetail
    {
        $ipd = new IpdDetail();
        $ipd->id = 15;
        $ipd->ipd_no = 'IPDN0015';
        $ipd->date = $admission;

        return $ipd;
    }
}
