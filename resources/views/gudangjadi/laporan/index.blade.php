@extends('layouts.app')
@section('titlepage', 'Laporan Gudang Jadi')

@section('content')

@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Laporan Gudang Jadi</h4>
            <small class="text-muted">Laporan mutasi dan persediaan gudang jadi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Gudang Jadi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-report me-1"></i>Laporan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-8 col-md-10 col-12">
        <div class="nav-align-left nav-tabs-shadow mb-4">
            <ul class="nav nav-tabs" role="tablist">
                @can('gj.persediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#persediaan"
                            aria-controls="persediaan" aria-selected="false" tabindex="-1">
                            <i class="ti ti-box me-2"></i> Persediaan
                        </button>
                    </li>
                @endcan
                @can('gj.rekappersediaan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappersediaan"
                            aria-controls="rekappersediaan" aria-selected="true">
                            <i class="ti ti-file-analytics me-2"></i> Rekap Persediaan
                        </button>
                    </li>
                @endcan
                @can('gj.rekaphasilproduksi')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekaphasilproduksi"
                            aria-controls="rekaphasilproduksi" aria-selected="true">
                            <i class="ti ti-assembly me-2"></i> Hasil Produksi
                        </button>
                    </li>
                @endcan
                @can('gj.rekappengeluaran')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#rekappengeluaran"
                            aria-controls="rekappengeluaran" aria-selected="true">
                            <i class="ti ti-arrow-bar-to-up me-2"></i> Rekap Pengeluaran
                        </button>
                    </li>
                @endcan
                @can('gj.realisasikiriman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#realisasikiriman"
                            aria-controls="realisasikiriman" aria-selected="true">
                            <i class="ti ti-truck-delivery me-2"></i> Realisasi Kiriman
                        </button>
                    </li>
                @endcan
                @can('gj.realisasioman')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#realisasioman"
                            aria-controls="realisasioman" aria-selected="true">
                            <i class="ti ti-clipboard-list me-2"></i> Realisasi OMAN
                        </button>
                    </li>
                @endcan
                @can('gj.angkutan')
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#angkutan"
                            aria-controls="angkutan" aria-selected="true">
                            <i class="ti ti-truck me-2"></i> Angkutan
                        </button>
                    </li>
                @endcan
            </ul>
            <div class="tab-content" style="padding: 0 !important; border: none !important; background: transparent !important;">
                @can('gj.persediaan')
                    <div class="tab-pane fade active show" id="persediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Laporan Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.persediaan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.rekappersediaan')
                    <div class="tab-pane fade" id="rekappersediaan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-analytics me-2"></i>Rekap Persediaan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.rekappersediaan')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.rekaphasilproduksi')
                    <div class="tab-pane fade" id="rekaphasilproduksi" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-assembly me-2"></i>Rekap Hasil Produksi</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.rekaphasilproduksi')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.rekappengeluaran')
                    <div class="tab-pane fade" id="rekappengeluaran" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-arrow-bar-to-up me-2"></i>Rekap Pengeluaran</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.rekappengeluaran')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.realisasikiriman')
                    <div class="tab-pane fade" id="realisasikiriman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck-delivery me-2"></i>Realisasi Kiriman</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.realisasikiriman')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.realisasioman')
                    <div class="tab-pane fade" id="realisasioman" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Realisasi OMAN</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.realisasioman')
                            </div>
                        </div>
                    </div>
                @endcan
                @can('gj.angkutan')
                    <div class="tab-pane fade" id="angkutan" role="tabpanel">
                        <div class="card shadow-none border">
                            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                                <h6 class="m-0 fw-bold text-white"><i class="ti ti-truck me-2"></i>Laporan Angkutan</h6>
                            </div>
                            <div class="card-body pt-4">
                                @include('gudangjadi.laporan.angkutan')
                            </div>
                        </div>
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
