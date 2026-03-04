@extends('layouts.app')
@section('titlepage', 'Kategori Produk')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Kategori Produk</h4>
            <small class="text-muted">Mengelola pengelompokan kategori produk.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-category me-1"></i>Kategori Produk</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('kategoriproduk.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Kategori Produk" value="{{ Request('nama_kategori_produk') }}" name="nama_kategori_produk"
                        icon="ti ti-search" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-category me-2"></i>Data Kategori Produk</h6>
                    @can('kategoriproduk.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateKategoriproduk"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode</th>
                            <th class="text-white">Nama Kategori</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($kategoriproduk as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_kategori_produk }}</span></td>
                                <td>{{ $d->nama_kategori_produk }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('kategoriproduk.edit')
                                            <a href="#" class="editKategoriproduk text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_kategori_produk="{{ Crypt::encrypt($d->kode_kategori_produk) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('kategoriproduk.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('kategoriproduk.delete', Crypt::encrypt($d->kode_kategori_produk)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
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
                    {{-- {{ $kategoriproduk->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateKategoriproduk" size="" show="loadcreateKategoriproduk"
    title="Tambah Kategori Produk" />
<x-modal-form id="mdleditKategoriproduk" size="" show="loadeditKategoriproduk" title="Edit Kategori Produk" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateKategoriproduk").click(function(e) {
            $('#mdlcreateKategoriproduk').modal("show");
            $("#loadcreateKategoriproduk").load('/kategoriproduk/create');
        });

        $(".editKategoriproduk").click(function(e) {
            var kode_kategori_produk = $(this).attr("kode_kategori_produk");
            e.preventDefault();
            $('#mdleditKategoriproduk').modal("show");
            $("#loadeditKategoriproduk").load('/kategoriproduk/' + kode_kategori_produk + '/edit');
        });
    });
</script>
@endpush
