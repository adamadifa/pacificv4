@extends('layouts.app')
@section('titlepage', 'Laporan Pembelian')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Pembelian</h4>
            <small class="text-muted">Laporan pembelian, pembayaran, dan hutang supplier.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Pembelian</a>
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
                @can('pb.pembelian')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#pembelian"
                            aria-controls="pembelian" aria-selected="false" tabindex="-1">
                            <i class="ti ti-shopping-cart me-2"></i> Pembelian
                        </button>
                    </li>
                @endcan
                @can('pb.pembayaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pembayaran"
                            aria-controls="pembayaran" aria-selected="false" tabindex="-1">
                            <i class="ti ti-wallet me-2"></i> Pembayaran
                        </button>
                    </li>
                @endcan
                @can('pb.rekapsupplier')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapsupplier"
                            aria-controls="rekapsupplier" aria-selected="false" tabindex="-1">
                            <i class="ti ti-users me-2"></i> Rekap Supplier
                        </button>
                    </li>
                @endcan
                @can('pb.rekappembelian')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappembelian"
                            aria-controls="rekappembelian" aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-analytics me-2"></i> Rekap Pembelian
                        </button>
                    </li>
                @endcan
                @can('pb.kartuhutang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartuhutang"
                            aria-controls="kartuhutang" aria-selected="false" tabindex="-1">
                            <i class="ti ti-address-book me-2"></i> Kartu Hutang
                        </button>
                    </li>
                @endcan
                @can('pb.auh')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#auh" aria-controls="auh"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-chart-bar me-2"></i> AUH
                        </button>
                    </li>
                @endcan
                @can('pb.bahankemasan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bahankemasan"
                            aria-controls="bahankemasan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-package me-2"></i> Bahan Kemasan
                        </button>
                    </li>
                @endcan
                @can('pb.rekapbahankemasan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbahankemasan"
                            aria-controls="rekapbahankemasan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-package-export me-2"></i> Kemasan / Supplier
                        </button>
                    </li>
                @endcan
                @can('pb.jurnalkoreksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#jurnalkoreksi"
                            aria-controls="jurnalkoreksi" aria-selected="false" tabindex="-1">
                            <i class="ti ti-adjustments-horizontal me-2"></i> Jurnal Koreksi
                        </button>
                    </li>
                @endcan
                @can('pb.rekapakun')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapakun"
                            aria-controls="rekapakun" aria-selected="false" tabindex="-1">
                            <i class="ti ti-book me-2"></i> Rekap Akun
                        </button>
                    </li>
                @endcan
                @can('pb.rekapkontrabon')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapkontrabon"
                            aria-controls="rekapkontrabon" aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-text me-2"></i> Rekap Kontrabon
                        </button>
                    </li>
                @endcan
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappo" aria-controls="rekappo"
                        aria-selected="false" tabindex="-1">
                        <i class="ti ti-list-check me-2"></i> Rekap PO
                    </button>
                </li>
            </ul>

            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('pb.pembelian')
                    <div class="tab-pane fade active show" id="pembelian" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-shopping-cart me-2"></i>Laporan Pembelian</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.pembelian')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.pembayaran')
                    <div class="tab-pane fade" id="pembayaran" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Laporan Pembayaran</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.pembayaran')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.rekapsupplier')
                    <div class="tab-pane fade" id="rekapsupplier" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Rekap Pembelian Supplier</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.rekapsupplier')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.rekappembelian')
                    <div class="tab-pane fade" id="rekappembelian" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-analytics me-2"></i>Rekap Pembelian</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.rekappembelian')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.kartuhutang')
                    <div class="tab-pane fade" id="kartuhutang" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-address-book me-2"></i>Laporan Kartu Hutang</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.kartuhutang')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.auh')
                    <div class="tab-pane fade" id="auh" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-chart-bar me-2"></i>Analisa Umur Hutang</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.auh')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.bahankemasan')
                    <div class="tab-pane fade" id="bahankemasan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-package me-2"></i>Laporan Bahan Kemasan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.bahankemasan')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.rekapbahankemasan')
                    <div class="tab-pane fade" id="rekapbahankemasan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-export me-2"></i>Rekap Bahan Kemasan / Supplier</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.rekapbahankemasan')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.jurnalkoreksi')
                    <div class="tab-pane fade" id="jurnalkoreksi" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-adjustments-horizontal me-2"></i>Laporan Jurnal Koreksi</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.jurnalkoreksi')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.rekapakun')
                    <div class="tab-pane fade" id="rekapakun" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Rekap Akun</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.rekapakun')
                            </div>
                        </div>
                    </div>
                @endcan

                @can('pb.rekapkontrabon')
                    <div class="tab-pane fade" id="rekapkontrabon" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-text me-2"></i>Rekap Kontrabon</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('pembelian.laporan.rekapkontrabon')
                            </div>
                        </div>
                    </div>
                @endcan

                <div class="tab-pane fade" id="rekappo" role="tabpanel">
                    <div class="card shadow-none border">
                        <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                            <h6 class="m-0 fw-bold text-white"><i class="ti ti-list-check me-2"></i>Rekap PO</h6>
                        </div>
                        <div class="card-body pt-4">
                            @include('pembelian.laporan.rekappo')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
