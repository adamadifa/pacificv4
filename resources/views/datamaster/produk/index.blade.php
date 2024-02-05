@extends('layouts.app')
@section('titlepage', 'Produk')

@section('content')
@section('navigasi')
    <span>Produk</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('produk.create')
                    <a href="#" class="btn btn-primary" id="btncreateProduk"><i class="fa fa-plus me-2"></i> Tambah
                        Produk</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('produk.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}"
                                        name="nama_produk" icon="ti ti-search" />
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
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Isi Pcs / Dus</th>
                                        <th>Isi Pack / Dus</th>
                                        <th>Isi Pcs / Pack</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>

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
<x-modal-form id="mdlcreateProduk" size="" show="loadcreateProduk" title="Tambah Produk" />
<x-modal-form id="mdleditProduk" size="" show="loadeditProduk" title="Edit Produk" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateProduk").click(function(e) {
            $('#mdlcreateProduk').modal("show");
            $("#loadcreateProduk").load('/produk/create');
        });

        $(".editProduk").click(function(e) {
            var kode_produk = $(this).attr("kode_produk");
            e.preventDefault();
            $('#mdleditProduk').modal("show");
            $("#loadeditProduk").load('/produk/' + kode_produk + '/edit');
        });
    });
</script>
@endpush
