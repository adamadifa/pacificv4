<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StreamDownloadDirect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:stream-direct {--output=} {--chunk-size=1000} {--progress}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stream download backup database langsung dari database tanpa simpan file di server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai streaming download langsung dari database...');
        $this->info('📊 File akan langsung download ke komputer tanpa disimpan di server');

        $outputPath = $this->option('output');
        $chunkSize = (int) $this->option('chunk-size');
        $showProgress = $this->option('progress');

        try {
            // Set unlimited time dan memory
            set_time_limit(0);
            ini_set('memory_limit', '2G');

            // Generate nama file backup
            $filename = $outputPath ?: 'backup_direct_' . date('Y-m-d_H-i-s') . '.sql';

            $this->info("📄 Output file: {$filename}");
            $this->info("🔧 Chunk size: {$chunkSize} rows");
            $this->info("📈 Progress tracking: " . ($showProgress ? 'Enabled' : 'Disabled'));

            // Dapatkan semua tabel
            $tables = DB::select('SHOW TABLES');
            $totalTables = count($tables);

            $this->info("📊 Total tabel yang akan di-backup: {$totalTables}");

            // Buat file output di komputer user
            $outputHandle = fopen($filename, 'w');
            if (!$outputHandle) {
                $this->error("❌ Gagal membuat file output: {$filename}");
                return Command::FAILURE;
            }

            // Header file
            fwrite($outputHandle, "-- Direct Database Backup\n");
            fwrite($outputHandle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($outputHandle, "-- Streaming from Database\n\n");

            // Progress bar jika diaktifkan
            if ($showProgress) {
                $progressBar = $this->output->createProgressBar($totalTables);
                $progressBar->start();
            }

            $startTime = microtime(true);
            $totalRows = 0;

            // Process setiap tabel
            foreach ($tables as $index => $table) {
                $tableName = array_values((array) $table)[0];
                $currentTable = $index + 1;

                if ($showProgress) {
                    $this->info("\n📋 Processing table {$currentTable}/{$totalTables}: {$tableName}");
                }

                // Structure table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createStatement = array_values((array) $createTable[0])[1];

                fwrite($outputHandle, "-- Table structure for table `{$tableName}`\n");
                fwrite($outputHandle, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($outputHandle, $createStatement . ";\n\n");

                // Data table dengan chunking
                $tableRows = DB::table($tableName)->count();
                $totalRows += $tableRows;

                if ($tableRows > 0) {
                    fwrite($outputHandle, "-- Data for table `{$tableName}` ({$tableRows} rows)\n");

                    // Process data in chunks
                    for ($offset = 0; $offset < $tableRows; $offset += $chunkSize) {
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

                            fwrite($outputHandle, "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n");
                        }

                        // Progress comment setiap 1000 rows
                        if ($showProgress && $offset % 1000 === 0) {
                            $this->info("  📊 Processed {$offset}/{$tableRows} rows");
                        }

                        // Flush buffer setiap chunk
                        fflush($outputHandle);

                        // Reset memory dan garbage collection
                        if (function_exists('gc_collect_cycles')) {
                            gc_collect_cycles();
                        }

                        // Sleep sebentar untuk mengurangi beban server
                        usleep(10000); // 10ms
                    }
                    fwrite($outputHandle, "\n");
                }

                // Update progress bar
                if ($showProgress) {
                    $progressBar->advance();
                }

                // Flush buffer setiap tabel
                fflush($outputHandle);
            }

            if ($showProgress) {
                $progressBar->finish();
                $this->newLine();
            }

            // Completion comment
            fwrite($outputHandle, "-- Backup completed successfully!\n");
            fwrite($outputHandle, "-- Total tables processed: {$totalTables}\n");
            fwrite($outputHandle, "-- Total rows processed: {$totalRows}\n");
            fwrite($outputHandle, "-- Generated: " . date('Y-m-d H:i:s') . "\n");

            fclose($outputHandle);

            // Calculate statistics
            $elapsedTime = microtime(true) - $startTime;
            $fileSize = filesize($filename);
            $averageSpeed = $fileSize / $elapsedTime;

            $this->info('✅ Streaming download berhasil!');
            $this->info("📄 File: {$filename}");
            $this->info("📊 Ukuran: " . $this->formatFileSize($fileSize));
            $this->info("📋 Total tabel: {$totalTables}");
            $this->info("📊 Total rows: {$totalRows}");
            $this->info("⏱️  Waktu: " . round($elapsedTime, 2) . " detik");
            $this->info("🚀 Rata-rata speed: " . $this->formatFileSize($averageSpeed) . "/s");
            $this->info("📍 Lokasi: " . realpath($filename));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("❌ Terjadi kesalahan: " . $e->getMessage());
            Log::error('Streaming download error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Format file size
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
