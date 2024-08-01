@extends('layouts.app')
@section('titlepage', 'Laporan General Affair')

@section('content')

@section('navigasi')
    <span>Laporan General Affair</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('pb.pembelian')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#pembelian"
                            aria-controls="pembelian" aria-selected="false" tabindex="-1">
                            Pembelian
                        </button>
                    </li>
                @endcan
                @can('pb.pembayaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#pembayaran"
                            aria-controls="pembayaran" aria-selected="false" tabindex="-1">
                            Pembayaran
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('pb.pembelian')
                    <div class="tab-pane fade active show" id="pembelian" role="tabpanel">
                        @include('pembelian.laporan.pembelian')
                    </div>
                @endcan

                @can('pb.pembayaran')
                    <div class="tab-pane fade" id="pembayaran" role="tabpanel">
                        @include('pembelian.laporan.pembayaran')
                    </div>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
