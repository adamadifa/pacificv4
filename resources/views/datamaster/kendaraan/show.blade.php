@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@section('titlepage', 'Kendaraan')

@section('content')
@section('navigasi')
    <span class="text-muted">Kendaraan/</span> Detail
@endsection
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset('assets/img/pages/profile-bg.jpg') }}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    <img src="{{ asset('assets/img/illustrations/truck.png') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">

                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{ textUppercase($kendaraan->no_polisi) }}</h4>
                            <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-color-swatch"></i> {{ textCamelCase($kendaraan->tipe_kendaraan) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1"><i class="ti ti-user"></i>
                                    {{ textCamelCase($kendaraan->atas_nama) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-database"></i> {{ formatRupiah($kendaraan->kapasitas) }}
                                </li>
                            </ul>
                        </div>
                        @if ($kendaraan->status_aktif_kendaraan === '1')
                            <a href="javascript:void(0)" class="btn btn-success waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Aktif
                            </a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Nonaktif
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- User Profile Content -->
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5">
        <!-- About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Data Kendaraan</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Kode
                            Kendaraan:</span> <span>{{ $kendaraan->kode_Kendaraan }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. Polisi:</span> <span>{{ $kendaraan->no_polisi }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. STNK:</span> <span>{{ $kendaraan->no_stnk }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. Uji:</span> <span>{{ $kendaraan->no_uji }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">
                            SIPA:</span> <span>{{ $kendaraan->sipa }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Merk:</span> <span>{{ $kendaraan->merek }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Type Kendaraan:</span> <span>{{ $kendaraan->tipe_kendaraan }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Type:</span> <span>{{ $kendaraan->tipe }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. Rangka:</span> <span>{{ $kendaraan->no_rangka }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. Mesin:</span> <span>{{ $kendaraan->no_mesin }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file-description text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Tahun Pembuatan:</span> <span>{{ $kendaraan->tahun_pembuatan }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Atas Nama:</span> <span>{{ textCamelcase($kendaraan->atas_nama) }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Jatuh Tempo Kir:</span>
                        <span>{{ !empty($kendaraan->jatuhtempo_kir) ? DateToIndo($kendaraan->jatuhtempo_kir) : '' }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Jatuh Tempo Pajak 1 Tahun:</span>
                        <span>{{ !empty($kendaraan->jatuhtempo_pajak_satutahun) ? DateToIndo($kendaraan->jatuhtempo_pajak_satutahun) : '' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Jatuh Tempo Pajak 5 Tahun:</span>
                        <span>{{ !empty($kendaraan->jatuhtempo_pajak_limatahun) ? DateToIndo($kendaraan->jatuhtempo_pajak_limatahun) : '' }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-box text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Kapasitas:</span>
                        <span>{{ formatRupiah($kendaraan->kapasitas) }}</span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-box text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Cabang:</span>
                        <span>{{ textCamelcase($kendaraan->nama_cabang) }}</span>
                    </li>
                </ul>

            </div>
        </div>
        <!--/ About User -->

    </div>
    <div class="col-xl-8 col-lg-7 col-md-7">
        <!-- Activity Timeline -->
        <div class="card card-action mb-4">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">Activity Timeline</h5>
            </div>
            <div class="card-body pb-0">

            </div>
        </div>
        <!--/ Activity Timeline -->
    </div>
</div>
<!--/ User Profile Content -->
@endsection
