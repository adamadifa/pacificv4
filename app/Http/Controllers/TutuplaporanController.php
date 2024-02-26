<?php

namespace App\Http\Controllers;

use App\Models\Tutuplaporan;
use Illuminate\Http\Request;

class TutuplaporanController extends Controller
{
    public function cektutuplaporan(Request $request)
    {
        $tanggal = explode("-", $request->tanggal);
        $bulan = $tanggal[1];
        $tahun = $tanggal[0];
        $cek = Tutuplaporan::where('jenis_laporan', $request->jenis_laporan)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 1)
            ->count();
        return $cek;
    }
}
