<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BackupDatabaseController extends Controller
{


    public function index()
    {
        $backupFiles = $this->getBackupFiles();

        return view('utilities.backup-database.index', compact('backupFiles'));
    }

    public function create()
    {
        try {
            // Generate nama file backup
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';

            // Path untuk menyimpan backup
            $backupPath = storage_path('app/backups');

            // Buat direktori jika belum ada
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // Cek apakah mysqldump tersedia
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                // Gunakan mysqldump jika tersedia
                $result = $this->backupWithMysqldump($mysqldumpPath, $backupPath, $filename);
            } else {
                // Gunakan alternatif Laravel DB jika mysqldump tidak tersedia
                $result = $this->backupWithLaravel($backupPath, $filename);
            }

            if ($result['success']) {
                return redirect()->route('backup.database.index')
                    ->with('success', 'Backup database berhasil dibuat: ' . $filename);
            } else {
                return redirect()->route('backup.database.index')
                    ->with('error', 'Gagal membuat backup database. Error: ' . $result['error']);
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.database.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return redirect()->route('backup.database.index')
                ->with('error', 'File backup tidak ditemukan.');
        }

        // Cek ukuran file untuk menentukan metode download
        $fileSize = File::size($filePath);
        $largeFileThreshold = 100 * 1024 * 1024; // 100MB

        if ($fileSize > $largeFileThreshold) {
            // Gunakan streaming download untuk file besar
            return $this->streamDownload($filePath, $filename);
        } else {
            // Gunakan download biasa untuk file kecil
            return response()->download($filePath);
        }
    }

    /**
     * Streaming download untuk file besar
     */
    private function streamDownload($filePath, $filename)
    {
        // Set headers untuk streaming
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => File::size($filePath),
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        // Stream file dalam chunks
        return response()->stream(
            function () use ($filePath) {
                $handle = fopen($filePath, 'rb');
                $chunkSize = 1024 * 1024; // 1MB chunks

                while (!feof($handle)) {
                    echo fread($handle, $chunkSize);

                    // Flush output buffer
                    if (ob_get_level()) {
                        ob_flush();
                    }
                    flush();

                    // Sleep sebentar untuk mengurangi beban server
                    usleep(10000); // 10ms
                }

                fclose($handle);
            },
            200,
            $headers
        );
    }

    /**
     * Streaming download langsung dari database tanpa simpan file
     */
    public function streamDownloadFromDatabase()
    {
        try {
            // Set unlimited time dan memory
            set_time_limit(0);
            ini_set('memory_limit', '2G');

            // Generate nama file backup
            $filename = 'backup_direct_' . date('Y-m-d_H-i-s') . '.sql';

            // Set headers untuk streaming
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            // Stream langsung dari database
            return response()->stream(
                function () {
                    $dbConfig = config('database.connections.mysql');

                    // Header file
                    echo "-- Direct Database Backup\n";
                    echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
                    echo "-- Streaming from Database\n\n";

                    // Dapatkan semua tabel
                    $tables = DB::select('SHOW TABLES');
                    $totalTables = count($tables);
                    $currentTable = 0;

                    foreach ($tables as $table) {
                        $currentTable++;
                        $tableName = array_values((array) $table)[0];

                        // Structure table
                        $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                        $createStatement = array_values((array) $createTable[0])[1];

                        echo "-- Table structure for table `{$tableName}`\n";
                        echo "DROP TABLE IF EXISTS `{$tableName}`;\n";
                        echo $createStatement . ";\n\n";

                        // Data table dengan chunking
                        $totalRows = DB::table($tableName)->count();
                        $chunkSize = 1000; // Process 1000 rows at a time

                        if ($totalRows > 0) {
                            echo "-- Data for table `{$tableName}` ({$totalRows} rows)\n";

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

                                    echo "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                                }

                                // Flush output buffer setiap chunk
                                if (ob_get_level()) {
                                    ob_flush();
                                }
                                flush();

                                // Reset memory dan garbage collection
                                if (function_exists('gc_collect_cycles')) {
                                    gc_collect_cycles();
                                }

                                // Sleep sebentar untuk mengurangi beban server
                                usleep(10000); // 10ms
                            }
                            echo "\n";
                        }

                        // Flush buffer setiap tabel
                        if (ob_get_level()) {
                            ob_flush();
                        }
                        flush();
                    }
                },
                200,
                $headers
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Streaming download dengan progress tracking (untuk database besar)
     */
    public function streamDownloadWithProgress()
    {
        try {
            // Set unlimited time dan memory
            set_time_limit(0);
            ini_set('memory_limit', '2G');

            // Generate nama file backup
            $filename = 'backup_progress_' . date('Y-m-d_H-i-s') . '.sql';

            // Set headers untuk streaming
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            // Stream dengan progress tracking
            return response()->stream(
                function () {
                    $dbConfig = config('database.connections.mysql');

                    // Header file
                    echo "-- Database Backup with Progress\n";
                    echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
                    echo "-- Streaming with Progress Tracking\n\n";

                    // Dapatkan semua tabel
                    $tables = DB::select('SHOW TABLES');
                    $totalTables = count($tables);
                    $currentTable = 0;

                    foreach ($tables as $table) {
                        $currentTable++;
                        $tableName = array_values((array) $table)[0];

                        // Progress comment
                        echo "-- Progress: Table {$currentTable}/{$totalTables} - {$tableName}\n";

                        // Structure table
                        $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                        $createStatement = array_values((array) $createTable[0])[1];

                        echo "-- Table structure for table `{$tableName}`\n";
                        echo "DROP TABLE IF EXISTS `{$tableName}`;\n";
                        echo $createStatement . ";\n\n";

                        // Data table dengan chunking yang lebih kecil
                        $totalRows = DB::table($tableName)->count();
                        $chunkSize = 500; // Process 500 rows at a time

                        if ($totalRows > 0) {
                            echo "-- Data for table `{$tableName}` ({$totalRows} rows)\n";

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

                                    echo "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                                }

                                // Progress comment setiap 1000 rows
                                if ($offset % 1000 === 0) {
                                    echo "-- Progress: {$tableName} - {$offset}/{$totalRows} rows processed\n";
                                }

                                // Flush output buffer setiap chunk
                                if (ob_get_level()) {
                                    ob_flush();
                                }
                                flush();

                                // Reset memory dan garbage collection
                                if (function_exists('gc_collect_cycles')) {
                                    gc_collect_cycles();
                                }

                                // Sleep sebentar untuk mengurangi beban server
                                usleep(15000); // 15ms
                            }
                            echo "\n";
                        }

                        // Flush buffer setiap tabel
                        if (ob_get_level()) {
                            ob_flush();
                        }
                        flush();
                    }

                    // Completion comment
                    echo "-- Backup completed successfully!\n";
                    echo "-- Total tables processed: {$totalTables}\n";
                    echo "-- Generated: " . date('Y-m-d H:i:s') . "\n";
                },
                200,
                $headers
            );
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Resume download untuk file yang terputus
     */
    public function resumeDownload($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (!File::exists($filePath)) {
            return redirect()->route('backup.database.index')
                ->with('error', 'File backup tidak ditemukan.');
        }

        // Cek range header untuk resume download
        $range = request()->header('Range');
        $fileSize = File::size($filePath);

        if ($range) {
            // Parse range header
            $ranges = $this->parseRangeHeader($range, $fileSize);

            if (empty($ranges)) {
                return response('Requested Range Not Satisfiable', 416);
            }

            $range = $ranges[0];
            $start = $range['start'];
            $end = $range['end'];
            $length = $end - $start + 1;

            // Set headers untuk partial content
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Content-Length' => $length,
                'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
                'Accept-Ranges' => 'bytes',
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0',
            ];

            // Stream partial content
            return response()->stream(
                function () use ($filePath, $start, $end) {
                    $handle = fopen($filePath, 'rb');
                    fseek($handle, $start);

                    $chunkSize = 1024 * 1024; // 1MB chunks
                    $remaining = $end - $start + 1;

                    while ($remaining > 0 && !feof($handle)) {
                        $readSize = min($chunkSize, $remaining);
                        $data = fread($handle, $readSize);

                        if ($data === false) {
                            break;
                        }

                        echo $data;
                        $remaining -= strlen($data);

                        // Flush output buffer
                        if (ob_get_level()) {
                            ob_flush();
                        }
                        flush();

                        // Sleep sebentar
                        usleep(10000); // 10ms
                    }

                    fclose($handle);
                },
                206, // Partial Content
                $headers
            );
        } else {
            // Full download dengan streaming
            return $this->streamDownload($filePath, $filename);
        }
    }

    /**
     * Parse Range header
     */
    private function parseRangeHeader($range, $fileSize)
    {
        if (!preg_match('/^bytes=(\d+)-(\d*)$/', $range, $matches)) {
            return [];
        }

        $start = (int) $matches[1];
        $end = $matches[2] ? (int) $matches[2] : $fileSize - 1;

        // Validasi range
        if ($start >= $fileSize || $end >= $fileSize || $start > $end) {
            return [];
        }

        return [['start' => $start, 'end' => $end]];
    }

    public function destroy($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);

        if (File::exists($filePath)) {
            File::delete($filePath);

            return redirect()->route('backup.database.index')
                ->with('success', 'File backup berhasil dihapus.');
        }

        return redirect()->route('backup.database.index')
            ->with('error', 'File backup tidak ditemukan.');
    }

    private function getBackupFiles()
    {
        $backupPath = storage_path('app/backups');

        if (!File::exists($backupPath)) {
            return [];
        }

        $files = File::files($backupPath);
        $backupFiles = [];

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $backupFiles[] = [
                    'name' => pathinfo($file, PATHINFO_BASENAME),
                    'size' => $this->formatFileSize($file->getSize()),
                    'created_at' => Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i:s'),
                    'size_bytes' => $file->getSize()
                ];
            }
        }

        // Urutkan berdasarkan waktu pembuatan (terbaru dulu)
        usort($backupFiles, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backupFiles;
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

    /**
     * Cari path mysqldump yang tersedia di sistem
     */
    private function findMysqldump()
    {
        // Cek beberapa kemungkinan path mysqldump
        $possiblePaths = [
            'mysqldump', // Jika ada di PATH
            '/usr/bin/mysqldump', // Linux
            '/usr/local/bin/mysqldump', // macOS
            'C:\Program Files\MySQL\MySQL Server 8.0\bin\mysqldump.exe', // Windows
            'C:\Program Files\MySQL\MySQL Server 5.7\bin\mysqldump.exe', // Windows
            'C:\xampp\mysql\bin\mysqldump.exe', // XAMPP
            'C:\wamp64\bin\mysql\mysql8.0.31\bin\mysqldump.exe', // WAMP
            'C:\wamp\bin\mysql\mysql5.7.36\bin\mysqldump.exe', // WAMP
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
            // Windows
            return file_exists($path);
        } else {
            // Linux/macOS
            return file_exists($path) && is_executable($path);
        }
    }

    /**
     * Backup menggunakan mysqldump
     */
    private function backupWithMysqldump($mysqldumpPath, $backupPath, $filename)
    {
        try {
            // Ambil konfigurasi database dari config
            $dbConfig = config('database.connections.mysql');

            // Command untuk backup database
            $command = sprintf(
                '"%s" --user=%s --password=%s --host=%s --port=%s %s > "%s"',
                $mysqldumpPath,
                escapeshellarg($dbConfig['username']),
                escapeshellarg($dbConfig['password']),
                escapeshellarg($dbConfig['host']),
                escapeshellarg($dbConfig['port'] ?? 3306),
                escapeshellarg($dbConfig['database']),
                $backupPath . '/' . $filename
            );

            // Eksekusi command
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
     * Backup menggunakan Laravel DB (alternatif jika mysqldump tidak tersedia)
     */
    private function backupWithLaravel($backupPath, $filename)
    {
        try {
            $dbConfig = config('database.connections.mysql');
            $backupFile = $backupPath . '/' . $filename;

            // Buat file backup
            $file = fopen($backupFile, 'w');

            // Header file
            fwrite($file, "-- Backup Database: " . $dbConfig['database'] . "\n");
            fwrite($file, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($file, "-- Laravel Backup Alternative\n\n");

            // Dapatkan semua tabel
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];

                // Structure table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createStatement = array_values((array) $createTable[0])[1];

                fwrite($file, "-- Table structure for table `{$tableName}`\n");
                fwrite($file, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($file, $createStatement . ";\n\n");

                // Data table dengan chunking untuk database besar
                $totalRows = DB::table($tableName)->count();
                $chunkSize = 1000; // Process 1000 rows at a time

                if ($totalRows > 0) {
                    fwrite($file, "-- Data for table `{$tableName}`\n");

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

                        // Flush buffer untuk memastikan data tersimpan
                        fflush($file);

                        // Reset memory limit
                        if (function_exists('gc_collect_cycles')) {
                            gc_collect_cycles();
                        }
                    }
                    fwrite($file, "\n");
                }
            }

            fclose($file);

            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Backup database besar dengan progress tracking
     */
    public function createLargeBackup()
    {
        try {
            // Generate nama file backup
            $filename = 'backup_large_' . date('Y-m-d_H-i-s') . '.sql';

            // Path untuk menyimpan backup
            $backupPath = storage_path('app/backups');

            // Buat direktori jika belum ada
            if (!File::exists($backupPath)) {
                File::makeDirectory($backupPath, 0755, true);
            }

            // Cek apakah mysqldump tersedia
            $mysqldumpPath = $this->findMysqldump();

            if ($mysqldumpPath) {
                // Gunakan mysqldump dengan optimasi untuk database besar
                $result = $this->backupLargeWithMysqldump($mysqldumpPath, $backupPath, $filename);
            } else {
                // Gunakan Laravel dengan optimasi
                $result = $this->backupLargeWithLaravel($backupPath, $filename);
            }

            if ($result['success']) {
                return redirect()->route('backup.database.index')
                    ->with('success', 'Backup database besar berhasil dibuat: ' . $filename);
            } else {
                return redirect()->route('backup.database.index')
                    ->with('error', 'Gagal membuat backup database besar. Error: ' . $result['error']);
            }
        } catch (\Exception $e) {
            return redirect()->route('backup.database.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Backup database besar menggunakan mysqldump dengan optimasi
     */
    private function backupLargeWithMysqldump($mysqldumpPath, $backupPath, $filename)
    {
        try {
            $dbConfig = config('database.connections.mysql');

            // Command untuk backup database besar dengan optimasi
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

            // Eksekusi command dengan timeout yang lebih lama
            set_time_limit(0); // No timeout
            ini_set('memory_limit', '2G'); // Increase memory limit

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
     * Backup database besar menggunakan Laravel dengan optimasi
     */
    private function backupLargeWithLaravel($backupPath, $filename)
    {
        try {
            $dbConfig = config('database.connections.mysql');
            $backupFile = $backupPath . '/' . $filename;

            // Set timeout dan memory limit
            set_time_limit(0);
            ini_set('memory_limit', '2G');

            // Buat file backup
            $file = fopen($backupFile, 'w');

            // Header file
            fwrite($file, "-- Backup Database: " . $dbConfig['database'] . "\n");
            fwrite($file, "-- Generated: " . date('Y-m-d H:i:s') . "\n");
            fwrite($file, "-- Laravel Large Database Backup\n\n");

            // Dapatkan semua tabel
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];

                // Structure table
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $createStatement = array_values((array) $createTable[0])[1];

                fwrite($file, "-- Table structure for table `{$tableName}`\n");
                fwrite($file, "DROP TABLE IF EXISTS `{$tableName}`;\n");
                fwrite($file, $createStatement . ";\n\n");

                // Data table dengan chunking yang lebih kecil untuk database besar
                $totalRows = DB::table($tableName)->count();
                $chunkSize = 500; // Process 500 rows at a time untuk database besar

                if ($totalRows > 0) {
                    fwrite($file, "-- Data for table `{$tableName}`\n");

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
            }

            fclose($file);

            return ['success' => true, 'error' => null];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
