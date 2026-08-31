<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\WaKomplainPelanggan;
use App\Models\Cabang;
use App\Models\User;

class WaKomplainController extends Controller
{
    public function index(Request $request)
    {
        $query = WaKomplainPelanggan::query()->with(['pelanggan', 'cabang', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kode_cabang')) {
            $query->where('kode_cabang', $request->kode_cabang);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('no_komplain', 'like', "%{$keyword}%")
                  ->orWhere('wa_number', 'like', "%{$keyword}%")
                  ->orWhere('nama_pelanggan', 'like', "%{$keyword}%")
                  ->orWhere('isi_komplain', 'like', "%{$keyword}%");
            });
        }

        $komplains = $query->orderBy('created_at', 'desc')->paginate(15);
        $cabangs = Cabang::orderBy('nama_cabang', 'asc')->get();

        return view('wa_komplain.index', compact('komplains', 'cabangs'));
    }

    public function show($id)
    {
        $komplain = WaKomplainPelanggan::with(['pelanggan', 'cabang', 'user'])->findOrFail($id);
        $csUsers = User::orderBy('name', 'asc')->get(); // list users to assign to

        return view('wa_komplain.show', compact('komplain', 'csUsers'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai,ditolak',
            'catatan_cs' => 'nullable|string'
        ]);

        $komplain = WaKomplainPelanggan::findOrFail($id);
        $komplain->update([
            'status' => $request->status,
            'catatan_cs' => $request->catatan_cs,
            'ditangani_oleh' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Status komplain berhasil diperbarui.');
    }

    public function assignTo(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $komplain = WaKomplainPelanggan::findOrFail($id);
        $komplain->update([
            'ditangani_oleh' => $request->user_id,
            'status' => 'diproses' // Automatically move to in-progress when assigned
        ]);

        return redirect()->back()->with('success', 'Komplain berhasil ditugaskan.');
    }
}
