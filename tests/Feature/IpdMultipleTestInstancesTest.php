<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\IpdDetail;
use App\Models\IpdPrescription;
use App\Models\IpdPrescriptionTest;
use App\Models\Pathology;
use App\Models\Radio;
use App\Models\PathologyBilling;
use App\Models\PathologyReport;
use App\Models\RadiologyBilling;
use App\Models\RadiologyReport;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IpdMultipleTestInstancesTest extends TestCase
{
    use RefreshDatabase;

    protected $ipd;
    protected $doctor;
    protected $pathologyTest;
    protected $radiologyTest;
    protected $testDate;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $this->doctor = Doctor::firstOrCreate(
            ['doctor_id' => 'TEST001'],
            ['name' => 'Test Doctor', 'hospital_id' => '00000001', 'branch_id' => '00000001']
        );

        $this->ipd = IpdDetail::firstOrCreate(
            ['ipd_id' => 'TEST-IPD-001'],
            [
                'patient_id' => 1,
                'admission_date' => Carbon::now()->toDateString(),
                'hospital_id' => '00000001',
                'branch_id' => '00000001',
            ]
        );

        $this->pathologyTest = Pathology::firstOrCreate(
            ['test_name' => 'CBC Test'],
            ['hospital_id' => '00000001', 'branch_id' => '00000001']
        );

        $this->radiologyTest = Radio::firstOrCreate(
            ['test_name' => 'X-Ray Chest'],
            ['hospital_id' => '00000001', 'branch_id' => '00000001']
        );

        $this->testDate = Carbon::now()->toDateString();
    }

    /**
     * Test Case 1.1: Database Migration - Instance Tracking Fields
     */
    public function test_database_has_instance_tracking_fields()
    {
        $columns = DB::select("SHOW COLUMNS FROM ipd_prescription_test");
        $columnNames = array_column($columns, 'Field');
        
        $this->assertContains('instance_number', $columnNames, 'instance_number column exists');
        $this->assertContains('test_date', $columnNames, 'test_date column exists');
        $this->assertContains('prescription_time', $columnNames, 'prescription_time column exists');
        $this->assertContains('notes', $columnNames, 'notes column exists');
    }

    /**
     * Test Case 2.1: Add Prescription - Single Test Instance
     */
    public function test_add_prescription_single_test_instance()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );

        $test = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instanceNumber,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $this->assertEquals(1, $test->instance_number);
        $this->assertEquals($this->testDate, $test->test_date);
        $this->assertNotNull($test->prescription_time);
    }

    /**
     * Test Case 2.2: Add Prescription - Same Test Twice
     */
    public function test_add_prescription_same_test_twice()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // First instance
        $instance1 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );

        $test1 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance1,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // Second instance
        $instance2 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );

        $test2 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance2,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $this->assertEquals(1, $test1->instance_number);
        $this->assertEquals(2, $test2->instance_number);
        $this->assertEquals($this->testDate, $test1->test_date);
        $this->assertEquals($this->testDate, $test2->test_date);
    }

    /**
     * Test Case 2.3: Add Prescription - Same Test Three Times
     */
    public function test_add_prescription_same_test_three_times()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $tests = [];
        for ($i = 0; $i < 3; $i++) {
            $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                $this->pathologyTest->id,
                $this->testDate,
                'pathology',
                $prescription->id
            );

            $tests[] = IpdPrescriptionTest::create([
                'ipd_prescription_id' => $prescription->id,
                'pathology_id' => $this->pathologyTest->id,
                'instance_number' => $instanceNumber,
                'test_date' => $this->testDate,
                'prescription_time' => Carbon::now()->format('H:i:s'),
                'hospital_id' => '00000001',
                'branch_id' => '00000001',
            ]);
        }

        $this->assertEquals(1, $tests[0]->instance_number);
        $this->assertEquals(2, $tests[1]->instance_number);
        $this->assertEquals(3, $tests[2]->instance_number);
    }

    /**
     * Test Case 2.5: Add Prescription - Instance Notes
     */
    public function test_add_prescription_with_instance_notes()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance1 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );

        $test1 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance1,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'notes' => 'Morning sample',
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance2 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );

        $test2 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance2,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'notes' => 'Evening sample',
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $this->assertEquals('Morning sample', $test1->notes);
        $this->assertEquals('Evening sample', $test2->notes);
    }

    /**
     * Test Case 2.8: Multiple Prescriptions Same Day - Instance Continuity
     */
    public function test_multiple_prescriptions_instance_continuity()
    {
        // First prescription
        $prescription1 = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance1 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription1->id
        );

        $test1 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription1->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance1,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // Second prescription same day
        $prescription2 = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance2 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription2->id
        );

        $test2 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription2->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance2,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $this->assertEquals(1, $test1->instance_number);
        $this->assertEquals(2, $test2->instance_number, 'Instance number should continue across prescriptions');
    }

    /**
     * Test Case EC4: Same Test Different Days
     */
    public function test_same_test_different_days_resets_instance()
    {
        $date1 = Carbon::now()->toDateString();
        $date2 = Carbon::now()->addDay()->toDateString();

        $prescription1 = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $date1,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance1 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $date1,
            'pathology',
            $prescription1->id
        );

        $test1 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription1->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance1,
            'test_date' => $date1,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // Second day
        $prescription2 = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $date2,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $instance2 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $date2,
            'pathology',
            $prescription2->id
        );

        $test2 = IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription2->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance2,
            'test_date' => $date2,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        $this->assertEquals(1, $test1->instance_number);
        $this->assertEquals(1, $test2->instance_number, 'Instance number should reset on new day');
    }

    /**
     * Test Case 5.2: IpdPrescriptionTest Model - Scope Same Test Same Day
     */
    public function test_scope_same_test_same_day()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // Create 3 instances
        for ($i = 0; $i < 3; $i++) {
            $instanceNumber = IpdPrescriptionTest::getNextInstanceNumber(
                $this->pathologyTest->id,
                $this->testDate,
                'pathology',
                $prescription->id
            );

            IpdPrescriptionTest::create([
                'ipd_prescription_id' => $prescription->id,
                'pathology_id' => $this->pathologyTest->id,
                'instance_number' => $instanceNumber,
                'test_date' => $this->testDate,
                'prescription_time' => Carbon::now()->format('H:i:s'),
                'hospital_id' => '00000001',
                'branch_id' => '00000001',
            ]);
        }

        $instances = IpdPrescriptionTest::sameTestSameDay(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology'
        )->get();

        $this->assertEquals(3, $instances->count());
        $this->assertEquals(1, $instances->first()->instance_number);
        $this->assertEquals(3, $instances->last()->instance_number);
    }

    /**
     * Test Case 5.3: IpdPrescriptionTest Model - Get Next Instance Number
     */
    public function test_get_next_instance_number()
    {
        $prescription = IpdPrescription::create([
            'ipd_id' => $this->ipd->id,
            'date' => $this->testDate,
            'prescribe_by' => $this->doctor->id,
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // First call should return 1
        $instance1 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );
        $this->assertEquals(1, $instance1);

        // Create first instance
        IpdPrescriptionTest::create([
            'ipd_prescription_id' => $prescription->id,
            'pathology_id' => $this->pathologyTest->id,
            'instance_number' => $instance1,
            'test_date' => $this->testDate,
            'prescription_time' => Carbon::now()->format('H:i:s'),
            'hospital_id' => '00000001',
            'branch_id' => '00000001',
        ]);

        // Second call should return 2
        $instance2 = IpdPrescriptionTest::getNextInstanceNumber(
            $this->pathologyTest->id,
            $this->testDate,
            'pathology',
            $prescription->id
        );
        $this->assertEquals(2, $instance2);
    }
}
