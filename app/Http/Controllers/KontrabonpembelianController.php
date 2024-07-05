<?php

namespace App\Http\Controllers;

use App\Models\Detailkontrabonpembelian;
use App\Models\Kontrabonpembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KontrabonpembelianController extends Controller
{
    public function index(Request $request)
    {
        $kb = new Kontrabonpembelian();
        $kontrabon = $kb->getKontrabonpembelian(request: $request)->paginate(15);
        $kontrabon->appends(request()->all());
        $data['kontrabon'] = $kontrabon;
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        return view('pembelian.kontrabon.index', $data);
    }

    public function create()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        return view('pembelian.kontrabon.create', $data);
    }

    public function show($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonpembelian();
        $data['kontrabon'] = $kb->getKontrabonpembelian(no_kontrabon: $no_kontrabon)->first();
        $data['detail'] = Detailkontrabonpembelian::where('no_kontrabon', $no_kontrabon)
            ->join('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->get();
        return view('pembelian.kontrabon.show', $data);
    }

    public function cetak($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonpembelian();
        $data['kontrabon'] = $kb->getKontrabonpembelian(no_kontrabon: $no_kontrabon)->first();
        $data['detail'] = Detailkontrabonpembelian::select('pembelian_kontrabon_detail.*', 'pembelian.tanggal as tgl_pembelian', 'nama_barang', 'pembelian_detail.jumlah as qty', 'harga', 'penyesuaian')
            ->join('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->join('pembelian_detail', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian_detail.no_bukti')
            ->join('pembelian_barang', 'pembelian_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('pembelian_kontrabon_detail.no_kontrabon', $no_kontrabon)->get();
        return view('pembelian.kontrabon.cetak', $data);
    }

    public function approve($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        try {
            Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)->update(['status' => 1]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }

    public function cancel($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        try {
            Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)->update(['status' => 0]);
            return Redirect::back()->with(messageSuccess('Data Berhasil Disetujui'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
            //throw $th;
        }
    }

    public function destroy($no_kontrabon)
    {
        DB::beginTransaction();
        try {
            $no_kontrabon = Crypt::decrypt($no_kontrabon);
            $kontrabonpembelian = Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)->firstOrFail();
            if ($kontrabonpembelian->status == 0) {
                $kontrabonpembelian->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
