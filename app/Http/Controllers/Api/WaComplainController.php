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

        // Clean WA number format to standard format or search as is
        $waNumber = $request->wa_number;
        // Lookup matching customer by whatsapp/phone number in pelanggan table
        // We'll search in no_hp or similar column in pelanggan table. Let's lookup pelanggan fields.
        // For now, we will do a simple match on contact or phone columns.
        $pelanggan = Pelanggan::where('no_hp', 'like', "%{$waNumber}%")
            ->orWhere('no_hp', 'like', '%' . substr($waNumber, 4) . '%')
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
