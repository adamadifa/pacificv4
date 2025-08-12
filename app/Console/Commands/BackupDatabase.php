<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--filename=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai backup database...');

        try {
            // Generate nama file backup
            $filename = $this->option('filename') ?: 'backup_' . date('Y-m-d_H-i-s') . '.sql';

            // Path untuk menyimpan backup
            $backupPath = storage_path('app/backups');

            // Buat direktori jika belum ada
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
                $this->info('Direktori backup berhasil dibuat.');
            }

            // Command untuk backup database
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                config('database.connections.mysql.username'),
                config('database.connections.mysql.password'),
                config('database.connections.mysql.host'),
                config('database.connections.mysql.database'),
                $backupPath . '/' . $filename
            );

            // Eksekusi command
            exec($command, $output, $returnCode);

            if ($returnCode === 0) {
                $fileSize = File::size($backupPath . '/' . $filename);
                $formattedSize = $this->formatFileSize($fileSize);

                $this->info('Backup database berhasil dibuat!');
                $this->info('File: ' . $filename);
                $this->info('Ukuran: ' . $formattedSize);
                $this->info('Lokasi: ' . $backupPath . '/' . $filename);

                return Command::SUCCESS;
            } else {
                $this->error('Gagal membuat backup database. Silakan cek konfigurasi database.');
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

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
