<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BackupLargeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:large-database {--filename=} {--chunk-size=100} {--use-queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database besar dengan optimasi untuk menghindari timeout';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Memulai backup database besar...');

        // Set unlimited time dan memory
        set_time_limit(0);
        ini_set('memory_limit', '2G');

        try {
            // Generate nama file backup
            $filename = $this->option('filename') ?: 'backup_large_' . date('Y-m-d_H-i-s') . '.sql';
            $chunkSize = (int) $this->option('chunk-size');
            $useQueue = $this->option('use-queue');

            // Path untuk menyimpan backup
            $backupPath = storage_path('app/backups');

            // Buat direktori jika belum ada
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
                $this->info('📁 Direktori backup berhasil dibuat.');
            }

            if ($useQueue) {
                $this->info('⏳ Menggunakan queue system untuk backup...');
                $this->dispatchQueueJob($filename);
                return Command::SUCCESS;
            }

            // Cek apakah mysqldump tersedia
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                $this->info('🔧 Menggunakan mysqldump dengan optimasi...');
                $result = $this->backupWithMysqldump($mysqldumpPath, $backupPath, $filename);
            } else {
                $this->info('🔧 Menggunakan Laravel DB dengan optimasi...');
                $result = $this->backupWithLaravel($backupPath, $filename, $chunkSize);
            }

            if ($result['success']) {
                $fileSize = File::size($backupPath . '/' . $filename);
                $formattedSize = $this->formatFileSize($fileSize);

                $this->info('✅ Backup database besar berhasil dibuat!');
                $this->info('📄 File: ' . $filename);
                $this->info('📊 Ukuran: ' . $formattedSize);
                $this->info('📍 Lokasi: ' . $backupPath . '/' . $filename);

                return Command::SUCCESS;
            } else {
                $this->error('❌ Gagal membuat backup database besar. Error: ' . $result['error']);
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('❌ Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Dispatch queue job untuk backup
     */
    private function dispatchQueueJob($filename)
    {
        try {
            $job = new \App\Jobs\BackupLargeDatabaseJob($filename, auth()->id());
            dispatch($job);

            $this->info('✅ Job backup database telah di-queue.');
            $this->info('📋 Cek status di log atau queue monitor.');
        } catch (\Exception $e) {
            $this->error('❌ Gagal dispatch job: ' . $e->getMessage());
        }
    }

    /**
     * Cari path mysqldump yang tersedia di sistem
     */
    private function findMysqldump()
    {
        $possiblePaths = [
            'mysqldump',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe',
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe',
            'C:\xampp\mysql\bin\mysqldump.exe',
            'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe',
            'C:\wamp\bin\mysql\mysql5.7.36\bin\mysqldump.exe',
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isExecutable($path)) {
                return $path;
            }
        }

        return false;
    }

    /**
     * Cek apakah file executable
     */
    private function isExecutable($path)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            return file_exists($path);
        } else {
            return file_exists($path) && is_executable($path);
        }
    }

    /**
     * Backup menggunakan mysqldump dengan optimasi
     */
    private function backupWithMysqldump($mysqldumpPath, $backupPath, $filename)
    {
        try {
            $dbConfig = config('database.connections.mysql');

            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --port=%s --single-transaction --routines --triggers --set-gtid-purged=OFF --max_allowed_packet=1G --net_buffer_length=16384 %s > "%s"',
                $mysqldumpPath,
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port'] ?? 3306),
                escapeshellarg($dbConfig['database']),
                $backupPath . '/' . $filename
            );

            $this->info('⏳ Eksekusi mysqldump (ini mungkin memakan waktu lama)...');

            exec($command . ' 2>&1', $output, $returnCode);

            if ($returnCode === 0) {
                return ['success' => true, 'error' => null];
            } else {
                $errorMessage = implode("\n", $output);
                return ['success' => false, 'error' => $errorMessage];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Backup menggunakan Laravel DB dengan optimasi
     */
    private function backupWithLaravel($backupPath, $filename, $chunkSize)
    {
        try {
            $dbConfig = config('database.connections.mysql');
            $backupFile = $backupPath . '/' . $filename;

            // Buat file backup
            $file = fopen($backupFile, 'w');

            // Header file
            fwrite($file, "-- Large Database Backup\n");
            fwrite($file, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($file, "-- Command Line Backup\n\n");

            // Dapatkan semua tabel
            $tables = DB::select('SHOW TABLES');
            $totalTables = count($tables);

            $this->info("📊 Total tabel yang akan di-backup: {$totalTables}");

            // Progress bar untuk tabel
            $progressBar = $this->output->createProgressBar($totalTables);
            $progressBar->start();

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];

                // Structure table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createStatement = array_values((array) $createTable[0])[1];

                fwrite($file, "-- Table structure for table `{$tableName}`\n");
                fwrite($file, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($file, $createStatement . ";\n\n");

                // Data table dengan chunking
                $totalRows = DB::table($tableName)->count();

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
                        usleep(10000); // 10ms
                    }
                    fwrite($file, "\n");
                }

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            fclose($file);

            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
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
