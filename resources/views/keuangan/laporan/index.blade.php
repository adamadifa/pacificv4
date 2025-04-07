@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan')

@section('content')

@section('navigasi')
    <span>Laporan Keuangan</span>
@endsection
<div class="row">
    <div class="col-xl-8 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('keu.kaskecil')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#kaskecil"
                            aria-controls="kaskecil" aria-selected="false" tabindex="-1">
                            Kas Kecil
                        </button>
                    </li>
                @endcan
                @can('keu.ledger')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#ledger" aria-controls="ledger"
                            aria-selected="false" tabindex="-1">
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
                            aria-controls="kaskecil" aria-selected="false" tabindex="-1">
                            Mutasi Keuangan
                        </button>
                    </li>
                @endcan
                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapledger"
                            aria-controls="saldokasbesar" aria-selected="false" tabindex="-1">
                            Rekap Ledger
                        </button>
                    </li>
                @endhasanyrole
                @can('keu.saldokasbesar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#saldokasbesar"
                            aria-controls="saldokasbesar" aria-selected="false" tabindex="-1">
                            Saldo Kas Besar
                        </button>
                    </li>
                @endcan
                @can('keu.lpu')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#lpu" aria-controls="lpu"
                            aria-selected="false" tabindex="-1">
                            LPU
                        </button>
                    </li>
                @endcan
                @can('keu.penjualan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#penjualan" aria-controls="penjualan"
                            aria-selected="false" tabindex="-1">
                            Penjualan
                        </button>
                    </li>
                @endcan
                @can('keu.uanglogam')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#uanglogam" aria-controls="penjualan"
                            aria-selected="false" tabindex="-1">
                            Uang Logam
                        </button>
                    </li>
                @endcan
                @can('keu.rekapbg')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbg" aria-controls="rekapbg"
                            aria-selected="false" tabindex="-1">
                            Rekap BG
                        </button>
                    </li>
                @endcan
                @can('keu.pinjaman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pinjaman" aria-controls="pinjaman"
                            aria-selected="false" tabindex="-1">
                            PJP
                        </button>
                    </li>
                @endcan
                @can('keu.kasbon')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kasbon" aria-controls="kasbon"
                            aria-selected="false" tabindex="-1">
                            Kasbon
                        </button>
                    </li>
                @endcan
                @can('keu.piutangkaryawan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#piutangkaryawan"
                            aria-controls="piutangkaryawan" aria-selected="false" tabindex="-1">
                            Piutang Karyawan
                        </button>
                    </li>
                @endcan
                @can('keu.kartupinjaman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupinjaman"
                            aria-controls="kartupinjaman" aria-selected="false" tabindex="-1">
                            Kartu PJP
                        </button>
                    </li>
                @endcan
                @can('keu.kartukasbon')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartukasbon"
                            aria-controls="kartukasbon" aria-selected="false" tabindex="-1">
                            Kartu Kasbon
                        </button>
                    </li>
                @endcan
                @can('keu.kartupiutangkaryawan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartupiutangkaryawan"
                            aria-controls="kartupiutangkaryawan" aria-selected="false" tabindex="-1">
                            Kartu Piutang Karyawan
                        </button>
                    </li>
                @endcan
                @can('keu.rekapkartupiutang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapkartupiutang"
                            aria-controls="piutangkaryawan" aria-selected="false" tabindex="-1">
                            Rekap Kartu Pinjaman
                        </button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content">
                @can('keu.kaskecil')
                    <div class="tab-pane fade active show" id="kaskecil" role="tabpanel">
                        @include('keuangan.laporan.kaskecil')
                    </div>
                @endcan
                @can('keu.ledger')
                    <div class="tab-pane fade" id="ledger" role="tabpanel">
                        @include('keuangan.laporan.ledger')
                    </div>
                @endcan
                @can('keu.mutasikeuangan')
                    <div class="tab-pane fade" id="mutasikeuangan" role="tabpanel">
                        @include('keuangan.laporan.mutasikeuangan')
                    </div>
                @endcan
                @hasanyrole(['super admin', 'gm administrasi', 'manager keuangan', 'direktur'])
                    <div class="tab-pane fade" id="rekapledger" role="tabpanel">
                        @include('keuangan.laporan.rekapledger')
                    </div>
                @endhasanyrole
                @can('keu.saldokasbesar')
                    <div class="tab-pane fade" id="saldokasbesar" role="tabpanel">
                        @include('keuangan.laporan.saldokasbesar')
                    </div>
                @endcan
                @can('keu.lpu')
                    <div class="tab-pane fade" id="lpu" role="tabpanel">
                        @include('keuangan.laporan.lpu')
                    </div>
                @endcan
                @can('keu.penjualan')
                    <div class="tab-pane fade" id="penjualan" role="tabpanel">
                        @include('keuangan.laporan.penjualan')
                    </div>
                @endcan
                @can('keu.uanglogam')
                    <div class="tab-pane fade" id="uanglogam" role="tabpanel">
                        @include('keuangan.laporan.uanglogam')
                    </div>
                @endcan
                @can('keu.rekapbg')
                    <div class="tab-pane fade" id="rekapbg" role="tabpanel">
                        @include('keuangan.laporan.rekapbg')
                    </div>
                @endcan
                @can('keu.pinjaman')
                    <div class="tab-pane fade" id="pinjaman" role="tabpanel">
                        @include('keuangan.laporan.pinjaman')
                    </div>
                @endcan
                @can('keu.kasbon')
                    <div class="tab-pane fade" id="kasbon" role="tabpanel">
                        @include('keuangan.laporan.kasbon')
                    </div>
                @endcan
                @can('keu.kasbon')
                    <div class="tab-pane fade" id="piutangkaryawan" role="tabpanel">
                        @include('keuangan.laporan.piutangkaryawan')
                    </div>
                @endcan
                @can('keu.kartupinjaman')
                    <div class="tab-pane fade" id="kartupinjaman" role="tabpanel">
                        @include('keuangan.laporan.kartupjp')
                    </div>
                @endcan
                @can('keu.kartupinjaman')
                    <div class="tab-pane fade" id="kartukasbon" role="tabpanel">
                        @include('keuangan.laporan.kartukasbon')
                    </div>
                @endcan
                @can('keu.kartupiutangkaryawan')
                    <div class="tab-pane fade" id="kartupiutangkaryawan" role="tabpanel">
                        @include('keuangan.laporan.kartupiutangkaryawan')
                    </div>
                @endcan
                @can('keu.rekapkartupiutang')
                    <div class="tab-pane fade" id="rekapkartupiutang" role="tabpanel">
                        @include('keuangan.laporan.rekapkartupiutang')
                    </div>
                @endcan

            </div>
        </div>
    </div>
</div>
@endsection
