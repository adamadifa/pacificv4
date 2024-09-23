<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Cabang;
use App\Models\Detailkontrabonpembelian;
use App\Models\Historibayarpembelian;
use App\Models\Kaskecil;
use App\Models\Kaskecilkontrabon;
use App\Models\Kontrabonpembelian;
use App\Models\Ledger;
use App\Models\Ledgerkontrabon;
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
        if (request()->is('kontrabonpembelian')) {
            return view('pembelian.kontrabon.index', $data);
        } else if (request()->is('kontrabonkeuangan/pembelian')) {
            return view('keuangan.kontrabon.pembelian', $data);
        }
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

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_supplier' => 'required',
            'kategori' => 'required',
            'jenis_bayar' => 'required',
        ]);

        $no_bukti = $request->no_bukti_item;
        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        DB::beginTransaction();
        try {

            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if (empty($no_bukti)) {
                return Redirect::back()->with(messageError('Detail Kontrabon Masih Kosong'));
            }


            //Generate No. Kontrabon
            $dari = date('Y-m', strtotime($request->tanggal)) . "-01";
            $sampai = date('Y-m-t', strtotime($dari));
            $lastkontrabon = Kontrabonpembelian::select('no_kontrabon')
                ->whereBetween('tanggal', [$dari, $sampai])
                ->where('kategori', $request->kategori)
                ->orderBy('no_kontrabon', 'desc')
                ->first();
            $lastnokontrabon = $lastkontrabon != null ? $lastkontrabon->no_kontrabon : '';
            $no_kontrabon = buatkode($lastnokontrabon, $request->kategori, 3) . "/" . date('m', strtotime($request->tanggal)) . "/" . date('Y', strtotime($request->tanggal));


            for ($i = 0; $i < count($no_bukti); $i++) {
                $detail[] = [
                    'no_kontrabon' => $no_kontrabon,
                    'no_bukti' => $no_bukti[$i],
                    'keterangan' => $keterangan[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }

            Kontrabonpembelian::create([
                'no_kontrabon' => $no_kontrabon,
                'tanggal' => $request->tanggal,
                'kategori' => $request->kategori,
                'kode_supplier' => $request->kode_supplier,
                'jenis_bayar' => $request->jenis_bayar,
                'no_dokumen' => $request->no_dokumen,
                'status' => 0,
                'id_user' => auth()->user()->id
            ]);

            Detailkontrabonpembelian::insert($detail);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            //dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
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


    public function edit($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonpembelian();
        $data['kontrabon'] = $kb->getKontrabonpembelian(no_kontrabon: $no_kontrabon)->first();
        $data['detail'] = Detailkontrabonpembelian::where('no_kontrabon', $no_kontrabon)
            ->join('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->get();
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        return view('pembelian.kontrabon.edit', $data);
    }


    public function update(Request $request, $no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'jenis_bayar' => 'required',
        ]);

        $no_bukti = $request->no_bukti_item;
        $keterangan = $request->keterangan_item;
        $jumlah = $request->jumlah_item;
        DB::beginTransaction();
        try {

            $kontrabon = Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)->first();

            $cektutuplaporankontrabon = cektutupLaporan($kontrabon->tanggal, "pembelian");
            if ($cektutuplaporankontrabon > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }


            if (empty($no_bukti)) {
                return Redirect::back()->with(messageError('Detail Kontrabon Masih Kosong'));
            }


            //Generate No. Kontrabon
            //Jika Kategori Sebelumny Berbeda denagn Kategori Baru

            if ($kontrabon->kategori != $request->kategori) {
                $dari = date('Y-m', strtotime($request->tanggal)) . "-01";
                $sampai = date('Y-m-t', strtotime($dari));
                $lastkontrabon = Kontrabonpembelian::select('no_kontrabon')
                    ->whereBetween('tanggal', [$dari, $sampai])
                    ->where('kategori', $request->kategori)
                    ->orderBy('no_kontrabon', 'desc')
                    ->first();
                $lastnokontrabon = $lastkontrabon != null ? $lastkontrabon->no_kontrabon : '';
                $no_kontrabon_new = buatkode($lastnokontrabon, $request->kategori, 3) . "/" . date('m', strtotime($request->tanggal)) . "/" . date('Y', strtotime($request->tanggal));
            } else {
                $no_kontrabon_new = $no_kontrabon;
            }

            for ($i = 0; $i < count($no_bukti); $i++) {
                $detail[] = [
                    'no_kontrabon' => $no_kontrabon_new,
                    'no_bukti' => $no_bukti[$i],
                    'keterangan' => $keterangan[$i],
                    'jumlah' => toNumber($jumlah[$i])
                ];
            }

            //Hapus Detail Kontrabon Sebelumnya
            Detailkontrabonpembelian::where('no_kontrabon', $no_kontrabon)->delete();


            Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)->update([
                'no_kontrabon' => $no_kontrabon_new,
                'tanggal' => $request->tanggal,
                'kategori' => $request->kategori,
                'jenis_bayar' => $request->jenis_bayar,
                'no_dokumen' => $request->no_dokumen,
                'status' => 0,
                'id_user' => auth()->user()->id
            ]);



            Detailkontrabonpembelian::insert($detail);

            DB::commit();
            return Redirect::route('kontrabonpmb.edit', ['no_kontrabon' => Crypt::encrypt($no_kontrabon_new)])->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function proses($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $kb = new Kontrabonpembelian();
        $data['kontrabon'] = $kb->getKontrabonpembelian(no_kontrabon: $no_kontrabon)->first();
        $data['detail'] = Detailkontrabonpembelian::where('no_kontrabon', $no_kontrabon)
            ->join('pembelian', 'pembelian_kontrabon_detail.no_bukti', '=', 'pembelian.no_bukti')
            ->get();
        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $bank = new Bank();
        $data['bank'] = $bank->getBank()->get();
        return view('pembelian.kontrabon.proses', $data);
    }

    public function storeproses(Request $request, $no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        $request->validate([
            'tanggal' => 'required',
            'kode_akun' => 'required',
            'keterangan' => 'required',
        ]);

        $tahun = date('y', strtotime($request->tanggal));
        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "ledger");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $kontrabon = Kontrabonpembelian::where('no_kontrabon', $no_kontrabon)
                ->join('supplier', 'pembelian_kontrabon.kode_supplier', '=', 'supplier.kode_supplier')
                ->first();

            $detailkontrabon = Detailkontrabonpembelian::where('no_kontrabon', $no_kontrabon)->get();
            $totalbayar = 0;
            foreach ($detailkontrabon as $d) {
                $totalbayar += $d->jumlah;
                $list_no_bukti[] = $d->no_bukti;
            }

            //Generate No. Bukti Ledger
            $lastledger = Ledger::select('no_bukti')
                ->whereRaw('LEFT(no_bukti,7) ="LRPST' . $tahun . '"')
                ->whereRaw('LENGTH(no_bukti)=12')
                ->orderBy('no_bukti', 'desc')
                ->first();
            $last_no_bukti = $lastledger != null ?  $lastledger->no_bukti : '';
            $no_bukti = buatkode($last_no_bukti, 'LRPST' . $tahun, 5);

            //Insert ke Histori Pembayara
            Historibayarpembelian::create([
                'no_kontrabon' => $no_kontrabon,
                'tanggal' => $request->tanggal,
                'jumlah' => $totalbayar,
                'kode_bank' => $request->kode_bank,
                'kode_cabang' => $request->kode_cabang,
                'id_user' => auth()->user()->id

            ]);

            if ($request->kode_bank == 'BK071') {
                $kaskecil = Kaskecil::create([
                    'no_bukti' => $request->no_bkk,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan . " " . implode(", ", $list_no_bukti),
                    'jumlah' => $totalbayar,
                    'debet_kredit' => 'D',
                    'kode_cabang' => $request->kode_cabang,
                    'kode_akun' => $request->kode_akun
                ]);

                Kaskecilkontrabon::create([
                    'id_kaskecil' => $kaskecil->id,
                    'no_kontrabon' => $no_kontrabon
                ]);
            } else {
                Ledger::create([
                    'no_bukti' => $no_bukti,
                    'tanggal' => $request->tanggal,
                    'pelanggan' => $kontrabon->nama_supplier,
                    'kode_bank' => $request->kode_bank,
                    'keterangan' => $request->keterangan . " " . implode(", ", $list_no_bukti),
                    'kode_akun' => $request->kode_akun,
                    'jumlah'  => $totalbayar,
                    'debet_kredit' => 'D',
                ]);

                Ledgerkontrabon::create([
                    'no_bukti' => $no_bukti,
                    'no_kontrabon' => $no_kontrabon
                ]);
            }

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function cancelproses($no_kontrabon)
    {
        $no_kontrabon = Crypt::decrypt($no_kontrabon);
        DB::beginTransaction();
        try {
            $kontrabon = Historibayarpembelian::where('no_kontrabon', $no_kontrabon)->first();
            // dd($kontrabon->tanggal);
            $cektutuplaporan = cektutupLaporan($kontrabon->tanggal, "ledger");
            // dd($cektutuplaporan);
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            if ($kontrabon->kode_bank == 'BK071') {
                $kaskecilkontrabon = Kaskecilkontrabon::where('no_kontrabon', $no_kontrabon)->first();
                Historibayarpembelian::where('no_kontrabon', $no_kontrabon)->delete();
                //Hapus Histori Pembayaran

                Kaskecil::where('id', $kaskecilkontrabon->id_kaskecil)->delete();
            } else {
                $ledgerkontrabon = Ledgerkontrabon::where('no_kontrabon', $no_kontrabon)->first();
                Historibayarpembelian::where('no_kontrabon', $no_kontrabon)->delete();
                //Cek no Bukti Ledger
                //Hapus Ledger
                Ledger::where('no_bukti', $ledgerkontrabon->no_bukti)->delete();
            }
            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil di Batalkan'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
