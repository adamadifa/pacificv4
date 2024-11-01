<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Kontrabonangkutan extends Model
{
    use HasFactory;

    protected $table = "gudang_jadi_angkutan_kontrabon";
    protected $primaryKey = "no_kontrabon";
    protected $guarded = [];
    public $incrementing = false;

    function getKontrabonangkutan($no_kontrabon = "", Request $request = null)
    {
        $start_date = config('global.start_date');
        $end_date = config('global.end_date');
        $query = Kontrabonangkutan::query();
        $query->select(
            'gudang_jadi_angkutan_kontrabon.*',
            'nama_angkutan',
            'keuangan_ledger.tanggal as tanggal_bayar',
            'ledgerhutang.tanggal as tanggal_bayar_hutang'
        );
        $query->leftJoin('keuangan_ledger_kontrabonangkutan', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan.no_kontrabon');
        $query->leftJoin('keuangan_ledger', 'keuangan_ledger_kontrabonangkutan.no_bukti', '=', 'keuangan_ledger.no_bukti');
        $query->leftJoin('keuangan_ledger_kontrabonangkutan_hutang', 'gudang_jadi_angkutan_kontrabon.no_kontrabon', '=', 'keuangan_ledger_kontrabonangkutan_hutang.no_kontrabon');
        $query->leftJoin('keuangan_ledger as ledgerhutang', 'keuangan_ledger_kontrabonangkutan_hutang.no_bukti', '=', 'ledgerhutang.no_bukti');
        $query->join('angkutan', 'gudang_jadi_angkutan_kontrabon.kode_angkutan', '=', 'angkutan.kode_angkutan');
        if (!empty($request)) {
            if (!empty($request->dari) && !empty($request->sampai)) {
                $query->whereBetween('gudang_jadi_angkutan_kontrabon.tanggal', [$request->dari, $request->sampai]);
            } else {
                $query->whereBetween('gudang_jadi_angkutan_kontrabon.tanggal', [$start_date, $end_date]);
            }

            if (!empty($request->kode_angkutan_search)) {
                $query->where('gudang_jadi_angkutan_kontrabon.kode_angkutan', $request->kode_angkutan_search);
            }

            if (!empty($request->status_search)) {
                if ($request->status_search == 'SP') {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNotNull('keuangan_ledger.tanggal')->orWhereNotNull('ledgerhutang.tanggal');
                    });
                } else {
                    $query->whereNull('keuangan_ledger.tanggal');
                    $query->whereNull('ledgerhutang.tanggal');
                }
            }
        }

        if (!empty($no_kontrabon)) {
            $query->where('gudang_jadi_angkutan_kontrabon.no_kontrabon', $no_kontrabon);
        }

        $query->orderBy('gudang_jadi_angkutan_kontrabon.tanggal', 'desc');
        return $query;
    }
}
