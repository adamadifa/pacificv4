<?php

namespace App\Http\Controllers;

use App\Models\Coadepartemen;
use App\Models\Jurnalkoreksi;
use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class JurnalkoreksiController extends Controller
{
    public function index(Request $request)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');

        if (!empty($request->dari) && !empty($request->sampai)) {
            if (lockreport($request->dari) == "error") {
                return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
            }
        }

        $query = Jurnalkoreksi::query();
        $query->select('pembelian_jurnalkoreksi.*', 'nama_barang', 'nama_akun');
        $query->join('pembelian_barang', 'pembelian_jurnalkoreksi.kode_barang', '=', 'pembelian_barang.kode_barang');
        $query->join('coa', 'pembelian_jurnalkoreksi.kode_akun', '=', 'coa.kode_akun');
        $query->whereBetween('pembelian_jurnalkoreksi.tanggal', [$request->dari, $request->sampai]);
        if (!empty($request->no_bukti_search)) {
            $query->where('pembelian_jurnalkoreksi.no_bukti', $request->no_bukti_search);
        }
        $query->orderBy('pembelian_jurnalkoreksi.tanggal', 'desc');
        $query->orderBy('pembelian_jurnalkoreksi.no_bukti');
        $query->orderBy('pembelian_jurnalkoreksi.debet_kredit', 'desc');
        $jurnalkoreksi = $query->paginate(15);
        $jurnalkoreksi = $jurnalkoreksi->appends(request()->all());

        $data['jurnalkoreksi'] = $jurnalkoreksi;

        return view('pembelian.jurnalkoreksi.index', $data);
    }

    public function create()
    {
        $data['supplier'] = Supplier::orderBy('nama_supplier')->get();
        $data['coa'] = Coadepartemen::where('kode_dept', 'PMB')
            ->join('coa', 'coa_departemen.kode_akun', '=', 'coa.kode_akun')
            ->orderBy('coa_departemen.kode_akun')
            ->get();
        return view('pembelian.jurnalkoreksi.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kode_supplier' => 'required',
            'no_bukti' => 'required',
            'kode_barang' => 'required',
            'keterangan' => 'required',
            'jumlah' => 'required',
            'harga' => 'required',
            'kode_akun_debet' => 'required',
            'kode_akun_kredit' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $cektutuplaporan = cektutupLaporan($request->tanggal, "pembelian");
            if ($cektutuplaporan > 0) {
                return Redirect::back()->with(messageError('Periode Laporan Sudah Ditutup'));
            }

            $lastjurnalkoreksi = Jurnalkoreksi::select('kode_jurnalkoreksi')
                ->whereRaw('LEFT(kode_jurnalkoreksi,6)="JK' . date('ym', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_jurnalkoreksi', 'desc')
                ->first();
            $last_kode_jurnalkoreksi = $lastjurnalkoreksi != null ? $lastjurnalkoreksi->kode_jurnalkoreksi : '';

            $kode_jk_debet = buatkode($last_kode_jurnalkoreksi, 'JK' . date('y', strtotime($request->tanggal)) . date('m', strtotime($request->tanggal)), 3);
            $kode_jk_kredit = buatkode($kode_jk_debet, 'JK' . date('y', strtotime($request->tanggal)) . date('m', strtotime($request->tanggal)), 3);

            //Insert Debet
            Jurnalkoreksi::create([
                'kode_jurnalkoreksi' => $kode_jk_debet,
                'tanggal' => $request->tanggal,
                'no_bukti' => $request->no_bukti,
                'kode_barang' => $request->kode_barang,
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'harga' => toNumber($request->harga),
                'debet_kredit' => 'D',
                'kode_akun' => $request->kode_akun_debet
            ]);

            //Insert Kredit
            Jurnalkoreksi::create([
                'kode_jurnalkoreksi' => $kode_jk_kredit,
                'tanggal' => $request->tanggal,
                'no_bukti' => $request->no_bukti,
                'kode_barang' => $request->kode_barang,
                'keterangan' => $request->keterangan,
                'jumlah' => toNumber($request->jumlah),
                'harga' => toNumber($request->harga),
                'debet_kredit' => 'K',
                'kode_akun' => $request->kode_akun_kredit
            ]);

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_jurnalkoreksi)
    {
        DB::beginTransaction();
        try {
            $kode_jurnalkoreksi = Crypt::decrypt($kode_jurnalkoreksi);
            $jurnalkoreksi = Jurnalkoreksi::where('kode_jurnalkoreksi', $kode_jurnalkoreksi)->firstOrFail();
            $jurnalkoreksi->delete();

            DB::commit();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
