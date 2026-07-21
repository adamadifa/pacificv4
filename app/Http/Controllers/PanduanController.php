<?php

namespace App\Http\Controllers;

use App\Models\PanduanArtikel;
use App\Models\PanduanQA;
use App\Models\PanduanPertanyaanUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanduanController extends Controller
{
    /**
     * Tampilkan halaman utama panduan (daftar kategori & artikel)
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = PanduanArtikel::where('aktif', 1)->orderBy('urutan');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('konten', 'like', '%' . $search . '%');
            });
        }

        $artikels = $query->get();

        // Group articles by category
        $kategoriGroup = $artikels->groupBy('kategori');

        // Fetch some common Q&A for Quick links / FAQ section
        $faqs = PanduanQA::where('aktif', 1)->take(5)->get();

        return view('panduan.index', compact('kategoriGroup', 'faqs', 'search'));
    }

    /**
     * Tampilkan detail artikel panduan
     */
    public function show($slug)
    {
        $artikel = PanduanArtikel::where('slug', $slug)->where('aktif', 1)->firstOrFail();

        // Get other articles in same category
        $related = PanduanArtikel::where('kategori', $artikel->kategori)
            ->where('id', '!=', $artikel->id)
            ->where('aktif', 1)
            ->get();

        // Get all categories for sidebar navigation
        $allCategories = PanduanArtikel::where('aktif', 1)
            ->select('kategori')
            ->distinct()
            ->get()
            ->pluck('kategori');

        return view('panduan.show', compact('artikel', 'related', 'allCategories'));
    }

    /**
     * AJAX Q&A Chat Assistant
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = trim($request->input('message'));
        $words = array_filter(explode(' ', strtolower($message)), function ($w) {
            return strlen($w) > 2; // only look at words with >2 chars
        });

        // 1. Cek di database Q&A
        $match = null;
        if (!empty($words)) {
            $query = PanduanQA::query()->where('aktif', 1);
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('kata_kunci', 'like', '%' . $word . '%')
                      ->orWhere('pertanyaan', 'like', '%' . $word . '%');
                }
            });
            $match = $query->first();
        }

        if ($match) {
            return response()->json([
                'status' => 'success',
                'reply' => $match->jawaban
            ]);
        }

        // 2. Cek di judul / isi artikel panduan
        $artikelMatch = null;
        if (!empty($words)) {
            $query = PanduanArtikel::query()->where('aktif', 1);
            $query->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('judul', 'like', '%' . $word . '%')
                      ->orWhere('konten', 'like', '%' . $word . '%');
                }
            });
            $artikelMatch = $query->first();
        }

        if ($artikelMatch) {
            return response()->json([
                'status' => 'success',
                'reply' => "Saya menemukan artikel panduan yang mungkin membantu: <b><a href='" . route('panduan.show', $artikelMatch->slug) . "'>" . $artikelMatch->judul . "</a></b>.<br>Silakan klik link tersebut untuk membaca langkah lengkapnya."
            ]);
        }

        // 3. Jika tidak ketemu, simpan pertanyaan user ke database untuk dijawab admin
        PanduanPertanyaanUser::create([
            'id_user' => Auth::id(),
            'pertanyaan' => $message,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'reply' => "Maaf, saya tidak menemukan jawaban yang cocok untuk pertanyaan Anda. Namun, pertanyaan Anda sudah dicatat oleh sistem dan akan segera dijawab oleh Admin/Super Admin nanti ya! 😊"
        ]);
    }
}
