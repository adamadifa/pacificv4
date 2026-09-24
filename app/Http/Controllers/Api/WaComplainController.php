<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\WaKomplainPelanggan;
use App\Models\Pelanggan;
use Illuminate\Support\Carbon;

class WaComplainController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'wa_number' => 'required|string',
            'nama_pelanggan' => 'required|string',
            'isi_komplain' => 'required|string',
            'ringkasan_ai' => 'nullable|string',
            'kategori_ai' => 'nullable|string',
            'chat_history' => 'nullable|array'
        ]);

        // Clean WA number format to search in pelanggan table (support 628xxx and 08xxx)
        $waNumber = $request->wa_number;
        $normalizedNumber = preg_replace('/[^0-9]/', '', $waNumber);
        $localNumber = preg_replace('/^62/', '0', $normalizedNumber);
        
        $pelanggan = Pelanggan::where('no_hp_pelanggan', 'like', "%{$normalizedNumber}%")
            ->orWhere('no_hp_pelanggan', 'like', "%{$localNumber}%")
            ->orWhere('no_hp_pelanggan', 'like', '%' . substr($normalizedNumber, 4) . '%')
            ->first();

        // Generate no_komplain: KMP/MM/YY/XXXX
        $bulan = date("m");
        $tahun = date("y");
        $prefix = "KMP/" . $bulan . "/" . $tahun . "/";

        $lastKomplain = WaKomplainPelanggan::where('no_komplain', 'like', $prefix . '%')
            ->orderBy('no_komplain', 'desc')
            ->first();

        if ($lastKomplain) {
            $lastNum = (int) substr($lastKomplain->no_komplain, -4);
            $nextNum = str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $nextNum = "0001";
        }

        $noKomplain = $prefix . $nextNum;

        $komplain = WaKomplainPelanggan::create([
            'no_komplain' => $noKomplain,
            'wa_number' => $waNumber,
            'nama_pelanggan' => $request->nama_pelanggan,
            'kode_pelanggan' => $pelanggan ? $pelanggan->kode_pelanggan : null,
            'kode_cabang' => $pelanggan ? $pelanggan->kode_cabang : null,
            'isi_komplain' => $request->isi_komplain,
            'ringkasan_ai' => $request->ringkasan_ai,
            'kategori_ai' => $request->kategori_ai,
            'status' => 'baru',
            'chat_history' => $request->chat_history,
            'tanggal_komplain' => Carbon::now()->toDateString()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komplain berhasil dicatat.',
            'data' => [
                'no_komplain' => $komplain->no_komplain,
                'nama_pelanggan' => $komplain->nama_pelanggan,
                'status' => $komplain->status
            ]
        ], 201);
    }
}
