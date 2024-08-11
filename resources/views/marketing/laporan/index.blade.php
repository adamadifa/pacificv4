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
            </ul>
            <div class="tab-content">
                @can('mkt.penjualan')
                    <div class="tab-pane fade active show" id="penjualan" role="tabpanel">
                        @include('marketing.laporan.penjualan')
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
            </div>
        </div>
    </div>
</div>
@endsection
