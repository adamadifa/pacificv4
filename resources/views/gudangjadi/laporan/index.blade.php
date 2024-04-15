@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Jadi')

@section('content')

@section('navigasi')
    <span>Laporan Gudang Jadi</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gj.persediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#persediaan" aria-controls="persediaan" aria-selected="false" tabindex="-1">
                            Laporan Persediaan
                        </button>
                    </li>
                @endcan
                @can('gj.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekappersediaan" aria-controls="rekappersediaan" aria-selected="true">
                            Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('gj.rekaphasilproduksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekaphasilproduksi" aria-controls="rekaphasilproduksi" aria-selected="true">
                            Rekap Hasil Produksi
                        </button>
                    </li>
                @endcan
                @can('gj.rekappengeluaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekappengeluaran" aria-controls="rekappengeluaran" aria-selected="true">
                            Rekap Pengeluaran
                        </button>
                    </li>
                @endcan
                @can('gj.realisasikiriman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#realisasikiriman" aria-controls="realisasikiriman" aria-selected="true">
                            Realisasi Kiriman
                        </button>
                    </li>
                @endcan
                @can('gj.realisasioman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#realisasioman" aria-controls="realisasioman" aria-selected="true">
                            Realisasi OMAN
                        </button>
                    </li>
                @endcan
                @can('gj.angkutan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#angkutan" aria-controls="angkutan" aria-selected="true">
                            Angkutan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                <!-- Laporan Persediaan-->
                @can('gj.persediaan')
                    <div class="tab-pane fade active show" id="persediaan" role="tabpanel">
                        @include('gudangjadi.laporan.persediaan')
                    </div>
                @endcan
                @can('gj.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                        @include('gudangjadi.laporan.rekappersediaan')
                    </div>
                @endcan
                @can('gj.rekaphasilproduksi')
                    <div class="tab-pane fade" id="rekaphasilproduksi" role="tabpanel">
                        @include('gudangjadi.laporan.rekaphasilproduksi')
                    </div>
                @endcan
                @can('gj.rekappengeluaran')
                    <div class="tab-pane fade" id="rekappengeluaran" role="tabpanel">
                        @include('gudangjadi.laporan.rekappengeluaran')
                    </div>
                @endcan
                @can('gj.realisasikiriman')
                    <div class="tab-pane fade" id="realisasikiriman" role="tabpanel">
                        @include('gudangjadi.laporan.realisasikiriman')
                    </div>
                @endcan
                @can('gj.realisasioman')
                    <div class="tab-pane fade" id="realisasioman" role="tabpanel">
                        @include('gudangjadi.laporan.realisasioman')
                    </div>
                @endcan
                @can('gj.angkutan')
                    <div class="tab-pane fade" id="angkutan" role="tabpanel">
                        @include('gudangjadi.laporan.angkutan')
                    </div>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection


@push('myscript')
<script>
    $(function() {
        const select2Kodeproduk = $('.select2Kodeproduk');
        if (select2Kodeproduk.length) {
            select2Kodeproduk.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Produk',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

        const select2Kodeangkutan = $('.select2Kodeangkutan');
        if (select2Kodeangkutan.length) {
            select2Kodeangkutan.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Angkutan',
                    dropdownParent: $this.parent(),
                    allowClear: true
                });
            });
        }

    });
</script>
@endpush
