@extends('layouts.app')
@section('titlepage', 'Barang Pembelian')

@section('content')
@section('navigasi')
    <span>Barang Pembelian</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('barangpembelian.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Barang</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('barangpembelian.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Barang" value="{{ Request('nama_barang') }}" name="nama_barang"
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
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Barang</th>
                                        <th>Nama Barang</th>
                                        <th>Satuan</th>
                                        <th>Jenis Barang</th>
                                        <th>Kategori</th>
                                        <th>Group</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $barang->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_barang }}</td>
                                            <td>{{ textUpperCase($d->nama_barang) }}</td>
                                            <td>{{ textUpperCase($d->satuan) }}</td>
                                            <td>{{ textUpperCase($jenis_barang[$d->kode_jenis_barang]) }}</td>
                                            <td>{{ textUpperCase($d->nama_kategori) }}</td>
                                            <td>{{ textUpperCase($group[$d->kode_group]) }} </td>
                                            <td>
                                                @if ($d->status === '1')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('barangpembelian.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" kode_barang="{{ Crypt::encrypt($d->kode_barang) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('barangpembelian.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('barangpembelian.delete', Crypt::encrypt($d->kode_barang)) }}">
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
                            {{ $barang->links() }}
                        </div>
                    </div>
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
