<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Coa;
use App\Models\Ledger;
use App\Models\Saldoawalledger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LaporankeuanganController extends Controller
{
    public function index()
    {
        $b = new Bank();
        $data['bank'] = $b->getBank()->get();
        $data['coa'] = Coa::orderby('kode_akun')->get();
        return view('keuangan.laporan.index', $data);
    }

    public function cetakledger(Request $request)
    {

        if (lockreport($request->dari) == "error") {
            return Redirect::back()->with(messageError('Data Tidak Ditemukan'));
        }
        $data['dari'] = $request->dari;
        $data['sampai'] = $request->sampai;
        $data['bank'] = Bank::where('kode_bank', $request->kode_bank_ledger)->first();
        if ($request->formatlaporan == '1') {
            $query = Ledger::query();
            $query->select(
                'keuangan_ledger.*',
                'nama_akun',
                'nama_bank',
                'bank.no_rekening',
                'hrd_jabatan.kategori',
                DB::raw('IFNULL(marketing_penjualan_transfer.tanggal,marketing_penjualan_giro.tanggal) as tanggal_penerimaan')
            );
            $query->join('coa', 'keuangan_ledger.kode_akun', '=', 'coa.kode_akun');
            $query->join('bank', 'keuangan_ledger.kode_bank', '=', 'bank.kode_bank');
            //PJP
            $query->leftJoin('keuangan_ledger_pjp', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_pjp.no_bukti');
            $query->leftJoin('keuangan_pjp', 'keuangan_ledger_pjp.no_pinjaman', '=', 'keuangan_pjp.no_pinjaman');
            $query->leftJoin('hrd_karyawan', 'keuangan_pjp.nik', '=', 'hrd_karyawan.nik');
            $query->leftJoin('hrd_jabatan', 'hrd_karyawan.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan');

            //Transfer
            $query->leftJoin('keuangan_ledger_transfer', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_transfer.no_bukti');
            $query->leftJoin('marketing_penjualan_transfer', 'keuangan_ledger_transfer.kode_transfer', '=', 'marketing_penjualan_transfer.kode_transfer');

            //Giro
            $query->leftJoin('keuangan_ledger_giro', 'keuangan_ledger.no_bukti', '=', 'keuangan_ledger_giro.no_bukti');
            $query->leftJoin('marketing_penjualan_giro', 'keuangan_ledger_giro.kode_giro', '=', 'marketing_penjualan_giro.kode_giro');

            $query->orderBy('keuangan_ledger.tanggal');
            $query->orderBy('keuangan_ledger.created_at');
            $query->whereBetween('keuangan_ledger.tanggal', [$request->dari, $request->sampai]);
            if ($request->kode_bank_ledger != "") {
                $query->where('keuangan_ledger.kode_bank', $request->kode_bank_ledger);
            }
            if (!empty($request->kode_akun_dari) && !empty($request->kode_akun_sampai)) {
                $query->whereBetween('keuangan_ledger.kode_akun', [$request->kode_akun_dari, $request->kode_akun_sampai]);
            }
            $data['ledger'] = $query->get();

            $data['saldo_awal'] = Saldoawalledger::where('bulan', date('m', strtotime($request->dari)))
                ->where('tahun', date('Y', strtotime($request->dari)))
                ->where('kode_bank', $request->kode_bank_ledger)
                ->first();

            return view('keuangan.laporan.ledger_cetak', $data);
        }
    }
}
