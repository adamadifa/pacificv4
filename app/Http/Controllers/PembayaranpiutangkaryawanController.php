<?php

namespace App\Http\Controllers;

use App\Models\Historibayarpiutangkaryawan;
use App\Models\Historibayarpjp;
use App\Models\Piutangkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PembayaranpiutangkaryawanController extends Controller
{

    public function create()
    {
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan'] = config('global.nama_bulan');
        $data['start_year'] = config('global.start_year');
        return view('keuangan.pembayaranpiutangkaryawan.create', $data);
    }
    public function gethistoribayar(Request $request)
    {
        $data['historibayar'] = Historibayarpiutangkaryawan::where('no_pinjaman', $request->no_pinjaman)->orderBy('tanggal')->get();
        return view('keuangan.pembayaranpiutangkaryawan.gethistoribayar', $data);
    }

    public function store(Request $request)
    {
        $jumlah = toNumber($request->jumlah);
        $bulanpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "bulan");
        $tahunpotongan = getbulandantahunberikutnya($request->bulan, $request->tahun, "tahun");
        $kode_potongan = $request->jenis_bayar == 1 ? "GJ" . $request->bulan . $request->tahun : "";
        $tanggal = $tahunpotongan . "-" . $bulanpotongan . "-01";


        DB::beginTransaction();
        try {
            $pk = new Piutangkaryawan();
            $piutangkaryawan = $pk->getPiutangkaryawan(no_pinjaman: $request->no_pinjaman)->first();
            $sisa_tagihan = $piutangkaryawan->jumlah - $piutangkaryawan->totalpembayaran;


            $cekpotonggaji = Historibayarpiutangkaryawan::where('no_pinjaman', $request->no_pinjaman)->where('kode_potongan', $kode_potongan)->count();

            $cekpotongkomisi = Historibayarpiutangkaryawan::where('no_pinjaman', $request->no_pinjaman)
                ->where('tanggal', $tanggal)
                ->where('jenis_bayar', 2)
                ->count();

            $cektitipan = Historibayarpiutangkaryawan::where('no_pinjaman', $request->no_pinjaman)
                ->where('tanggal', $tanggal)
                ->where('jenis_bayar', 3)->count();



            if ($jumlah > $sisa_tagihan) {
                return 2;
            }

            if ($cekpotonggaji > 0 && $request->jenis_bayar == 1) {
                return 3;
            } else if ($request->jenis_bayar == 2 && $cekpotongkomisi > 0) {
                return 4;
            } else if ($request->jenis_bayar == 3 && $cektitipan > 0) {
                return 5;
            }

            $lasthistoribayar = Historibayarpiutangkaryawan::select('no_bukti')
                ->whereRaw('YEAR(tanggal)="' . date('Y', strtotime($tanggal)) . '"')
                ->orderBy("no_bukti", "desc")
                ->first();
            $last_nobukti = $lasthistoribayar != null ? $lasthistoribayar->no_bukti : '';
            $no_bukti  = buatkode($last_nobukti, "PK" . date('y', strtotime($tanggal)), 4);

            Historibayarpiutangkaryawan::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $tanggal,
                'no_pinjaman' => $request->no_pinjaman,
                'jumlah' => $jumlah,
                'jenis_bayar' => $request->jenis_bayar,
                'kode_potongan' => $kode_potongan,
                'id_user' => auth()->user()->id
            ]);
            DB::commit();
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            // return 1;
            dd($e);
        }
    }


    public function destroy(Request $request)
    {
        $no_bukti = Crypt::decrypt($request->no_bukti);
        try {
            Historibayarpiutangkaryawan::where('no_bukti', $no_bukti)->delete();
            echo 0;
        } catch (\Exception $e) {
            echo 1;
        }
    }
}
