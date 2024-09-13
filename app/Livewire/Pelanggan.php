<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pelanggan as MPelanggan;

class Pelanggan extends Component
{
    public $datapelanggan;
    public $namapelanggan_search = '';
    public function render()
    {
        $this->datapelanggan = MPelanggan::join('wilayah', 'pelanggan.kode_wilayah', '=', 'wilayah.kode_wilayah')
            ->where('kode_salesman', auth()->user()->kode_salesman)
            ->when($this->namapelanggan_search, function ($query) {
                $query->where('nama_pelanggan', 'like', '%' . $this->namapelanggan_search . '%');
                $query->orwhere('kode_pelanggan', 'like', '%' . $this->namapelanggan_search . '%');
            })
            ->orderBy('tanggal_register', 'desc')
            ->limit(30)
            ->get();
        return view('livewire.pelanggan');
    }
}
