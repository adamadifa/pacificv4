@extends('layouts.app')
@section('titlepage', 'Laporan Accounting')

@section('content')

@section('navigasi')
    <span>Laporan Accounting</span>
@endsection
<div class="row">
    <!-- Col-6 / Col-8 constraint -->
    <div class="col-lg-8 col-md-10 col-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('akt.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-box me-1"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('akt.rekapbj')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbj" aria-controls="rekapbj"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-file-invoice me-1"></i> Rekap BJ
                        </button>
                    </li>
                @endcan
                @can('akt.costratio')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#costratio" aria-controls="costratio"
                            aria-selected="false" tabindex="-1">
                            <i class="ti ti-chart-pie me-1"></i> Cost Ratio
                        </button>
                    </li>
                @endcan
                @can('akt.jurnalumum')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#jurnalumum"
                            aria-controls="jurnalumum" aria-selected="false" tabindex="-1">
                            <i class="ti ti-book me-1"></i> Jurnal Umum
                        </button>
                    </li>
                @endcan
                @can('lk.bukubesar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#bukubesar"
                            aria-controls="bukubesar" aria-selected="false" tabindex="-1">
                            <i class="ti ti-report-money me-1"></i> Laporan Keuangan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                @can('akt.rekappersediaan')
                    <div class="tab-pane fade active show" id="rekappersediaan" role="tabpanel">
                        @include('accounting.laporan.rekappersediaan')
                    </div>
                @endcan
                @can('akt.rekapbj')
                    <div class="tab-pane fade" id="rekapbj" role="tabpanel">
                        @include('accounting.laporan.rekapbj')
                    </div>
                @endcan
                @can('akt.costratio')
                    <div class="tab-pane fade" id="costratio" role="tabpanel">
                        @include('accounting.laporan.costratio')
                    </div>
                @endcan
                @can('akt.jurnalumum')
                    <div class="tab-pane fade" id="jurnalumum" role="tabpanel">
                        @include('accounting.laporan.jurnalumum')
                    </div>
                @endcan
                @can('lk.bukubesar')
                    <div class="tab-pane fade" id="bukubesar" role="tabpanel">
                        @include('accounting.laporan.lk.bukubesar')
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
