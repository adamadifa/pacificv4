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

            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('keu.ledger')
                    <div class="tab-pane fade active show" id="ledger" role="tabpanel">
                        @include('keuangan.laporan.ledger')
                    </div>
                @endcan


            </div>
        </div>
    </div>
</div>
@endsection
