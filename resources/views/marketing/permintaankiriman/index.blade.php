@extends('layouts.app')
@section('titlepage', 'Permintaan Kiriman')

@section('content')
@section('navigasi')
    <span>Permintaan Kiriman</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-sm-12 col-xs-12">
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
                                        {{-- <th>No. SJ</th>
                                        <th>No. Dok</th>
                                        <th>Tanggal SJ</th> --}}
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
                                                        {{ $d->no_mutasi }}
                                                    </span>
                                                @else
                                                    <span class="badge badge bg-danger">
                                                        Belum Di Proses
                                                    </span>
                                                @endif
                                            </td>
                                            <td>

                                                @php
                                                    $nama_sales = explode(' ', $d->nama_salesman);
                                                @endphp
                                                {{ $nama_sales[0] }}
                                            </td>
                                            {{-- <td>
                                                @if (!empty($d->no_mutasi))
                                                    {{ $d->no_mutasi }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->no_dok))
                                                    {{ $d->no_dok }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($d->no_mutasi))
                                                    {{ date('d-m-Y', strtotime($d->tanggal_surat_jalan)) }}
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td> --}}
                                            <td>
                                                @if ($d->status == 1)
                                                    @if ($d->status_surat_jalan == 0)
                                                        <span class="badge bg-danger">Belum Diterima Cabang</span>
                                                    @elseif($d->status_surat_jalan == 1)
                                                        <span class="badge bg-success">Sudah Diterima Cabang</span>
                                                    @elseif($d->status_surat_jalan == 2)
                                                        <span class="badge bg-info">Transit Out</span>
                                                    @endif
                                                @else
                                                    <i class="ti ti-refresh text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('permintaankiriman.edit')
                                                        @if ($d->status == 0)
                                                            <div>
                                                                <a href="#" class="me-2 btnEdit"
                                                                    no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                    @can('permintaankiriman.show')
                                                        <div>
                                                            <a href="#" class="me-2 btnShow"
                                                                no_permintaan="{{ Crypt::encrypt($d->no_permintaan) }}">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('permintaankiriman.delete')
                                                        @if ($d->status == 0)
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('permintaankiriman.delete', Crypt::encrypt($d->no_permintaan)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm ml-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $pk->links() }}
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
