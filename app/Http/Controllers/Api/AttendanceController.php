<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AttendanceController extends Controller
{
    public function getHistory(Request $request)
    {
        $user = $request->user();
        $nik = $user->nik;

        $query = DB::table('hrd_presensi')
            ->select(
                'hrd_presensi.id',
                'hrd_presensi.tanggal',
                'hrd_presensi.jam_in',
                'hrd_presensi.jam_out',
                'hrd_presensi.status',
                'hrd_presensi.kode_jam_kerja',
                'hrd_jamkerja.jam_masuk as jam_mulai',
                'hrd_jamkerja.jam_pulang as jam_selesai',
                'hrd_jamkerja.istirahat',
                'hrd_jamkerja.jam_awal_istirahat',
                'hrd_jamkerja.jam_akhir_istirahat',
                'hrd_jamkerja.lintashari',
                'hrd_presensi_izinterlambat.kode_izin_terlambat',
                'hrd_jeniscuti.nama_cuti'
            )
            ->leftJoin('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
            ->leftJoin('hrd_presensi_izinterlambat', 'hrd_presensi.id', '=', 'hrd_presensi_izinterlambat.id_presensi')
            ->leftJoin('hrd_presensi_izincuti', 'hrd_presensi.id', '=', 'hrd_presensi_izincuti.id_presensi')
            ->leftJoin('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti')
            ->leftJoin('hrd_jeniscuti', 'hrd_izincuti.kode_cuti', '=', 'hrd_jeniscuti.kode_cuti')
            ->where('hrd_presensi.nik', $nik)
            ->orderBy('hrd_presensi.tanggal', 'desc');

        if ($request->has('bulan') && $request->has('tahun')) {
            $query->whereMonth('hrd_presensi.tanggal', $request->bulan)
                  ->whereYear('hrd_presensi.tanggal', $request->tahun);
        } else {
            $query->limit(30);
        }

        $presensi = $query->get();

        $history = $presensi->map(function ($d) use ($user) {
            $tanggal_presensi = $d->tanggal;
            $lintashari = $d->lintashari;
            $tanggal_selesai = $lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi))) : $tanggal_presensi;

            // Jam Jadwal
            $j_mulai = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d->jam_mulai));
            $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

            // Jam Absen
            $jam_in = !empty($d->jam_in) ? date('Y-m-d H:i', strtotime($d->jam_in)) : null;
            $jam_out = !empty($d->jam_out) ? date('Y-m-d H:i', strtotime($d->jam_out)) : null;

            // Istirahat
            if ($d->istirahat == '1') {
                if ($lintashari == '0') {
                    $jam_awal_istirahat = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d->jam_awal_istirahat));
                    $jam_akhir_istirahat = date('Y-m-d H:i', strtotime($tanggal_presensi . ' ' . $d->jam_akhir_istirahat));
                } else {
                    $jam_awal_istirahat = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_awal_istirahat));
                    $jam_akhir_istirahat = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_akhir_istirahat));
                }
            } else {
                $jam_awal_istirahat = null;
                $jam_akhir_istirahat = null;
            }

            // Hitung Terlambat
            $terlambat = presensiHitungJamTerlambat($jam_in, $j_mulai);
            
            // Hitung Denda
            $denda = presensiHitungDenda(
                $terlambat['jamterlambat'],
                $terlambat['menitterlambat'],
                $d->kode_izin_terlambat,
                $user->kode_dept,
                $user->kode_jabatan
            );

            // Hitung Pulang Cepat
            $pulangcepat = presensiHitungPulangCepat(
                $jam_out,
                $j_selesai,
                $jam_awal_istirahat,
                $jam_akhir_istirahat
            );

            // Determine Display Status and Keterangan
            if ($d->status == 'h') {
                $display_status = $terlambat['status'] ? 'Terlambat' : 'Tepat Waktu';
                $display_keterangan = $terlambat['keterangan'];
            } elseif ($d->status == 's') {
                $display_status = 'Sakit';
                $display_keterangan = 'Sakit';
            } elseif ($d->status == 'i') {
                $display_status = 'Izin';
                $display_keterangan = 'Izin';
            } elseif ($d->status == 'c') {
                $display_status = 'Cuti';
                $display_keterangan = 'Cuti ' . ($d->nama_cuti ? '(' . $d->nama_cuti . ')' : '');
            } else {
                $display_status = 'Alpa';
                $display_keterangan = 'Alpa';
            }

            return [
                'tanggal' => $d->tanggal,
                'hari' => getNamahari($d->tanggal),
                'jam_in' => $d->jam_in ? date('H:i', strtotime($d->jam_in)) : null,
                'jam_out' => $d->jam_out ? date('H:i', strtotime($d->jam_out)) : null,
                'status' => $d->status,
                'terlambat_min' => ($terlambat['jamterlambat'] * 60) + $terlambat['menitterlambat'],
                'pulang_cepat_min' => $pulangcepat['status'] ? round($pulangcepat['desimal'] * 60) : 0,
                'denda' => $denda['denda'],
                'keterangan' => $display_keterangan
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $history
        ]);
    }

    public function getSummary(Request $request)
    {
        $user = $request->user();
        $nik = $user->nik;
        $month = date('m');
        $year = date('Y');

        // Total Hadir bulan ini
        $hadir = DB::table('hrd_presensi')
            ->where('nik', $nik)
            ->where('status', 'h')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();

        // Total Izin & Sakit bulan ini
        $izin = DB::table('hrd_presensi')
            ->where('nik', $nik)
            ->whereIn('status', ['i', 's'])
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->count();

        // Sisa Cuti (12 - used C01 this year)
        $used_cuti = DB::table('hrd_presensi_izincuti')
            ->join('hrd_presensi', 'hrd_presensi_izincuti.id_presensi', '=', 'hrd_presensi.id')
            ->join('hrd_izincuti', 'hrd_presensi_izincuti.kode_izin_cuti', '=', 'hrd_izincuti.kode_izin_cuti')
            ->where('hrd_presensi.nik', $nik)
            ->where('hrd_izincuti.kode_cuti', 'C01')
            ->whereYear('hrd_presensi.tanggal', $year)
            ->count();

        $kuota_cuti = 12; // Standard quota
        $sisa_cuti = $kuota_cuti - $used_cuti;

        return response()->json([
            'success' => true,
            'data' => [
                'hadir' => $hadir,
                'izin' => $izin,
                'sisa_cuti' => $sisa_cuti
            ]
        ]);
    }

    public function getAttendanceToday(Request $request)
    {
        $hariini_tgl = date("Y-m-d");
        $nik = $request->user()->nik;

        //Cek Apakah Sedang Perjalanan Dinas Ke Cabang lain
        $cekperjalanandinas = DB::table('hrd_izindinas')
            ->whereRaw('"' . $hariini_tgl . '" >= dari')
            ->whereRaw('"' . $hariini_tgl . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
        } else {
            $kode_cabang = $request->user()->kode_cabang;
        }

        //Cek Lokasi Cabang
        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();

        // Cek Apakah Sudah Absen
        $cek = DB::table('hrd_presensi')->where('tanggal', $hariini_tgl)->where('nik', $nik)->first();
        if ($cek) {
            $cek->foto_in = $cek->foto_in ? asset('storage/uploads/absensi/' . $cek->foto_in) : null;
            $cek->foto_out = $cek->foto_out ? asset('storage/uploads/absensi/' . $cek->foto_out) : null;
        }

        //Cek Apakah Memiliki Jadwal Shift
        $cekjadwalshift = DB::table('hrd_jadwalshift_detail')
            ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
            ->whereRaw('"' . $hariini_tgl . '" >= dari')
            ->whereRaw('"' . $hariini_tgl . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        //Cek Apakah Ada Pergantian Shift
        $cekgantishift = DB::table('hrd_gantishift')->where('tanggal', $hariini_tgl)->where('nik', $nik)->first();

        //Jika Ada Pergantian Shift
        if ($cekgantishift != null) {
            $kode_jadwal = $cekgantishift->kode_jadwal;
            //Jika Memiliki Jadwal Shift
        } else if ($cekjadwalshift != null) {
            $kode_jadwal = $cekjadwalshift->kode_jadwal;

            //Jika Sedang Perjalanan Dinas
        } else if ($cekperjalanandinas != null) {
            //Sesuaikan dengan Jadwal Cabang Tujuan
            $cekjadwaldinas = DB::table('hrd_jadwalkerja')
                ->where('nama_jadwal', 'NON SHIFT')
                ->where('kode_cabang', $cekperjalanandinas->kode_cabang)->first();
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {
            //Gunakan Jadwal Default
            $kode_jadwal = $request->user()->kode_jadwal;
        }

        //Tanggal 5 Jam Ketika Besok Libur
        $libur = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $hariini_tgl);

        $ceklibur = $libur->count();
        $datalibur = $libur->first();
        $tanggal_libur = $datalibur != null ? $datalibur->tanggal : '';

        //Cek Libur Hari ini
        $cekliburhariini = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini_tgl)
            ->where('kategori', 1)
            ->first();

        // Cek Wfh Hari Ini
        $cekwfhhariini = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini_tgl)
            ->where('kategori', 3)
            ->first();

        //Cek Libur Pengganti Hari Minggu
        $cekliburpenggantiminggu = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal', $hariini_tgl)
            ->where('kategori', 2)
            ->first();

        //Cek Hari Minggu Masuk
        if (getNamahari($hariini_tgl) == "Minggu") {
            $cekminggumasuk = DB::table('hrd_harilibur_detail')
                ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
                ->where('nik', $nik)
                ->where('kode_cabang', $kode_cabang)
                ->where('tanggal_diganti', $hariini_tgl)
                ->where('kategori', 2)
                ->first();
        } else {
            $cekminggumasuk = null;
        }

        //Cek Lembur
        $ceklembur = DB::table('hrd_lembur_detail')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $hariini_tgl)->count();

        if ($ceklibur > 0) {
            $namahari = "Sabtu";
        } elseif ($cekminggumasuk != null) {
            $namahari = getNamahari($cekminggumasuk->tanggal);
        } else {
            $namahari = getNamahari($hariini_tgl);
        }

        if ($namahari == "Sabtu" && $ceklembur > 0) {
            $namahari = "Jumat";
        }

        $kode_jabatan = $request->user()->kode_jabatan;
        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', $kode_jabatan)->first();

        $nik_normal = ["17.07.302", "10.01.114", "17.07.280", "12.02.061", "12.11.094", "23.02.214", "11.11.146", "16.03.089", "97.01.026", "18.01.256", "20.04.110", "23.11.277", "24.01.035", "08.07.092"];

        if ($hariini_tgl == '2024-10-25' && $kode_cabang == 'PST' && !in_array($nik, $nik_normal)) {
            $namahari = "Sabtu";
        }

        if ($jabatan->nama_jabatan == "SECURITY" && $namahari == "Sabtu") {
            $namahari = "Senin";
        }

        $jadwal = DB::table('hrd_jadwalkerja_detail')
            ->select('hrd_jadwalkerja_detail.*', 'hrd_jadwalkerja.nama_jadwal')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $namahari)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)->first();

        if ($jadwal == null && empty($cekminggumasuk)) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }

        $jam_kerja = DB::table('hrd_jamkerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'cek' => $cek,
                'lok_kantor' => $lok_kantor,
                'jam_kerja' => $jam_kerja,
                'jadwal' => $jadwal,
                'status_libur' => $cekliburhariini != null,
                'status_wfh' => $cekwfhhariini != null,
                'status_libur_pengganti' => $cekliburpenggantiminggu != null,
                'status_perjalanan_dinas' => $cekperjalanandinas != null,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $nik = $user->nik;
        $lock_location = $user->lock_location;
        $tgl_presensi = date("Y-m-d");

        $cekperjalanandinas = DB::table('hrd_izindinas')
            ->whereRaw('"' . $tgl_presensi . '" >= dari')
            ->whereRaw('"' . $tgl_presensi . '" <= sampai')
            ->where('nik', $nik)
            ->first();

        if ($cekperjalanandinas != null) {
            $kode_cabang = $cekperjalanandinas->kode_cabang;
            $lock_location = 1; // 1 to ignore radius check
        } else {
            $kode_cabang = $user->kode_cabang;
        }

        $lastday = date('Y-m-d', strtotime('-1 day', strtotime($tgl_presensi)));
        $jam = date("Y-m-d H:i:s");

        $lok_kantor = DB::table('cabang')->where('kode_cabang', $kode_cabang)->first();
        $lok = explode(",", $lok_kantor->lokasi_cabang);
        $latitudekantor = $lok[0];
        $longitudekantor = $lok[1];
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longitudeuser = $lokasiuser[1];
        $statuspresensi = $request->statuspresensi;

        $ket = ($statuspresensi == "masuk") ? "in" : "out";

        if (isset($request->image)) {
            $image = $request->image;
            $folderPath = "public/uploads/absensi/";
            $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
            $image_parts = explode(";base64", $image);
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = $formatName . ".jpg";
            $file = $folderPath . $fileName;

            // Image Compression
            $img = Image::make($image_base64);
            $img->resize(640, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $image_compressed = $img->encode('jpg', 80);
        } else {
            $fileName = null;
        }

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
            $kode_jadwal = $cekjadwaldinas->kode_jadwal;
        } else {
            $kode_jadwal = $user->kode_jadwal;
        }

        $libur = DB::table('hrd_harilibur_detail')
            ->leftJoin('hrd_harilibur', 'hrd_harilibur_detail.kode_libur', '=', 'hrd_harilibur.kode_libur')
            ->where('nik', $nik)
            ->where('kode_cabang', $kode_cabang)
            ->where('tanggal_limajam', $tgl_presensi);

        $ceklibur = $libur->count();
        $ceklembur = DB::table('hrd_lembur_detail')
            ->join('hrd_lembur', 'hrd_lembur_detail.kode_lembur', '=', 'hrd_lembur.kode_lembur')
            ->where('nik', $nik)
            ->where('tanggal', $tgl_presensi)->count();

        $namahari = ($ceklibur > 0) ? "Sabtu" : getNamahari($tgl_presensi);
        if ($ceklembur > 0 && $namahari == "Sabtu") {
            $namahari = "Jumat";
        }

        $kode_jabatan = $user->kode_jabatan;
        $jabatan = DB::table('hrd_jabatan')->where('kode_jabatan', $kode_jabatan)->first();

        $nik_normal = ["17.07.302", "10.01.114", "17.07.280", "12.02.061", "12.11.094", "23.02.214", "11.11.146", "16.03.089", "97.01.026", "18.01.256", "20.04.110", "23.11.277", "24.01.035", "08.07.092"];

        if (date('Y-m-d') == '2024-10-25' && $kode_cabang == 'PST' && !in_array($nik, $nik_normal)) {
            $namahari = "Sabtu";
        }

        if ($jabatan->nama_jabatan == "SECURITY" && $namahari == "Sabtu") {
            $namahari = "Senin";
        }

        $jadwal = DB::table('hrd_jadwalkerja_detail')
            ->join('hrd_jadwalkerja', 'hrd_jadwalkerja_detail.kode_jadwal', '=', 'hrd_jadwalkerja.kode_jadwal')
            ->where('hari', $namahari)->where('hrd_jadwalkerja_detail.kode_jadwal', $kode_jadwal)
            ->first();
        $jam_kerja = DB::table('hrd_jamkerja')->where('kode_jam_kerja', $jadwal->kode_jam_kerja)->first();

        $jarak = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak["meters"]);

        $jam_sekarang = date("H:i:s");

        if ($radius > $lok_kantor->radius_cabang && $lock_location == 0) {
            return response()->json([
                'success' => false,
                'message' => "Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " meter dari Kantor",
                'type' => 'radius'
            ], 422);
        }

        $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->first();

        if ($statuspresensi == "masuk") {
            $jam_masuk_limit = $tgl_presensi . " " . "10:00";
            if (($kode_jadwal == "JD004" || $kode_jadwal == "JD003") && $jam <= $jam_masuk_limit) {
                return response()->json(['success' => false, 'message' => 'Maaf Belum Waktunya Absen Masuk'], 422);
            }

            if ($cek != null && !empty($cek->jam_in)) {
                return response()->json(['success' => false, 'message' => 'Maaf Gagal absen, Anda Sudah Melakukan Presensi Masuk'], 422);
            }

            if ($cek != null && empty($cek->jam_in)) {
                $data_masuk = ['jam_in' => $jam, 'foto_in' => $fileName, 'lokasi_in' => $lokasi];
                $update = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi)->where('nik', $nik)->update($data_masuk);
                if ($update) {
                    if (isset($request->image)) { Storage::put($file, $image_compressed); }
                    return response()->json(['success' => true, 'message' => 'Terimkasih, Selamat Bekerja']);
                }
            } else if ($cek == null) {
                $data = [
                    'nik' => $nik, 'tanggal' => $tgl_presensi, 'jam_in' => $jam, 'foto_in' => $fileName, 'lokasi_in' => $lokasi,
                    'kode_jadwal' => $kode_jadwal, 'kode_jam_kerja' => $jadwal->kode_jam_kerja, 'status' => 'h',
                ];
                $simpan = DB::table('hrd_presensi')->insert($data);
                if ($simpan) {
                    if (isset($request->image)) { Storage::put($file, $image_compressed); }
                    return response()->json(['success' => true, 'message' => 'Terimkasih, Selamat Bekerja']);
                }
            }
        } else if ($statuspresensi == "pulang") {
            $ceklastpresensi = DB::table('hrd_presensi')
                ->join('hrd_jamkerja', 'hrd_presensi.kode_jam_kerja', '=', 'hrd_jamkerja.kode_jam_kerja')
                ->where('nik', $nik)->where('tanggal', $lastday)->first();

            $last_lintashari = $ceklastpresensi != null ? $ceklastpresensi->lintashari : "";
            $tgl_pulang_shift_3_check = date("H:i", strtotime(($jam)));

            $cekjadwalshiftlast = DB::table('hrd_jadwalshift_detail')
                ->join('hrd_jadwalshift', 'hrd_jadwalshift_detail.kode_jadwalshift', '=', 'hrd_jadwalshift.kode_jadwalshift')
                ->whereRaw('"' . $lastday . '" >= dari')
                ->whereRaw('"' . $lastday . '" <= sampai')
                ->where('nik', $nik)
                ->first();
            $kode_jadwal_last = $cekjadwalshiftlast != null ? $cekjadwalshiftlast->kode_jadwal : $kode_jadwal;
            $kode_jam_kerja_store = $jadwal->kode_jam_kerja;

            $tgl_presensi_final = $tgl_presensi;
            $kode_jadwal_final = $kode_jadwal;

            if (!empty($last_lintashari)) {
                if ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") {
                    $tgl_presensi_final = $lastday;
                }

                if ($namahari != "Sabtu") {
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                    $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($ceklastpresensi->jam_pulang));
                } else {
                    $jam_pulang = $tgl_presensi_final . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }
            } else {
                if ($tgl_pulang_shift_3_check <= "08:00" && $kode_jadwal_last == "JD004") {
                    $tgl_presensi_final = $lastday;
                    $tgl_pulang = date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                    $jam_pulang = $tgl_pulang . " 07:00";
                    $kode_jam_kerja_store = "JK08";
                    $kode_jadwal_final = "JD004";
                } else {
                    if ($kode_jadwal == "JD004") {
                        if ($namahari != "Sabtu") {
                            $tgl_pulang = ($jam_sekarang > "00:00" && $jam_sekarang <= "08:00") ? $tgl_presensi_final : date('Y-m-d', strtotime('+1 day', strtotime($tgl_presensi_final)));
                        } else {
                            $tgl_pulang = $tgl_presensi_final;
                        }
                    } else {
                        $tgl_pulang = $tgl_presensi_final;
                    }
                    $jam_pulang = $tgl_pulang . " " . date("H:i", strtotime($jam_kerja->jam_pulang));
                }
            }

            $date_jampulang = date("Y-m-d", strtotime($jam_pulang));
            $hour_jampulang = (date("H", strtotime($jam_pulang)) - 2);
            $h_jampulang = $hour_jampulang < 10 ? "0" . $hour_jampulang : $hour_jampulang;
            $jam_pulang_final = $date_jampulang . " " . $h_jampulang . ":00";

            if ($jam < $jam_pulang_final) {
                return response()->json(['success' => false, 'message' => "Maaf Belum Waktunya Absen Pulang, Absen Pulang di Mulai Pada Pukul " . $jam_pulang_final], 422);
            }

            $cek = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi_final)->where('nik', $nik)->first();
            if ($cek == null) {
                $data = [
                    'nik' => $nik, 'tanggal' => $tgl_presensi_final, 'jam_out' => $jam, 'foto_out' => $fileName, 'lokasi_out' => $lokasi,
                    'kode_jadwal' => $kode_jadwal_final, 'kode_jam_kerja' => $kode_jam_kerja_store, 'status' => 'h',
                ];
                $simpan = DB::table('hrd_presensi')->insert($data);
                if ($simpan) {
                    if (isset($request->image)) { Storage::put($file, $image_compressed); }
                    return response()->json(['success' => true, 'message' => 'Terimkasih, Hati Hati Di Jalan']);
                }
            } else if ($cek != null && !empty($cek->jam_out)) {
                return response()->json(['success' => false, 'message' => 'Maaf Gagal absen, Anda Sudah Melakukan Presensi Pulang'], 422);
            } else {
                $data_pulang = ['jam_out' => $jam, 'foto_out' => $fileName, 'lokasi_out' => $lokasi];
                $update = DB::table('hrd_presensi')->where('tanggal', $tgl_presensi_final)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    if (isset($request->image)) { Storage::put($file, $image_compressed); }
                    return response()->json(['success' => true, 'message' => 'Terimkasih, Hati Hati Di Jalan']);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Maaf Gagal absen, Hubungi Tim IT'], 500);
    }

    private function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }
}
