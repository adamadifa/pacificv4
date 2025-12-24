<?php

namespace App\Http\Controllers;

use App\Models\Barangmasukgudanglogistik;
use App\Models\Barangmasukmaintenance;
use App\Models\Barangpembelian;
use App\Models\Cabang;
use App\Models\Coa;
use App\Models\Coadepartemen;
use App\Models\Costratio;
use App\Models\Detailbarangmasukgudanglogistik;
use App\Models\Detailbarangmasukmaintenance;
use App\Models\Detailkontrabonpembelian;
use App\Models\Detailpembelian;
use App\Models\Detailpo;
use App\Models\Kontrabonpembelian;
use App\Models\PO;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Yajra\DataTables\Facades\DataTables;

class POController extends Controller
{
    public function index(Request $request)
    {

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $pmb = new PO();
        $po = $pmb->getPO(request: $request)->paginate(15);
        $po->appends(request()->all());
        $data['pembelian'] = $po;

        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $cbg = new Cabang();
        $cabang = $cbg->getCabang();
        $data['cabang'] = $cabang;
        return view('pembelian.po.index', $data);
    }


    public function show($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pmb = new PO();
        $data['po'] = $pmb->getPO(no_bukti: $no_bukti)->first();

        $data['detail'] = Detailpo::select('po_detail.*', 'nama_barang')
            ->join('pembelian_barang', 'po_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $no_bukti)
            ->get();
        return view('pembelian.po.show', $data);
    }


    public function create()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('pembelian.po.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'kode_supplier' => 'required',
        ]);

        $kode_barang = $request->kode_barang_item;
        $jumlah = $request->jumlah_item;
        $harga = $request->harga_item;
        $keterangan = $request->keterangan_item;
        DB::beginTransaction();
        try {
            if (count($kode_barang) == 0) {
                return Redirect::back()->with(messageError('Detail Pembelian Masih Kosong'));
            }

            $total_pembelian = 0;
            for ($i = 0; $i < count($kode_barang); $i++) {
                $subtotal = toNumber($jumlah[$i]) * toNumber($harga[$i]);
                $total_pembelian += $subtotal;
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'harga' => toNumber($harga[$i]),
                    'keterangan' => $keterangan[$i],
                ];
            }

            PO::create([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_supplier' => $request->kode_supplier,
                'kategori_perusahaan' => $request->kategori_perusahaan,
                'id_user' => auth()->user()->id
            ]);


            Detailpo::insert($detail);

            //Jika Ada Data Pembleian Yang Masuk Kategori Cost Ratio

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function destroy($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        DB::beginTransaction();
        try {
            PO::where('no_bukti', $no_bukti)->delete();
            Detailpo::where('no_bukti', $no_bukti)->delete();
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($no_bukti)
    {
        $no_bukti = Crypt::decrypt($no_bukti);
        $pmb = new PO();
        $data['po'] = $pmb->getPO(no_bukti: $no_bukti)->first();

        $data['detail'] = Detailpo::select('po_detail.*', 'nama_barang')
            ->join('pembelian_barang', 'po_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('po_detail.no_bukti', $no_bukti)
            ->select('po_detail.*', 'pembelian_barang.nama_barang')
            ->get();

        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        //dd($data['cekhistoribayar']);

        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('pembelian.po.edit', $data);
    }


    public function editbarang(Request $request)
    {
        $databarang = $request->databarang;
        $data['databarang'] = $databarang;

        $data['barang'] = Barangpembelian::where('kode_barang', $databarang['kode_barang'])->first();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        return view('pembelian.po.editbarang', $data);
    }


    public function update($no_bukti, Request $request)
    {
        $request->validate([
            'no_bukti' => 'required',
            'tanggal' => 'required',
            'kode_supplier' => 'required',
        ]);

        $no_bukti = Crypt::decrypt($no_bukti);
        $kode_barang = $request->kode_barang_item;
        $jumlah = $request->jumlah_item;
        $harga = $request->harga_item;
        $keterangan = $request->keterangan_item;
        //dd($keterangan_potongan);
        //dd($harga);
        DB::beginTransaction();
        try {

            if (count($kode_barang) == 0) {
                return Redirect::back()->with(messageError('Detail Pembelian Masih Kosong'));
            }


            for ($i = 0; $i < count($kode_barang); $i++) {
                $detail[] = [
                    'no_bukti' => $request->no_bukti,
                    'kode_barang' => $kode_barang[$i],
                    'jumlah' => toNumber($jumlah[$i]),
                    'harga' => toNumber($harga[$i]),
                    'keterangan' => toNumber($keterangan[$i]),
                ];
            }


            //Update Data pembelian
            PO::where('no_bukti', $no_bukti)->update([
                'no_bukti' => $request->no_bukti,
                'tanggal' => $request->tanggal,
                'kode_supplier' => $request->kode_supplier,
                'kategori_perusahaan' => $request->kategori_perusahaan,
                'id_user' => auth()->user()->id
            ]);


            //Hapus Detail Pembelian Sebelumnya
            Detailpo::where('no_bukti', $request->no_bukti)->delete();

            Detailpo::insert($detail);

            DB::commit();
            return redirect(route('po.edit', Crypt::encrypt($request->no_bukti)))->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            //dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function getPObysupplier($kode_supplier)
    {
        $pmb = new PO();
        $po = $pmb->getPO(kode_supplier: $kode_supplier)->get();
        echo "<option value=''>No. Bukti Pembelian</option>";
        foreach ($po as $d) {
            echo "<option value='$d->no_bukti'>" . $d->no_bukti . " (" . date('d-m-y', strtotime($d->tanggal)) . " ) </option>";
        }
    }


    public function getbarangpembelian(Request $request)
    {
        $detail = Detailpo::join('pembelian_barang', 'po_detail.kode_barang', '=', 'pembelian_barang.kode_barang')
            ->where('no_bukti', $request->no_bukti)->get();
        echo "<option value=''>Pilih Barang</option>";
        foreach ($detail as $d) {
            echo "<option value='$d->kode_barang'>" . $d->nama_barang . "</option>";
        }
    }


}
