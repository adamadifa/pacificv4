@extends('layouts.app')
@section('titlepage', 'Tracking Truck')

@section('content')
@section('navigasi')
    <span>Tracking Truck</span>
@endsection

<!-- Tabs Navigation -->
<div class="row mb-3">
    <div class="col-12">
        <ul class="nav nav-pills nav-fill bg-white p-1 rounded border shadow-sm" id="trackingTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold d-flex align-items-center justify-content-center gap-2 py-2.5" 
                        id="live-gps-tab" data-bs-toggle="tab" data-bs-target="#live-gps" type="button" role="tab" 
                        aria-controls="live-gps" aria-selected="true">
                    <i class="ti ti-map-pin fs-4"></i> Live GPS (Tracksolid)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold d-flex align-items-center justify-content-center gap-2 py-2.5" 
                        id="rekap-laporan-tab" data-bs-toggle="tab" data-bs-target="#rekap-laporan" type="button" role="tab" 
                        aria-controls="rekap-laporan" aria-selected="false">
                    <i class="ti ti-file-analytics fs-4"></i> Rekap Perjalanan per Tanggal
                </button>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content p-0 border-0 shadow-none" id="trackingTabsContent">
    
    <!-- Tab 1: Live GPS (Tracksolid) -->
    <div class="tab-pane fade show active" id="live-gps" role="tabpanel" aria-labelledby="live-gps-tab">
        <div class="row mb-3">
            <div class="col-12">
                <div class="card shadow-sm border">
                    <div class="card-body py-2 px-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="ti ti-info-circle text-primary fs-4"></i>
                                <span class="fw-bold text-dark">Akses Login Tracksolid</span>
                            </div>
                            <div class="d-flex gap-4 flex-wrap">
                                <!-- Username Section -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small">Username:</span>
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="text" id="track_user" class="form-control bg-light border-0" value="cvmakmurpermata" readonly>
                                        <button class="btn btn-outline-primary border-0" type="button" onclick="copyText('track_user')">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- Password Section -->
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-muted small">Password:</span>
                                    <div class="input-group input-group-sm" style="width: 200px;">
                                        <input type="password" id="track_pass" class="form-control bg-light border-0" value="Makmurpermata@160" readonly>
                                        <button class="btn btn-outline-primary border-0" type="button" onclick="togglePass()">
                                            <i class="ti ti-eye" id="eye_icon"></i>
                                        </button>
                                        <button class="btn btn-outline-primary border-0" type="button" onclick="copyText('track_pass')">
                                            <i class="ti ti-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="text-muted small italic">
                                <i class="ti ti-help me-1"></i>Salin & tempel pada form login di bawah
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border">
                    <div class="card-body p-0" style="height: 70vh;">
                        <iframe src="https://www.tracksolidpro.com/resource/dev/index.html?t=247023#/live" 
                                frameborder="0" 
                                width="100%" 
                                height="100%" 
                                allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Rekap Laporan GPS -->
    <div class="tab-pane fade" id="rekap-laporan" role="tabpanel" aria-labelledby="rekap-laporan-tab">
        
        <!-- Statistics Section -->
        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-primary shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-truck fs-4"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ $reports->unique('device_name')->count() }}</h4>
                        </div>
                        <p class="mb-1 fw-medium">Total Kendaraan</p>
                        <p class="mb-0 text-muted small">Unit terdata di filter tanggal</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-success shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-success"><i class="ti ti-road fs-4"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ number_format($reports->sum('total_mileage'), 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">km</span></h4>
                        </div>
                        <p class="mb-1 fw-medium">Total Jarak Tempuh</p>
                        <p class="mb-0 text-muted small">Jarak tempuh periode filter</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-warning shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-gas-station fs-4"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ number_format($reports->sum('total_fuel_consumption'), 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">L</span></h4>
                        </div>
                        <p class="mb-1 fw-medium">Total Konsumsi BBM</p>
                        <p class="mb-0 text-muted small">BBM terpakai periode filter</p>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="card card-border-shadow-info shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2 pb-1">
                            <div class="avatar me-2">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-gauge fs-4"></i></span>
                            </div>
                            <h4 class="ms-1 mb-0">{{ number_format($reports->avg('average_speed'), 2, ',', '.') }} <span class="fs-6 fw-normal text-muted">km/h</span></h4>
                        </div>
                        <p class="mb-1 fw-medium">Rata-rata Kecepatan</p>
                        <p class="mb-0 text-muted small">Kecepatan rata-rata unit</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Control Section -->
        <div class="card shadow-sm border mb-3">
            <div class="card-body">
                <form action="{{ route('trackingtruck.index') }}" method="GET" class="row g-3 align-items-end">
                    <!-- Filter Tanggal Mulai -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tanggal Mulai</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            <input type="text" name="start_date" id="start_date" class="form-control flatpickr-date" value="{{ $start_date }}" readonly>
                        </div>
                    </div>

                    <!-- Filter Tanggal Selesai -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">Tanggal Selesai</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            <input type="text" name="end_date" id="end_date" class="form-control flatpickr-date" value="{{ $end_date }}" readonly>
                        </div>
                    </div>

                    <!-- Dropdown Kendaraan -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Pilih Kendaraan</label>
                        <select name="search" class="form-select select2" onchange="this.form.submit()">
                            <option value="">Semua Kendaraan</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->no_polisi }}" {{ $search == $v->no_polisi ? 'selected' : '' }}>
                                    {{ $v->no_polisi }} [{{ $v->merek }}]
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-md-2 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-primary btn-icon" data-bs-toggle="modal" data-bs-target="#modalImportGPS" data-bs-toggle="tooltip" data-bs-placement="top" title="Import Excel">
                            <i class="ti ti-file-import fs-4"></i>
                        </button>
                        
                        <button type="button" class="btn btn-outline-danger btn-icon" onclick="confirmDeletePeriod()" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Filtered">
                            <i class="ti ti-trash fs-4"></i>
                        </button>
                    </div>
                </form>
                <form action="{{ route('trackingtruck.delete-period') }}" method="POST" id="formDeletePeriod" style="display:none;">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="start_date" value="{{ $start_date }}">
                    <input type="hidden" name="end_date" value="{{ $end_date }}">
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card shadow-sm border">
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-bordered table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 5%;">NO</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">DEVICE NAME</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">IMEI</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">MODEL</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">MILAGE (km)</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">FUEL USED (L)</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">FUEL/100 (L)</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">AVG SPEED (km/h)</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">MAX SPEED (km/h)</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important; width: 10%;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $r)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td class="text-center fw-bold">{{ formatIndo($r->tanggal) }}</td>
                                <td class="fw-bold">{{ $r->device_name }}</td>
                                <td class="text-center text-muted">{{ $r->imei }}</td>
                                <td class="text-center"><span class="badge bg-label-info">{{ $r->model }}</span></td>
                                <td class="text-end fw-semibold text-success">{{ number_format($r->total_mileage, 2, ',', '.') }}</td>
                                <td class="text-end text-success">{{ $r->total_fuel_consumption > 0 ? number_format($r->total_fuel_consumption, 2, ',', '.') : 'N/A' }}</td>
                                <td class="text-end">{{ $r->fuel_ratio > 0 ? number_format($r->fuel_ratio, 2, ',', '.') : 'N/A' }}</td>
                                <td class="text-end">{{ number_format($r->average_speed, 2, ',', '.') }}</td>
                                <td class="text-end text-danger fw-semibold">{{ number_format($r->max_speed, 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-info d-flex align-items-center gap-1 mx-auto show-details" 
                                            data-device-name="{{ $r->device_name }}" data-tanggal="{{ $r->tanggal }}">
                                        <i class="ti ti-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="ti ti-database-x fs-1 mb-2 d-block text-secondary"></i>
                                    Belum ada data perjalanan untuk filter ini. Silakan import file Excel perjalanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Import GPS -->
<div class="modal fade" id="modalImportGPS" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="ti ti-file-spreadsheet text-success fs-3"></i> Import Excel Detail Perjalanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('trackingtruck.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info py-2 px-3 d-flex align-items-start gap-2 mb-3">
                        <i class="ti ti-info-circle fs-4 mt-0.5"></i>
                        <div>
                            <small class="d-block">Sistem akan secara otomatis mendeteksi identitas kendaraan (Device Name & IMEI) dari file Excel detail perjalanan.</small>
                            <small class="d-block mt-1 text-warning fw-bold">Jika tidak terdeteksi otomatis, tentukan kendaraan fallback di bawah.</small>
                        </div>
                    </div>
                    
                    <!-- Manual Device Fallback -->
                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">No Polisi / Kendaraan (Fallback)</label>
                            <select name="device_name_manual" class="form-select form-select-sm select2">
                                <option value="">-- Pilih Kendaraan Fallback --</option>
                                @foreach ($vehicles as $v)
                                    <option value="{{ $v->no_polisi }}">{{ $v->no_polisi }} [{{ $v->merek }}]</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">IMEI (Fallback / Opsional)</label>
                            <input type="text" name="imei_manual" class="form-control form-control-sm" placeholder="Contoh: 863539031234567">
                        </div>
                    </div>

                    <!-- Manual Period Input -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tanggal Mulai (Opsional)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" name="periode_start" class="form-control form-control-sm flatpickr-date" placeholder="Mulai" readonly>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tanggal Selesai (Opsional)</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                                <input type="text" name="periode_end" class="form-control form-control-sm flatpickr-date" placeholder="Selesai" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- File Input -->
                    <div class="form-group">
                        <label class="form-label fw-bold">Pilih File Excel Detail (.xlsx, .xls, .csv)</label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="ti ti-upload fs-5"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Perjalanan -->
<div class="modal fade" id="modalDetailPerjalanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary py-3">
                <h5 class="modal-title d-flex align-items-center gap-2 text-white">
                    <i class="ti ti-route fs-3"></i> Detail Perjalanan GPS
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" id="detailPerjalananContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Initialize flatpickr
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Keep active tab state on reload if tab query param or hash exists
        var hash = window.location.hash;
        if (hash) {
            $('.nav-pills a[href="' + hash + '"]').tab('show');
        }

        // If we filtered or searched, let's auto-switch to Recap tab
        @if(Request::has('start_date') || Request::has('end_date') || Request::has('search'))
            var recapTab = new bootstrap.Tab(document.querySelector('#rekap-laporan-tab'));
            recapTab.show();
        @endif

        // Show details dynamic loading
        $('.show-details').click(function() {
            var deviceName = $(this).data('device-name');
            var tanggal = $(this).data('tanggal');
            
            $('#detailPerjalananContent').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `);
            $('#modalDetailPerjalanan').modal('show');
            
            $.get('/trackingtruck/detail', {
                device_name: deviceName,
                tanggal: tanggal
            }, function(data) {
                $('#detailPerjalananContent').html(data);
            }).fail(function() {
                $('#detailPerjalananContent').html('<div class="alert alert-danger m-3">Gagal memuat data detail perjalanan.</div>');
            });
        });
    });

    function confirmDeletePeriod() {
        Swal.fire({
            title: "Hapus Laporan Filtered?",
            text: "Semua data GPS kendaraan pada rentang tanggal filter saat ini akan dihapus dan tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#ea5455",
            cancelButtonColor: "#8592a3",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formDeletePeriod').submit();
            }
        });
    }

    function copyText(id) {
        var copyText = document.getElementById(id);
        var originalType = copyText.type;
        
        // Temporarily change type to text to copy password
        copyText.type = "text";
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        copyText.type = originalType;

        // Visual feedback
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Data berhasil disalin ke clipboard',
            timer: 1000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }

    function togglePass() {
        var x = document.getElementById("track_pass");
        var icon = document.getElementById("eye_icon");
        if (x.type === "password") {
            x.type = "text";
            icon.classList.remove("ti-eye");
            icon.classList.add("ti-eye-off");
        } else {
            x.type = "password";
            icon.classList.remove("ti-eye-off");
            icon.classList.add("ti-eye");
        }
    }
</script>
@endpush
