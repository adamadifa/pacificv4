<?php

namespace App\Http\Controllers;

use App\Models\Movefaktur;
use App\Models\Penjualan;
use App\Models\Salesman;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class MovefakturController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:movefaktur.index', ['only' => ['index', 'show']]);
        $this->middleware('permission:movefaktur.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:movefaktur.delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Movefaktur::query();
        $query->select('marketing_penjualan_movefaktur.*', 'marketing_penjualan.tanggal as tanggal_faktur', 'nama_pelanggan', 'sl.nama_salesman as nama_salesman_lama', 'sb.nama_salesman as nama_salesman_baru', 'cabang.nama_cabang');
        $query->join('marketing_penjualan', 'marketing_penjualan_movefaktur.no_faktur', '=', 'marketing_penjualan.no_faktur');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman as sl', 'marketing_penjualan_movefaktur.kode_salesman_lama', '=', 'sl.kode_salesman');
        $query->join('salesman as sb', 'marketing_penjualan_movefaktur.kode_salesman_baru', '=', 'sb.kode_salesman');
        $query->join('cabang', 'sl.kode_cabang', '=', 'cabang.kode_cabang');

        if (!empty($request->no_faktur_search)) {
            $query->where('marketing_penjualan_movefaktur.no_faktur', 'like', '%' . $request->no_faktur_search . '%');
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('sl.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->kode_salesman_lama_search)) {
            $query->where('marketing_penjualan_movefaktur.kode_salesman_lama', $request->kode_salesman_lama_search);
        }

        if (!empty($request->kode_salesman_baru_search)) {
            $query->where('marketing_penjualan_movefaktur.kode_salesman_baru', $request->kode_salesman_baru_search);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_penjualan_movefaktur.tanggal', [$request->dari, $request->sampai]);
        }

        if ($request->status_warning == '1') {
            $query->whereRaw('MONTH(marketing_penjualan_movefaktur.tanggal) = MONTH(marketing_penjualan.tanggal)')
                ->whereRaw('YEAR(marketing_penjualan_movefaktur.tanggal) = YEAR(marketing_penjualan.tanggal)');
        }

        $movefaktur = $query->orderBy('marketing_penjualan_movefaktur.tanggal', 'desc')->paginate(15);
        $movefaktur->appends($request->all());

        $cabang = Cabang::all();
        return view('marketing.movefaktur.index', compact('movefaktur', 'cabang'));
    }

    public function create()
    {
        $cabang = Cabang::all();
        return view('marketing.movefaktur.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_faktur' => 'required',
            'tanggal' => 'required',
            'kode_salesman_baru' => 'required',
        ]);

        try {
            $penjualan = Penjualan::where('no_faktur', $request->no_faktur)->first();
            if (!$penjualan) {
                return redirect()->back()->with(['error' => 'Data Penjualan Tidak Ditemukan']);
            }

            Movefaktur::create([
                'no_faktur' => $request->no_faktur,
                'tanggal' => $request->tanggal,
                'kode_salesman_lama' => $penjualan->kode_salesman,
                'kode_salesman_baru' => $request->kode_salesman_baru,
            ]);

            return redirect()->route('movefaktur.index')->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function getfakturajax(Request $request)
    {
        $search = $request->search;
        $query = Penjualan::query();
        $query->select('marketing_penjualan.no_faktur', 'marketing_penjualan.tanggal', 'nama_pelanggan', 'nama_salesman');
        $query->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan');
        $query->join('salesman', 'marketing_penjualan.kode_salesman', '=', 'salesman.kode_salesman');
        $query->where('marketing_penjualan.no_faktur', 'like', '%' . $search . '%');
        $query->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
        $query->limit(20);
        $penjualan = $query->get();

        return view('marketing.movefaktur.getfakturajax', compact('penjualan'));
    }

    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            Movefaktur::where('id', $id)->delete();
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->ids;
        if (!$ids || count($ids) == 0) {
            return redirect()->back()->with(['error' => 'Pilih data yang akan dihapus']);
        }

        try {
            Movefaktur::whereIn('id', $ids)->delete();
            return redirect()->back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
