@extends('layouts.app')
@section('titlepage', 'Barang Pembelian')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Barang</h4>
            <small class="text-muted">Mengelola daftar barang pembelian.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Data Master</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-package me-1"></i>Barang</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('barangpembelian.index') }}">
            <div class="row g-2 mb-3 align-items-end">
                <div class="col-lg-10 col-md-10 col-sm-12">
                    <x-input-with-icon label="Cari Nama Barang" value="{{ Request('nama_barang') }}" name="nama_barang" icon="ti ti-search" />
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
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-package me-2"></i>Data Barang</h6>
                    @can('barangpembelian.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.</th>
                            <th class="text-white">Kode Barang</th>
                            <th class="text-white">Nama Barang</th>
                            <th class="text-white">Satuan</th>
                            <th class="text-white">Jenis Barang</th>
                            <th class="text-white">Kategori</th>
                            <th class="text-white">Group</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barang as $d)
                            <tr>
                                <td>{{ $loop->iteration + $barang->firstItem() - 1 }}</td>
                                <td><span class="fw-semibold">{{ $d->kode_barang }}</span></td>
                                <td>{{ textUpperCase($d->nama_barang) }}</td>
                                <td>{{ textUpperCase($d->satuan) }}</td>
                                <td>{{ textUpperCase($jenis_barang[$d->kode_jenis_barang]) }}</td>
                                <td>{{ textUpperCase($d->nama_kategori) }}</td>
                                <td>{{ textUpperCase($group[$d->kode_group]) }} </td>
                                <td class="text-center">
                                    @if ($d->status === '1')
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Non Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('barangpembelian.edit')
                                            <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_barang="{{ Crypt::encrypt($d->kode_barang) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('barangpembelian.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('barangpembelian.delete', Crypt::encrypt($d->kode_barang)) }}">
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
                    {{ $barang->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Barang");
            $("#loadmodal").load(`/barangpembelian/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_barang = $(this).attr('kode_barang');
            $("#modal").modal("show");
            $(".modal-title").text("Edit Data Barang");
            $("#loadmodal").load(`/barangpembelian/${kode_barang}/edit`);
        });
    });
</script>
@endpush
