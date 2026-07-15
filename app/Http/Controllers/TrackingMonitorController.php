<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SalesmanLastPosition;
use App\Models\SalesmanTracking;
use Carbon\Carbon;

class TrackingMonitorController extends Controller
{
    public function index()
    {
        $cabang = DB::table('cabang')->orderBy('nama_cabang')->get();
        return view('tracking.index', compact('cabang'));
    }

    public function getLatestPositions(Request $request)
    {
        $query = DB::table('salesman_last_position')
            ->join('salesman', 'salesman_last_position.kode_salesman', '=', 'salesman.kode_salesman')
            ->leftJoin('cabang', 'salesman.kode_cabang', '=', 'cabang.kode_cabang')
            ->select(
                'salesman_last_position.*',
                'salesman.nama_salesman',
                'cabang.nama_cabang',
                'salesman.no_hp_salesman'
            );

        if ($request->filled('kode_cabang')) {
            $query->where('salesman.kode_cabang', $request->input('kode_cabang'));
        }

        $positions = $query->get()->map(function ($pos) {
            $trackedAt = Carbon::parse($pos->tracked_at);
            $diffMinutes = $trackedAt->diffInMinutes(now());

            $status = 'red';
            if ($diffMinutes < 15) {
                $status = 'green';
            } elseif ($diffMinutes < 30) {
                $status = 'yellow';
            }

            $pos->status = $status;
            $pos->diff_minutes = $diffMinutes;
            $pos->formatted_tracked_at = $trackedAt->format('d-m-Y H:i:s');
            return $pos;
        });

        return response()->json([
            'success' => true,
            'data' => $positions
        ]);
    }

    public function getTrail($kode_salesman, Request $request)
    {
        $tanggal = $request->input('tanggal', date('Y-m-d'));

        $trail = DB::table('salesman_tracking')
            ->where('kode_salesman', $kode_salesman)
            ->whereDate('tracked_at', $tanggal)
            ->orderBy('tracked_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trail
        ]);
    }
}
