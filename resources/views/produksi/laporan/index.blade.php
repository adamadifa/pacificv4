@extends('layouts.app')
@section('titlepage', 'Laporan Produksi')

@section('content')

@section('navigasi')
    <span>Laporan Produksi</span>
@endsection
<div class="row">
    <div class="col-xl-6 col-md-12 col-sm-12">
        <h6 class="text-muted">Mutasi Produksi</h6>
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('prd.mutasiproduksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#mutasiproduksi" aria-controls="mutasiproduksi" aria-selected="false"
                            tabindex="-1">
                            Laporan Mutasi Produksi
                        </button>
                    </li>
                @endcan
                @can('prd.rekapmutasi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekapmutasiproduksi" aria-controls="rekapmutasiproduksi" aria-selected="true">
                            Rekap Mutasi Produksi
                        </button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content">
                <!-- Laporan Mutasi Produksi-->
                @can('prd.mutasiproduksi')
                    <div class="tab-pane fade active show" id="mutasiproduksi" role="tabpanel">
                        @include('produksi.laporan.mutasiproduksi')
                    </div>
                @endcan
                <!-- Rekap Mutasi Produksi-->
                @can('prd.rekapmutasi')
                    <div class="tab-pane fade " id="rekapmutasiproduksi" role="tabpanel">
                        @include('produksi.laporan.rekapmutasiproduksi')
                    </div>
                @endcan

            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-12 col-sm-12">
        <h6 class="text-muted">Mutasi Barang Produksi</h6>
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('prd.pemasukan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#barangmasuk" aria-controls="barangmasuk" aria-selected="false" tabindex="-1">
                            Laporan Barang Masuk
                        </button>
                    </li>
                @endcan
                @can('prd.pengeluaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#barangkeluar" aria-controls="barangkeluar" aria-selected="true">
                            Laporan Barang Keluar
                        </button>
                    </li>
                @endcan
                @can('prd.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekappersediaanbarang" aria-controls="rekappersediaanbarang"
                            aria-selected="true">
                            Rekap Persediaan
                        </button>
                    </li>
                @endcan

            </ul>
            <div class="tab-content">
                <!-- Laporan Barang masuk-->
                @can('prd.pemasukan')
                    <div class="tab-pane fade active show" id="barangmasuk" role="tabpanel">
                        @include('produksi.laporan.barangmasuk')
                    </div>
                @endcan
                <!-- Laporan Barang keluar-->
                @can('prd.pengeluaran')
                    <div class="tab-pane fade " id="barangkeluar" role="tabpanel">
                        @include('produksi.laporan.barangkeluar')
                    </div>
                @endcan
                <div class="tab-pane fade " id="rekappersediaanbarang" role="tabpanel">
                    @include('produksi.laporan.rekappersediaanbarang')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/laporanproduksi/mutasiproduksi.js') }}"></script>
<script src="{{ asset('assets/js/pages/laporanproduksi/rekapmutasiproduksi.js') }}"></script>
<script src="{{ asset('assets/js/pages/laporanproduksi/barangmasuk.js') }}"></script>
<script src="{{ asset('assets/js/pages/laporanproduksi/barangkeluar.js') }}"></script>
<script src="{{ asset('assets/js/pages/laporanproduksi/rekappersediaanbarang.js') }}"></script>
<script>
    $(function() {
        const select2Kodeproduk = $('.select2Kodeproduk');
        const select2Kodebarangmasuk = $('.select2Kodebarangmasuk');
        const select2Kodebarangkeluar = $('.select2Kodebarangkeluar');



        function initselect2select2Kodeproduk() {
            if (select2Kodeproduk.length) {
                select2Kodeproduk.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Pilih Produk',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }

        function initselect2Kodebarangmasuk() {
            if (select2Kodebarangmasuk.length) {
                select2Kodebarangmasuk.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        // placeholder: 'Semua Barang',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }


        function initselect2Kodebarangkeluar() {
            if (select2Kodebarangkeluar.length) {
                select2Kodebarangkeluar.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        // placeholder: 'Semua Barang',
                        dropdownParent: $this.parent(),

                    });
                });
            }
        }





        initselect2select2Kodeproduk();
        initselect2Kodebarangmasuk();
        initselect2Kodebarangkeluar();



    });
</script>
@endpush
