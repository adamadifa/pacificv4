<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\PenilaiankaryawanApprovalConfig;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class PenilaiankaryawanApprovalConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $config = PenilaiankaryawanApprovalConfig::select(
            'hrd_penilaian_config_approval.*',
            'nama_dept',
            'nama_jabatan',
            'nama_cabang'
        )
            ->leftJoin('hrd_departemen', 'hrd_penilaian_config_approval.kode_dept', '=', 'hrd_departemen.kode_dept')
            ->leftJoin('hrd_jabatan', 'hrd_penilaian_config_approval.kode_jabatan', '=', 'hrd_jabatan.kode_jabatan')
            ->leftJoin('cabang', 'hrd_penilaian_config_approval.kode_cabang', '=', 'cabang.kode_cabang')
            ->get();
        $data['config'] = $config;
        return view('hrd.penilaiankaryawan.config.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['jabatan'] = Jabatan::orderBy('nama_jabatan')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('hrd.penilaiankaryawan.config.create', $data);
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
            PenilaiankaryawanApprovalConfig::create([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'kategori_jabatan' => $request->kategori_jabatan,
                'kode_jabatan' => $request->kode_jabatan,
                'roles' => $request->roles
            ]);
            return redirect()->route('penilaiankaryawanconfig.index')->with(messageSuccess('Config Berhasil Disimpan'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['config'] = PenilaiankaryawanApprovalConfig::find($id);
        $data['departemen'] = Departemen::orderBy('nama_dept')->get();
        $data['jabatan'] = Jabatan::orderBy('nama_jabatan')->get();
        $data['cabang'] = Cabang::orderBy('nama_cabang')->get();
        $data['roles'] = Role::orderBy('name')->get();
        return view('hrd.penilaiankaryawan.config.edit', $data);
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
            PenilaiankaryawanApprovalConfig::find($id)->update([
                'kode_dept' => $request->kode_dept,
                'kode_cabang' => $request->kode_cabang,
                'kategori_jabatan' => $request->kategori_jabatan,
                'kode_jabatan' => $request->kode_jabatan,
                'roles' => $request->roles
            ]);
            return redirect()->route('penilaiankaryawanconfig.index')->with(messageSuccess('Config Berhasil Diupdate'));
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
            PenilaiankaryawanApprovalConfig::find($id)->delete();
            return redirect()->route('penilaiankaryawanconfig.index')->with(messageSuccess('Config Berhasil Dihapus'));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }
}
