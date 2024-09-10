<?php

namespace App\Http\Controllers;

use App\Models\Detailmutasigudangcabang;
use App\Models\Dpb;
use App\Models\Jenismutasigudangcabang;
use App\Models\Mutasigudangcabang;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MutasidpbController extends Controller
{
    public function create()
    {

        $data['jenis_mutasi'] = Jenismutasigudangcabang::where('kategori', 'DPB')->orderBy('order')->get();
        $data['produk'] = Produk::orderBy('kode_produk')->where('status_aktif_produk', 1)->get();
        return view('gudangcabang.mutasidpb.create', $data);
    }


    public function store(Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'jenis_mutasi' => 'required'
        ]);

        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if ($request->jenis_mutasi == "RT") {
                $kode = "RTR";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "HK") {
                $kode = "HK";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PT") {
                $kode = "PT";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PJ") {
                $kode = "PNJ";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "GB") {
                $kode = "RGB";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PH") {
                $kode = "PH";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "TR") {
                $kode = "TR";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PR") {
                $kode = "PR";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            }


            $mutasi = Mutasigudangcabang::select('no_mutasi')
                ->where('no_dpb', $request->no_dpb)->where('jenis_mutasi', $request->jenis_mutasi)
                ->orderBy('no_mutasi', 'desc')
                ->first();
            $last_no_mutasi = $mutasi != null ? $mutasi->no_mutasi : '';
            $no_mutasi = buatkode($last_no_mutasi, $kode . $request->no_dpb, 2);

            $dpb = Dpb::where('no_dpb', $request->no_dpb)
                ->join('salesman', 'gudang_cabang_dpb.kode_salesman', '=', 'salesman.kode_salesman')
                ->first();

            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);

                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                if (!empty($jumlah)) {
                    $detail[]   = [
                        'no_mutasi' => $no_mutasi,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah' => $jumlah
                    ];
                }
            }


            if (empty($detail)) {
                return response()->json(['status' => 'error', 'message' => 'Data Masih Kosong']);
            } else {
                Mutasigudangcabang::create([
                    'no_mutasi'  => $no_mutasi,
                    'tanggal' => $request->tanggal,
                    'no_dpb' => $request->no_dpb,
                    'kode_cabang' => $dpb->kode_cabang,
                    'kondisi' => $kondisi,
                    'in_out_good' => $in_out_good,
                    'in_out_bad' => $in_out_bad,
                    'jenis_mutasi' => $request->jenis_mutasi,
                    'id_user' => auth()->user()->id
                ]);

                Detailmutasigudangcabang::insert($detail);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Data Berhasilimpan']);
            }
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function edit($no_mutasi)
    {

        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['jenis_mutasi'] = Jenismutasigudangcabang::orderBy('kode_jenis_mutasi')->get();
        $data['produk'] = Produk::orderBy('kode_produk')
            ->select('produk.kode_produk', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'jumlah')
            ->leftJoin(
                DB::raw("(
                SELECT
                kode_produk,jumlah
                FROM
                gudang_cabang_mutasi_detail
                WHERE no_mutasi = '$no_mutasi'
            ) mutasi"),
                function ($join) {
                    $join->on('produk.kode_produk', '=', 'mutasi.kode_produk');
                }
            )
            ->where('status_aktif_produk', 1)->get();
        $data['mutasi'] = Mutasigudangcabang::select('no_mutasi', 'tanggal', 'gudang_cabang_jenis_mutasi.jenis_mutasi')
            ->select('no_mutasi', 'tanggal', 'gudang_cabang_mutasi.jenis_mutasi')
            ->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi')
            ->where('no_mutasi', $no_mutasi)
            ->first();
        return view('gudangcabang.mutasidpb.edit', $data);
    }


    public function update(Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'jenis_mutasi' => 'required'
        ]);
        $no_mutasi = Crypt::decrypt($request->no_mutasi);
        $kode_produk = $request->kode_produk;
        $jml_dus = $request->jml_dus;
        $jml_pack = $request->jml_pack;
        $jml_pcs = $request->jml_pcs;
        $isi_pcs_dus = $request->isi_pcs_dus;
        $isi_pcs_pack = $request->isi_pcs_pack;

        DB::beginTransaction();
        try {

            $mutasi = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();

            //Checking
            $cektutuplaporan_mutasi = cektutupLaporan($mutasi->tanggal, "gudangcabang");
            if ($cektutuplaporan_mutasi > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }


            $cektutuplaporan = cektutupLaporan($request->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            if ($request->jenis_mutasi == "RT") {
                $kode = "RTR";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "HK") {
                $kode = "HK";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PT") {
                $kode = "PT";
                $kondisi = "G";
                $in_out_good = "I";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PJ") {
                $kode = "PNJ";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "GB") {
                $kode = "RGB";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PH") {
                $kode = "PH";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "TR") {
                $kode = "TR";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            } else if ($request->jenis_mutasi == "PR") {
                $kode = "PR";
                $kondisi = "G";
                $in_out_good = "O";
                $in_out_bad = NULL;
            }

            if ($request->jenis_mutasi != $mutasi->jenis_mutasi) {
                $lastmutasi = Mutasigudangcabang::select('no_mutasi')
                    ->where('no_dpb', $mutasi->no_dpb)->where('jenis_mutasi', $request->jenis_mutasi)
                    ->orderBy('no_mutasi', 'desc')
                    ->first();
                $last_no_mutasi = $lastmutasi != null ? $lastmutasi->no_mutasi : '';
                $no_mutasi_new = buatkode($last_no_mutasi, $kode . $mutasi->no_dpb, 2);
            } else {
                $no_mutasi_new = $mutasi->no_mutasi;
            }


            $detail = [];
            for ($i = 0; $i < count($kode_produk); $i++) {
                $dus = toNumber(!empty($jml_dus[$i]) ? $jml_dus[$i] : 0);
                $pack = toNumber(!empty($jml_pack[$i]) ? $jml_pack[$i] : 0);
                $pcs = toNumber(!empty($jml_pcs[$i]) ? $jml_pcs[$i] : 0);

                $jumlah = ($dus * $isi_pcs_dus[$i]) + ($pack * $isi_pcs_pack[$i]) + $pcs;
                if (!empty($jumlah)) {
                    $detail[]   = [
                        'no_mutasi' => $no_mutasi_new,
                        'kode_produk' => $kode_produk[$i],
                        'jumlah' => $jumlah
                    ];
                }
            }


            if (empty($detail)) {
                return response()->json(['status' => 'error', 'message' => 'Produk Tidak Boleh Kosong !']);
            } else {

                Detailmutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();

                Mutasigudangcabang::where('no_mutasi', $no_mutasi)->update([
                    'no_mutasi'  => $no_mutasi_new,
                    'tanggal' => $request->tanggal,
                    'kondisi' => $kondisi,
                    'in_out_good' => $in_out_good,
                    'in_out_bad' => $in_out_bad,
                    'jenis_mutasi' => $request->jenis_mutasi
                ]);

                Detailmutasigudangcabang::insert($detail);
                DB::commit();
                return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan']);
            }
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function show($no_mutasi)
    {
        $no_mutasi = Crypt::decrypt($no_mutasi);
        $data['mutasi'] = Mutasigudangcabang::select('no_mutasi', 'tanggal', 'gudang_cabang_jenis_mutasi.jenis_mutasi')
            ->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi')
            ->where('no_mutasi', $no_mutasi)
            ->first();
        $data['detail'] = Detailmutasigudangcabang::select('gudang_cabang_mutasi_detail.kode_produk', 'nama_produk', 'jumlah', 'isi_pcs_dus', 'isi_pcs_pack')
            ->join('produk', 'gudang_cabang_mutasi_detail.kode_produk', '=', 'produk.kode_produk')
            ->where('no_mutasi', $no_mutasi)
            ->get();
        return view('gudangcabang.mutasidpb.show', $data);
    }
    public function getmutasidpb($no_dpb, $jenis_mutasi)
    {
        // $no_dpb = Crypt::decrypt($no_dpb);


        $query = Mutasigudangcabang::query();
        $query->select('no_mutasi', 'tanggal', 'gudang_cabang_jenis_mutasi.jenis_mutasi');
        if ($jenis_mutasi != 'null') {
            $query->where('gudang_cabang_mutasi.jenis_mutasi', $jenis_mutasi);
        }
        $query->join('gudang_cabang_jenis_mutasi', 'gudang_cabang_mutasi.jenis_mutasi', '=', 'gudang_cabang_jenis_mutasi.kode_jenis_mutasi');
        $query->where('no_dpb', $no_dpb);
        $query->orderBy('tanggal');
        // $query->orderBy('order');
        $data['mutasi'] = $query->get();
        return view('gudangcabang.mutasidpb.getmutasidpb', $data);
    }

    public function destroy(Request $request)
    {
        $no_mutasi = Crypt::decrypt($request->no_mutasi);
        $mutasi = Mutasigudangcabang::where('no_mutasi', $no_mutasi)->first();
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($mutasi->tanggal, "gudangcabang");
            if ($cektutuplaporan > 0) {
                return "warning|Periode Laporan Sudah Ditutup|Oops";
            }
            Mutasigudangcabang::where('no_mutasi', $no_mutasi)->delete();
            DB::commit();
            return "success|Data Berhasil Dihapus|Berhasil";
        } catch (\Exception $e) {
            DB::rollBack();
            return "error|" . $e->getMessage() . "|Error";
        }
    }
}
