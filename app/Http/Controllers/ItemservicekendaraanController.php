<?php

namespace App\Http\Controllers;

use App\Models\Itemservicekendaraan;
use Illuminate\Http\Request;

class ItemservicekendaraanController extends Controller
{

    public function create()
    {
        return view('generalaffair.itemservicekendaraan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_item' => 'required',
            'jenis_item' => 'required',
        ]);

        try {
            $lastitem = Itemservicekendaraan::orderBy("kode_item", "desc")->first();

            $last_kode_item = $lastitem != null ? $lastitem->kode_item : '';

            $kode_item  = buatkode($last_kode_item, "SV", 4);

            Itemservicekendaraan::create([
                'kode_item' => $kode_item,
                'nama_item' => $request->nama_item,
                'jenis_item' => $request->jenis_item
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
    public function getitem()
    {
        $item = Itemservicekendaraan::all();
        echo '<option value="">Pilih Item</option>';
        foreach ($item as $d) {
            echo '<option value="' . $d->kode_item . '">' . $d->nama_item . '</option>';
        }
    }
}
