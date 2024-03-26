@extends('layouts.app')
@section('titlepage', 'Permintaan Kiriman')

@section('content')
@section('navigasi')
    <span>Permintaan Kiriman</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('permintaankiriman.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat
                        Permintaan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('permintaankiriman.index') }}">
                            {{-- <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}"
                                        name="nama_produk" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div> --}}

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No. Permintaan</th>
                                        <th>Tanggal</th>
                                        <th>Cabang</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Salesman</th>
                                        <th>No. SJ</th>
                                        <th>No. Dok</th>
                                        <th>Tanggal SJ</th>
                                        <th>Status SJ</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pk as $d)
                                        <tr>
                                            <td>{{ $d->no_permintaan }}</td>
                                            <td>
                                                {{ date('d-m-Y', strtotime($d->tanggal)) }}
                                            </td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>
                                                @if ($d->status == 1)
                                                    <span class="badge badge-sm bg-success">
                                                        Sudah Di Proses
                                                    </span>
                                                @else
                                                    <span class="badge badge bg-danger">
                                                        Belum Di Proses
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $d->nama_salesman }}</td>
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
<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Buat Permintaan" />
<x-modal-form id="mdlEdit" size="" show="loadEdit" title="Edit Permintaan" />
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
