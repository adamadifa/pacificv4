<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
        $query->orderBy('activity_log.id', 'desc');
        $activity = $query->paginate(20);
        $activity->appends($request->all());
        $data['activity'] = $activity;
        $data['users'] = User::orderBy('name')->get();
        return view('activitylog.index', $data);
    }
}
