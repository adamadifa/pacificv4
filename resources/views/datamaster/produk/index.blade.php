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
                                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}" name="nama_produk"
                                        icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama Produk</th>
                                        <th>Satuan</th>
                                        <th class="text-center">Pcs / Dus</th>
                                        <th class="text-center">Pack / Dus</th>
                                        <th class="text-center">Pcs / Pack</th>
                                        <th>Jenis Produk</th>
                                        <th>Kategori</th>
                                        <th>SKU</th>
                                        <th>Diskon</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produk as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_produk }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td>{{ $d->satuan }}</td>
                                            <td class="text-center">{{ $d->isi_pcs_dus }}</td>
                                            <td class="text-center">{{ $d->isi_pack_dus }}</td>
                                            <td class="text-center">{{ $d->isi_pcs_pack }}</td>
                                            <td>{{ $d->nama_jenis_produk }}</td>
                                            <td>{{ $d->nama_kategori_produk }}</td>
                                            <td>{{ $d->kode_sku }}</td>
                                            <td>{{ $d->nama_kategori }}</td>
                                            <td>
                                                @if ($d->status_aktif_produk == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('produk.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editProduk"
                                                                kode_produk="{{ Crypt::encrypt($d->kode_produk) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('produk.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('produk.delete', Crypt::encrypt($d->kode_produk)) }}">
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
