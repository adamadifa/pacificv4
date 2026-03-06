<?php

namespace App\Http\Controllers;

use App\Models\AjuanfakturkreditConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AjuanfakturkreditConfigController extends Controller
{
    public function index()
    {
        $data['config'] = AjuanfakturkreditConfig::all();
        return view('marketing.ajuanfaktur.config.index', $data);
    }

    public function create()
    {
        $data['roles'] = Role::orderBy('name')->get();
        return view('marketing.ajuanfaktur.config.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            AjuanfakturkreditConfig::create([
                'roles' => $request->roles
            ]);
            return redirect()->route('ajuanfakturconfig.index')->with(messageSuccess('Konfigurasi Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $data['config'] = AjuanfakturkreditConfig::find($id);
        $data['roles'] = Role::orderBy('name')->get();
        return view('marketing.ajuanfaktur.config.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            AjuanfakturkreditConfig::find($id)->update([
                'roles' => $request->roles
            ]);
            return redirect()->route('ajuanfakturconfig.index')->with(messageSuccess('Konfigurasi Berhasil Diperbarui'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            AjuanfakturkreditConfig::find($id)->delete();
            return redirect()->route('ajuanfakturconfig.index')->with(messageSuccess('Konfigurasi Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
