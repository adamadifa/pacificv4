<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrackingtruckTrip;
use App\Models\Kendaraan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TrackingtruckController extends Controller
{
    public function index(Request $request)
    {
        // Get list of vehicles for dropdown
        $vehicles = Kendaraan::where('status_aktif_kendaraan', 1)
            ->orderBy('no_polisi')
            ->get();

        // Get filter values
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');
        $search = $request->get('search');

        // Default to current month if dates are not specified
        if (empty($start_date) && empty($end_date)) {
            $latestTrip = TrackingtruckTrip::orderBy('tanggal', 'desc')->first();
            if ($latestTrip) {
                $start_date = date('Y-m-01', strtotime($latestTrip->tanggal));
                $end_date = date('Y-m-t', strtotime($latestTrip->tanggal));
            } else {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-t');
            }
        }

        $query = TrackingtruckTrip::select(
            'device_name',
            'tanggal',
            DB::raw('MAX(imei) as imei'),
            DB::raw('MAX(model) as model'),
            DB::raw('SUM(mileage) as total_mileage'),
            DB::raw('SUM(fuel_consumption) as total_fuel_consumption'),
            DB::raw('AVG(average_speed) as average_speed'),
            DB::raw('MAX(max_speed) as max_speed'),
            DB::raw('AVG(fuel_ratio) as fuel_ratio')
        )
        ->whereBetween('tanggal', [$start_date, $end_date])
        ->groupBy('device_name', 'tanggal');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('device_name', 'like', "%{$search}%")
                  ->orWhere('imei', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        $reports = $query->orderBy('tanggal', 'desc')->orderBy('total_mileage', 'desc')->get();

        return view('trackingtruck.index', compact('reports', 'start_date', 'end_date', 'search', 'vehicles'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
            'device_name_manual' => 'nullable|string',
            'imei_manual' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Load Excel
            $data = Excel::toArray([], $request->file('file'));
            if (empty($data) || empty($data[0])) {
                return Redirect::back()->with(messageError('File Excel kosong atau tidak dapat dibaca'));
            }

            $rows = $data[0];

            // 1. Determine Device Name & IMEI
            $device_name = null;
            $imei = null;
            $model = 'GPS Tracker';

            for ($r = 0; $r < min(15, count($rows)); $r++) {
                foreach ($rows[$r] as $cell) {
                    if (is_string($cell)) {
                        if (preg_match('/Device\s*Name\s*:\s*(.+)/i', $cell, $m)) {
                            $device_name = trim($m[1]);
                        } elseif (preg_match('/IMEI\s*:\s*(.+)/i', $cell, $m)) {
                            $imei = trim($m[1]);
                        } elseif (preg_match('/Model\s*:\s*(.+)/i', $cell, $m)) {
                            $model = trim($m[1]);
                        }
                    }
                }
            }

            // Fallback manual
            if (empty($device_name)) {
                $device_name = $request->device_name_manual;
            }
            if (empty($imei)) {
                $imei = $request->imei_manual ?: 'GPS-' . time();
            }

            if (empty($device_name)) {
                return Redirect::back()->with(messageError('Identitas kendaraan tidak ditemukan di file Excel. Silakan pilih "No Polisi / Kendaraan" secara manual pada form.'));
            }

            // 2. Find Header Row with "Start time"
            $headerRowIndex = -1;
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (isset($row[0]) && is_string($row[0]) && trim(strtolower($row[0])) === 'start time') {
                    $headerRowIndex = $i;
                    break;
                }
            }

            if ($headerRowIndex === -1) {
                return Redirect::back()->with(messageError('Format Excel tidak sesuai. Baris header "Start time" tidak ditemukan.'));
            }

            // 3. Parse trip details
            $activeDate = null;
            $trips = [];

            for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                $col0 = isset($row[0]) ? trim($row[0]) : '';

                if (empty($col0)) {
                    continue;
                }

                // Check if it's a date group row (e.g. "2026-05-21")
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $col0)) {
                    $activeDate = $col0;
                    continue;
                }

                // Check if it's a summary row (e.g., "Total")
                if (strtolower($col0) === 'total' || strtolower($col0) === 'summary') {
                    continue;
                }

                // Check if col0 is a time format (e.g. "07:23:49") and active date is set
                if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $col0) && !empty($activeDate)) {
                    $start_time = $col0;
                    $start_location = trim($row[1] ?? '');
                    $end_time = trim($row[2] ?? '');
                    $end_location = trim($row[3] ?? '');
                    $mileage = floatval($row[4] ?? 0);
                    $travel_time = trim($row[5] ?? '');
                    $average_speed = floatval($row[6] ?? 0);
                    $max_speed = floatval($row[7] ?? 0);
                    $fuel_ratio = floatval($row[8] ?? 0);
                    $fuel_consumption = floatval($row[9] ?? 0);

                    $trips[] = [
                        'device_name' => $device_name,
                        'imei' => $imei,
                        'model' => $model,
                        'tanggal' => $activeDate,
                        'start_time' => $start_time,
                        'start_location' => $start_location,
                        'end_time' => $end_time,
                        'end_location' => $end_location,
                        'mileage' => $mileage,
                        'travel_time' => $travel_time,
                        'average_speed' => $average_speed,
                        'max_speed' => $max_speed,
                        'fuel_ratio' => $fuel_ratio,
                        'fuel_consumption' => $fuel_consumption,
                    ];
                }
            }

            if (empty($trips)) {
                return Redirect::back()->with(messageError('Tidak ada data perjalanan yang berhasil diimpor dari file Excel.'));
            }

            // 4. Clear existing records for this device name on the dates in the Excel file
            $datesInExcel = array_unique(array_column($trips, 'tanggal'));
            TrackingtruckTrip::where('device_name', $device_name)
                ->whereIn('tanggal', $datesInExcel)
                ->delete();

            // 5. Insert trips
            foreach ($trips as $trip) {
                TrackingtruckTrip::create($trip);
            }

            DB::commit();
            
            $firstDate = min($datesInExcel);
            $lastDate = max($datesInExcel);
            return Redirect::back()->with(messageSuccess("Berhasil mengimpor detail perjalanan {$device_name} untuk periode {$firstDate} s/d {$lastDate}."));
        } catch (\Exception $e) {
            DB::rollBack();
            return Redirect::back()->with(messageError('Gagal mengimpor file detail: ' . $e->getMessage()));
        }
    }

    public function show(Request $request)
    {
        $device_name = $request->device_name;
        $tanggal = $request->tanggal;

        $trips = TrackingtruckTrip::where('device_name', $device_name)
            ->where('tanggal', $tanggal)
            ->orderBy('start_time', 'asc')
            ->get();

        return view('trackingtruck.show', compact('trips', 'device_name', 'tanggal'));
    }

    public function destroyPeriod(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        TrackingtruckTrip::whereBetween('tanggal', [$request->start_date, $request->end_date])->delete();

        return Redirect::back()->with(messageSuccess("Data laporan GPS periode {$request->start_date} s/d {$request->end_date} berhasil dihapus."));
    }
}
