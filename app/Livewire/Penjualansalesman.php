<?php

namespace App\Livewire;

use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Penjualansalesman extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nofaktur_search;
    public $dari;
    public $sampai;
    public $nama_pelanggan;
    public function render()
    {
        $data = Penjualan::select(
            'marketing_penjualan.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'pelanggan.nama_pelanggan',
            'marketing_penjualan.tanggal',
            'marketing_penjualan.jenis_transaksi',
            'potongan',
            'potongan_istimewa',
            'penyesuaian',
            'ppn',
            'status',
            'status_batal'
        )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_penjualan_detail WHERE no_faktur = marketing_penjualan.no_faktur) as total_bruto'))
            ->join('pelanggan', 'marketing_penjualan.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->leftJoin(
                DB::raw("(
                    SELECT
                        marketing_penjualan.no_faktur,
                        IF( salesbaru IS NULL, marketing_penjualan.kode_salesman, salesbaru ) AS kode_salesman_baru,
                        IF( cabangbaru IS NULL, salesman.kode_cabang, cabangbaru ) AS kode_cabang_baru
                    FROM
                        marketing_penjualan
                    INNER JOIN salesman ON marketing_penjualan.kode_salesman = salesman.kode_salesman
                    LEFT JOIN (
                    SELECT
                        no_faktur,
                        marketing_penjualan_movefaktur.kode_salesman_baru AS salesbaru,
                        salesman.kode_cabang AS cabangbaru
                    FROM
                        marketing_penjualan_movefaktur
                        INNER JOIN salesman ON marketing_penjualan_movefaktur.kode_salesman_baru = salesman.kode_salesman
                    WHERE id IN (SELECT MAX(id) as id FROM marketing_penjualan_movefaktur GROUP BY no_faktur)
                    ) movefaktur ON ( marketing_penjualan.no_faktur = movefaktur.no_faktur)
                ) pindahfaktur"),
                function ($join) {
                    $join->on('marketing_penjualan.no_faktur', '=', 'pindahfaktur.no_faktur');
                }
            )
            ->when($this->nofaktur_search, function ($query) {
                $query->where('marketing_penjualan.no_faktur', $this->nofaktur_search);
            })
            ->when($this->nama_pelanggan, function ($query) {
                $query->where('pelanggan.nama_pelanggan', 'like', '%' . $this->nama_pelanggan . '%');
            })
            ->when($this->dari && $this->sampai, function ($query) {
                $query->whereBetween('marketing_penjualan.tanggal', [$this->dari, $this->sampai]);
            })
            ->when(!$this->dari && !$this->sampai, function ($query) {
                $query->whereBetween('marketing_penjualan.tanggal', [config('global.start_date'), config('global.end_date')]);
            })
            ->where('kode_salesman_baru', auth()->user()->kode_salesman)
            ->orderBy('marketing_penjualan.tanggal', 'desc')
            ->limit(10)
            ->get();
        return view('livewire.penjualansalesman', ['datapenjualan' => $data]);
    }
}
