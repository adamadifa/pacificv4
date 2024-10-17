<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukproduksi;
use App\Models\Barangproduksi;
use App\Models\Detailbarangmasukproduksi;
use App\Models\Detailbarangmasukproduksiedit;
use App\Models\Detailbarangmasukproduksitemp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class BarangmasukproduksiController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangmasukproduksi::query();
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_asal_barang_search)) {
            $query->where('kode_asal_barang', $request->kode_asal_barang_search);
        }
        $barangmasuk = $query->simplePaginate(20);
        $barangmasuk->appends(request()->all());

        $asal_barang = config('produksi.asal_barang_produksi');
        return view('produksi.barangmasuk.index', compact('barangmasuk', 'asal_barang'));
    }


    public function create()
    {

        return view('produksi.barangmasuk.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'kode_asal_barang' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp = Detailbarangmasukproduksitemp::where('kode_asal_barang', $request->kode_asal_barang)
                ->join('produksi_barang', 'produksi_barang_masuk_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
                ->where('id_user', auth()->user()->id);


            $cekdetailtemp = $temp->count();
            if (empty($cekdetailtemp)) {
                return Redirect::back()->with(messageError('Data  Masih Kosong !'));
            }

            $tanggal = explode("-", $request->tanggal);
            $bulan = $tanggal[1];
            $tahun = $tanggal[0];
            $thn = substr($tahun, 2, 2);
            $blnthn = $bulan . $thn;

            $barangmasukproduksi = Barangmasukproduksi::whereRaw('MID(no_bukti,6,4)=' . $blnthn)
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $barangmasukproduksi != null ? $barangmasukproduksi->no_bukti : '';
            $format = "PRDM/" . $bulan . $thn . "/";
            $no_bukti = buatkode($last_no_bukti, $format, 3);

            $detailtemp = $temp->get();
            foreach ($detailtemp as $d) {
                $detail[] = [
                    'no_bukti' => $no_bukti,
                    'kode_barang_produksi' => $d->kode_barang_produksi,
                    'keterangan' => $d->keterangan,
                    'jumlah' => $d->jumlah
                ];
            }

            Barangmasukproduksi::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_asal_barang' => $request->kode_asal_barang
            ]);

            Detailbarangmasukproduksi::insert($detail);

            $temp->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangmasukproduksi = Barangmasukproduksi::where('no_bukti', $no_bukti)->first();
        $detail = Detailbarangmasukproduksi::where('no_bukti', $no_bukti)
            ->join('produksi_barang', 'produksi_barang_masuk_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->get();
        DB::beginTransaction();
        try {
            foreach ($detail as $d) {
                $detailedit[] = [
                    'id' => Str::uuid(),
                    'no_bukti' => $no_bukti,
                    'kode_barang_produksi' => $d->kode_barang_produksi,
                    'keterangan' => $d->keterangan,
                    'jumlah' => $d->jumlah,
                    'id_user' => auth()->user()->id
                ];
            }

            Detailbarangmasukproduksiedit::where('no_bukti', $no_bukti)->where('id_user', auth()->user()->id)->delete();
            Detailbarangmasukproduksiedit::insert($detailedit);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Data Tidak Bisa Di Edit' . $e->getMessage()));
        }
        return view('produksi.barangmasuk.edit', compact('barangmasukproduksi'));
    }


    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);

        $request->validate([
            'tanggal' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $barangmasukproduksi = Barangmasukproduksi::where('no_bukti', $no_bukti)->first();
            $cektutuplaporan = cektutupLaporan($barangmasukproduksi->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp_edit = Detailbarangmasukproduksiedit::join('produksi_barang', 'produksi_barang_masuk_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
                ->where('no_bukti', $no_bukti)
                ->where('id_user', auth()->user()->id);


            $cekdetailtemp = $temp_edit->count();
            if (empty($cekdetailtemp)) {
                return Redirect::back()->with(messageError('Data  Masih Kosong !'));
            }



            $detailtemp = $temp_edit->get();
            foreach ($detailtemp as $d) {
                $detail[] = [
                    'no_bukti' => $no_bukti,
                    'kode_barang_produksi' => $d->kode_barang_produksi,
                    'keterangan' => $d->keterangan,
                    'jumlah' => $d->jumlah
                ];
            }

            Barangmasukproduksi::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
            ]);

            Detailbarangmasukproduksi::where('no_bukti', $no_bukti)->delete();
            Detailbarangmasukproduksi::insert($detail);
            $temp_edit->delete();
            DB::commit();
            return redirect(route('barangmasukproduksi.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('barangmasukproduksi.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangmasukproduksi = Barangmasukproduksi::where('no_bukti', $no_bukti)->first();
        $detail = Detailbarangmasukproduksi::where('no_bukti', $no_bukti)
            ->join('produksi_barang', 'produksi_barang_masuk_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_masuk_detail.kode_barang_produksi')
            ->get();
        $asal_barang = config('produksi.asal_barang_produksi');
        return view('produksi.barangmasuk.show', compact('barangmasukproduksi', 'asal_barang', 'detail'));
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangmasukproduksi = Barangmasukproduksi::where('no_bukti', $no_bukti)->first();
        try {
            $cektutuplaporan = cektutupLaporan($barangmasukproduksi->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Barangmasukproduksi::where('no_bukti', $no_bukti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    //AJAX REQUEST
    public function storedetailtemp(Request $request)
    {

        $cek = Detailbarangmasukproduksitemp::where('kode_barang_produksi', $request->kode_barang_produksi)
            ->where('id_user', auth()->user()->id)->count();
        if ($cek > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
        } else {
            try {
                Detailbarangmasukproduksitemp::create([
                    'kode_barang_produksi' => $request->kode_barang_produksi,
                    'keterangan' => $request->keterangan,
                    'jumlah' => toNumber($request->jumlah),
                    'id_user' => auth()->user()->id,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }
    }

    public function getdetailtemp($kode_asal_barang)
    {
        $detailtemp = Detailbarangmasukproduksitemp::where('id_user', auth()->user()->id)
            ->where('kode_asal_barang', $kode_asal_barang)
            ->join('produksi_barang', 'produksi_barang_masuk_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_masuk_detail_temp.kode_barang_produksi')
            ->get();
        return view('produksi.barangmasuk.getdetailtemp', compact('detailtemp'));
    }

    public function getbarangbyasalbarang(Request $request)
    {
        $barangproduksi = Barangproduksi::where('status_aktif_barang', 1)
            ->where('kode_asal_barang', $request->kode_asal_barang)
            ->orderBy('kode_barang_produksi')->get();
        echo '<option value="">Pilih Barang</option>';
        foreach ($barangproduksi as $d) {
            echo "<option value='$d->kode_barang_produksi'>$d->kode_barang_produksi - $d->nama_barang</option>";
        }
    }

    public function cekdetailtemp(Request $request)
    {
        $cek = Detailbarangmasukproduksitemp::where('kode_asal_barang', $request->kode_asal_barang)
            ->where('id_user', auth()->user()->id)
            ->join('produksi_barang', 'produksi_barang_masuk_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->count();
        return $cek;
    }

    public function deletetemp(Request $request)
    {
        try {
            Detailbarangmasukproduksitemp::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }


    public function storedetailedit(Request $request)
    {

        $cek = Detailbarangmasukproduksiedit::where('kode_barang_produksi', $request->kode_barang_produksi)
            ->where('id_user', auth()->user()->id)
            ->where('no_bukti', $request->no_bukti)
            ->count();
        if ($cek > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
        } else {
            try {
                Detailbarangmasukproduksiedit::create([
                    'no_bukti' => $request->no_bukti,
                    'kode_barang_produksi' => $request->kode_barang_produksi,
                    'keterangan' => $request->keterangan,
                    'jumlah' => toNumber($request->jumlah),
                    'id_user' => auth()->user()->id,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }
    }
    public function getdetailedit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $detailtemp = Detailbarangmasukproduksiedit::where('id_user', auth()->user()->id)
            ->where('no_bukti', $no_bukti)
            ->join('produksi_barang', 'produksi_barang_masuk_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_masuk_detail_edit.kode_barang_produksi')
            ->get();
        return view('produksi.barangmasuk.getdetailedit', compact('detailtemp'));
    }


    public function cekdetailedit(Request $request)
    {
        $cek = Detailbarangmasukproduksiedit::where('no_bukti', $request->no_bukti)
            ->where('id_user', auth()->user()->id)
            ->join('produksi_barang', 'produksi_barang_masuk_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->count();
        return $cek;
    }


    public function editbarang($id)
    {
        $id = Crypt::decrypt($id);
        $detail = Detailbarangmasukproduksiedit::where('id', $id)
            ->join('produksi_barang', 'produksi_barang_masuk_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->first();
        return view('produksi.barangmasuk.editbarang', compact('detail'));
    }

    public function updatebarang(Request $request)
    {
        $request->validate([
            'jumlah' => 'required'
        ]);
        try {
            Detailbarangmasukproduksiedit::where('id', $request->id)->update([
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah)
            ]);
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Disimpan ' . $e->getMessage()], 400);
        }
    }
    public function deleteedit(Request $request)
    {
        try {
            Detailbarangmasukproduksiedit::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Dihapus ' . $e->getMessage()], 400);
        }
    }
}
