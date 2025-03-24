<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Ticket;
use App\Models\Ticketupdatedata;
use App\Models\User;
use Illuminate\Http\Request;

class TicketupdateController extends Controller
{
    public function index(Request $request)
    {
        $user = User::findorfail(auth()->user()->id);
        $roles_access_all_cabang = config('global.roles_access_all_cabang');
        $query = Ticketupdatedata::query();
        $query->select('tickest_update_data.*', 'users.name', 'approval.name as approval', 'users.kode_cabang');
        $query->join('users', 'tickets_update_data.id_user', '=', 'users.id');
        $query->leftJoin('users as approval', 'tickets_update_data.id_approval', '=', 'approval.id');
        if (!$user->hasRole($roles_access_all_cabang)) {
            $query->where('users.kode_cabang', auth()->user()->kode_cabang);
        }

        if (!empty($request->kode_cabang_search)) {
            $query->where('users.kode_cabang', $request->kode_cabang_search);
        }

        if (!empty($request->status_search)) {
            if ($request->status_search == "pending") {
                $query->where('tickest_update_data.status', 0);
            } else {
                $query->where('tickest_update_data.status', 1);
            }
        }
        $query->orderBy('status');
        $query->orderBy('kode_pengajuan', 'asc');
        $ticket = $query->paginate(10);
        $ticket->appends($request->all());

        $cbg = new Cabang();
        $data['cabang'] = $cbg->getCabang();
        $data['ticket'] = $ticket;
        return view('utilities.tickets_update.index', $data);
    }
}
