@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Keuangan</h4>
            <small class="text-muted">Laporan kas kecil, ledger, mutasi, dan pinjaman karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Keuangan</a>
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
                @can('keu.kaskecil')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#kaskecil"
                            aria-controls="kaskecil" aria-selected="false" tabindex="-1">
                            <i class="ti ti-wallet me-2"></i> Kas Kecil
                        </button>
                    </li>
                @endcan
                @can('keu.ledger')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#ledger" aria-controls="ledger"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-book me-2"></i>
                            @if (auth()->user()->kode_cabang == 'PST')
                                Ledger
                            @else
                                Mutasi Bank
                            @endif
                        </button>
                    </li>
                @endcan
                @can('keu.mutasikeuangan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#mutasikeuangan"
                            aria-controls="mutasikeuangan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-arrows-exchange me-2"></i> Mutasi Keuangan
                        </button>
                    </li>
                @endcan
                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapledger"
                            aria-controls="rekapledger" aria-selected="false" tabindex="-1">
                            <i class="ti ti-report-analytics me-2"></i> Rekap Ledger
                        </button>
                    </li>
                @endhasanyrole
                @can('keu.saldokasbesar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#saldokasbesar"
                            aria-controls="saldokasbesar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-cash-banknote me-2"></i> Saldo Kas Besar
                        </button>
                    </li>
                @endcan
                @can('keu.lpu')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lpu" aria-controls="lpu"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-clipboard-list me-2"></i> LPU
                        </button>
                    </li>
                @endcan
                @can('keu.penjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-shopping-cart me-2"></i> Penjualan
                        </button>
                    </li>
                @endcan
                @can('keu.uanglogam')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#uanglogam"
                            aria-controls="uanglogam" aria-selected="false" tabindex="-1">
                            <i class="ti ti-coins me-2"></i> Uang Logam
                        </button>
                    </li>
                @endcan
                @can('keu.rekapbg')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbg" aria-controls="rekapbg"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-receipt-2 me-2"></i> Rekap BG
                        </button>
                    </li>
                @endcan
                @can('keu.pinjaman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pinjaman"
                            aria-controls="pinjaman" aria-selected="false" tabindex="-1">
                            <i class="ti ti-user-check me-2"></i> PJP
                        </button>
                    </li>
                @endcan
                @can('keu.kasbon')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kasbon" aria-controls="kasbon"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-cash me-2"></i> Kasbon
                        </button>
                    </li>
                @endcan
                @can('keu.piutangkaryawan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#piutangkaryawan"
                            aria-controls="piutangkaryawan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-user-minus me-2"></i> Piutang Karyawan
                        </button>
                    </li>
                @endcan
                @can('keu.kartupinjaman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupinjaman"
                            aria-controls="kartupinjaman" aria-selected="false" tabindex="-1">
                            <i class="ti ti-id-badge me-2"></i> Kartu PJP
                        </button>
                    </li>
                @endcan
                @can('keu.kartukasbon')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartukasbon"
                            aria-controls="kartukasbon" aria-selected="false" tabindex="-1">
                            <i class="ti ti-id-badge me-2"></i> Kartu Kasbon
                        </button>
                    </li>
                @endcan
                @can('keu.kartupiutangkaryawan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupiutangkaryawan"
                            aria-controls="kartupiutangkaryawan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-id-badge me-2"></i> Kartu Piutang Karyawan
                        </button>
                    </li>
                @endcan
                @can('keu.rekapkartupiutang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapkartupiutang"
                            aria-controls="rekapkartupiutang" aria-selected="false" tabindex="-1">
                            <i class="ti ti-files me-2"></i> Rekap Kartu Pinjaman
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('keu.kaskecil')
                    <div class="tab-pane fade active show" id="kaskecil" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-wallet me-2"></i>Laporan Kas Kecil</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.kaskecil')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.ledger')
                    <div class="tab-pane fade" id="ledger" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-book me-2"></i>Laporan Ledger / Mutasi Bank</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.ledger')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.mutasikeuangan')
                    <div class="tab-pane fade" id="mutasikeuangan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrows-exchange me-2"></i>Laporan Mutasi Keuangan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.mutasikeuangan')
                            </div>
                        </div>
                    </div>
                @endcan
                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <div class="tab-pane fade" id="rekapledger" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-report-analytics me-2"></i>Rekap Ledger</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.rekapledger')
                            </div>
                        </div>
                    </div>
                @endhasanyrole
                @can('keu.saldokasbesar')
                    <div class="tab-pane fade" id="saldokasbesar" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-cash-banknote me-2"></i>Laporan Saldo Kas Besar</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.saldokasbesar')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.lpu')
                    <div class="tab-pane fade" id="lpu" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Laporan LPU</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.lpu')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.penjualan')
                    <div class="tab-pane fade" id="penjualan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-shopping-cart me-2"></i>Laporan Penjualan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.penjualan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.uanglogam')
                    <div class="tab-pane fade" id="uanglogam" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-coins me-2"></i>Laporan Uang Logam</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.uanglogam')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.rekapbg')
                    <div class="tab-pane fade" id="rekapbg" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-receipt-2 me-2"></i>Rekap BG</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.rekapbg')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.pinjaman')
                    <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-user-check me-2"></i>Laporan PJP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.pinjaman')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.kasbon')
                    <div class="tab-pane fade" id="kasbon" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-cash me-2"></i>Laporan Kasbon</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.kasbon')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.piutangkaryawan')
                    <div class="tab-pane fade" id="piutangkaryawan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-user-minus me-2"></i>Laporan Piutang Karyawan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.piutangkaryawan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.kartupinjaman')
                    <div class="tab-pane fade" id="kartupinjaman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-id-badge me-2"></i>Kartu PJP</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.kartupjp')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.kartukasbon')
                    <div class="tab-pane fade" id="kartukasbon" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-id-badge me-2"></i>Kartu Kasbon</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.kartukasbon')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.kartupiutangkaryawan')
                    <div class="tab-pane fade" id="kartupiutangkaryawan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-id-badge me-2"></i>Kartu Piutang Karyawan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.kartupiutangkaryawan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('keu.rekapkartupiutang')
                    <div class="tab-pane fade" id="rekapkartupiutang" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-files me-2"></i>Rekap Kartu Pinjaman</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('keuangan.laporan.rekapkartupiutang')
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
