<?php

namespace App\Http\Controllers;

use App\Charts\HasilproduksiChart;
use App\Models\Karyawan;
use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::findorfail(auth()->user()->id);
        if ($user->hasRole(['super admin'])) {
            return $this->marketing();
        } else {
            return $this->marketing();
        }
    }

    public function marketing()
    {
        return view('dashboard.marketing');
    }


    public function produksi()
    {
        $data['start_year'] = config('global.start_year');
        $data['list_bulan'] = config('global.list_bulan');
        $data['nama_bulan_singkat'] = config('global.nama_bulan_singkat');
        return view('dashboard.produksi', $data);
    }

    public function generalaffair()
    {

        $kendaraan = new Kendaraan();
        $data['kir_lewat'] = $kendaraan->getKirJatuhtempo(0)->get();
        $data['kir_bulanini'] = $kendaraan->getKirJatuhtempo(1)->get();
        $data['kir_bulandepan'] = $kendaraan->getKirJatuhtempo(2)->get();
        $data['kir_duabulan'] = $kendaraan->getKirJatuhtempo(3)->get();

        $data['pajaksatutahun_lewat'] = $kendaraan->getPajak1tahunjatuhtempo(0)->get();
        $data['pajaksatutahun_bulanini'] = $kendaraan->getPajak1tahunjatuhtempo(1)->get();
        $data['pajaksatutahun_bulandepan'] = $kendaraan->getPajak1tahunjatuhtempo(2)->get();
        $data['pajaksatutahun_duabulan'] = $kendaraan->getPajak1tahunjatuhtempo(3)->get();


        $data['pajaklimatahun_lewat'] = $kendaraan->getPajak5tahunjatuhtempo(0)->get();
        $data['pajaklimatahun_bulanini'] = $kendaraan->getPajak5tahunjatuhtempo(1)->get();
        $data['pajaklimatahun_bulandepan'] = $kendaraan->getPajak5tahunjatuhtempo(2)->get();
        $data['pajaklimatahun_duabulan'] = $kendaraan->getPajak5tahunjatuhtempo(3)->get();

        $data['rekapkendaraan'] = $kendaraan->getRekapkendaraancabang()->get();
        $data['jmlkendaraan'] = Kendaraan::count();
        return view('dashboard.generalaffair', $data);
    }

    public function hrd()
    {
        $sk = new Karyawan();
        $data['status_karyawan'] = $sk->getRekapstatuskaryawan();
        $data['kontrak_lewat'] = $sk->getRekapkontrak(0);
        $data['kontrak_bulanini'] = $sk->getRekapkontrak(1);
        $data['kontrak_bulandepan'] = $sk->getRekapkontrak(2);
        $data['kontrak_duabulan'] = $sk->getRekapkontrak(3);
        $data['karyawancabang'] = $sk->getRekapkaryawancabang();
        return view('dashboard.hrd', $data);
    }
}
