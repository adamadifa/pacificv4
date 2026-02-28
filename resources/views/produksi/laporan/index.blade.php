@extends('layouts.app')
@section('titlepage', 'Laporan Produksi')

@section('content')

@section('navigasi')
    <span>Laporan Produksi</span>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('prd.mutasiproduksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#mutasiproduksi" aria-controls="mutasiproduksi" aria-selected="false"
                            tabindex="-1">
                            <i class="ti ti-refresh me-1"></i> Mutasi Produksi
                        </button>
                    </li>
                @endcan
                @can('prd.rekapmutasi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekapmutasiproduksi" aria-controls="rekapmutasiproduksi" aria-selected="true">
                            <i class="ti ti-file-analytics me-1"></i> Rekap Mutasi
                        </button>
                    </li>
                @endcan
                @can('prd.pemasukan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#barangmasuk" aria-controls="barangmasuk" aria-selected="false" tabindex="-1">
                            <i class="ti ti-arrow-bar-to-down me-1"></i> Barang Masuk
                        </button>
                    </li>
                @endcan
                @can('prd.pengeluaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#barangkeluar" aria-controls="barangkeluar" aria-selected="true">
                            <i class="ti ti-arrow-bar-to-up me-1"></i> Barang Keluar
                        </button>
                    </li>
                @endcan
                @can('prd.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                            data-bs-target="#rekappersediaanbarang" aria-controls="rekappersediaanbarang" aria-selected="true">
                            <i class="ti ti-box me-1"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content">
                @can('prd.mutasiproduksi')
                    <div class="tab-pane fade active show" id="mutasiproduksi" role="tabpanel">
                        @include('produksi.laporan.mutasiproduksi')
                    </div>
                @endcan
                @can('prd.rekapmutasi')
                    <div class="tab-pane fade" id="rekapmutasiproduksi" role="tabpanel">
                        @include('produksi.laporan.rekapmutasiproduksi')
                    </div>
                @endcan
                @can('prd.pemasukan')
                    <div class="tab-pane fade" id="barangmasuk" role="tabpanel">
                        @include('produksi.laporan.barangmasuk')
                    </div>
                @endcan
                @can('prd.pengeluaran')
                    <div class="tab-pane fade" id="barangkeluar" role="tabpanel">
                        @include('produksi.laporan.barangkeluar')
                    </div>
                @endcan
                @can('prd.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaanbarang" role="tabpanel">
                        @include('produksi.laporan.rekappersediaanbarang')
                    </div>
                @endcan
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
