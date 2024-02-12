<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();
        if (!empty($request->nama_supplier)) {
            $query->where('nama_supplier', 'like', '%' . $request->nama_supplier . '%');
        }

        $query->orderBy('kode_supplier', 'desc');
        $supplier = $query->paginate(15);
        $supplier->appends(request()->all());
        return view('datamaster.supplier.index', compact('supplier'));
    }

    public function create()
    {
        return view('datamaster.supplier.create');
    }


    public function store(Request $request)
    {

        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            $lastsupplier = Supplier::orderBy('kode_supplier', 'desc')->first();
            $last_kode_supplier = $lastsupplier != NULL ? $lastsupplier->kode_supplier : '';
            $kode_supplier =  buatkode($last_kode_supplier, "SP", 4);

            Supplier::create([
                'kode_supplier' => $kode_supplier,
                'nama_supplier' => $request->nama_supplier,
                'contact_person' => $request->contact_person,
                'no_hp_supplier' => $request->no_hp_supplier,
                'alamat_supplier' => $request->alamat_supplier,
                'email_supplier' => $request->email_supplier,
                'no_rekening_supplier' => $request->no_rekening_supplier,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $supplier = Supplier::where('kode_supplier', $kode_supplier)->first();
        return view('datamaster.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        $request->validate([
            'nama_supplier' => 'required'
        ]);

        try {
            Supplier::where('kode_supplier', $kode_supplier)->update([

                'nama_supplier' => $request->nama_supplier,
                'contact_person' => $request->contact_person,
                'no_hp_supplier' => $request->no_hp_supplier,
                'alamat_supplier' => $request->alamat_supplier,
                'email_supplier' => $request->email_supplier,
                'no_rekening_supplier' => $request->no_rekening_supplier,
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Di Update'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_supplier)
    {
        $kode_supplier = Crypt::decrypt($kode_supplier);
        try {
            Supplier::where('kode_supplier', $kode_supplier)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }
}
