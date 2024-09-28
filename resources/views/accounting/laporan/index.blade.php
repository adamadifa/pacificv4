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

            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('akt.rekappersediaan')
                    <div class="tab-pane fade active show" id="goodstok" role="tabpanel">
                        @include('accounting.laporan.rekappersediaan')
                    </div>
                @endcan


            </div>
        </div>
    </div>

</div>
@endsection
