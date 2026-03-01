@extends('layouts.app')
@section('titlepage', 'Barang Keluar Maintenance')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Barang Keluar Maintenance</h4>
            <small class="text-muted">Mengelola data barang keluar maintenance.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Maintenance</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-export me-1"></i>Barang Keluar</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_maintenance')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('barangkeluarmtc.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-10 col-md-10 col-sm-12">
                            <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                value="{{ Request('no_bukti_search') }}" />
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i> Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3"
                style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-export me-2"></i>Data Barang Keluar</h6>
                    @can('barangkeluarmtc.create')
                        <a href="#" class="btn btn-primary btn-sm shadow-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Data
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. BUKTI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">DEPARTEMEN</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barangkeluar as $d)
                            <tr>
                                <td><span class="fw-bold text-primary">{{ $d->no_bukti }}</span></td>
                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                <td>{{ $d->nama_dept }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('barangkeluarmtc.show')
                                            <a href="#" class="btnShow text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('barangkeluarmtc.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('barangkeluarmtc.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $barangkeluar->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        function loadingElement() {
            const loading = `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`;

            return loading;
        };

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Barang Keluar");
            $("#loadmodal").html(loadingElement());
            $("#modal").find(".modal-dialog").addClass('modal-lg');
            $("#loadmodal").load(`/barangkeluarmaintenance/create`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Detail Barang Keluar");
            $("#loadmodal").html(loadingElement());
            $("#modal").find(".modal-dialog").addClass('modal-lg');
            $("#loadmodal").load(`/barangkeluarmaintenance/${no_bukti}/show`);
        });


    });
</script>
@endpush
