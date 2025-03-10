@extends('layouts.app')
@section('titlepage', 'Laporan HRD')

@section('content')

@section('navigasi')
    <span>Laporan HRD</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('hrd.presensi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#presensi"
                            aria-controls="presensi" aria-selected="false" tabindex="-1">
                            Presensi
                        </button>
                    </li>
                @endcan
                @can('hrd.gaji')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#gaji" aria-controls="gaji"
                            aria-selected="false" tabindex="-1">
                            Gaji
                        </button>
                    </li>
                @endcan
                @can('hrd.presensi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#cuti" aria-controls="cuti"
                            aria-selected="false" tabindex="-1">
                            Cuti
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('hrd.presensi')
                    <div class="tab-pane fade active show" id="presensi" role="tabpanel">
                        @include('hrd.laporan.presensi')
                    </div>
                @endcan
                @can('hrd.gaji')
                    <div class="tab-pane fade" id="gaji" role="tabpanel">
                        @include('hrd.laporan.gaji')
                    </div>
                @endcan
                @can('hrd.presensi')
                    <div class="tab-pane fade" id="cuti" role="tabpanel">
                        @include('hrd.laporan.cuti')
                    </div>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
