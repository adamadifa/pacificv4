<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kendaraan extends Model
{
    use HasFactory;
    protected $table = "kendaraan";
    protected $primaryKey = "kode_kendaraan";
    protected $guarded = [];
    public $incrementing = false;

    //Kategori = 1 : Sudah Lewat : 2 Belum Lewat
    public function getKirJatuhtempo($kategori)
    {
        $bulanini = date("m");
        $tahunini = date("Y");
        $start_date_bulanini = $tahunini . "-" . $bulanini . "-01";
        $end_date_bulanini = date("Y-m-t", strtotime($start_date_bulanini));
        //Jika Bulan + 1 Lebih dari 12 Maka Bulan + 1 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 1
        $bulandepan = date("m") + 1 > 12 ? (date("m") + 1) - 12 : date("m") + 1;
        $tahunbulandepan = date("m") + 1 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_bulandepan = $tahunbulandepan . "-" . $bulandepan . "-01";
        $end_date_bulandepan = date("Y-m-t", strtotime($start_date_bulandepan));

        //Jika Bulan + 2 Lebih dari 12 Maka Bulan + 2 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 2
        //Sampel Jika Bulan = Desember (12) Maka Dua bulan adalah Februari (2) (12+2-12);
        $duabulan = date("m") + 2 > 12 ? (date("m") + 2) - 12 : date("m") + 2;
        $tahunduabulan = date("m") + 2 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_duabulan = $tahunduabulan . "-" . $duabulan . "-01";
        $end_date_duabulan = date("Y-m-t", strtotime($start_date_duabulan));

        $query = Kendaraan::query();
        if ($kategori == 0) { // Lewat Jatuh Tempo
            $query->where('jatuhtempo_kir', '<', $start_date_bulanini);
        } else if ($kategori == 1) { // Jatuh Tempo Bulan Ini
            $query->whereBetween('jatuhtempo_kir', [$start_date_bulanini, $end_date_bulanini]);
        } else if ($kategori == 2) { // Jatuh Tempo Bulan Depan
            $query->whereBetween('jatuhtempo_kir', [$start_date_bulandepan, $end_date_bulandepan]);
        } else if ($kategori == 3) { // Jatuh Tempo Dua Bulan
            $query->whereBetween('jatuhtempo_kir', [$start_date_duabulan, $end_date_duabulan]);
        }
        $query->where('status_aktif_kendaraan', 1);
        return $query;
    }


    public function getPajak1tahunjatuhtempo($kategori)
    {
        $bulanini = date("m");
        $tahunini = date("Y");
        $start_date_bulanini = $tahunini . "-" . $bulanini . "-01";
        $end_date_bulanini = date("Y-m-t", strtotime($start_date_bulanini));
        //Jika Bulan + 1 Lebih dari 12 Maka Bulan + 1 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 1
        $bulandepan = date("m") + 1 > 12 ? (date("m") + 1) - 12 : date("m") + 1;
        $tahunbulandepan = date("m") + 1 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_bulandepan = $tahunbulandepan . "-" . $bulandepan . "-01";
        $end_date_bulandepan = date("Y-m-t", strtotime($start_date_bulandepan));

        //Jika Bulan + 2 Lebih dari 12 Maka Bulan + 2 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 2
        //Sampel Jika Bulan = Desember (12) Maka Dua bulan adalah Februari (2) (12+2-12);
        $duabulan = date("m") + 2 > 12 ? (date("m") + 2) - 12 : date("m") + 2;
        $tahunduabulan = date("m") + 2 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_duabulan = $tahunduabulan . "-" . $duabulan . "-01";
        $end_date_duabulan = date("Y-m-t", strtotime($start_date_duabulan));

        $query = Kendaraan::query();
        if ($kategori == 0) { // Lewat Jatuh Tempo
            $query->where('jatuhtempo_pajak_satutahun', '<', $start_date_bulanini);
        } else if ($kategori == 1) { // Jatuh Tempo Bulan Ini
            $query->whereBetween('jatuhtempo_pajak_satutahun', [$start_date_bulanini, $end_date_bulanini]);
        } else if ($kategori == 2) { // Jatuh Tempo Bulan Depan
            $query->whereBetween('jatuhtempo_pajak_satutahun', [$start_date_bulandepan, $end_date_bulandepan]);
        } else if ($kategori == 3) { // Jatuh Tempo Dua Bulan
            $query->whereBetween('jatuhtempo_pajak_satutahun', [$start_date_duabulan, $end_date_duabulan]);
        }
        $query->where('status_aktif_kendaraan', 1);
        return $query;
    }


    public function getPajak5tahunjatuhtempo($kategori)
    {
        $bulanini = date("m");
        $tahunini = date("Y");
        $start_date_bulanini = $tahunini . "-" . $bulanini . "-01";
        $end_date_bulanini = date("Y-m-t", strtotime($start_date_bulanini));
        //Jika Bulan + 1 Lebih dari 12 Maka Bulan + 1 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 1
        $bulandepan = date("m") + 1 > 12 ? (date("m") + 1) - 12 : date("m") + 1;
        $tahunbulandepan = date("m") + 1 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_bulandepan = $tahunbulandepan . "-" . $bulandepan . "-01";
        $end_date_bulandepan = date("Y-m-t", strtotime($start_date_bulandepan));

        //Jika Bulan + 2 Lebih dari 12 Maka Bulan + 2 - 12 dan Tahun + 1 Jika Tidak Maka Bulan Depan = Bulan + 2
        //Sampel Jika Bulan = Desember (12) Maka Dua bulan adalah Februari (2) (12+2-12);
        $duabulan = date("m") + 2 > 12 ? (date("m") + 2) - 12 : date("m") + 2;
        $tahunduabulan = date("m") + 2 > 12 ? $tahunini + 1 : $tahunini;
        $start_date_duabulan = $tahunduabulan . "-" . $duabulan . "-01";
        $end_date_duabulan = date("Y-m-t", strtotime($start_date_duabulan));

        $query = Kendaraan::query();
        if ($kategori == 0) { // Lewat Jatuh Tempo
            $query->where('jatuhtempo_pajak_limatahun', '<', $start_date_bulanini);
        } else if ($kategori == 1) { // Jatuh Tempo Bulan Ini
            $query->whereBetween('jatuhtempo_pajak_limatahun', [$start_date_bulanini, $end_date_bulanini]);
        } else if ($kategori == 2) { // Jatuh Tempo Bulan Depan
            $query->whereBetween('jatuhtempo_pajak_limatahun', [$start_date_bulandepan, $end_date_bulandepan]);
        } else if ($kategori == 3) { // Jatuh Tempo Dua Bulan
            $query->whereBetween('jatuhtempo_pajak_limatahun', [$start_date_duabulan, $end_date_duabulan]);
        }
        $query->where('status_aktif_kendaraan', 1);
        return $query;
    }


    function getRekapkendaraancabang()
    {

        $query = Kendaraan::query();
        $query->select('kendaraan.kode_cabang', 'nama_cabang', DB::raw('count(kendaraan.kode_cabang) as total'));
        $query->join('cabang', 'kendaraan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->groupBy('kendaraan.kode_cabang', 'cabang.nama_cabang');
        return $query;
    }
}
