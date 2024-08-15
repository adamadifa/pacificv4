<?php

namespace App\Livewire;

use App\Models\Retur;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Returpelanggan extends Component
{
    public $kode_pelanggan;
    public $nofaktur_search;
    public function render()
    {

        $data = Retur::select(
            'marketing_retur.no_retur',
            'marketing_retur.tanggal',
            'marketing_retur.no_faktur',
            'marketing_penjualan.kode_pelanggan',
            'jenis_retur'
        )
            ->addSelect(DB::raw('(SELECT SUM(subtotal) FROM marketing_retur_detail WHERE no_retur = marketing_retur.no_retur) as total_retur'))
            ->join('marketing_penjualan', 'marketing_retur.no_faktur', '=', 'marketing_penjualan.no_faktur')
            ->when($this->kode_pelanggan, function ($query) {
                $query->where('marketing_penjualan.kode_pelanggan', $this->kode_pelanggan);
            })
            ->when($this->nofaktur_search, function ($query) {
                $query->where('marketing_penjualan.no_faktur', 'like', '%' . $this->nofaktur_search . '%');
            })
            ->orderBy('marketing_retur.tanggal', 'desc')
            ->orderBy('marketing_retur.no_retur', 'desc')
            ->paginate(3);

        return view('livewire.returpelanggan', ['dataretur' => $data]);
    }
}
