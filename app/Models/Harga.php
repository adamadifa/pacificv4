<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = "produk_harga";
    protected $primaryKey = "kode_harga";
    protected $guarded = [];
    public $incrementing = false;

    function getHargabypelanggan($kode_pelanggan)
    {
        $pelanggan = Pelanggan::select('kode_pelanggan', 'nama_pelanggan', 'salesman.kode_cabang', 'salesman.kode_kategori_salesman')
            ->join('salesman', 'pelanggan.kode_salesman', '=', 'salesman.kode_salesman')
            ->where('kode_pelanggan', $kode_pelanggan)->first();

        $kode_kategori_salesman = !empty($pelanggan->kode_kategori_salesman) ? $pelanggan->kode_kategori_salesman : 'NM';
        $kode_cabang = $pelanggan->kode_cabang;
        $kode_pelanggan = $pelanggan->kode_pelanggan;

        $cek_harga_pelanggan = Harga::where('kode_pelanggan', $kode_pelanggan)->count();

        if ($cek_harga_pelanggan > 0) {
            $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon', 'kode_kategori_produk')
                ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                ->where('kode_pelanggan', $kode_pelanggan)
                ->where('status_aktif_produk', 1)
                ->orderBy('nama_produk')
                ->get();
        } else if (str_contains($pelanggan->nama_pelanggan, 'KPBN') || str_contains($pelanggan->nama_pelanggan, 'WSI')) {
            $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon')
                ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                ->where('kode_kategori_salesman', 'TO')
                ->where('kode_cabang', $kode_cabang)
                ->where('status_aktif_produk', 1)
                ->where('status_promo', 0)
                ->whereNull('kode_pelanggan')
                ->orderBy('nama_produk')
                ->get();
        } else if (str_contains($pelanggan->nama_pelanggan, 'SMM')) {
            $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon')
                ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                ->where('status_aktif_produk', 1)
                ->where('status_promo', 1)
                ->where('kode_cabang', $kode_cabang)
                ->orderBy('nama_produk')
                ->get();
        } else {
            if ($kode_kategori_salesman == 'TC') {
                $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon')
                    ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                    ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                    ->where('kode_kategori_salesman', 'CV')
                    ->where('kode_cabang', $kode_cabang)
                    ->where('status_aktif_produk', 1)
                    ->where('status_promo', 0)
                    ->whereNull('kode_pelanggan')
                    ->orWhere('kode_kategori_salesman', 'TO')
                    ->where('kode_cabang', $kode_cabang)
                    ->where('status_aktif_produk', 1)
                    ->where('status_promo', 0)
                    ->whereNull('kode_pelanggan')
                    ->orderBy('nama_produk')
                    ->get();

                if ($kode_cabang == 'BKI') {
                    $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon')
                        ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                        ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                        ->where('kode_kategori_salesman', 'CV')
                        ->where('kode_cabang', $kode_cabang)
                        ->where('status_aktif_produk', 1)
                        ->where('status_ppn', 'IN')
                        ->where('status_promo', 0)
                        ->orWhere('kode_kategori_salesman', 'TO')
                        ->where('kode_cabang', $kode_cabang)
                        ->where('status_aktif_produk', 1)
                        ->where('status_ppn', 'IN')
                        ->where('status_promo', 0)
                        ->orderBy('nama_produk')
                        ->get();
                }
            } else {
                $harga = Harga::select('produk_harga.*', 'nama_produk', 'isi_pcs_dus', 'isi_pcs_pack', 'produk.kode_kategori_diskon')
                    ->join('produk', 'produk_harga.kode_produk', '=', 'produk.kode_produk')
                    ->leftjoin('produk_diskon_kategori', 'produk.kode_kategori_diskon', '=', 'produk_diskon_kategori.kode_kategori_diskon')
                    ->where('kode_kategori_salesman', $kode_kategori_salesman)
                    ->where('kode_cabang', $kode_cabang)
                    ->whereNull('produk_harga.kode_pelanggan')
                    ->where('status_aktif_produk', 1)
                    ->where('status_promo', 0)
                    ->orderBy('nama_produk')
                    ->get();
            }
        }

        return $harga;
    }
}
