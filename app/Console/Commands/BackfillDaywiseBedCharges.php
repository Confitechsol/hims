<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DaywiseBedChargeService;
use Carbon\Carbon;

class BackfillDaywiseBedCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bedcharges:backfill {ipd_id} {--start=} {--end=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backfill daywise bed charges for an IPD over a date range';

    protected $service;

    public function __construct(DaywiseBedChargeService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    public function handle()
    {
        $ipdId = $this->argument('ipd_id');
        $start = $this->option('start') ?: Carbon::parse(now())->subDays(1)->format('Y-m-d');
        $end = $this->option('end') ?: $start;

        $this->info("Starting backfill for IPD {$ipdId} from {$start} to {$end}");
        $result = $this->service->calculateChargesForDateRange($ipdId, $start, $end);

        $this->info('Backfill result:');
        $this->info(json_encode($result));
        return 0;
    }
}
