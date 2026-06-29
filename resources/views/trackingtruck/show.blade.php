<div class="card border-0 shadow-none">
    <!-- Header Summary Details -->
    <div class="card-header bg-light border-bottom py-3">
        <div class="row g-3">
            <div class="col-md-3 border-end">
                <span class="text-muted small d-block">Nama Kendaraan</span>
                <strong class="text-dark fs-5">{{ $device_name }}</strong>
            </div>
            <div class="col-md-3 border-end">
                <span class="text-muted small d-block">Tanggal Perjalanan</span>
                <strong class="text-dark fs-5">{{ formatIndo($tanggal) }}</strong>
            </div>
            <div class="col-md-3 border-end">
                <span class="text-muted small d-block">Total Jarak Tempuh</span>
                <strong class="text-success fs-5">{{ number_format($trips->sum('mileage'), 2, ',', '.') }} km</strong>
            </div>
            <div class="col-md-3">
                <span class="text-muted small d-block">Total Konsumsi BBM</span>
                <strong class="text-warning fs-5">{{ number_format($trips->sum('fuel_consumption'), 2, ',', '.') }} L</strong>
            </div>
        </div>
    </div>
    
    <!-- Detail List Grouped by Date -->
    <div class="card-body p-0">
        <div class="table-responsive text-nowrap">
            <table class="table table-hover table-bordered table-striped align-middle mb-0" style="font-size: 12px;">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 50px;">NO</th>
                        <th class="text-center" style="width: 100px;">START TIME</th>
                        <th>START LOCATION</th>
                        <th class="text-center" style="width: 100px;">END TIME</th>
                        <th>END LOCATION</th>
                        <th class="text-end" style="width: 95px;">DISTANCE</th>
                        <th class="text-center" style="width: 95px;">TRAVEL TIME</th>
                        <th class="text-end" style="width: 95px;">AVG SPEED</th>
                        <th class="text-end" style="width: 95px;">MAX SPEED</th>
                        <th class="text-end" style="width: 95px;">FUEL/100</th>
                        <th class="text-end" style="width: 95px;">FUEL USED</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trips as $t)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td class="text-center fw-semibold text-primary">{{ $t->start_time }}</td>
                            <td class="text-wrap" style="max-width: 250px;">
                                <small class="text-dark d-block" title="{{ $t->start_location }}">{{ Str::limit($t->start_location, 70) }}</small>
                            </td>
                            <td class="text-center fw-semibold text-secondary">{{ $t->end_time }}</td>
                            <td class="text-wrap" style="max-width: 250px;">
                                <small class="text-dark d-block" title="{{ $t->end_location }}">{{ Str::limit($t->end_location, 70) }}</small>
                            </td>
                            <td class="text-end fw-semibold">{{ number_format($t->mileage, 3, ',', '.') }} km</td>
                            <td class="text-center">{{ $t->travel_time }}</td>
                            <td class="text-end">{{ number_format($t->average_speed, 1, ',', '.') }} km/h</td>
                            <td class="text-end text-danger fw-semibold">{{ number_format($t->max_speed, 1, ',', '.') }} km/h</td>
                            <td class="text-end">{{ $t->fuel_ratio > 0 ? number_format($t->fuel_ratio, 1, ',', '.') . ' L' : 'N/A' }}</td>
                            <td class="text-end text-success">{{ $t->fuel_consumption > 0 ? number_format($t->fuel_consumption, 1, ',', '.') . ' L' : 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center py-4 text-muted">
                                Tidak ada log detail perjalanan untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-light border-top d-flex justify-content-end py-2">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup Detail</button>
    </div>
</div>
