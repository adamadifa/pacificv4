@extends('layouts.app')
@section('titlepage', 'Produk')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Barang Produksi</h4>
            <small class="text-muted">Mengelola data barang produksi (bahan baku, penolong, dll).</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-box me-1"></i>Barang Produksi</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row mb-3">
    <div class="col-lg-12 col-md-12 col-sm-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('barangproduksi.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-lg-11 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Nama Barang" value="{{ Request('nama_barang') }}"
                        name="nama_barang" icon="ti ti-search" />
                </div>
                <div class="col-lg-1 col-md-2 col-sm-12">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-box me-2"></i>Data Barang Produksi</h6>
                    @can('produk.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btncreateBarang">
                            <i class="ti ti-plus me-1"></i> Tambah Barang
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="text-white" style="background-color: #002e65 !important;">NO.</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KODE</th>
                            <th class="text-white" style="background-color: #002e65 !important;">NAMA BARANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">SATUAN</th>
                            <th class="text-white" style="background-color: #002e65 !important;">ASAL BARANG</th>
                            <th class="text-white" style="background-color: #002e65 !important;">KATEGORI</th>
                            <th class="text-white" style="background-color: #002e65 !important;">STATUS</th>
                            <th class="text-white text-center" style="background-color: #002e65 !important;">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barangproduksi as $d)
                            <tr>
                                <td>{{ $loop->iteration + $barangproduksi->firstItem() - 1 }}</td>
                                <td>{{ $d->kode_barang_produksi }}</td>
                                <td><span class="fw-semibold">{{ $d->nama_barang }}</span></td>
                                <td>{{ $d->satuan }}</td>
                                <td>{{ textupperCase($asal_barang_produksi[$d->kode_asal_barang]) }}</td>
                                <td>{{ $kategori_barang_produksi[$d->kode_kategori] }}</td>
                                <td>
                                    @if ($d->status_aktif_barang === '1')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Non Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('barangproduksi.edit')
                                            <a href="#" class="editBarang text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_barang_produksi="{{ Crypt::encrypt($d->kode_barang_produksi) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('barangproduksi.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('barangproduksi.delete', Crypt::encrypt($d->kode_barang_produksi)) }}">
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
                    {{ $barangproduksi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBarang" size="" show="loadcreateBarang" title="Tambah Barang" />
<x-modal-form id="mdleditBarang" size="" show="loadeditBarang" title="Edit Barang" />
@endsection

@push('myscript')
<script>
    $(function() {
        $("#btncreateBarang").click(function(e) {
            $('#mdlcreateBarang').modal("show");
            $("#loadcreateBarang").load('/barangproduksi/create');
        });

        $(".editBarang").click(function(e) {
            var kode_barang_produksi = $(this).attr("kode_barang_produksi");
            e.preventDefault();
            $('#mdleditBarang').modal("show");
            $("#loadeditBarang").load('/barangproduksi/' + kode_barang_produksi + '/edit');
        });
    });
</script>
@endpush
