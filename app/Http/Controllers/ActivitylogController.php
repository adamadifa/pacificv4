<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ActivitylogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::query();
        $query->join('users', 'activity_log.causer_id', '=', 'users.id');
        $query->select('activity_log.*', 'name');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween(DB::raw('DATE(activity_log.created_at)'), [$request->dari, $request->sampai]);
        }

        if (!empty($request->id_user)) {
            $query->where('activity_log.causer_id', $request->id_user);
        }

        if (!empty($request->log_name)) {
            $query->where('activity_log.log_name', $request->log_name);
        }

        if (!empty($request->event)) {
            $query->where('activity_log.event', $request->event);
        }

        if (!empty($request->status)) {
            if ($request->status == 'pending') {
                $query->where('activity_log.status_log', 0);
                $query->whereNot('event', 'create');
            }
        }

        if (!empty($request->no_bukti)) {
            $query->where('activity_log.description', 'like', '%' . $request->no_bukti . '%');
        }
        $query->orderBy('activity_log.id', 'desc');
        $activity = $query->paginate(20);
        $activity->appends($request->all());
        $data['activity'] = $activity;
        $data['users'] = User::orderBy('name')->get();
        $data['kategori'] = Activity::select('log_name')->groupBy('log_name')->get();
        return view('activitylog.index', $data);
    }
}
