<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuanlimitkredit extends Model
{
    use HasFactory;
    protected $table = "marketing_ajuan_limitkredit";
    protected $primaryKey = "no_pengajuan";
    protected $guarded = [];
    public $incrementing = false;


    function getAjuanlimitkredit($no_pengajuan)
    {
        $ajuanlimit = Ajuanlimitkredit::select(
            'marketing_ajuan_limitkredit.*',
            'pelanggan.nama_pelanggan',
            'pelanggan.nik',
            'pelanggan.alamat_pelanggan',
            'pelanggan.alamat_toko',
            'pelanggan.no_hp_pelanggan',
            'salesman.nama_salesman',
            'cabang.nama_cabang',
            'cabang.nama_pt',
            'cabang.alamat_cabang',
            'pelanggan.hari',
            'pelanggan.latitude',
            'pelanggan.longitude',
            'pelanggan.foto',
            'pelanggan.foto_owner',

        )
            ->join('pelanggan', 'marketing_ajuan_limitkredit.kode_pelanggan', '=', 'pelanggan.kode_pelanggan')
            ->join('salesman', 'marketing_ajuan_limitkredit.kode_salesman', 'salesman.kode_salesman')
            ->join('cabang', 'salesman.kode_cabang', 'cabang.kode_cabang')
            ->where('no_pengajuan', $no_pengajuan)->first();

        return $ajuanlimit;
    }
}
