@extends('layouts.app')
@section('titlepage', 'Laporan Maintenance')

@section('content')

@section('navigasi')
    <span>Laporan Maintenance</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('mtc.bahanbakar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#bahanbakar"
                            aria-controls="bahanbakar" aria-selected="false" tabindex="-1">
                            Bahan Bakar
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('mtc.bahanbakar')
                    <div class="tab-pane fade active show" id="bahanbakar" role="tabpanel">
                        @include('maintenance.laporan.bahanbakar');
                    </div>
                @endcan


            </div>
        </div>
    </div>

</div>
@endsection
