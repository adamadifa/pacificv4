<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BackupLargeDatabaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 3600; // 1 hour timeout
    public $tries = 1; // Only try once

    protected $filename;
    protected $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($filename, $userId = null)
    {
        $this->filename = $filename;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Log::info('Starting large database backup job: ' . $this->filename);

            $backupPath = storage_path('app/backups');
            $backupFile = $backupPath . '/' . $this->filename;

            // Set unlimited time dan memory
            set_time_limit(0);
            ini_set('memory_limit', '2G');

            // Buat file backup
            $file = fopen($backupFile, 'w');

            // Header file
            fwrite($file, "-- Large Database Backup\n");
            fwrite($file, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($file, "-- Job Queue Backup\n\n");

            // Dapatkan semua tabel
            $tables = DB::select('SHOW TABLES');
            $totalTables = count($tables);
            $currentTable = 0;

            foreach ($tables as $table) {
                $currentTable++;
                $tableName = array_values((array) $table)[0];

                Log::info("Processing table {$currentTable}/{$totalTables}: {$tableName}");

                // Structure table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createStatement = array_values((array) $createTable[0])[1];

                fwrite($file, "-- Table structure for table `{$tableName}`\n");
                fwrite($file, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($file, $createStatement . ";\n\n");

                // Data table dengan chunking yang sangat kecil untuk database besar
                $totalRows = DB::table($tableName)->count();
                $chunkSize = 100; // Process 100 rows at a time untuk database sangat besar

                if ($totalRows > 0) {
                    fwrite($file, "-- Data for table `{$tableName}` ({$totalRows} rows)\n");

                    // Process data in chunks
                    for ($offset = 0; $offset < $totalRows; $offset += $chunkSize) {
                        $rows = DB::table($tableName)
                            ->offset($offset)
                            ->limit($chunkSize)
                            ->get();

                        foreach ($rows as $row) {
                            $values = array_map(function ($value) {
                                if ($value === null) return 'NULL';
                                if (is_string($value)) return "'" . addslashes($value) . "'";
                                return $value;
                            }, (array) $row);

                            fwrite($file, "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n");
                        }

                        // Flush buffer setiap chunk
                        fflush($file);

                        // Reset memory dan garbage collection
                        if (function_exists('gc_collect_cycles')) {
                            gc_collect_cycles();
                        }

                        // Sleep sebentar untuk mengurangi beban server
                        usleep(50000); // 50ms

                        // Log progress setiap 1000 rows
                        if ($offset % 1000 === 0) {
                            Log::info("Table {$tableName}: Processed {$offset}/{$totalRows} rows");
                        }
                    }
                    fwrite($file, "\n");
                }

                Log::info("Completed table {$tableName}");
            }

            fclose($file);

            Log::info('Large database backup completed successfully: ' . $this->filename);

            // Update status atau kirim notifikasi jika diperlukan
            $this->notifyCompletion();
        } catch (\Exception $e) {
            Log::error('Large database backup failed: ' . $e->getMessage());

            // Cleanup file yang gagal
            if (isset($file) && is_resource($file)) {
                fclose($file);
            }

            if (File::exists($backupFile)) {
                File::delete($backupFile);
            }

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Large database backup job failed: ' . $exception->getMessage());

        // Cleanup dan notifikasi error
        $backupPath = storage_path('app/backups');
        $backupFile = $backupPath . '/' . $this->filename;

        if (File::exists($backupFile)) {
            File::delete($backupFile);
        }

        $this->notifyFailure($exception);
    }

    /**
     * Notify completion
     */
    private function notifyCompletion()
    {
        // Implementasi notifikasi completion
        // Bisa berupa email, webhook, atau update status di database
        Log::info('Backup completed notification sent');
    }

    /**
     * Notify failure
     */
    private function notifyFailure($exception)
    {
        // Implementasi notifikasi failure
        Log::error('Backup failed notification sent: ' . $exception->getMessage());
    }
}
