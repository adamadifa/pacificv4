@extends('layouts.app')
@section('titlepage', 'Laporan HRD')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan HRD</h4>
            <small class="text-muted">Laporan presensi, gaji, cuti, dan pelanggaran karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report me-1"></i>Laporan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-10 col-md-12 col-12">
        <div class="nav-align-left mb-4 shadow-none">
            <ul class="nav nav-tabs" role="tablist">
                @can('hrd.presensi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#presensi"
                            aria-controls="presensi" aria-selected="false" tabindex="-1">
                            <i class="ti ti-fingerprint me-2"></i> Presensi
                        </button>
                    </li>
                @endcan
                @can('hrd.gaji')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#gaji" aria-controls="gaji"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-report-money me-2"></i> Gaji
                        </button>
                    </li>
                @endcan
                @can('hrd.presensi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cuti" aria-controls="cuti"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-calendar-event me-2"></i> Cuti
                        </button>
                    </li>
                @endcan
                @can('hrd.presensi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#keterlambatan"
                            aria-controls="keterlambatan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-clock-off me-2"></i> Rekap Keterlambatan
                        </button>
                    </li>
                @endcan
                @can('hrd.pelanggaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pelanggaran"
                            aria-controls="pelanggaran" aria-selected="false" tabindex="-1">
                            <i class="ti ti-alert-triangle me-2"></i> Pelanggaran
                        </button>
                    </li>
                @endcan
            </ul>

            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('hrd.presensi')
                    <div class="tab-pane fade active show" id="presensi" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-fingerprint me-2"></i>Laporan Presensi</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('hrd.laporan.presensi')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('hrd.gaji')
                    <div class="tab-pane fade" id="gaji" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Laporan Gaji</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('hrd.laporan.gaji')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('hrd.presensi')
                    <div class="tab-pane fade" id="cuti" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar-event me-2"></i>Laporan Cuti</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('hrd.laporan.cuti')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('hrd.presensi')
                    <div class="tab-pane fade" id="keterlambatan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clock-off me-2"></i>Rekap Keterlambatan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('hrd.laporan.keterlambatan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('hrd.pelanggaran')
                    <div class="tab-pane fade" id="pelanggaran" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-alert-triangle me-2"></i>Laporan Pelanggaran</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('hrd.laporan.pelanggaran')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
