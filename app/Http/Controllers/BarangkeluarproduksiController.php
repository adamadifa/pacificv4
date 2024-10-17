<?php

namespace App\Http\Controllers;

use App\Models\Barangkeluarproduksi;
use App\Models\Barangproduksi;
use App\Models\Detailbarangkeluarproduksi;
use App\Models\Detailbarangkeluarproduksiedit;
use App\Models\Detailbarangkeluarproduksitemp;
use App\Models\Detailbarangmasukproduksi;
use App\Models\Detailbarangmasukproduksitemp;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class BarangkeluarproduksiController extends Controller
{
    public function index(Request $request)
    {
        $start_year = config('global.start_year');
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangkeluarproduksi::query();
        $query->leftjoin('supplier', 'produksi_barang_keluar.kode_supplier', '=', 'supplier.kode_supplier');
        $query->orderBy('tanggal', 'desc');
        $query->orderBy('produksi_barang_keluar.created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_jenis_pengeluaran_search)) {
            $query->where('kode_jenis_pengeluaran', $request->kode_jenis_pengeluaran_search);
        }
        $barangkeluar = $query->simplePaginate(20);
        $barangkeluar->appends(request()->all());


        $jenis_pengeluaran = config('produksi.jenis_pengeluaran');
        return view('produksi.barangkeluar.index', compact('barangkeluar', 'jenis_pengeluaran'));
    }

    public function create()
    {
        $barangproduksi = Barangproduksi::where('status_aktif_barang', 1)->orderBy('kode_barang_produksi')->get();
        $supplier = Supplier::orderBy('nama_supplier')->get();
        return view('produksi.barangkeluar.create', compact('barangproduksi', 'supplier'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'tanggal' => 'required',
            'kode_jenis_pengeluaran' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp = Detailbarangkeluarproduksitemp::join('produksi_barang', 'produksi_barang_keluar_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
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

            $barangkeluarproduksi = Barangkeluarproduksi::whereRaw('MID(no_bukti,6,4)=' . $blnthn)
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $barangkeluarproduksi != null ? $barangkeluarproduksi->no_bukti : '';
            $format = "PRDK/" . $bulan . $thn . "/";
            $no_bukti = buatkode($last_no_bukti, $format, 3);

            $detailtemp = $temp->get();
            foreach ($detailtemp as $d) {
                $detail[] = [
                    'no_bukti' => $no_bukti,
                    'kode_barang_produksi' => $d->kode_barang_produksi,
                    'keterangan' => $d->keterangan,
                    'jumlah' => $d->jumlah,
                    'jumlah_berat' => $d->jumlah_berat
                ];
            }

            Barangkeluarproduksi::create([
                'no_bukti' => $no_bukti,
                'tanggal' => $request->tanggal,
                'kode_jenis_pengeluaran' => $request->kode_jenis_pengeluaran,
                'kode_supplier' => $request->kode_jenis_pengeluaran == "RO" ? $request->kode_supplier : NULL,
            ]);

            Detailbarangkeluarproduksi::insert($detail);

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

        DB::beginTransaction();
        try {
            $barangkeluarproduksi = Barangkeluarproduksi::where('no_bukti', $no_bukti)->first();
            $detail = Detailbarangkeluarproduksi::where('no_bukti', $no_bukti)
                ->join('produksi_barang', 'produksi_barang_keluar_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
                ->get();
            foreach ($detail as $d) {
                $detailedit[] = [
                    'id' => Str::uuid(),
                    'no_bukti' => $no_bukti,
                    'kode_barang_produksi' => $d->kode_barang_produksi,
                    'keterangan' => $d->keterangan,
                    'jumlah' => $d->jumlah,
                    'jumlah_berat' => $d->jumlah_berat,
                    'id_user' => auth()->user()->id
                ];
            }

            Detailbarangkeluarproduksiedit::where('no_bukti', $no_bukti)->where('id_user', auth()->user()->id)->delete();
            Detailbarangkeluarproduksiedit::insert($detailedit);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Data Tidak Bisa Di Edit' . $e->getMessage()));
        }
        $barangproduksi = Barangproduksi::where('status_aktif_barang', 1)->orderBy('kode_barang_produksi')->get();
        $supplier = Supplier::orderBy('nama_supplier')->get();
        return view('produksi.barangkeluar.edit', compact('barangkeluarproduksi', 'supplier', 'barangproduksi'));
    }

    public function update($no_bukti, Request $request)
    {
        $no_bukti = Crypt::decrypt($no_bukti);

        $request->validate([
            'tanggal' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $barangkeluarproduksi = Barangkeluarproduksi::where('no_bukti', $no_bukti)->first();
            $cektutuplaporan = cektutupLaporan($barangkeluarproduksi->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }

            $temp_edit = Detailbarangkeluarproduksiedit::join('produksi_barang', 'produksi_barang_keluar_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
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
                    'jumlah' => $d->jumlah,
                    'jumlah_berat' => $d->jumlah_berat
                ];
            }

            Barangkeluarproduksi::where('no_bukti', $no_bukti)->update([
                'tanggal' => $request->tanggal,
            ]);

            Detailbarangkeluarproduksi::where('no_bukti', $no_bukti)->delete();
            Detailbarangkeluarproduksi::insert($detail);
            $temp_edit->delete();
            DB::commit();
            return redirect(route('barangkeluarproduksi.index'))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect(route('barangkeluarproduksi.index'))->with(messageError($e->getMessage()));
        }
    }

    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangkeluarproduksi = Barangkeluarproduksi::where('no_bukti', $no_bukti)->first();
        $detail = Detailbarangkeluarproduksi::where('no_bukti', $no_bukti)
            ->join('produksi_barang', 'produksi_barang_keluar_detail.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_keluar_detail.kode_barang_produksi')
            ->get();
        $jenis_pengeluaran = config('produksi.jenis_pengeluaran');
        return view('produksi.barangkeluar.show', compact('barangkeluarproduksi', 'jenis_pengeluaran', 'detail'));
    }

    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $barangkeluarproduksi = Barangkeluarproduksi::where('no_bukti', $no_bukti)->first();
        try {
            $cektutuplaporan = cektutupLaporan($barangkeluarproduksi->tanggal, "produksi");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup !'));
            }
            Barangkeluarproduksi::where('no_bukti', $no_bukti)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    //AJAX REQUEST
    public function cekdetailtemp(Request $request)
    {
        $cek = Detailbarangkeluarproduksitemp::where('id_user', auth()->user()->id)
            ->join('produksi_barang', 'produksi_barang_keluar_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->count();
        return $cek;
    }


    public function getdetailtemp()
    {
        $detailtemp = Detailbarangkeluarproduksitemp::where('id_user', auth()->user()->id)
            ->join('produksi_barang', 'produksi_barang_keluar_detail_temp.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_keluar_detail_temp.kode_barang_produksi')
            ->get();
        return view('produksi.barangkeluar.getdetailtemp', compact('detailtemp'));
    }

    public function deletetemp(Request $request)
    {
        try {
            Detailbarangkeluarproduksitemp::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Dihapus ' . $e->getMessage()], 400);
        }
    }

    public function storedetailtemp(Request $request)
    {

        $cek = Detailbarangkeluarproduksitemp::where('kode_barang_produksi', $request->kode_barang_produksi)
            ->where('id_user', auth()->user()->id)->count();
        if ($cek > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
        } else {
            try {
                Detailbarangkeluarproduksitemp::create([
                    'kode_barang_produksi' => $request->kode_barang_produksi,
                    'keterangan' => $request->keterangan,
                    'jumlah' => toNumber($request->jumlah),
                    'jumlah_berat' => empty($request->jumlah_berat) ? 0 : toNumber($request->jumlah_berat),
                    'id_user' => auth()->user()->id,
                ]);

                return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }
    }


    public function cekdetailedit(Request $request)
    {
        $cek = Detailbarangkeluarproduksiedit::where('no_bukti', $request->no_bukti)
            ->where('id_user', auth()->user()->id)
            ->join('produksi_barang', 'produksi_barang_keluar_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->count();
        return $cek;
    }

    public function getdetailedit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $detailtemp = Detailbarangkeluarproduksiedit::where('id_user', auth()->user()->id)
            ->where('no_bukti', $no_bukti)
            ->join('produksi_barang', 'produksi_barang_keluar_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->orderBy('produksi_barang_keluar_detail_edit.kode_barang_produksi')
            ->get();
        return view('produksi.barangkeluar.getdetailedit', compact('detailtemp'));
    }


    public function storedetailedit(Request $request)
    {

        $cek = Detailbarangkeluarproduksiedit::where('kode_barang_produksi', $request->kode_barang_produksi)
            ->where('id_user', auth()->user()->id)
            ->where('no_bukti', $request->no_bukti)
            ->count();
        if ($cek > 0) {
            return response()->json(['status' => 'error', 'message' => 'Data Sudah Ada'], 400);
        } else {
            try {
                Detailbarangkeluarproduksiedit::create([
                    'no_bukti' => $request->no_bukti,
                    'kode_barang_produksi' => $request->kode_barang_produksi,
                    'keterangan' => $request->keterangan,
                    'jumlah' => toNumber($request->jumlah),
                    'jumlah_berat' => empty($request->jumlah_berat) ? 0 : toNumber($request->jumlah_berat),
                    'id_user' => auth()->user()->id,
                ]);

                return "success|Data Berhasil Disimpan";
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }
    }

    public function editbarang($id)
    {
        $id = Crypt::decrypt($id);
        $detail = Detailbarangkeluarproduksiedit::where('id', $id)
            ->join('produksi_barang', 'produksi_barang_keluar_detail_edit.kode_barang_produksi', '=', 'produksi_barang.kode_barang_produksi')
            ->first();
        return view('produksi.barangkeluar.editbarang', compact('detail'));
    }

    public function updatebarang(Request $request)
    {
        $request->validate([
            'jumlah' => 'required'
        ]);
        try {
            Detailbarangkeluarproduksiedit::where('id', $request->id)->update([
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'jumlah_berat' => empty($request->jumlah_berat) ? 0 : toNumber($request->jumlah_berat),
            ]);
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Disimpan ' . $e->getMessage()], 400);
        }
    }
    public function deleteedit(Request $request)
    {
        try {
            Detailbarangkeluarproduksiedit::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data Berhasil Dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data Gagal Dihapus ' . $e->getMessage()], 400);
        }
    }
}
