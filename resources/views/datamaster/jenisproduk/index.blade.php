@extends('layouts.app')
@section('titlepage', 'Jenis Produk')

@section('content')
@section('navigasi')
    <span>Jenis Produk</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jenisproduk.create')
                    <a href="#" class="btn btn-primary" id="btncreateJenisproduk"><i class="fa fa-plus me-2"></i>
                        Tambah Jenis Produk
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('jenisproduk.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Jenis Produk"
                                        value="{{ Request('nama_jenis_produk') }}" name="nama_jenis_produk"
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
                                        <th>Nama Jenis</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jenisproduk as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_jenis_produk }}</td>
                                            <td>{{ $d->nama_jenis_produk }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jenisproduk.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editJenisproduk"
                                                                kode_jenis_produk="{{ Crypt::encrypt($d->kode_jenis_produk) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('jenisproduk.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('jenisproduk.delete', Crypt::encrypt($d->kode_jenis_produk)) }}">
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
                            {{-- {{ $produk->links() }} --}}
                        </div>
                    </div>
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
