@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Logistik')

@section('content')

@section('navigasi')
    <span>Laporan Gudang Logistik</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gl.barangmasuk')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#barangmasuk"
                            aria-controls="barangmasuk" aria-selected="false" tabindex="-1">
                            Laporan Barang Masuk
                        </button>
                    </li>
                @endcan
                @can('gl.barangkeluar')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#barangkeluar"
                            aria-controls="barangkeluar" aria-selected="false" tabindex="-1">
                            Laporan Barang Keluar
                        </button>
                    </li>
                @endcan
                @can('gl.persediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persediaan"
                            aria-controls="persediaan" aria-selected="false" tabindex="-1">
                            Laporan Persediaan
                        </button>
                    </li>
                @endcan
                @can('gl.persediaanopname')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#persediaanopname"
                            aria-controls="persediaanopname" aria-selected="false" tabindex="-1">
                            Persediaan Opname
                        </button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content">
                <!-- Laporan Barang Masuk-->
                @can('gl.barangmasuk')
                    <div class="tab-pane fade active show" id="barangmasuk" role="tabpanel">
                        @include('gudanglogistik.laporan.barangmasuk')
                    </div>
                @endcan
                <!--Laporan Barang Keluar-->
                @can('gl.barangkeluar')
                    <div class="tab-pane fade" id="barangkeluar" role="tabpanel">
                        @include('gudanglogistik.laporan.barangkeluar')
                    </div>
                @endcan

                @can('gl.persediaan')
                    <div class="tab-pane fade" id="persediaan" role="tabpanel">
                        @include('gudanglogistik.laporan.persediaan')
                    </div>
                @endcan
                @can('gl.persediaanopname')
                    <div class="tab-pane fade" id="persediaanopname" role="tabpanel">
                        @include('gudanglogistik.laporan.opname')
                    </div>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
