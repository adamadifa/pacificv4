<?php

namespace App\Http\Controllers;

use App\Models\AjuanlimitkreditConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AjuanLimitConfigController extends Controller
{
    public function index()
    {
        $data['config'] = AjuanlimitkreditConfig::orderBy('min_limit')->get();
        return view('marketing.ajuanlimit.config.index', $data);
    }

    public function create()
    {
        $data['roles'] = Role::orderBy('name')->get();
        return view('marketing.ajuanlimit.config.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'min_limit' => 'required',
            'max_limit' => 'required',
            'roles' => 'required|array'
        ]);

        try {
            AjuanlimitkreditConfig::create([
                'min_limit' => toNumber($request->min_limit),
                'max_limit' => toNumber($request->max_limit),
                'roles' => $request->roles
            ]);
            return redirect()->route('ajuanlimitconfig.index')->with(messageSuccess('Konfigurasi Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function edit($id)
    {
        $data['config'] = AjuanlimitkreditConfig::find($id);
        $data['roles'] = Role::orderBy('name')->get();
        return view('marketing.ajuanlimit.config.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'min_limit' => 'required',
            'max_limit' => 'required',
            'roles' => 'required|array'
        ]);

        try {
            AjuanlimitkreditConfig::find($id)->update([
                'min_limit' => toNumber($request->min_limit),
                'max_limit' => toNumber($request->max_limit),
                'roles' => $request->roles
            ]);
            return redirect()->route('ajuanlimitconfig.index')->with(messageSuccess('Konfigurasi Berhasil Diperbarui'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($id)
    {
        try {
            AjuanlimitkreditConfig::find($id)->delete();
            return redirect()->route('ajuanlimitconfig.index')->with(messageSuccess('Konfigurasi Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
