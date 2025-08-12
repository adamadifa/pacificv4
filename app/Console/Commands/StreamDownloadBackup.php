<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StreamDownloadBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:stream-download {filename} {--output=} {--chunk-size=1024}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stream download backup database dengan progress bar dan resume capability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filename = $this->argument('filename');
        $outputPath = $this->option('output') ?: $filename;
        $chunkSize = (int) $this->option('chunk-size') * 1024; // Convert to bytes

        $this->info('🚀 Memulai streaming download backup database...');
        $this->info("📄 File: {$filename}");
        $this->info("📁 Output: {$outputPath}");
        $this->info("🔧 Chunk Size: " . ($chunkSize / 1024) . "KB");

        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!File::exists($backupPath)) {
                $this->error("❌ File backup tidak ditemukan: {$filename}");
                return Command::FAILURE;
            }

            $fileSize = File::size($backupPath);
            $this->info("📊 Ukuran file: " . $this->formatFileSize($fileSize));

            // Cek apakah file output sudah ada (untuk resume)
            $resumeFrom = 0;
            if (File::exists($outputPath)) {
                $existingSize = File::size($outputPath);
                if ($existingSize < $fileSize) {
                    $resumeFrom = $existingSize;
                    $this->warn("⚠️  File output sudah ada. Resume dari byte ke-{$resumeFrom}");
                } else {
                    $this->warn("⚠️  File output sudah lengkap. Hapus file lama? (y/n)");
                    if ($this->confirm('Hapus file lama?')) {
                        File::delete($outputPath);
                        $resumeFrom = 0;
                    } else {
                        $this->info("✅ Download dibatalkan.");
                        return Command::SUCCESS;
                    }
                }
            }

            // Buat atau buka file output
            $outputHandle = fopen($outputPath, $resumeFrom > 0 ? 'ab' : 'wb');
            if (!$outputHandle) {
                $this->error("❌ Gagal membuka file output: {$outputPath}");
                return Command::FAILURE;
            }

            // Buka file backup
            $backupHandle = fopen($backupPath, 'rb');
            if (!$backupHandle) {
                $this->error("❌ Gagal membuka file backup: {$backupPath}");
                fclose($outputHandle);
                return Command::FAILURE;
            }

            // Set pointer ke posisi resume
            if ($resumeFrom > 0) {
                fseek($backupHandle, $resumeFrom);
            }

            // Progress bar
            $progressBar = $this->output->createProgressBar($fileSize);
            $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%');
            $progressBar->start();

            // Set current progress
            $progressBar->setProgress($resumeFrom);

            $startTime = microtime(true);
            $downloadedBytes = $resumeFrom;
            $lastSpeedUpdate = $startTime;
            $speedBytes = 0;

            // Stream download
            while (!feof($backupHandle)) {
                $data = fread($backupHandle, $chunkSize);

                if ($data === false) {
                    break;
                }

                $dataLength = strlen($data);
                fwrite($outputHandle, $data);

                $downloadedBytes += $dataLength;
                $speedBytes += $dataLength;

                // Update progress bar
                $progressBar->setProgress($downloadedBytes);

                // Update speed setiap detik
                $currentTime = microtime(true);
                if ($currentTime - $lastSpeedUpdate >= 1.0) {
                    $speed = $speedBytes / ($currentTime - $lastSpeedUpdate);
                    $progressBar->setMessage("Speed: " . $this->formatFileSize($speed) . "/s");

                    $speedBytes = 0;
                    $lastSpeedUpdate = $currentTime;
                }

                // Sleep sebentar untuk mengurangi beban
                usleep(1000); // 1ms
            }

            $progressBar->finish();
            $this->newLine();

            // Cleanup
            fclose($backupHandle);
            fclose($outputHandle);

            // Verifikasi file
            $finalSize = File::size($outputPath);
            if ($finalSize === $fileSize) {
                $elapsedTime = microtime(true) - $startTime;
                $averageSpeed = $fileSize / $elapsedTime;

                $this->info('✅ Download berhasil!');
                $this->info("📊 Ukuran final: " . $this->formatFileSize($finalSize));
                $this->info("⏱️  Waktu: " . round($elapsedTime, 2) . " detik");
                $this->info("🚀 Rata-rata speed: " . $this->formatFileSize($averageSpeed) . "/s");
                $this->info("📍 Lokasi: {$outputPath}");

                return Command::SUCCESS;
            } else {
                $this->error("❌ Verifikasi gagal. Expected: {$fileSize}, Got: {$finalSize}");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error("❌ Terjadi kesalahan: " . $e->getMessage());
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
