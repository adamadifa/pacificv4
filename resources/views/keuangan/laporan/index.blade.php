@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan')

@section('content')

@section('navigasi')
    <span>Laporan Keuangan</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('keu.ledger')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#ledger" aria-controls="pembelian"
                            aria-selected="false" tabindex="-1">
                            Ledger
                        </button>
                    </li>
                @endcan
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
            </ul>
            <div class="tab-content">
                @can('keu.ledger')
                    <div class="tab-pane fade active show" id="ledger" role="tabpanel">
                        @include('keuangan.laporan.ledger')
                    </div>
                @endcan
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
            </div>
        </div>
    </div>
</div>
@endsection
