<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukgudanglogistik;
use App\Models\Barangpembelian;
use App\Models\Detailbarangmasukgudanglogistik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class BarangmasukgudanglogistikController extends Controller
{
    public function index(Request $request)
    {

        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        $query = Barangmasukgudanglogistik::query();
        $query->select('gudang_logistik_barang_masuk.*','pembelian.tanggal as tanggal_pembelian');
        $query->leftJoin('pembelian','gudang_logistik_barang_masuk.no_bukti','=','pembelian.no_bukti');
        $query->orderBy('gudang_logistik_barang_masuk.tanggal', 'desc');
        $query->orderBy('gudang_logistik_barang_masuk.created_at', 'desc');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('gudang_logistik_barang_masuk.tanggal', [$request->dari, $request->sampai]);
        } else {
            $query->whereBetween('gudang_logistik_barang_masuk.tanggal', [$start_date, $end_date]);
        }

        if (!empty($request->no_bukti_search)) {
            $query->where('no_bukti', $request->no_bukti_search);
        }

        if (!empty($request->kode_asal_barang_search)) {
            $query->where('kode_asal_barang', $request->kode_asal_barang_search);
        }

        $barangmasuk = $query->simplePaginate(20);
        $barangmasuk->appends(request()->all());
        $data['barangmasuk'] = $barangmasuk;
        return view('gudanglogistik.barangmasuk.index', $data);
    }

    public function create(){
        $data['barang'] = Barangpembelian::where('kode_group', 'GDL')->get();
        return view('gudanglogistik.barangmasuk.create',$data);
    }
    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $data['barangmasuk'] = Barangmasukgudanglogistik::where('gudang_logistik_barang_masuk.no_bukti', $no_bukti)
        ->select('gudang_logistik_barang_masuk.*', 'pembelian.kode_supplier', 'nama_supplier')
        ->leftJoin('pembelian', 'gudang_logistik_barang_masuk.no_bukti', '=', 'pembelian.no_bukti')
        ->leftJoin('supplier', 'pembelian.kode_supplier', '=', 'supplier.kode_supplier')
        ->first();
        $data['detail'] = Detailbarangmasukgudanglogistik::join('pembelian_barang', 'gudang_logistik_barang_masuk_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();

        return view('gudanglogistik.barangmasuk.show', $data);
    }

}
