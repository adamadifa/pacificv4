@extends('layouts.app')
@section('titlepage', 'Laporan Marketing')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Marketing</h4>
            <small class="text-muted">Laporan penjualan, piutang, dan performa salesman.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Marketing</a>
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
                @can('mkt.penjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-report-money me-2"></i> Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekappenjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappenjualan"
                            aria-controls="rekappenjualan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-analytics me-2"></i> Rekap Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.kasbesar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kasbesar"
                            aria-controls="kasbesar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-wallet me-2"></i> Kas Besar
                        </button>
                    </li>
                @endcan
                @can('mkt.retur')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#retur" aria-controls="retur"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-backspace me-2"></i> Retur
                        </button>
                    </li>
                @endcan
                @can('mkt.tunaikredit')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tunaikredit"
                            aria-controls="tunaikredit" aria-selected="false" tabindex="-1">
                            <i class="ti ti-cash me-2"></i> Tunai Kredit
                        </button>
                    </li>
                @endcan
                @can('mkt.kartupiutang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupiutang"
                            aria-controls="kartupiutang" aria-selected="false" tabindex="-1">
                            <i class="ti ti-address-book me-2"></i> Kartu Piutang
                        </button>
                    </li>
                @endcan
                @can('mkt.aup')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#aup" aria-controls="aup"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-chart-bar me-2"></i> AUP
                        </button>
                    </li>
                @endcan
                @can('mkt.lebihsatufaktur')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lebihsatufaktur"
                            aria-controls="lebihsatufaktur" aria-selected="false" tabindex="-1">
                            <i class="ti ti-files me-2"></i> Lebih 1 Faktur
                        </button>
                    </li>
                @endcan
                @can('mkt.dpp')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#dpp" aria-controls="dpp"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-receipt-2 me-2"></i> DPP
                        </button>
                    </li>
                @endcan
                @can('mkt.dppp')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#dppp" aria-controls="dppp"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-receipt me-2"></i> DPPP
                        </button>
                    </li>
                @endcan
                @can('mkt.omsetpelanggan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#omsetpelanggan"
                            aria-controls="omsetpelanggan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-users me-2"></i> Omset Pelanggan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekappelanggan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappelanggan"
                            aria-controls="rekappelanggan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-user-exclamation me-2"></i> Rekap Pelanggan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekapkendaraan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapkendaraan"
                            aria-controls="rekapkendaraan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-truck me-2"></i> Rekap Kendaraan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekapwilayah')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapwilayah"
                            aria-controls="rekapwilayah" aria-selected="false" tabindex="-1">
                            <i class="ti ti-map-pin me-2"></i> Rekap Wilayah
                        </button>
                    </li>
                @endcan
                @can('mkt.analisatransaksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#analisatransaksi"
                            aria-controls="analisatransaksi" aria-selected="false" tabindex="-1">
                            <i class="ti ti-device-analytics me-2"></i> Analisa Transaksi
                        </button>
                    </li>
                @endcan
                @can('mkt.tunaitransfer')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tunaitransfer"
                            aria-controls="tunaitransfer" aria-selected="false" tabindex="-1">
                            <i class="ti ti-arrows-transfer-down me-2"></i> Tunai Transfer
                        </button>
                    </li>
                @endcan
                @can('mkt.effectivecall')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#effectivecall"
                            aria-controls="effectivecall" aria-selected="false" tabindex="-1">
                            <i class="ti ti-phone-check me-2"></i> Effective Call
                        </button>
                    </li>
                @endcan
                @can('mkt.lhp')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lhp" aria-controls="lhp"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-clipboard-list me-2"></i> LHP
                        </button>
                    </li>
                @endcan
                @can('mkt.harganet')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#harganet"
                            aria-controls="harganet" aria-selected="false" tabindex="-1">
                            <i class="ti ti-tag me-2"></i> Harga Net
                        </button>
                    </li>
                @endcan
                @can('mkt.routingsalesman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#routingsalesman"
                            aria-controls="routingsalesman" aria-selected="false" tabindex="-1">
                            <i class="ti ti-route me-2"></i> Routing Salesman
                        </button>
                    </li>
                @endcan
                @can('mkt.salesperfomance')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#salesperfomance"
                            aria-controls="salesperfomance" aria-selected="false" tabindex="-1">
                            <i class="ti ti-chart-infographic me-2"></i> Sales Performance
                        </button>
                    </li>
                @endcan
                @can('mkt.persentasesfa')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persentasesfa"
                            aria-controls="persentasesfa" aria-selected="false" tabindex="-1">
                            <i class="ti ti-device-mobile me-2"></i> Persentase SFA
                        </button>
                    </li>
                @endcan
                @can('mkt.persentasesfa')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persentasedatapelanggan"
                            aria-controls="persentasedatapelanggan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-database me-2"></i> Data Pelanggan
                        </button>
                    </li>
                @endcan
                @can('mkt.komisisalesman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#komisisalesman"
                            aria-controls="komisisalesman" aria-selected="false" tabindex="-1">
                            <i class="ti ti-user-dollar me-2"></i> Komisi Salesman
                        </button>
                    </li>
                @endcan
                @can('mkt.komisidriverhelper')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#komisidriverhelper"
                            aria-controls="komisidriverhelper" aria-selected="false" tabindex="-1">
                            <i class="ti ti-steering-wheel me-2"></i> Komisi Driver Helper
                        </button>
                    </li>
                @endcan
            </ul>

            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('mkt.penjualan')
                    <div class="tab-pane fade active show" id="penjualan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-money me-2"></i>Laporan Penjualan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.penjualan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.rekappenjualan')
                    <div class="tab-pane fade" id="rekappenjualan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-analytics me-2"></i>Rekap Penjualan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.rekappenjualan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.kasbesar')
                    <div class="tab-pane fade" id="kasbesar" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Laporan Kas Besar</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.kasbesar')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.retur')
                    <div class="tab-pane fade" id="retur" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-backspace me-2"></i>Laporan Retur</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.retur')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.tunaikredit')
                    <div class="tab-pane fade" id="tunaikredit" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-cash me-2"></i>Laporan Tunai Kredit</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.tunaikredit')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.dpp')
                    <div class="tab-pane fade" id="dpp" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt-2 me-2"></i>Laporan DPP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.dpp')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.omsetpelanggan')
                    <div class="tab-pane fade" id="omsetpelanggan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-users me-2"></i>Omset Pelanggan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.omsetpelanggan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.rekappelanggan')
                    <div class="tab-pane fade" id="rekappelanggan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-user-exclamation me-2"></i>Rekap Pelanggan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.rekappelanggan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.rekapkendaraan')
                    <div class="tab-pane fade" id="rekapkendaraan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Rekap Kendaraan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.rekapkendaraan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.rekapwilayah')
                    <div class="tab-pane fade" id="rekapwilayah" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-map-pin me-2"></i>Rekap Wilayah</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.rekapwilayah')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.analisatransaksi')
                    <div class="tab-pane fade" id="analisatransaksi" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-device-analytics me-2"></i>Analisa Transaksi</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.analisatransaksi')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.tunaitransfer')
                    <div class="tab-pane fade" id="tunaitransfer" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-transfer-down me-2"></i>Laporan Tunai Transfer</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.tunaitransfer')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.effectivecall')
                    <div class="tab-pane fade" id="effectivecall" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-phone-check me-2"></i>Laporan Effective Call</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.effectivecall')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.kartupiutang')
                    <div class="tab-pane fade" id="kartupiutang" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-address-book me-2"></i>Laporan Kartu Piutang</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.kartupiutang')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.aup')
                    <div class="tab-pane fade" id="aup" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-chart-bar me-2"></i>Laporan AUP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.aup')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.lebihsatufaktur')
                    <div class="tab-pane fade" id="lebihsatufaktur" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-files me-2"></i>Laporan Lebih 1 Faktur</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.lebihsatufaktur')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.dppp')
                    <div class="tab-pane fade" id="dppp" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt me-2"></i>Laporan DPPP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.dppp')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.lhp')
                    <div class="tab-pane fade" id="lhp" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Laporan LHP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.lhp')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.harganet')
                    <div class="tab-pane fade" id="harganet" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-tag me-2"></i>Laporan Harga Net</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.harganet')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.routingsalesman')
                    <div class="tab-pane fade" id="routingsalesman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-route me-2"></i>Routing Salesman</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.routingsalesman')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.salesperfomance')
                    <div class="tab-pane fade" id="salesperfomance" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-chart-infographic me-2"></i>Sales Performance</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.salesperfomance')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.persentasesfa')
                    <div class="tab-pane fade" id="persentasesfa" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-device-mobile me-2"></i>Persentase SFA</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.persentasesfa')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.persentasesfa')
                    <div class="tab-pane fade" id="persentasedatapelanggan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-database me-2"></i>Persentase Data Pelanggan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.persentasedatapelanggan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.komisisalesman')
                    <div class="tab-pane fade" id="komisisalesman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-user-dollar me-2"></i>Komisi Salesman</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.komisisalesman')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('mkt.komisidriverhelper')
                    <div class="tab-pane fade" id="komisidriverhelper" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-steering-wheel me-2"></i>Komisi Driver Helper</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('marketing.laporan.komisidriverhelper')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

@endsection
