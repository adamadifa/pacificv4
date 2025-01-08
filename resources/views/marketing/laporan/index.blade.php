@extends('layouts.app')
@section('titlepage', 'Laporan Marketing')

@section('content')

@section('navigasi')
    <span>Laporan Marketing</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('mkt.penjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekappenjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappenjualan"
                            aria-controls="penjualan" aria-selected="false" tabindex="-1">
                            Rekap Penjualan
                        </button>
                    </li>
                @endcan
                @can('mkt.kasbesar')
                    <li class="nav-item" role="kasbesar">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kasbesar" aria-controls="kasbesar"
                            aria-selected="false" tabindex="-1">
                            Kas Besar
                        </button>
                    </li>
                @endcan
                @can('mkt.retur')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#retur" aria-controls="retur"
                            aria-selected="false" tabindex="-1">
                            Retur
                        </button>
                    </li>
                @endcan
                @can('mkt.tunaikredit')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tunaikredit"
                            aria-controls="tunaikredit" aria-selected="false" tabindex="-1">
                            Tunai Kredit
                        </button>
                    </li>
                @endcan
                @can('mkt.kartupiutang')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupiutang"
                            aria-controls="kartupiutang" aria-selected="false" tabindex="-1">
                            Kartu Piutang
                        </button>
                    </li>
                @endcan
                @can('mkt.aup')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#aup" aria-controls="aup"
                            aria-selected="false" tabindex="-1">
                            AUP
                        </button>
                    </li>
                @endcan
                @can('mkt.lebihsatufaktur')
                    <li class="nav-item" role="lebihsatufaktur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lebihsatufaktur"
                            aria-controls="lebihsatufaktur" aria-selected="false" tabindex="-1">
                            Lebih 1 Faktur
                        </button>
                    </li>
                @endcan
                @can('mkt.dpp')
                    <li class="nav-item" role="dpp">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#dpp" aria-controls="dpp"
                            aria-selected="false" tabindex="-1">
                            DPP
                        </button>
                    </li>
                @endcan
                @can('mkt.dppp')
                    <li class="nav-item" role="dppp">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#dppp" aria-controls="dppp"
                            aria-selected="false" tabindex="-1">
                            DPPP
                        </button>
                    </li>
                @endcan
                @can('mkt.omsetpelanggan')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#omsetpelanggan"
                            aria-controls="omsetpelanggan" aria-selected="false" tabindex="-1">
                            Omset Pelanggan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekappelanggan')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappelanggan"
                            aria-controls="rekappelanggan" aria-selected="false" tabindex="-1">
                            Rekap Pelanggan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekapkendaraan')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapkendaraan"
                            aria-controls="rekapkendaraan" aria-selected="false" tabindex="-1">
                            Rekap Kendaraan
                        </button>
                    </li>
                @endcan
                @can('mkt.rekapwilayah')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapwilayah"
                            aria-controls="rekapwilayah" aria-selected="false" tabindex="-1">
                            Rekap Wilayah
                        </button>
                    </li>
                @endcan
                @can('mkt.analisatransaksi')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#analisatransaksi"
                            aria-controls="rekapwilayah" aria-selected="false" tabindex="-1">
                            Analisa Transaksi
                        </button>
                    </li>
                @endcan
                @can('mkt.tunaitransfer')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tunaitransfer"
                            aria-controls="tunaitransfer" aria-selected="false" tabindex="-1">
                            Tunai Transfer
                        </button>
                    </li>
                @endcan
                @can('mkt.effectivecall')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#effectivecall"
                            aria-controls="tunaitransfer" aria-selected="false" tabindex="-1">
                            Effective Call
                        </button>
                    </li>
                @endcan
                @can('mkt.lhp')
                    <li class="nav-item" role="retur">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lhp" aria-controls="lhp"
                            aria-selected="false" tabindex="-1">
                            LHP
                        </button>
                    </li>
                @endcan
                @can('mkt.harganet')
                    <li class="nav-item" role="harganet">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#harganet"
                            aria-controls="harganet" aria-selected="false" tabindex="-1">
                            Harga Net
                        </button>
                    </li>
                @endcan
                @can('mkt.routingsalesman')
                    <li class="nav-item" role="routingsalesman">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#routingsalesman"
                            aria-controls="harganet" aria-selected="false" tabindex="-1">
                            Routing Salesman
                        </button>
                    </li>
                @endcan
                @can('mkt.salesperfomance')
                    <li class="nav-item" role="salesperfomance">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#salesperfomance"
                            aria-controls="harganet" aria-selected="false" tabindex="-1">
                            Sales Perfomance
                        </button>
                    </li>
                @endcan
                @can('mkt.persentasesfa')
                    <li class="nav-item" role="persentasesfa">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persentasesfa"
                            aria-controls="persentasesfa" aria-selected="false" tabindex="-1">
                            Persentase SFA
                        </button>
                    </li>
                @endcan
                @can('mkt.persentasesfa')
                    <li class="nav-item" role="persentasedatapelanggan">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persentasedatapelanggan"
                            aria-controls="persentasedatapelanggan" aria-selected="false" tabindex="-1">
                            Persentase Data Pelanggan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                @can('mkt.penjualan')
                    <div class="tab-pane fade active show" id="penjualan" role="tabpanel">
                        @include('marketing.laporan.penjualan')
                    </div>
                @endcan
                @can('mkt.rekappenjualan')
                    <div class="tab-pane fade" id="rekappenjualan" role="tabpanel">
                        @include('marketing.laporan.rekappenjualan')
                    </div>
                @endcan
                @can('mkt.kasbesar')
                    <div class="tab-pane fade" id="kasbesar" role="tabpanel">
                        @include('marketing.laporan.kasbesar')
                    </div>
                @endcan
                @can('mkt.retur')
                    <div class="tab-pane fade" id="retur" role="tabpanel">
                        @include('marketing.laporan.retur')
                    </div>
                @endcan
                @can('mkt.tunaikredit')
                    <div class="tab-pane fade" id="tunaikredit" role="tabpanel">
                        @include('marketing.laporan.tunaikredit')
                    </div>
                @endcan
                @can('mkt.dpp')
                    <div class="tab-pane fade" id="dpp" role="tabpanel">
                        @include('marketing.laporan.dpp')
                    </div>
                @endcan
                @can('mkt.omsetpelanggan')
                    <div class="tab-pane fade" id="omsetpelanggan" role="tabpanel">
                        @include('marketing.laporan.omsetpelanggan')
                    </div>
                @endcan
                @can('mkt.rekappelanggan')
                    <div class="tab-pane fade" id="rekappelanggan" role="tabpanel">
                        @include('marketing.laporan.rekappelanggan')
                    </div>
                @endcan
                @can('mkt.rekapkendaraan')
                    <div class="tab-pane fade" id="rekapkendaraan" role="tabpanel">
                        @include('marketing.laporan.rekapkendaraan')
                    </div>
                @endcan
                @can('mkt.rekapwilayah')
                    <div class="tab-pane fade" id="rekapwilayah" role="tabpanel">
                        @include('marketing.laporan.rekapwilayah')
                    </div>
                @endcan
                @can('mkt.analisatransaksi')
                    <div class="tab-pane fade" id="analisatransaksi" role="tabpanel">
                        @include('marketing.laporan.analisatransaksi')
                    </div>
                @endcan
                @can('mkt.tunaitransfer')
                    <div class="tab-pane fade" id="tunaitransfer" role="tabpanel">
                        @include('marketing.laporan.tunaitransfer')
                    </div>
                @endcan
                @can('mkt.effectivecall')
                    <div class="tab-pane fade" id="effectivecall" role="tabpanel">
                        @include('marketing.laporan.effectivecall')
                    </div>
                @endcan
                @can('mkt.kartupiutang')
                    <div class="tab-pane fade" id="kartupiutang" role="tabpanel">
                        @include('marketing.laporan.kartupiutang')
                    </div>
                @endcan
                @can('mkt.aup')
                    <div class="tab-pane fade" id="aup" role="tabpanel">
                        @include('marketing.laporan.aup')
                    </div>
                @endcan
                @can('mkt.lebihsatufaktur')
                    <div class="tab-pane fade" id="lebihsatufaktur" role="tabpanel">
                        @include('marketing.laporan.lebihsatufaktur')
                    </div>
                @endcan

                @can('mkt.dppp')
                    <div class="tab-pane fade" id="dppp" role="tabpanel">
                        @include('marketing.laporan.dppp')
                    </div>
                @endcan

                @can('mkt.lhp')
                    <div class="tab-pane fade" id="lhp" role="tabpanel">
                        @include('marketing.laporan.lhp')
                    </div>
                @endcan

                @can('mkt.harganet')
                    <div class="tab-pane fade" id="harganet" role="tabpanel">
                        @include('marketing.laporan.harganet')
                    </div>
                @endcan

                @can('mkt.routingsalesman')
                    <div class="tab-pane fade" id="routingsalesman" role="tabpanel">
                        @include('marketing.laporan.routingsalesman')
                    </div>
                @endcan

                @can('mkt.salesperfomance')
                    <div class="tab-pane fade" id="salesperfomance" role="tabpanel">
                        @include('marketing.laporan.salesperfomance')
                    </div>
                @endcan

                @can('mkt.persentasesfa')
                    <div class="tab-pane fade" id="persentasesfa" role="tabpanel">
                        @include('marketing.laporan.persentasesfa')
                    </div>
                @endcan
                @can('mkt.persentasesfa')
                    <div class="tab-pane fade" id="persentasedatapelanggan" role="tabpanel">
                        @include('marketing.laporan.persentasedatapelanggan')
                    </div>
                @endcan
            </div>
        </div>
    </div>
    @if (auth()->user()->hasAnyPermission(['mkt.komisisalesman', 'mkt.komisidriverhelper']))
        <div class="col-xl-6 col-md-12 col-sm-12">
            <div class="nav-align-left nav-tabs-shadow mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    @can('mkt.komisisalesman')
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#komisisalesman"
                                aria-controls="komisisalesman" aria-selected="false" tabindex="-1">
                                Komisi Salesman
                            </button>
                        </li>
                    @endcan
                    @can('mkt.komisidriverhelper')
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#komisidriverhelper"
                                aria-controls="komisidriverhelper" aria-selected="false" tabindex="-1">
                                Komisi Driver Helper
                            </button>
                        </li>
                    @endcan
                </ul>
                <div class="tab-content">
                    @can('mkt.komisisalesman')
                        <div class="tab-pane fade show active" id="komisisalesman" role="tabpanel">
                            @include('marketing.laporan.komisisalesman')
                        </div>
                    @endcan
                    @can('mkt.komisidriverhelper')
                        <div class="tab-pane fade" id="komisidriverhelper" role="tabpanel">
                            @include('marketing.laporan.komisidriverhelper')
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
