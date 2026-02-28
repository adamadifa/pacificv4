@extends('layouts.app')
@section('titlepage', 'Barang Keluar Produksi')

@section('content')
@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Barang Keluar Produksi</h4>
            <small class="text-muted">Mengelola data barang keluar dari produksi.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Produksi</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package-export me-1"></i>Barang Keluar</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_mutasibarangproduksi')
        </div>

        {{-- Filter Section (Below Navigation) --}}
        <form action="{{ route('barangkeluarproduksi.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 align-items-end">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                datepicker="flatpickr-date" value="{{ Request('dari') }}" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                datepicker="flatpickr-date" value="{{ Request('sampai') }}" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                value="{{ Request('no_bukti_search') }}" />
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis Pengeluaran</label>
                                <select name="kode_jenis_pengeluaran_search" id="kode_jenis_pengeluaran_search"
                                    class="form-select">
                                    <option value="">Semua</option>
                                    <option value="RO"
                                        {{ Request('kode_jenis_pengeluaran_search') == 'RO' ? 'selected' : '' }}>
                                        Retur Out</option>
                                    <option value="PK"
                                        {{ Request('kode_jenis_pengeluaran_search') == 'PK' ? 'selected' : '' }}>
                                        Pemakaian</option>
                                    <option value="LN"
                                        {{ Request('kode_jenis_pengeluaran_search') == 'LN' ? 'selected' : '' }}>
                                        Lainnya
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1 col-md-2 col-sm-12">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package-export me-2"></i>Data Barang Keluar</h6>
                    @can('barangkeluarproduksi.create')
                        <a href="{{ route('barangkeluarproduksi.create') }}" class="btn btn-primary btn-sm shadow-sm">
                            <i class="ti ti-plus me-1"></i> Tambah Data
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO.</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NO. BUKTI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">TANGGAL</th>
                            <th class="text-white" style="background-color: #002e65 !important;">JENIS PENGELUARAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">SUPPLIER</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barangkeluar as $d)
                            <tr>
                                <td>{{ $loop->iteration + $barangkeluar->firstItem() - 1 }}</td>
                                <td><span class="fw-bold text-primary">{{ $d->no_bukti }}</span></td>
                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                <td>{{ $jenis_pengeluaran[$d->kode_jenis_pengeluaran] }}</td>
                                <td>{{ $d->nama_supplier }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('barangkeluarproduksi.edit')
                                            <a href="{{ route('barangkeluarproduksi.edit', Crypt::encrypt($d->no_bukti)) }}"
                                                class="text-success" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('barangkeluarproduksi.show')
                                            <a href="#" class="showDetail text-info" data-bs-toggle="tooltip" title="Detail"
                                                no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                <i class="ti ti-file-description fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('barangkeluarproduksi.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('barangkeluarproduksi.delete', Crypt::encrypt($d->no_bukti)) }}">
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

<x-modal-form id="mdldetail" size="modal-xl" show="loaddetail" title="Detail" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".showDetail").click(function(e) {
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/barangkeluarproduksi/' + no_bukti + '/show');
        });
    });
</script>
@endpush
