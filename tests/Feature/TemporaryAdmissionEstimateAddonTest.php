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
    public function test_past_as_of_after_admission_is_resolved(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 15:00:00'));

        $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
        $ipd = $this->ipd('2026-11-09 10:00:00');
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => '2026-11-10T12:00',
        ]);

        $resolved = $addon->resolve($request, $ipd, false);

        $this->assertNotNull($resolved);
        $this->assertSame('2026-11-10 12:00:00', $resolved->format('Y-m-d H:i:s'));
        $this->assertSame('2026-11-09 10:00:00', $ipd->date);

        Carbon::setTestNow();
    }

    public function test_same_minute_as_now_does_not_activate_as_of_override(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 15:05:40'));

        $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
        $ipd = $this->ipd('2026-11-09 10:00:00');
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => '2026-11-10T15:05',
        ]);

        $this->assertNull($addon->resolve($request, $ipd, false));

        Carbon::setTestNow();
    }

    public function test_before_admission_is_rejected(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 15:00:00'));

        try {
            $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
            $ipd = $this->ipd('2026-11-10 12:00:00');
            $addon->resolve(
                Request::create('/estimate', 'GET', ['temp_admission_at' => '2026-11-10T11:00']),
                $ipd,
                false
            );
            $this->fail('Expected InvalidTemporaryAdmissionException');
        } catch (InvalidTemporaryAdmissionException $e) {
            $this->assertStringContainsString('before admission', $e->getMessage());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_after_current_time_is_rejected(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 15:00:00'));

        try {
            $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
            $ipd = $this->ipd('2026-11-09 10:00:00');
            $addon->resolve(
                Request::create('/estimate', 'GET', ['temp_admission_at' => '2026-11-10T16:00']),
                $ipd,
                false
            );
            $this->fail('Expected InvalidTemporaryAdmissionException');
        } catch (InvalidTemporaryAdmissionException $e) {
            $this->assertStringContainsString('current date and time', $e->getMessage());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_approval_bills_ignore_estimate_as_of_even_when_invalid(): void
    {
        $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => 'not-a-date',
        ]);

        $this->assertNull($addon->resolve($request, $this->ipd('2026-11-10 08:15:00'), true));
    }

    public function test_invalid_estimate_time_is_rejected(): void
    {
        $addon = new TemporaryAdmissionEstimateAddon(new TemporaryAdmissionEstimateContext());
        $ipd = $this->ipd('2026-11-10 08:15:00');

        $this->expectException(InvalidTemporaryAdmissionException::class);
        $addon->resolve(
            Request::create('/estimate', 'GET', ['temp_admission_at' => 'not-a-date']),
            $ipd,
            false
        );
    }

    public function test_run_does_not_override_admission_context(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-11-10 15:00:00'));

        $context = new TemporaryAdmissionEstimateContext();
        $addon = new TemporaryAdmissionEstimateAddon($context);
        $ipd = $this->ipd('2026-11-09 08:15:00');
        $request = Request::create('/estimate', 'GET', [
            'temp_admission_at' => '2026-11-10T12:00',
        ]);

        $seen = 'unset';
        $addon->run($request, $ipd, false, function () use ($context, &$seen) {
            $seen = $context->admissionAt();

            return null;
        });

        $this->assertNull($seen);

        Carbon::setTestNow();
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
