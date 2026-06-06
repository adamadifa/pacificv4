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
            ->where('pelanggan.kode_salesman', auth()->user()->kode_salesman)
            ->where('pelanggan.kode_cabang', auth()->user()->kode_cabang)
            ->when($this->namapelanggan_search, function ($query) {
                $query->where(function($q) {
                    $q->where('nama_pelanggan', 'like', '%' . $this->namapelanggan_search . '%')
                      ->orWhere('kode_pelanggan', 'like', '%' . $this->namapelanggan_search . '%');
                });
            })
            ->orderBy('tanggal_register', 'desc')
            ->limit(30)
            ->get();
        return view('livewire.pelanggan');
    }
}
