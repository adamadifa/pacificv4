<?php

namespace App\Livewire;

use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Penjualanpelanggan extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    // public $datapenjualan;
    public $kode_pelanggan;
    public $nofaktur_search;
    // public function mount($kode_pelanggan)
    // {
    //     $this->kode_pelanggan = $kode_pelanggan;
    // }
    public function render()
    {

        $data = Penjualan::select(
            'marketing_penjualan.no_faktur',
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
            ->when($this->kode_pelanggan, function ($query) {
                $query->where('marketing_penjualan.kode_pelanggan', $this->kode_pelanggan);
            })
            ->when($this->nofaktur_search, function ($query) {
                $query->where('no_faktur', 'like', '%' . $this->nofaktur_search . '%');
            })
            ->orderBy('marketing_penjualan.tanggal', 'desc')
            ->orderBy('marketing_penjualan.no_faktur', 'desc')
            ->paginate(3);
        return view('livewire.penjualanpelanggan', ['datapenjualan' => $data]);
    }
}
