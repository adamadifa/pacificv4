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
            </ul>
            <div class="tab-content">
                @can('mkt.penjualan')
                    <div class="tab-pane fade active show" id="ledger" role="tabpanel">
                        @include('marketing.laporan.penjualan')
                    </div>
                @endcan


            </div>
        </div>
    </div>
</div>
@endsection
