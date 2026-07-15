<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SalesmanTracking;
use App\Models\SalesmanLastPosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrackingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'locations' => 'required|array',
            'locations.*.latitude' => 'required|numeric',
            'locations.*.longitude' => 'required|numeric',
            'locations.*.accuracy' => 'nullable|numeric',
            'locations.*.tracked_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $user = Auth::user();
        if (!$user || !$user->kode_salesman) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized or user is not a salesman.'
            ], 401);
        }

        $kodeSalesman = $user->kode_salesman;
        $locations = $request->input('locations');

        DB::beginTransaction();
        try {
            $insertData = [];
            $latestLocation = null;

            foreach ($locations as $loc) {
                $insertData[] = [
                    'kode_salesman' => $kodeSalesman,
                    'latitude' => $loc['latitude'],
                    'longitude' => $loc['longitude'],
                    'accuracy' => $loc['accuracy'] ?? null,
                    'tracked_at' => $loc['tracked_at'],
                    'created_at' => now(),
                ];

                if ($latestLocation === null || strtotime($loc['tracked_at']) > strtotime($latestLocation['tracked_at'])) {
                    $latestLocation = $loc;
                }
            }

            // Bulk insert to historical tracking table
            SalesmanTracking::insert($insertData);

            // Upsert the latest location in cache table
            if ($latestLocation) {
                SalesmanLastPosition::updateOrCreate(
                    ['kode_salesman' => $kodeSalesman],
                    [
                        'latitude' => $latestLocation['latitude'],
                        'longitude' => $latestLocation['longitude'],
                        'accuracy' => $latestLocation['accuracy'] ?? null,
                        'tracked_at' => $latestLocation['tracked_at'],
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Locations tracking data stored successfully.',
                'count' => count($locations)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing salesman tracking data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to store tracking data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
