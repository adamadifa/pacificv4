<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Karyawan;
use App\Models\Presensi;
use App\Models\MesinFingerprint;
use App\Models\LogMesinPresensi;
use Carbon\Carbon;

class AdmsController extends Controller
{
    /**
     * Method to handle JSON data from machine (e.g. Fingerspot)
     */
    public function capture(Request $request)
    {
        // 1. Identify Serial Number (Support Multi-Format)
        $devId = $request->header('dev-id') ??
            $request->header('dev_id') ??
            $request->header('X-Dev-Id') ??
            $_SERVER['HTTP_DEV_ID'] ??
            $_SERVER['DEV_ID'] ??
            $request->query('sn') ??
            '';

        $rawBody = $request->getContent();

        // 2. Parse JSON from body
        $jsonStart = strpos($rawBody, '{');
        $jsonEnd = strrpos($rawBody, '}');
        $jsonData = [];
        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonString = substr($rawBody, $jsonStart, $jsonEnd - $jsonStart + 1);
            $jsonData = json_decode($jsonString, true) ?? [];
        }

        // 3. Find Machine
        $mesin = MesinFingerprint::where('sn', $devId)->where('status', 'Aktif')->first();
        if (!$mesin) {
            Log::warning('Unregistered or inactive machine tried to send data', [
                'sn' => $devId,
                'ip' => $request->ip()
            ]);

            return response("OK", 200)
                ->header('Content-Type', 'application/octet-stream; charset=utf-8')
                ->header('response_code', 'OK')
                ->header('Connection', 'close');
        }

        // 4. Handle Heartbeat
        if (empty($jsonData)) {
            return response("OK", 200)
                ->header('Content-Type', 'application/octet-stream; charset=utf-8')
                ->header('response_code', 'OK')
                ->header('Connection', 'close');
        }

        // 5. Process Attendance Data
        if (isset($jsonData['user_id']) && isset($jsonData['io_time'])) {
            // Format: 20260326011015 -> 2026-03-26 01:10:15
            $io_time_str = $jsonData['io_time'];
            $scan = (strlen($io_time_str) == 14)
                ? substr($io_time_str, 0, 4) . '-' . substr($io_time_str, 4, 2) . '-' . substr($io_time_str, 6, 2) . ' ' . substr($io_time_str, 8, 2) . ':' . substr($io_time_str, 10, 2) . ':' . substr($io_time_str, 12, 2)
                : date('Y-m-d H:i:s');

            $io_mode = $jsonData['io_mode'] ?? 0;
            $status_scan = ($io_mode >= 16777216) ? ($io_mode / 16777216) - 1 : ($jsonData['status_scan'] ?? 0);

            $this->processAttendance($jsonData['user_id'], $scan, $status_scan, $mesin);
        }

        return response("OK", 200)
            ->header('Content-Type', 'application/octet-stream; charset=utf-8')
            ->header('response_code', 'OK')
            ->header('Connection', 'close');
    }

    /**
     * Method to handle Plain Text format (e.g. ZKTeco X100C)
     */
    public function receiveX100c(Request $request)
    {
        $devId = $request->query('SN', '');

        if ($request->isMethod('GET')) {
            return response("OK\n", 200)->header('Content-Type', 'text/plain');
        }

        $rawBody = $request->getContent();
        $mesin = MesinFingerprint::where('sn', $devId)->where('status', 'Aktif')->first();
        
        if (!$mesin) {
            Log::warning('Unregistered X100C machine tried to send data', ['sn' => $devId, 'ip' => $request->ip()]);
            return response("OK\n", 200)->header('Content-Type', 'text/plain');
        }

        $lines = explode("\n", $rawBody);
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            $parts = explode("\t", $line);
            if (count($parts) >= 3) {
                // Format: PIN \t Time \t Status
                $pin = $parts[0];
                $scan = $parts[1];
                $status_scan = (int)$parts[2];

                $this->processAttendance($pin, $scan, $status_scan, $mesin);
            }
        }

        return response("OK\n", 200)->header('Content-Type', 'text/plain');
    }

    /**
     * Core Presence Logic (Adapted from PresensiController)
     */
    public function processAttendance($pin, $scan, $status_scan, $mesin)
    {
        $tgl_presensi = date("Y-m-d", strtotime($scan));
        $karyawan = DB::table('hrd_karyawan')->where('pin', $pin)->first();

        if ($karyawan == null) {
            $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "PIN Tidak Ditemukan");
            return;
        }

        $nik = $karyawan->nik;
        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', $karyawan->kode_jabatan)->first();

        // Cek Perjalanan Dinas
        $cekperjalanandinas = DB::table('hrd_izindinas')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();
        
        $kode_cabang = ($cekperjalanandinas != null) ? $cekperjalanandinas->kode_cabang : $karyawan->kode_cabang;
        $lastday = date('Y-m-d', strtotime('-1 day', strtotime($tgl_presensi)));

        // Cek Jadwal Shift
        $cekjadwalshift = DB::table('hrd_jadwalshift_detail')
            ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        $cekgantishift = DB::table('hrd_gantishift')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;
        } else if ($cekperjalanandinas != null) {
            $cekjadwaldinas = DB::table('hrd_jadwalkerja')
                ->where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal ?? $karyawan->kode_jadwal;
        } else {
            $kode_jadwal = $karyawan->kode_jadwal;
        }

        // Cek Libur
        $libur = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $tgl_presensi);
        
        $ceklibur = $libur->count();
        $datalibur = $libur->first();
        $tanggal_libur = $datalibur != null ? $datalibur->tanggal_libur : '';

        // Cek Lembur
        $ceklembur = DB::table('hrd_lembur_detail')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $tgl_presensi)->count();

        $hariini = ($ceklibur > 0 && $this->getHari(date('D', strtotime($tanggal_libur))) == "Sabtu") ? "Sabtu" : $this->getHari(date('D', strtotime($tgl_presensi)));

        if ($jabatan && $jabatan->nama_jabatan == "SECURITY" && $hariini == "Sabtu") {
            $hariini = "Senin";
        }

        if ($ceklembur > 0 && $hariini == "Sabtu") {
            $hariini = "Jumat";
        }

        // Logic group Saus
        $id_group = $karyawan->kode_group;
        $group_saus = [29, 26, 27];
        if ($tgl_presensi == '2024-02-10' && in_array((int)$id_group, $group_saus)) {
            $hariini = "Senin";
        }

        $jadwal = DB::table('hrd_jadwalkerja_detail')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $hariini)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)
            ->first();

        if (!$jadwal) {
            $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Jadual Kerja Tidak Ditemukan ($hariini, $kode_jadwal)");
            return;
        }

        $jam_kerja = DB::table('hrd_jamkerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();
        if (!$jam_kerja) {
            $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Jam Kerja Tidak Ditemukan ({$jadwal->kode_jam_kerja})");
            return;
        }

        $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();
        $jam_sekarang = date("H:i:s", strtotime($scan));

        // Logic Presensi MASUK
        if (in_array((int)$status_scan, [0, 2, 4, 6, 8])) {
            $jam_masuk_limit = $tgl_presensi . " " . "10:00";
            if (($kode_jadwal == "JD004" || $kode_jadwal == "JD003") && $scan <= $jam_masuk_limit) {
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Belum Waktunya Absen Masuk");
                return;
            }

            if ($cek != null && !empty($cek->jam_in)) {
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Sudah Melakukan Presensi Masuk");
            } else if ($cek != null && empty($cek->jam_in)) {
                DB::table('hrd_presensi')->where('id', $cek->id)->update([
                    'jam_in' => $scan,
                    'id_mesin' => $mesin->id,
                    'lokasi_in' => $mesin->titik_koordinat ?? 'Fingerprint ADMS',
                ]);
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 1, "Berhasil Update Presensi Masuk");
            } else {
                DB::table('hrd_presensi')->insert([
                    'nik' => $nik,
                    'tanggal' => $tgl_presensi,
                    'jam_in' => $scan,
                    'kode_jadwal' => $kode_jadwal,
                    'kode_jam_kerja' => $jadwal->kode_jam_kerja,
                    'status' => 'h',
                    'id_mesin' => $mesin->id,
                    'lokasi_in' => $mesin->titik_koordinat ?? 'Fingerprint ADMS',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 1, "Berhasil Presensi Masuk Baru");
            }
        } 
        // Logic Presensi PULANG
        else {
            $ceklastpresensi = DB::table('hrd_presensi')
                ->join('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
                ->where('nik', $nik)->where('tanggal', $lastday)->first();

            $last_lintashari = $ceklastpresensi ? $ceklastpresensi->lintashari : "";
            $tgl_pulang_time = date("H:i", strtotime($scan));

            $cekjadwalshiftlast = DB::table('hrd_jadwalshift_detail')
                ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
                ->whereRaw('"' . $lastday . '" >= dari')
                ->whereRaw('"' . $lastday . '" <= sampai')
                ->where('nik', $nik)
                ->first();
            $kode_jadwal_last = $cekjadwalshiftlast ? $cekjadwalshiftlast->kode_jadwal : $kode_jadwal;

            $kode_jam_kerja_final = $jadwal->kode_jam_kerja;
            $tgl_presensi_final = $tgl_presensi;
            $kode_jadwal_final = $kode_jadwal;

            if (!empty($last_lintashari)) {
                if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                    $tgl_presensi_final = $lastday;
                }

                if ($hariini != "Sabtu") {
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                    $jam_pulang_target = $tgl_pulang . " " . date("H:i", strtotime($ceklastpresensi->jam_pulang));
                } else {
                    $jam_pulang_target = $tgl_presensi_final . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }
            } else {
                if ($tgl_pulang_time <= "08:00" && $kode_jadwal_last == "JD004") {
                    $tgl_presensi_final = $lastday;
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                    $jam_pulang_target = $tgl_pulang . " 07:00";
                    $kode_jam_kerja_final = "JK08";
                    $kode_jadwal_final = "JD004";
                } else {
                    if ($kode_jadwal == "JD004") {
                        if ($hariini != "Sabtu") {
                            $tgl_pulang = ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") ? $tgl_presensi_final : date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                        } else {
                            $tgl_pulang = $tgl_presensi_final;
                        }
                    } else {
                        $tgl_pulang = $tgl_presensi_final;
                    }
                    $jam_pulang_target = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }
            }

            $date_jampulang = date("Y-m-d", strtotime($jam_pulang_target));
            $hour_jampulang = (date("H", strtotime($jam_pulang_target)) - 2);
            $h_jampulang = $hour_jampulang < 10 ? "0" . $hour_jampulang : $hour_jampulang;
            $jam_pulang_limit = $date_jampulang . " " . $h_jampulang . ":00";

            if ($scan < $jam_pulang_limit) {
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Belum Waktunya Absen Pulang (Mulai Pukul $jam_pulang_limit)");
                return;
            }

            $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi_final)->where('nik', $nik)->first();
            if ($cek == null) {
                DB::table('hrd_presensi')->insert([
                    'nik' => $nik,
                    'tanggal' => $tgl_presensi_final,
                    'jam_out' => $scan,
                    'kode_jadwal' => $kode_jadwal_final,
                    'kode_jam_kerja' => $kode_jam_kerja_final,
                    'status' => 'h',
                    'id_mesin' => $mesin->id,
                    'lokasi_out' => $mesin->titik_koordinat ?? 'Fingerprint ADMS',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 1, "Berhasil Presensi Pulang Baru");
            } else if ($cek != null && !empty($cek->jam_out)) {
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 0, "Sudah Melakukan Presensi Pulang");
            } else {
                DB::table('hrd_presensi')->where('id', $cek->id)->update([
                    'jam_out' => $scan,
                    'id_mesin' => $mesin->id,
                    'lokasi_out' => $mesin->titik_koordinat ?? 'Fingerprint ADMS',
                ]);
                $this->recordLogMesin($pin, $scan, $status_scan, $mesin->id, 1, "Berhasil Update Presensi Pulang");
            }
        }
    }

    private function getHari($day)
    {
        $days = [
            'Sun' => 'Minggu',
            'Mon' => 'Senin',
            'Tue' => 'Selasa',
            'Wed' => 'Rabu',
            'Thu' => 'Kamis',
            'Fri' => 'Jumat',
            'Sat' => 'Sabtu'
        ];
        return $days[$day] ?? 'Tidak diketahui';
    }

    private function recordLogMesin($pin, $scan, $status_scan, $id_mesin, $status, $keterangan)
    {
        try {
            LogMesinPresensi::create([
                'pin' => $pin,
                'status_scan' => $status_scan,
                'jam_absen' => $scan,
                'id_mesin' => $id_mesin,
                'status' => $status,
                'keterangan' => $keterangan,
            ]);
        } catch (\Exception $ex) {
            Log::error('Failed to record machine presence log: ' . $ex->getMessage());
        }
    }
}
