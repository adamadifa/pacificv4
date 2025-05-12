<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Programikatan;
use App\Models\Ajuanprogramikatanenambulan;
use App\Models\User;
use Illuminate\Http\Request;

class AjuanprogramikatanenambulanController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ajuanprogramikatanenambulan::query();
        $query->join('cabang', 'marketing_program_ikatan_enambulan.kode_cabang', '=', 'cabang.kode_cabang');
        $query->join('program_ikatan', 'marketing_program_ikatan_enambulan.kode_program', '=', 'program_ikatan.kode_program');
        $query->orderBy('marketing_program_ikatan_enambulan.no_pengajuan', 'desc');
        if (!$user->hasRole($roles_access_all_cabang)) {
            if ($user->hasRole('regional sales manager')) {
                $query->where('cabang.kode_regional', auth()->user()->kode_regional);
            } else {
                $query->where('marketing_program_ikatan_enambulan.kode_cabang', auth()->user()->kode_cabang);
            }
        }

        if (!empty($request->kode_cabang)) {
            $query->where('marketing_program_ikatan_enambulan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_program)) {
            $query->where('marketing_program_ikatan_enambulan.kode_program', $request->kode_program);
        }

        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('marketing_program_ikatan_enambulan.tanggal', [$request->dari, $request->sampai]);
        }

        if (!empty($request->nomor_dokumen)) {
            $query->where('marketing_program_ikatan_enambulan.nomor_dokumen', $request->nomor_dokumen);
        }

        if ($user->hasRole('regional sales manager')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.om');
                    $query->whereNull('marketing_program_ikatan_enambulan.rsm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.rsm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.om');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('gm marketing')) {
            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.rsm');
                    $query->whereNull('marketing_program_ikatan_enambulan.gm');
                } else if ($request->status == 'approved') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.gm');
                    $query->where('status', 0);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.rsm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else if ($user->hasRole('direktur')) {

            if (!empty($request->status)) {
                if ($request->status == 'pending') {
                    $query->whereNotnull('marketing_program_ikatan_enambulan.gm');
                    $query->whereNull('marketing_program_ikatan_enambulan.direktur');
                    $query->where('status', 0);
                } else if ($request->status == 'approved') {
                    $query->where('status', 1);
                } else if ($request->status == 'rejected') {
                    $query->where('status', 2);
                }
            }
            $query->whereNotNull('marketing_program_ikatan_enambulan.gm');
            // $query->where('marketing_program_ikatan.status', '!=', 2);
        } else {
            if ($request->status == 'pending') {
                $query->where('status', 0);
            } else if ($request->status == 'approved') {
                $query->where('status', 1);
            } else if ($request->status == 'rejected') {
                $query->where('status', 2);
            }
        }
        $ajuanprogramikatan = $query->paginate(15);
        $ajuanprogramikatan->appends(request()->all());
        $data['ajuanprogramikatan'] = $ajuanprogramikatan;

        $cbg = new Cabang();
        $data['user'] = $user;
        $data['cabang'] = $cbg->getCabang();
        $data['programikatan'] = Programikatan::orderBy('kode_program')->get();
        return view('worksheetom.ajuanprogramikatanenambulan.index', $data);
    }
}
