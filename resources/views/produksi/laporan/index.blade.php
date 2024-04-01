@extends('layouts.app')
@section('titlepage', 'Laporan Produksi')

@section('content')
@section('navigasi')
    <span>Laporan Produksi</span>
@endsection
<div class="row">
    <div class="col-xl-6">
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
                <div class="tab-pane fade active show" id="mutasiproduksi" role="tabpanel">
                    @include('produksi.laporan.mutasiproduksi')
                </div>
                <div class="tab-pane fade " id="rekapmutasiproduksi" role="tabpanel">
                    @include('produksi.laporan.rekapmutasiproduksi')
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script src="{{ asset('assets/js/pages/laporanproduksi/mutasiproduksi.js') }}"></script>
<script>
    $(function() {
        const select2Kodeproduk = $('.select2Kodeproduk');

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

        initselect2select2Kodeproduk();

        $("#exportButton").click(function(e) {
            $("#export").val(1);
        });
    });
</script>
@endpush
