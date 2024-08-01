@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Bahan')

@section('content')

@section('navigasi')
    <span>Laporan Gudang Bahan</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gb.barangmasuk')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#barangmasuk"
                            aria-controls="barangmasuk" aria-selected="false" tabindex="-1">
                            Laporan Barang Masuk
                        </button>
                    </li>
                @endcan
                @can('gb.barangkeluar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#barangkeluar"
                            aria-controls="barangkeluar" aria-selected="false" tabindex="-1">
                            Laporan Barang Keluar
                        </button>
                    </li>
                @endcan
                @can('gb.persediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persediaan"
                            aria-controls="persediaan" aria-selected="false" tabindex="-1">
                            Laporan Persediaan
                        </button>
                    </li>
                @endcan
                @can('gb.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="false" tabindex="-1">
                            Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('gb.kartugudang')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#kartugudang"
                            aria-controls="kartugudang" aria-selected="false" tabindex="-1">
                            Kartu Gudang
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('gb.barangmasuk')
                    <div class="tab-pane fade active show" id="barangmasuk" role="tabpanel">
                        @include('gudangbahan.laporan.barangmasuk')
                    </div>
                @endcan
                @can('gb.barangkeluar')
                    <div class="tab-pane fade" id="barangkeluar" role="tabpanel">
                        @include('gudangbahan.laporan.barangkeluar')
                    </div>
                @endcan
                @can('gb.persediaan')
                    <div class="tab-pane fade" id="persediaan" role="tabpanel">
                        @include('gudangbahan.laporan.persediaan')
                    </div>
                @endcan

                @can('gb.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                        @include('gudangbahan.laporan.rekappersediaan')
                    </div>
                @endcan

                @can('gb.kartugudang')
                    <div class="tab-pane fade" id="kartugudang" role="tabpanel">
                        @include('gudangbahan.laporan.kartugudang')
                    </div>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
