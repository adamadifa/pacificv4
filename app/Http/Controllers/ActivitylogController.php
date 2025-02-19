<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivitylogController extends Controller
{
    public function index()
    {
        $data['activity'] = Activity::join('users', 'activity_log.causer_id', '=', 'users.id')
            ->select('activity_log.*', 'name')
            ->orderBy('activity_log.id', 'desc')
            ->paginate(20);
        return view('activitylog.index', $data);
    }
}
