@extends('layouts.app')
@section('titlepage', 'Laporan Accounting')

@section('content')

@section('navigasi')
    <span>Laporan Accounting</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('akt.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#rekkappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('akt.rekapbj')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapbj" aria-controls="rekapbj"
                            aria-selected="false" tabindex="-1">
                            Rekap BJ
                        </button>
                    </li>
                @endcan
                @can('akt.costratio')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#costratio" aria-controls="costratio"
                            aria-selected="false" tabindex="-1">
                            Cost Ratio
                        </button>
                    </li>
                @endcan
                @can('akt.jurnalumum')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#jurnalumum"
                            aria-controls="jurnalumum" aria-selected="false" tabindex="-1">
                            Jurnal Umum
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('akt.rekappersediaan')
                    <div class="tab-pane fade active show" id="rekkappersediaan" role="tabpanel">
                        @include('accounting.laporan.rekappersediaan')
                    </div>
                @endcan
                @can('akt.rekapbj')
                    <div class="tab-pane fad" id="rekapbj" role="tabpanel">
                        @include('accounting.laporan.rekapbj')
                    </div>
                @endcan
                @can('akt.costratio')
                    <div class="tab-pane fad" id="costratio" role="tabpanel">
                        @include('accounting.laporan.costratio')
                    </div>
                @endcan
                @can('akt.jurnalumum')
                    <div class="tab-pane fad" id="jurnalumum" role="tabpanel">
                        @include('accounting.laporan.jurnalumum')
                    </div>
                @endcan
            </div>
        </div>
    </div>
    @if (auth()->user()->hasAnyPermission(['lk.bukubesar']))
        <div class="col-xl-6 col-md-12 col-sm-12">
            <div class="nav-align-left nav-tabs-shadow mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    @can('lk.bukubesar')
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#rekkappersediaan"
                                aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                                Laporan Keuangan
                            </button>
                        </li>
                    @endcan

                </ul>
                <div class="tab-content">
                    <!-- Laporan Persediaan-->
                    @can('lk.bukubesar')
                        <div class="tab-pane fade active show" id="rekkappersediaan" role="tabpanel">
                            @include('accounting.laporan.lk.bukubesar')
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
