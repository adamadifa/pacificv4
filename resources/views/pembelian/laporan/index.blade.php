@extends('layouts.app')
@section('titlepage', 'Laporan Pembelian')

@section('content')

@section('navigasi')
    <span>Laporan Pembelian</span>
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
                @can('pb.rekapsupplier')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekapsupplier"
                            aria-controls="rekapsupplier" aria-selected="false" tabindex="-1">
                            Rekap Pembelian Supplier
                        </button>
                    </li>
                @endcan
                @can('pb.rekappembelian')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappembelian"
                            aria-controls="rekappembelian" aria-selected="false" tabindex="-1">
                            Rekap Pembelian
                        </button>
                    </li>
                @endcan
                @can('pb.kartuhutang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartuhutang"
                            aria-controls="kartuhutang" aria-selected="false" tabindex="-1">
                            Kartu Hutang
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

                @can('pb.rekapsupplier')
                    <div class="tab-pane fade" id="rekapsupplier" role="tabpanel">
                        @include('pembelian.laporan.rekapsupplier')
                    </div>
                @endcan
                @can('pb.rekappembelian')
                    <div class="tab-pane fade" id="rekappembelian" role="tabpanel">
                        @include('pembelian.laporan.rekappembelian')
                    </div>
                @endcan
                @can('pb.kartuhutang')
                    <div class="tab-pane fade" id="kartuhutang" role="tabpanel">
                        @include('pembelian.laporan.kartuhutang')
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
