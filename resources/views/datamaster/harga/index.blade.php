@extends('layouts.app')
@section('titlepage', 'Harga')

@section('content')
@section('navigasi')
    <span>Harga</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('harga.create')
                    <a href="#" class="btn btn-primary" id="btncreateHarga"><i class="fa fa-plus me-2"></i> Tambah
                        Harga</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('harga.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Produk" value="{{ Request('nama_produk') }}"
                                        name="nama_produk" icon="ti ti-search" />
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-3 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                    </div>
                                @endhasanyrole
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-select label="Kategori" name="kode_kategori_salesman" :data="$kategorisalesman"
                                        key="kode_kategori_salesman" textShow="nama_kategori_salesman"
                                        selected="{{ Request('kode_kategori_salesman') }}" />
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
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Kode</th>
                                        <th>Nama Produk</th>
                                        <th class="text-center">Satuan</th>
                                        <th class="text-center">Harga/Dus</th>
                                        <th class="text-center">Harga/Pack</th>
                                        <th class="text-center">Harga/Pcs</th>
                                        <th class="text-center">Kategori</th>
                                        <th class="text-center">Promo</th>
                                        <th class="text-center">PPN</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Cabang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($harga as $d)
                                        <tr>
                                            <td class="text-center"> {{ $loop->iteration + $harga->firstItem() - 1 }}
                                            </td>
                                            <td class="text-center">{{ $d->kode_harga }}</td>
                                            <td>{{ $d->nama_produk }}</td>
                                            <td class="text-center">{{ $d->satuan }}</td>
                                            <td class="text-end">{{ formatRupiah($d->harga_dus) }}</td>
                                            <td class="text-end">{{ formatRupiah($d->harga_pack) }}</td>
                                            <td class="text-end">{{ formatRupiah($d->harga_pcs) }}</td>
                                            <td class="text-center">{{ $d->kode_kategori_salesman }}</td>
                                            <td class="text-center">
                                                @if ($d->status_promo == 1)
                                                    <i class="ti ti-check text-success"></i>
                                                @else
                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status_ppn == 'IN')
                                                    <span class="badge bg-success">INCLUDE</span>
                                                @elseif($d->status_ppn == 'EX')
                                                    <span class="badge bg-danger">EXCLUDE</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status_aktif_harga == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $d->kode_cabang }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('harga.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editHarga"
                                                                kode_harga="{{ Crypt::encrypt($d->kode_harga) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('harga.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('harga.delete', Crypt::encrypt($d->kode_harga)) }}">
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
                            {{ $harga->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateHarga" size="modal-lg" show="loadcreateHarga" title="Tambah Harga" />
<x-modal-form id="mdleditHarga" size="" show="loadeditHarga" title="Edit Harga" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateHarga").click(function(e) {
            $('#mdlcreateHarga').modal("show");
            $("#loadcreateHarga").load('/harga/create');
        });

        $(".editHarga").click(function(e) {
            var kode_harga = $(this).attr("kode_harga");
            e.preventDefault();
            $('#mdleditHarga').modal("show");
            $("#loadeditHarga").load('/harga/' + kode_harga + '/edit');
        });
    });
</script>
@endpush
