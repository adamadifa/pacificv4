<?php

namespace App\Http\Controllers;

use App\Models\Coa;
use App\Models\Coakategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class CoaController extends Controller
{
    public function index()
    {
        // // Get all accounts with their sub-accounts
        // $accounts = Coa::where('sub_akun', 0)->get();

        // // Function to get sub-accounts recursively
        // $accounts = $this->getSubAccounts($accounts);

        $allAccounts = Coa::orderby('kode_akun')->whereNotIn('kode_akun', ['1', '2', '0-0000'])->get();


        return view('accounting.coa.index', compact('allAccounts'));
    }


    private function buildTree($elements, $parentId = 0)
    {


        $branch = [];

        foreach ($elements as $element) {
            // Periksa apakah 'sub_akun' elemen ini sama dengan parentId yang dicari
            if ($element->sub_akun == $parentId) {
                // Cari semua anak dari elemen ini
                $children = $this->buildTree($elements, $element->kode_akun);

                if ($children) {
                    // Jika ada anak, tambahkan sebagai properti 'children'
                    $element->children = $children;
                }

                $branch[] = $element;
            }
        }

        return $branch;
    }


    public function create()
    {
        $data['coa'] = Coa::orderBy('kode_akun')->whereNotIn('kode_akun', ['1', '2'])->get();
        $data['kategori'] = Coakategori::orderBy('kode_kategori')->get();
        return view('accounting.coa.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'sub_akun' => 'required',
            'kode_kategori' => 'required',
        ]);

        try {
            Coa::create([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'sub_akun' => $request->sub_akun,
                'kode_kategori' => $request->kode_kategori
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = Coa::where('kode_akun', $kode_akun)->first();
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        $data['coa'] = $coa;
        $data['sub_akun'] = Coa::orderBy('kode_akun')->whereNotIn('kode_akun', ['1', '2'])->get();
        $data['kategori'] = Coakategori::orderBy('kode_kategori')->get();
        return view('accounting.coa.edit', $data);
    }

    public function update(Request $request, $kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = Coa::where('kode_akun', $kode_akun)->first();
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        $request->validate([
            'kode_akun' => 'required',
            'nama_akun' => 'required',
            'sub_akun' => 'required',
            'kode_kategori' => 'required',
        ]);

        try {
            $coa->update([
                'kode_akun' => $request->kode_akun,
                'nama_akun' => $request->nama_akun,
                'sub_akun' => $request->sub_akun,
                'kode_kategori' => $request->kode_kategori
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_akun)
    {
        $kode_akun = Crypt::decrypt($kode_akun);
        $coa = Coa::where('kode_akun', $kode_akun);
        if (!$coa) {
            return Redirect::back()->with(messageError('Data tidak ditemukan'));
        }

        try {
            $coa->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
