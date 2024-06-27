<?php

namespace App\Http\Controllers;

use App\Models\Historibayarpjp;
use App\Models\Pjp;
use App\Models\Pjppotonggaji;
use App\Models\Rencanacicilanpjp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PembayaranpjpController extends Controller
{

    public function index(Request $request)
    {

        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_pjp = config('global.roles_access_all_pjp');

        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');

        $query = Pjppotonggaji::query();
        $query->select('keuangan_pjp_potonggaji.kode_potongan', 'bulan', 'tahun', 'totalpembayaran');
        if (!empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }
        if (!empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        } else {
            $query->where('tahun', date('Y'));
        }

        if (!$user->hasRole($roles_access_all_pjp)) {
            $whereKategorijabatan = "WHERE hrd_jabatan.kategori != 'MJ'";
        } else {
            $whereKategorijabatan = "";
        }

        $query->leftJoin(
            DB::raw("(
            SELECT kode_potongan,SUM(jumlah) as totalpembayaran
            FROM keuangan_pjp_historibayar
            INNER JOIN keuangan_pjp ON keuangan_pjp_historibayar.no_pinjaman = keuangan_pjp.no_pinjaman
            INNER JOIN hrd_karyawan ON keuangan_pjp.nik = hrd_karyawan.nik
            INNER JOIN hrd_jabatan ON hrd_karyawan.kode_jabatan = hrd_jabatan.kode_jabatan
            $whereKategorijabatan
            GROUP BY kode_potongan
        ) historibayar"),
            function ($join) {
                $join->on('keuangan_pjp_potonggaji.kode_potongan', '=', 'historibayar.kode_potongan');
            }
        );
        $query->orderBy('tahun');
        $query->orderBy('bulan');
        $data['historibayar'] = $query->get();
        return view('keuangan.pembayaranpjp.index', $data);
    }
    public function create($no_pinjaman)
    {
        $data['no_pinjaman'] = Crypt::decrypt($no_pinjaman);
        return view('keuangan.pembayaranpjp.create', $data);
    }

    public function creategenerate()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.pembayaranpjp.creategenerate', $data);
    }

    public function store(Request $request)
    {
        $no_pinjaman = $request->no_pinjaman;
        $tanggal = $request->tanggal;
        $jumlah = toNumber($request->jumlah);
        $id_user = auth()->user()->id;





        DB::beginTransaction();
        try {
            $pj = new Pjp();
            $pjp = $pj->getPjp(no_pinjaman: $no_pinjaman)->first();
            $sisa_tagihan = $pjp->jumlah_pinjaman - $pjp->totalpembayaran;
            if ($jumlah > $sisa_tagihan) {
                return 2;
            }
            //Generate No. Bukti
            $lasthistoribayar = Historibayarpjp::select('no_bukti')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($tanggal)) . '"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_nobukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
            $no_bukti  = buatkode($last_nobukti, "PJ" . date('y', strtotime($tanggal)), 4);




            $rencana = Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                ->whereRaw('jumlah != bayar')
                ->orderBy('cicilan_ke', 'asc')
                ->get();

            $mulaicicilan = Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                ->whereRaw('jumlah != bayar')
                ->orderBy('cicilan_ke', 'asc')
                ->first();
            $sisa = $jumlah;
            $cicilan = "";
            $i = $mulaicicilan->cicilan_ke;
            foreach ($rencana as $d) {
                if ($sisa >= $d->jumlah) {
                    Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                        ->where('cicilan_ke', $i)
                        ->update([
                            'bayar' => $d->jumlah
                        ]);
                    //$cicilan .=  $d->cicilan_ke . ",";
                    $sisapercicilan = $d->jumlah - $d->bayar;
                    $sisa = $sisa - $sisapercicilan;

                    if ($sisa == 0) {
                        $cicilan .=  $d->cicilan_ke;
                    } else {
                        $cicilan .=  $d->cicilan_ke . ",";
                    }

                    $coba = $cicilan;
                } else {
                    if ($sisa != 0) {
                        $sisapercicilan = $d->jumlah - $d->bayar;
                        if ($d->bayar != 0) {
                            if ($sisa >= $sisapercicilan) {
                                Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                    ->where('cicilan_ke', $i)
                                    ->update([
                                        'bayar' =>  DB::raw('bayar +' . $sisapercicilan)
                                    ]);
                                $cicilan .= $d->cicilan_ke . ",";
                                $sisa = $sisa - $sisapercicilan;
                            } else {
                                Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                    ->where('cicilan_ke', $i)
                                    ->update([
                                        'bayar' =>  DB::raw('bayar +' . $sisa)
                                    ]);
                                //$cicilan .= $d->cicilan_ke . ",";
                                $sisa = $sisa - $sisa;
                                if ($sisa == 0) {
                                    $cicilan .=  $d->cicilan_ke;
                                } else {
                                    $cicilan .=  $d->cicilan_ke . ",";
                                }
                            }
                        } else {
                            Rencanacicilanpjp::where('no_pinjaman', $no_pinjaman)
                                ->where('cicilan_ke', $i)
                                ->update([
                                    'bayar' =>  DB::raw('bayar +' . $sisa)
                                ]);
                            //$cicilan .= $d->cicilan_ke;
                            $sisa = $sisa - $sisa;
                            if ($sisa == 0) {
                                $cicilan .=  $d->cicilan_ke;
                            } else {
                                $cicilan .=  $d->cicilan_ke . ",";
                            }
                        }
                    }
                }
                $i++;
            }

            $data = [
                'no_bukti' => $no_bukti,
                'tanggal' => $tanggal,
                'no_pinjaman' => $no_pinjaman,
                'jumlah' => $jumlah,
                'cicilan_ke' => $cicilan,
                'id_user' => $id_user
            ];
            Historibayarpjp::create($data);
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            return 1;
            dd($e);
        }
    }


    public function generatepjp(Request $request)
    {
        $kode_potongan = "GJ" . $request->bulan . $request->tahun;
        DB::beginTransaction();
        try {
            $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
            $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");

            $lastbulan = getbulandantahunlalu($request->bulan, $request->tahun, "bulan");
            $lasttahun = getbulandantahunlalu($request->bulan, $request->tahun, "tahun");

            //Cek Potongan Gaji
            $cek = Pjppotonggaji::count();
            //Ce Potongan gaji Bulan Sebelumnya
            $ceklast = Pjppotonggaji::where('bulan', $lastbulan)->where('tahun', $lasttahun)->count();
            $ceknext = Pjppotonggaji::where('bulan', $bulanpotongan)->where('tahun', $tahunpotongan)->count();
            $ceknow = Pjppotonggaji::where('bulan', $request->bulan)->where('tahun', $request->tahun)->count();
            if ($ceklast == 0) {
                return Redirect::back()->with(messageError('Potongan Gaji Bulan Sebelumnya Belum di Generate'));
            } else if ($ceknext >  0) {
                return Redirect::back()->with(messageError('Periode Laporan Ini Sudah Di Tutup'));
            } else if ($ceknow > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Ini Sudah Di Generate Untuk Generate Ulang Silahkan Hapus Dulu'));
            }

            Pjppotonggaji::create([
                'kode_potongan' => $kode_potongan,
                'bulan' => $request->bulan,
                'tahun' => $request->tahun
            ]);

            $cekpjpbelumdiproses = Rencanacicilanpjp::join('keuangan_pjp', 'keuangan_pjp_rencanacicilan.no_pinjaman', '=', 'keuangan_pjp.no_pinjaman')
                ->where('bulan', $bulanpotongan)->where('tahun', $tahunpotongan)
                ->where('keuangan_pjp.status', 0)
                ->where('keuangan_pjp.tanggal', '>', '2023-05-01')
                ->count();

            //dd($cekpjpbelumdiproses);
            if ($cekpjpbelumdiproses  > 0) {
                return Redirect::back()->with(messageError('Masih Ada Ajuan PJP yang Belum Di Proses'));
            }

            $rencanacicilan = Rencanacicilanpjp::where('bulan', $bulanpotongan)->where('tahun', $tahunpotongan)
                ->where('bayar', 0)
                ->get();


            foreach ($rencanacicilan as $d) {
                $lasthistoribayar = Historibayarpjp::select('no_bukti')
                    ->whereRaw('YEAR(tanggal)="' . $tahunpotongan . '"')
                    ->orderBy("no_bukti", "desc")
                    ->first();
                $last_nobukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
                $no_bukti  = buatkode($last_nobukti, "PJ" . substr($tahunpotongan, 2, 2), 4);

                $data = [
                    'no_bukti' => $no_bukti,
                    'tanggal' => $tahunpotongan . "-" . $bulanpotongan . "-01",
                    'no_pinjaman' => $d->no_pinjaman,
                    'jumlah' => $d->jumlah,
                    'cicilan_ke' => $d->cicilan_ke,
                    'id_user' => auth()->user()->id,
                    'kode_potongan' => $kode_potongan
                ];


                Historibayarpjp::create($data);
                Rencanacicilanpjp::where('bulan', $bulanpotongan)->where('tahun', $tahunpotongan)->where('no_pinjaman', $d->no_pinjaman)->update([
                    'bayar' => $d->jumlah,
                    'kode_potongan' => $kode_potongan
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    function destroy(Request $request)
    {
        $no_bukti = Crypt::decrypt($request->no_bukti);
        $trans = Historibayarpjp::where('no_bukti', $no_bukti)->first();
        $cicilan_ke = array_map('intval', explode(',', $trans->cicilan_ke));
        $rencana = Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
            ->whereIn('cicilan_ke', $cicilan_ke)
            ->orderBy('cicilan_ke', 'desc')
            ->get();
        //dd($rencana);
        $mulaicicilan = Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
            ->whereIn('cicilan_ke', $cicilan_ke)
            ->orderBy('cicilan_ke', 'desc')
            ->first();
        //dd($mulaicicilan);
        DB::beginTransaction();
        try {
            $sisa = $trans->jumlah;
            $i = $mulaicicilan->cicilan_ke;
            foreach ($rencana as $d) {
                if ($sisa >= $d->bayar) {
                    Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
                        ->where('cicilan_ke', $i)
                        ->update([
                            'bayar' => DB::raw('bayar -' . $d->bayar)
                        ]);
                    $sisa = $sisa - $d->bayar;
                } else {
                    if ($sisa != 0) {
                        Rencanacicilanpjp::where('no_pinjaman', $trans->no_pinjaman)
                            ->where('cicilan_ke', $i)
                            ->update([
                                'bayar' =>  DB::raw('bayar -' . $sisa)
                            ]);
                        $sisa = $sisa - $sisa;
                    }
                }

                $i--;
            }
            Historibayarpjp::where('no_bukti', $no_bukti)
                ->delete();


            DB::commit();

            echo 0;
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e);
            echo 1;
        }
    }


    public function destroygenerate($kode_potongan)
    {
        $kode_potongan = Crypt::decrypt($kode_potongan);

        DB::beginTransaction();
        try {
            $potonganpjp = Pjppotonggaji::where('kode_potongan', $kode_potongan)->first();

            $nextbulan = getbulandantahunberikutnya($potonganpjp->bulan, $potonganpjp->tahun, "bulan");
            $nexttahun = getbulandantahunberikutnya($potonganpjp->bulan, $potonganpjp->tahun, "tahun");

            $ceknext = Pjppotonggaji::where('bulan', $nextbulan)->where('tahun', $nexttahun)->count();

            if ($ceknext > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            Pjppotonggaji::where('kode_potongan', $kode_potongan)->delete();
            Historibayarpjp::where('kode_potongan', $kode_potongan)->delete();
            Rencanacicilanpjp::where('kode_potongan', $kode_potongan)->update([
                'bayar' => 0,
                'kode_potongan' => NULL
            ]);
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function show($kode_potongan, $export)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $role_access_all_pjp = config('global.roles_access_all_pjp');
        $dept_access = json_decode($user->dept_access, true) != null  ? json_decode($user->dept_access, true) : [];


        $kode_potongan = Crypt::decrypt($kode_potongan);
        $query = Historibayarpjp::query();
        $query->select(
            'keuangan_pjp_historibayar.*',
            'keuangan_pjp.nik',
            'hrd_karyawan.nama_karyawan',
            'hrd_jabatan.nama_jabatan',
            'hrd_departemen.nama_dept',
            'hrd_karyawan.kode_cabang',
            'hrd_karyawan.kode_dept',
            'cabang.nama_cabang'
        );
        $query->leftJoin('keuangan_pjp', 'keuangan_pjp_historibayar.no_pinjaman', '=', 'keuangan_pjp.no_pinjaman');
        $query->leftJoin('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
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
            if (!$user->hasRole($role_access_all_pjp)) {
                $query->where('hrd_jabatan.kategori', 'NM');
            }
        }
        $query->orderBy('keuangan_pjp.nik', 'asc');
        $data['historibayar'] = $query->get();


        $data['potonganpjp'] = Pjppotonggaji::where('kode_potongan', $kode_potongan)->first();

        if ($export == "true") {
            header("Content-type: application/vnd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Detail Pembayaran PJP.xls");
            return view('keuangan.pembayaranpjp.export', $data);
        } else {
            return view('keuangan.pembayaranpjp.show', $data);
        }
    }


    public function gethistoribayar(Request $request)
    {
        $data['historibayar'] = Historibayarpjp::where('no_pinjaman', $request->no_pinjaman)->orderBy('tanggal')->get();
        return view('keuangan.pembayaranpjp.gethistoribayar', $data);
    }
}
