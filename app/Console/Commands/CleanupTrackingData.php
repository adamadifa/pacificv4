<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupTrackingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up salesman tracking data older than 14 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = 14;
        $cutoffDate = now()->subDays($days);

        $this->info("Cleaning up salesman tracking data older than {$days} days (Cutoff: {$cutoffDate->toDateTimeString()})...");

        try {
            $deletedRows = DB::table('salesman_tracking')
                ->where('tracked_at', '<', $cutoffDate)
                ->delete();

            $message = "Successfully cleaned up {$deletedRows} tracking records.";
            $this->info($message);
            Log::info($message);
        } catch (\Exception $e) {
            $errorMessage = "Failed to clean up tracking records: " . $e->getMessage();
            $this->error($errorMessage);
            Log::error($errorMessage);
        }
    }
}
