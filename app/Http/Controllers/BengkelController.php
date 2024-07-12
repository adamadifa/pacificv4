<?php

namespace App\Http\Controllers;

use App\Models\Bengkel;
use Illuminate\Http\Request;

class BengkelController extends Controller
{
    public function getbengkel()
    {
        $bengkel = Bengkel::all();
        echo '<option value="">Pilih Bengkel</option>';
        foreach ($bengkel as $d) {
            echo '<option value="' . $d->kode_bengkel . '">' . $d->nama_bengkel . '</option>';
        }
    }

    public function create()
    {
        return view('generalaffair.bengkel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bengkel' => 'required',
        ]);
        try {
            $lastbengkel = Bengkel::orderBy("kode_bengkel", "desc")->first();

            $last_kode_bengkel = $lastbengkel != null ? $lastbengkel->kode_bengkel : '';

            $kode_bengkel  = buatkode($last_kode_bengkel, "BK", 4);

            Bengkel::create([
                'kode_bengkel' => $kode_bengkel,
                'nama_bengkel' => $request->nama_bengkel
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
