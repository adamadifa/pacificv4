@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@section('titlepage', 'Pelanggan')

@section('content')
@section('navigasi')
    <span class="text-muted">Pelanggan/</span> Detail
@endsection
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset('assets/img/pages/profile-banner.png') }}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    @if (Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                        <img src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="user image"
                            class="d-block  ms-0 ms-sm-4 rounded user-profile-img" height="150">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif

                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{ textCamelCase($pelanggan->nama_pelanggan) }}</h4>
                            <ul
                                class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-color-swatch"></i> {{ textCamelCase($pelanggan->nama_cabang) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1"><i class="ti ti-user"></i>
                                    {{ textCamelCase($pelanggan->nama_salesman) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-map-pin"></i> {{ textCamelCase($pelanggan->nama_wilayah) }}
                                </li>
                            </ul>
                        </div>
                        @if ($pelanggan->status_aktif_pelanggan === '1')
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
                <small class="card-text text-uppercase">Data Pelanggan</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Kode
                            Pelanggan:</span> <span>{{ $pelanggan->kode_pelanggan }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Nama Pelanggan:</span> <span>{{ textCamelCase($pelanggan->nama_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-credit-card text-heading"></i><span
                            class="fw-medium mx-2 text-heading">NIK:</span>
                        <span>{{ $pelanggan->nik }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-credit-card text-heading"></i><span class="fw-medium mx-2 text-heading">No.
                            KK:</span>
                        <span>{{ $pelanggan->no_kk }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">Tanggal
                            Lahir:</span>
                        <span>{{ !empty($pelanggan->tanggal_lahir) ? DateToIndo($pelanggan->tanggal_lahir) : '' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
                            Pelanggan:</span> <span>{{ textCamelCase($pelanggan->alamat_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Alamat
                            Toko:</span> <span>{{ textCamelCase($pelanggan->alamat_toko) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">Wilayah/Rute
                            :</span> <span>{{ textCamelCase($pelanggan->nama_wilayah) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-phone text-heading"></i><span class="fw-medium mx-2 text-heading">No. HP
                            :</span> <span>{{ textCamelCase($pelanggan->no_hp_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Hari
                            :</span> <span>{{ textCamelCase($pelanggan->hari) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-phone text-heading"></i><span class="fw-medium mx-2 text-heading">No. HP
                            :</span> <span>{{ textCamelCase($pelanggan->no_hp_pelanggan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">LJT
                            :</span> <span>{{ $pelanggan->ljt }} Hari</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Kepemilikan
                            :</span>
                        <span>{{ !empty($pelanggan->kepemilikan) ? $kepemilikan[$pelanggan->kepemilikan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Lama Berjualan
                            :</span>
                        <span>{{ !empty($pelanggan->lama_berjualan) ? $lama_berjualan[$pelanggan->lama_berjualan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Status Outlet
                            :</span>
                        <span>{{ !empty($pelanggan->status_outlet) ? $status_outlet[$pelanggan->status_outlet] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Type Outlet
                            :</span>
                        <span>{{ !empty($pelanggan->type_outlet) ? $type_outlet[$pelanggan->type_outlet] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Cara Pembayaran
                            :</span>
                        <span>{{ !empty($pelanggan->cara_pembayaran) ? $cara_pembayaran[$pelanggan->cara_pembayaran] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Lama Langganan
                            :</span>
                        <span>{{ !empty($pelanggan->lama_langganan) ? $lama_langganan[$pelanggan->lama_langganan] : '' }}
                        </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Jaminan
                            :</span> <span>{{ $pelanggan->jaminan == 1 ? 'Ada' : 'Tidak Ada' }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Omset Toko
                            :</span> <span>{{ formatRupiah($pelanggan->omset_toko) }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-file text-heading"></i><span class="fw-medium mx-2 text-heading">Limit
                            Pelanggan
                            :</span> <span>{{ formatRupiah($pelanggan->limit_pelanggan) }} </span>
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
