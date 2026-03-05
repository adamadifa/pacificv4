<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\LemburApprovalConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class LemburApprovalConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $config = LemburApprovalConfig::select(
            'hrd_lembur_config_approval.*',
            'nama_dept',
            'nama_cabang'
        )
            ->leftJoin('hrd_departemen', 'hrd_lembur_config_approval.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('cabang', 'hrd_lembur_config_approval.kode_cabang', '=', 'cabang.kode_cabang')
            ->get();
        $data['config'] = $config;
        return view('hrd.lembur.config.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('hrd.lembur.config.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            LemburApprovalConfig::create([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'roles' => $request->roles
            ]);
            return redirect()->route('lemburconfig.index')->with(messageSuccess('Config Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['config'] = LemburApprovalConfig::find($id);
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('hrd.lembur.config.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        try {
            LemburApprovalConfig::find($id)->update([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'roles' => $request->roles
            ]);
            return redirect()->route('lemburconfig.index')->with(messageSuccess('Config Berhasil Diupdate'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            LemburApprovalConfig::find($id)->delete();
            return redirect()->route('lemburconfig.index')->with(messageSuccess('Config Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
