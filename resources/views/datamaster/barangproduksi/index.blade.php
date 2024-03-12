@extends('layouts.app')
@section('titlepage', 'Produk')

@section('content')
@section('navigasi')
    <span>Barang Produksi</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('produk.create')
                    <a href="#" class="btn btn-primary" id="btncreateBarang"><i class="fa fa-plus me-2"></i> Tambah
                        Barang</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('barangproduksi.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Barang" value="{{ Request('nama_barang') }}"
                                        name="nama_barang" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Asal Barang</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barangproduksi as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $barangproduksi->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_barang_produksi }}</td>
                                            <td>{{ $d->nama_barang }}</td>
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
                                                <div class="d-flex">
                                                    @can('barangproduksi.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editBarang"
                                                                kode_barang_produksi="{{ Crypt::encrypt($d->kode_barang_produksi) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('barangproduksi.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('barangproduksi.delete', Crypt::encrypt($d->kode_barang_produksi)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $barangproduksi->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBarang" size="" show="loadcreateBarang" title="Tambah Barang" />
<x-modal-form id="mdleditBarang" size="" show="loadeditBarang" title="Edit Barang" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
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
