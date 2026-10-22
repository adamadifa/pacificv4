<?php

namespace App\Http\Controllers;

use App\Exports\PenyesuaianupahTemplateExport;
use App\Models\Detailpenyesuaianupah;
use App\Models\Karyawan;
use App\Models\Penyesuaianupah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class PenyesuaianupahController extends Controller
{
    public function index()
    {
        $data['penyupah'] = Penyesuaianupah::orderBy('tahun')->orderBy('bulan')->get();
        return view('hrd.penyesuaianupah.index', $data);
    }

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.penyesuaianupah.create', $data);
    }

    public function store(Request $request)
    {

        try {
            Penyesuaianupah::create([
                'kode_gaji' => 'GJ' . $request->bulan . $request->tahun,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $data['penyupah'] = Penyesuaianupah::where('kode_gaji', $kode_gaji)->first();
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        return view('hrd.penyesuaianupah.edit', $data);
    }

    public function update(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Penyesuaianupah::where('kode_gaji', $kode_gaji)->update([
                'bulan' => $request->bulan,
                'tahun' => $request->tahun,
            ]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        try {
            Penyesuaianupah::where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $penyupah = Penyesuaianupah::where('kode_gaji', $kode_gaji)->firstOrFail();
        $data['penyupah'] = $penyupah;

        $detailpenyupah = Detailpenyesuaianupah::select(
            'hrd_penyesuaian_upah_detail.*',
            'hrd_karyawan.nama_karyawan',
            'hrd_karyawan.kode_jabatan',
            'hrd_karyawan.kode_dept',
            'hrd_karyawan.kode_cabang',
            'hrd_jabatan.nama_jabatan',
            'hrd_departemen.nama_dept',
            'cabang.nama_cabang'
        )
            ->join('hrd_karyawan', 'hrd_penyesuaian_upah_detail.nik', '=', 'hrd_karyawan.nik')
            ->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->leftJoin('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('hrd_penyesuaian_upah_detail.kode_gaji', $kode_gaji)
            ->orderBy('hrd_karyawan.nama_karyawan', 'asc')
            ->get();

        $data['detailpenyupah'] = $detailpenyupah;
        $data['total_karyawan'] = $detailpenyupah->count();
        $data['total_penambah'] = $detailpenyupah->sum('penambah');
        $data['total_pengurang'] = $detailpenyupah->sum('pengurang');
        $data['total_net'] = $data['total_penambah'] - $data['total_pengurang'];

        return view('hrd.penyesuaianupah.show', $data);
    }

    public function tambahkaryawan($kode_gaji)
    {
        $data['kode_gaji'] = Crypt::decrypt($kode_gaji);
        $data['list_bulan'] = config('global.list_bulan');
        $data['start_year'] = config('global.start_year');
        $data['karyawan'] = Karyawan::where('status_aktif_karyawan', 1)->orderBy('nama_karyawan', 'asc')->get();
        return view('hrd.penyesuaianupah.tambahkaryawan', $data);
    }

    public function storekaryawan(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $request->validate([
            'nik' => 'required',
        ]);

        try {
            $cek = Detailpenyesuaianupah::where('nik', $request->nik)->where('kode_gaji', $kode_gaji)->first();
            if ($cek) {
                return Redirect::back()->with(messageError('Karyawan ini sudah ada di penyesuaian upah periode ini. Silahkan gunakan fitur Edit untuk mengubah nominal.'));
            }
            Detailpenyesuaianupah::create([
                'kode_gaji' => $kode_gaji,
                'nik' => $request->nik,
                'pengurang' => toNumber($request->pengurang),
                'penambah' => toNumber($request->penambah)
            ]);
            return Redirect::back()->with(messageSuccess('Data Karyawan Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function editkaryawan($kode_gaji, $nik)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $nik = Crypt::decrypt($nik);
        $data['kode_gaji'] = $kode_gaji;
        $data['detail'] = Detailpenyesuaianupah::where('hrd_penyesuaian_upah_detail.kode_gaji', $kode_gaji)
            ->where('hrd_penyesuaian_upah_detail.nik', $nik)
            ->join('hrd_karyawan', 'hrd_penyesuaian_upah_detail.nik', '=', 'hrd_karyawan.nik')
            ->select('hrd_penyesuaian_upah_detail.*', 'hrd_karyawan.nama_karyawan')
            ->firstOrFail();
        return view('hrd.penyesuaianupah.editkaryawan', $data);
    }

    public function updatekaryawan(Request $request, $kode_gaji, $nik)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $nik = Crypt::decrypt($nik);
        try {
            Detailpenyesuaianupah::where('kode_gaji', $kode_gaji)
                ->where('nik', $nik)
                ->update([
                    'pengurang' => toNumber($request->pengurang),
                    'penambah' => toNumber($request->penambah)
                ]);
            return Redirect::back()->with(messageSuccess('Data Penyesuaian Upah Berhasil Diperbarui'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function deletekaryawan($kode_gaji, $nik)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $nik = Crypt::decrypt($nik);
        try {
            Detailpenyesuaianupah::where('nik', $nik)->where('kode_gaji', $kode_gaji)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function downloadtemplate($kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $filename = 'template_penyesuaian_upah_' . $kode_gaji . '.xlsx';
        return Excel::download(new PenyesuaianupahTemplateExport($kode_gaji), $filename);
    }

    public function import(Request $request, $kode_gaji)
    {
        $kode_gaji = Crypt::decrypt($kode_gaji);
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        DB::beginTransaction();
        try {
            $data = Excel::toArray([], $request->file('file'));
            if (empty($data) || empty($data[0])) {
                return Redirect::back()->with(messageError('File Excel kosong atau format tidak sesuai.'));
            }

            $rows = $data[0];
            if (count($rows) < 2) {
                return Redirect::back()->with(messageError('File Excel tidak memiliki baris data karyawan.'));
            }

            // Identifikasi indeks kolom dari header baris pertama
            $header = array_map(function ($h) {
                return strtolower(trim((string)$h));
            }, $rows[0]);

            $nikIdx = false;
            $penambahIdx = false;
            $pengurangIdx = false;

            foreach ($header as $idx => $col) {
                if ($nikIdx === false && str_contains($col, 'nik')) {
                    $nikIdx = $idx;
                }
                if ($penambahIdx === false && str_contains($col, 'penambah')) {
                    $penambahIdx = $idx;
                }
                if ($pengurangIdx === false && str_contains($col, 'pengurang')) {
                    $pengurangIdx = $idx;
                }
            }

            if ($nikIdx === false) $nikIdx = 0;
            if ($penambahIdx === false) $penambahIdx = (count($header) >= 6) ? 4 : 2;
            if ($pengurangIdx === false) $pengurangIdx = (count($header) >= 6) ? 5 : 3;

            // Cache valid NIKs
            $validNiks = Karyawan::pluck('nik')->flip()->toArray();

            $imported = 0;
            $skipped = 0;

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (!isset($row[$nikIdx])) {
                    continue;
                }

                $nik = trim((string)$row[$nikIdx]);
                if (empty($nik) || !isset($validNiks[$nik])) {
                    $skipped++;
                    continue;
                }

                $penambah = $this->parseNumber($row[$penambahIdx] ?? 0);
                $pengurang = $this->parseNumber($row[$pengurangIdx] ?? 0);

                if ($penambah > 0 || $pengurang > 0) {
                    Detailpenyesuaianupah::updateOrCreate(
                        [
                            'kode_gaji' => $kode_gaji,
                            'nik' => $nik,
                        ],
                        [
                            'penambah' => $penambah,
                            'pengurang' => $pengurang,
                        ]
                    );
                    $imported++;
                }
            }

            DB::commit();

            if ($imported == 0) {
                return Redirect::back()->with(messageWarning('Tidak ada data penyesuaian yang disimpan (nilai penambah dan pengurang bernilai 0 atau NIK tidak valid).'));
            }

            $msg = "Berhasil memproses {$imported} data karyawan.";
            if ($skipped > 0) {
                $msg .= " ({$skipped} baris dilewati karena NIK tidak terdaftar).";
            }

            return Redirect::back()->with(messageSuccess($msg));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Gagal mengimport data: ' . $e->getMessage()));
        }
    }

    private function parseNumber($value)
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            return (int) round($value);
        }
        if (empty($value)) {
            return 0;
        }
        $str = trim((string)$value);
        if (strpos($str, ',') !== false) {
            $parts = explode(',', $str);
            $str = $parts[0];
        }
        $cleaned = preg_replace('/[^0-9]/', '', $str);
        return !empty($cleaned) ? (int)$cleaned : 0;
    }
}

