@extends('layouts.app')
@section('titlepage', 'Pelanggan')

@section('style')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Pelanggan</h4>
            <small class="text-muted">Manajemen database pelanggan dan limit piutang.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-users me-1"></i>Pelanggan</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Database Pelanggan! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan</p>
                        <h4 class="text-primary mb-1">{{ formatRupiah($jmlpelanggan) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/card-advance-sale.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Pelanggan Aktif! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan Aktif</p>
                        <h4 class="text-success mb-1">{{ formatRupiah($jmlpelangganaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/girl-with-laptop.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
        <div class="card">
            <div class="d-flex align-items-end row">
                <div class="col-7">
                    <div class="card-body text-nowrap">
                        <h5 class="card-title mb-0">Pelanggan Non Aktif! 🎉</h5>
                        <p class="mb-2">Jumlah Database Pelanggan Non Aktif</p>
                        <h4 class="text-danger mb-1">{{ formatRupiah($jmlpelanggannonaktif) }}</h4>
                    </div>
                </div>
                <div class="col-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-4">
                        <img src="{{ asset('assets/img/illustrations/inactive-customer.png') }}" height="140" alt="view sales">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3 mt-2">
    <div class="col-12 d-flex justify-content-between align-items-center">
        @can('pelanggan.create')
            <a href="#" class="btn btn-primary" id="btncreatePelanggan"><i class="ti ti-plus me-1"></i> Tambah Pelanggan</a>
        @endcan
        @can('pelanggan.show')
            <div class="d-flex gap-2">
                <form action="/pelanggan/export" method="GET" id="formCetak" target="_blank" class="d-flex gap-2">
                    <input type="hidden" name="dari" id='dari_cetak' value="{{ Request('dari') }}" />
                    <input type="hidden" name="sampai" id="sampai_cetak" value="{{ Request('sampai') }}" />
                    <input type="hidden" name="kode_cabang" id="kode_cabang_cetak" value="{{ Request('kode_cabang') }}" />
                    <input type="hidden" name="kode_salesman" id="kode_salesman_cetak" value="{{ Request('kode_salesman') }}" />
                    <input type="hidden" name="status" id="status_cetak" value="{{ Request('status') }}" />
                    <button class="btn btn-label-primary"><i class="ti ti-printer me-1"></i>Cetak</button>
                    <button class="btn btn-label-success" name="exportButton"><i class="ti ti-download me-1"></i>Excel</button>
                </form>
                <a href="#" class="btn btn-label-danger" id="btnNonaktif"><i class="ti ti-user-x me-1"></i>Nonaktifkan</a>
            </div>
        @endcan
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <form action="{{ route('pelanggan.index') }}">
            <div class="row mb-1">
                <div class="col">
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" hideLabel="true" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
                <div class="col-lg-2 col-sm-12 col-md-12">
                    <select name="status" id="status" class="form-select">
                        <option value="">Status</option>
                        <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>
                            Aktif</option>
                        <option value="nonaktif" {{ Request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                </div>
                @hasanyrole($roles_show_cabang)
                    <div class="col-lg-2 col-sm-12 col-md-12">
                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                            selected="{{ Request('kode_cabang') }}" hideLabel="true" />
                    </div>
                @endhasanyrole
                <div class="col-lg-2 col-sm-12 col-md-12">
                    <select name="kode_salesman" id="kode_salesman" class="form-select">
                        <option value="">Salesman</option>
                    </select>
                </div>
                <div class="col-lg-2 col-sm-12 col-md-12">
                    <x-input-with-icon label="Kode" value="{{ Request('kode_pelanggan') }}" name="kode_pelanggan"
                        icon="ti ti-barcode" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-sm-12 col-md-12">
                    <x-input-with-icon label="Nama Pelanggan" value="{{ Request('nama_pelanggan') }}" name="nama_pelanggan"
                        icon="ti ti-user" hideLabel="true" />
                </div>

                <div class="col-lg-1 col-sm-12 col-md-12">
                    <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
                <div class="row" id="pelanggan-list">
                    @foreach ($pelanggan as $d)
                        <div class="col-12 mb-2">
                            <div class="card shadow-none border {{ $d->status_aktif_pelanggan == '0' ? 'bg-label-danger border-danger' : '' }}">
                                <div class="card-body p-2">
                                    <div class="row align-items-center">
                                        <!-- Bagian 1: Identitas (Left) -->
                                        <div class="col-md-3 border-end">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-2">
                                                    <div class="avatar avatar-md">
                                                        @if (!empty($d->foto))
                                                            @if (Storage::disk('public')->exists('/pelanggan/' . $d->foto))
                                                                <img src="{{ getfotoPelanggan($d->foto) }}" alt="Avatar" class="rounded-circle">
                                                            @else
                                                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Avatar" class="rounded-circle">
                                                            @endif
                                                        @else
                                                            <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="Avatar" class="rounded-circle">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="mb-0 fw-bold text-dark line-clamp-2" style="font-size: 0.85rem; line-height: 1.2;">
                                                        {!! textCamelCase($d->nama_pelanggan) !!}
                                                    </div>
                                                    <span class="text-primary small fw-semibold" style="font-size: 0.75rem;">
                                                        {{ $d->kode_pelanggan }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bagian 2: Wilayah & Sales (Center) -->
                                        <div class="col-md-4 border-end">
                                            <div class="row g-0">
                                                <div class="col-6 pe-2">
                                                    <div class="text-muted mb-0" style="font-size: 0.7rem;">Wilayah</div>
                                                    <div class="text-dark fw-semibold line-clamp-2" style="font-size: 0.8rem;">
                                                        {{ textCamelCase($d->nama_wilayah) }}
                                                    </div>
                                                </div>
                                                <div class="col-6 pe-2">
                                                    <div class="text-muted mb-0" style="font-size: 0.7rem;">Salesman</div>
                                                    <div class="text-dark fw-semibold line-clamp-2" style="font-size: 0.8rem;">
                                                        {{ textCamelCase($d->nama_salesman) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bagian 3: Info Register & Limit (Center) -->
                                        <div class="col-md-3 border-end">
                                            <div class="row g-0">
                                                <div class="col-6 text-center pe-2">
                                                    <div class="text-muted mb-0" style="font-size: 0.7rem;">Register</div>
                                                    <div class="text-dark fw-semibold" style="font-size: 0.8rem;">
                                                        {{ date('d/m/y', strtotime($d->tanggal_register)) }}
                                                    </div>
                                                </div>
                                                <div class="col-6 text-center pe-2">
                                                    <div class="text-muted mb-0" style="font-size: 0.7rem;">Limit</div>
                                                    <div class="text-dark fw-bold" style="font-size: 0.8rem;">
                                                        {{ empty($d->limit_pelanggan) ? '-' : formatRupiah($d->limit_pelanggan) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bagian 4: Status & Actions (Right) -->
                                        <div class="col-md-2">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="text-center">
                                                    @if ($d->status_aktif_pelanggan == 1)
                                                        <span class="badge badge-dot bg-success"></span><span class="small ms-1">Aktif</span>
                                                    @else
                                                        <span class="badge badge-dot bg-danger"></span><span class="small ms-1 text-danger">Non</span>
                                                    @endif
                                                </div>
                                                <div class="btn-group shadow-sm">
                                                    @can('pelanggan.edit')
                                                        <a href="#" class="btn btn-icon btn-outline-success editPelanggan" kode_pelanggan="{{ Crypt::encrypt($d->kode_pelanggan) }}" title="Edit">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pelanggan.show')
                                                        <a href="{{ route('pelanggan.show', Crypt::encrypt($d->kode_pelanggan)) }}" class="btn btn-icon btn-outline-info" title="Detail">
                                                            <i class="ti ti-file-description"></i>
                                                        </a>
                                                    @endcan
                                                    @can('pelanggan.delete')
                                                        <form method="POST" name="deleteform" class="deleteform m-0" action="{{ route('pelanggan.delete', Crypt::encrypt($d->kode_pelanggan)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-outline-danger delete-confirm" title="Delete" style="border-top-left-radius: 0 !important; border-bottom-left-radius: 0 !important;">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div style="float: right;">
                            {{ $pelanggan->links() }}
                        </div>
                    </div>
                </div>
<x-modal-form id="mdlcreatePelanggan" size="modal-lg" show="loadcreatePelanggan" title="Tambah Pelanggan" />
<x-modal-form id="mdleditPelanggan" size="modal-lg" show="loadeditPelanggan" title="Edit Pelanggan" />
<x-modal-form id="mdlNonaktifPelanggan" size="modal-xl" show="loadNonaktifPelanggan" title="Nonaktifkan Pelanggan" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreatePelanggan").click(function(e) {
            e.preventDefault();
            $('#mdlcreatePelanggan').modal("show");
            $("#loadcreatePelanggan").load('/pelanggan/create');
        });

        $("#btnNonaktif").click(function(e) {
            e.preventDefault();
            $('#mdlNonaktifPelanggan').modal("show");
            $("#loadNonaktifPelanggan").html(`
            <div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>
            `);
            $("#loadNonaktifPelanggan").load('/pelanggan/nonaktif');
        });

        $(".editPelanggan").click(function(e) {
            var kode_pelanggan = $(this).attr("kode_pelanggan");
            e.preventDefault();
            $('#mdleditPelanggan').modal("show");
            $("#loadeditPelanggan").load('/pelanggan/' + kode_pelanggan + '/edit');
        });

        function getsalesmanbyCabang() {
            var kode_cabang = $("#kode_cabang").val();
            var kode_salesman = "{{ Request('kode_salesman') }}";
            //alert(selected);
            $.ajax({
                type: 'POST',
                url: '/salesman/getsalesmanbycabang',
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_cabang: kode_cabang,
                    kode_salesman: kode_salesman
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#kode_salesman").html(respond);
                }
            });
        }

        getsalesmanbyCabang();
        $("#kode_cabang").change(function(e) {
            getsalesmanbyCabang();
        });
    });
</script>
@endpush
