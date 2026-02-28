@extends('layouts.app')
@section('titlepage', 'Jenis Produk')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jenis Produk</h4>
            <small class="text-muted">Mengelola klasifikasi jenis produk.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-category me-1"></i>Jenis Produk</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-6 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('jenisproduk.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Jenis Produk" value="{{ Request('nama_jenis_produk') }}" name="nama_jenis_produk"
                        icon="ti ti-search" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-category me-2"></i>Data Jenis Produk</h6>
                    @can('jenisproduk.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateJenisproduk"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode</th>
                            <th class="text-white">Nama Jenis</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($jenisproduk as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_jenis_produk }}</span></td>
                                <td>{{ $d->nama_jenis_produk }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('jenisproduk.edit')
                                            <a href="#" class="editJenisproduk text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_jenis_produk="{{ Crypt::encrypt($d->kode_jenis_produk) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('jenisproduk.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('jenisproduk.delete', Crypt::encrypt($d->kode_jenis_produk)) }}">
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
                    {{-- {{ $jenisproduk->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateJenisproduk" size="" show="loadcreateJenisproduk" title="Tambah Jenis Produk" />
<x-modal-form id="mdleditJenisproduk" size="" show="loadeditJenisproduk" title="Edit Jenis Produk" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateJenisproduk").click(function(e) {
            $('#mdlcreateJenisproduk').modal("show");
            $("#loadcreateJenisproduk").load('/jenisproduk/create');
        });

        $(".editJenisproduk").click(function(e) {
            var kode_jenis_produk = $(this).attr("kode_jenis_produk");
            e.preventDefault();
            $('#mdleditJenisproduk').modal("show");
            $("#loadeditJenisproduk").load('/jenisproduk/' + kode_jenis_produk + '/edit');
        });
    });
</script>
@endpush
