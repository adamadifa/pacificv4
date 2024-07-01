<?php

namespace App\Http\Controllers;

use App\Models\Historibayarkasbon;
use App\Models\Kasbon;
use App\Models\Kasbonpotonggaji;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayarankasbonController extends Controller
{
    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_kasbon = config('global.roles_access_all_kasbon');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $query = Kasbonpotonggaji::query();
        $query->select('keuangan_kasbon_potonggaji.kode_potongan', 'bulan', 'tahun', 'totalpembayaran');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!$user->hasRole($roles_access_all_kasbon)) {
            $whereKategorijabatan = "WHERE hrd_jabatan.kategori != 'MJ'";
        } else {
            $whereKategorijabatan = "";
        }

        $query->leftJoin(
            DB::raw("(
            SELECT kode_potongan,SUM(keuangan_kasbon_historibayar.jumlah) as totalpembayaran
            FROM keuangan_kasbon_historibayar
            INNER JOIN keuangan_kasbon ON keuangan_kasbon_historibayar.no_kasbon = keuangan_kasbon.no_kasbon
            INNER JOIN hrd_karyawan ON keuangan_kasbon.nik = hrd_karyawan.nik
            INNER JOIN hrd_jabatan ON hrd_karyawan.kode_jabatan = hrd_jabatan.kode_jabatan
            $whereKategorijabatan
            GROUP BY kode_potongan
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_kasbon_potonggaji.kode_potongan', '=', 'historibayar.kode_potongan');
            }
        );
        $query->orderBy('tahun');
        $query->orderBy('bulan');
        $data['historibayar'] = $query->get();
        return view('keuangan.pembayarankasbon.index', $data);
    }


    public function show($kode_potongan, $export)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $role_access_all_kasbon = config('global.roles_access_all_kasbon');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];


        $kode_potongan = Crypt::decrypt($kode_potongan);
        $query = Historibayarkasbon::query();
        $query->select(
            'keuangan_kasbon_historibayar.*',
            'keuangan_kasbon.nik',
            'hrd_karyawan.nama_karyawan',
            'hrd_jabatan.nama_jabatan',
            'hrd_departemen.nama_dept',
            'hrd_karyawan.kode_cabang',
            'hrd_karyawan.kode_dept',
            'cabang.nama_cabang'
        );
        $query->leftJoin('keuangan_kasbon', 'keuangan_kasbon_historibayar.no_kasbon', '=', 'keuangan_kasbon.no_kasbon');
        $query->leftJoin('hrd_karyawan', 'keuangan_kasbon.nik', '=', 'hrd_karyawan.nik');
        $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');
        $query->leftJoin('cabang', 'hrd_karyawan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->leftJoin('hrd_departemen', 'hrd_karyawan.kode_dept', '=', 'hrd_departemen.kode_dept');
        $query->where('kode_potongan', $kode_potongan);
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', $user->kode_regional);
                $query->where('hrd_karyawan.kode_jabatan', '!=', 'J03');
            } else {
                $query->where('hrd_jabatan.kategori', 'NM');
                $query->where('hrd_karyawan.kode_cabang', $user->kode_cabang);
            }
        } else {
            if (!$user->hasRole($role_access_all_kasbon)) {
                $query->where('hrd_jabatan.kategori', 'NM');
            }
        }
        $query->orderBy('keuangan_kasbon.nik', 'asc');
        $data['historibayar'] = $query->get();


        $data['potongankasbon'] = Kasbonpotonggaji::where('kode_potongan', $kode_potongan)->first();

        if ($export == "true") {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Detail Pembayaran kasbon.xls");
            return view('keuangan.pembayarankasbon.export', $data);
        } else {
            return view('keuangan.pembayarankasbon.show', $data);
        }
    }

    public function creategenerate()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.pembayarankasbon.creategenerate', $data);
    }


    public function generatekasbon(Request $request)
    {
        $kode_potongan = "GJ" . $request->bulan . $request->tahun;
        DB::beginTransaction();
        try {
            $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
            $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

            $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
            $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");

            //Cek Potongan Gaji
            $cek = Kasbonpotonggaji::count();
            //Ce Potongan gaji Bulan Sebelumnya
            $ceklast = Kasbonpotonggaji::where('bulan', $lastbulan)->where('tahun', $lasttahun)->count();
            $ceknext = Kasbonpotonggaji::where('bulan', $bulanpotongan)->where('tahun', $tahunpotongan)->count();
            $ceknow = Kasbonpotonggaji::where('bulan', $request->bulan)->where('tahun', $request->tahun)->count();
            if ($ceklast == 0) {
                return Redirect::back()->with(messageError('Potongan Gaji Bulan Sebelumnya Belum di Generate'));
            } else if ($ceknext >  0) {
                return Redirect::back()->with(messageError('Periode Laporan Ini Sudah Di Tutup'));
            } else if ($ceknow > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Ini Sudah Di Generate Untuk Generate Ulang Silahkan Hapus Dulu'));
            }

            Kasbonpotonggaji::create([
                'kode_potongan' => $kode_potongan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun
            ]);

            $jatuhtempo = $tahunpotongan . "-" . $bulanpotongan . "-01";

            $cekkasbonbelumdiproses = Kasbon::where('jatuh_tempo', $jatuhtempo)
                ->where('keuangan_kasbon.status', 0)
                ->where('keuangan_kasbon.tanggal', '>', '2023-05-01')
                ->count();

            //dd($cekpjpbelumdiproses);
            if ($cekkasbonbelumdiproses  > 0) {
                return Redirect::back()->with(messageError('Masih Ada Ajuan PJP yang Belum Di Proses'));
            }

            $rencana = Kasbon::where('jatuh_tempo', $jatuhtempo)->get();


            foreach ($rencana as $d) {
                $lasthistoribayar = Historibayarkasbon::select('no_bukti')
                    ->whereRaw('YEAR(tanggal)="' . $tahunpotongan . '"')
                    ->orderBy("no_bukti", "desc")
                    ->first();
                $last_nobukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                $no_bukti  = buatkode($last_nobukti, "PJ" . substr($tahunpotongan, 2, 2), 4);

                $data = [
                    'no_bukti' => $no_bukti,
                    'tanggal' => $tahunpotongan . "-" . $bulanpotongan . "-01",
                    'no_kasbon' => $d->no_kasbon,
                    'jumlah' => $d->jumlah,
                    'id_user' => auth()->user()->id,
                    'kode_potongan' => $kode_potongan
                ];
                Historibayarkasbon::create($data);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroygenerate($kode_potongan)
    {
        $kode_potongan = Crypt::decrypt($kode_potongan);

        DB::beginTransaction();
        try {
            $potongankasbon = Kasbonpotonggaji::where('kode_potongan', $kode_potongan)->first();

            $nextbulan = getbulandantahunberikutnya($potongankasbon->bulan, $potongankasbon->tahun, "bulan");
            $nexttahun = getbulandantahunberikutnya($potongankasbon->bulan, $potongankasbon->tahun, "tahun");

            $ceknext = Kasbonpotonggaji::where('bulan', $nextbulan)->where('tahun', $nexttahun)->count();

            if ($ceknext > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            Kasbonpotonggaji::where('kode_potongan', $kode_potongan)->delete();
            Historibayarkasbon::where('kode_potongan', $kode_potongan)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
