@extends('layouts.app')
@section('titlepage', 'Kategori Produk')

@section('content')
@section('navigasi')
    <span>Kategori Produk</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('kategoriproduk.create')
                    <a href="#" class="btn btn-primary" id="btncreateKategoriproduk"><i class="fa fa-plus me-2"></i>
                        Tambah Kategori Produk
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('kategoriproduk.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Kategori Produk"
                                        value="{{ Request('nama_kategori_produk') }}" name="nama_kategori_produk"
                                        icon="ti ti-search" />
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
                                        <th>Kode</th>
                                        <th>Nama Kategori</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kategoriproduk as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_kategori_produk }}</td>
                                            <td>{{ $d->nama_kategori_produk }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('kategoriproduk.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editKategoriproduk"
                                                                kode_kategori_produk="{{ Crypt::encrypt($d->kode_kategori_produk) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('kategoriproduk.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('kategoriproduk.delete', Crypt::encrypt($d->kode_kategori_produk)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $produk->links() }} --}}
                        </div>
                    </div>
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
