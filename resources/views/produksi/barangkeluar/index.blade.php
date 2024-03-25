@extends('layouts.app')
@section('titlepage', 'Barang Keluar Produksi')

@section('content')
@section('navigasi')
    <span>Barang Keluar Produksi</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_mutasibarangproduksi')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('barangkeluarproduksi.create')
                        <a href="{{ route('barangkeluarproduksi.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('barangkeluarproduksi.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari"
                                            datepicker="flatpickr-date" value="{{ Request('dari') }}" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai"
                                            datepicker="flatpickr-date" value="{{ Request('sampai') }}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <x-input-with-icon icon="ti ti-barcode" label="No. Bukti" name="no_bukti_search"
                                            value="{{ Request('no_bukti_search') }}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-3">
                                            <select name="kode_jenis_pengeluaran_search"
                                                id="kode_jenis_pengeluaran_search" class="form-select">
                                                <option value="">Jenis Pengeluaran</option>
                                                <option value="RO"
                                                    {{ Request('kode_jenis_pengeluaran_search') == 'RO' ? 'selected' : '' }}>
                                                    Retur Out</option>
                                                <option value="PK"
                                                    {{ Request('kode_jenis_pengeluaran_search') == 'PK' ? 'selected' : '' }}>
                                                    Pemakaian</option>
                                                <option value="LN"
                                                    {{ Request('kode_jenis_pengeluaran_search') == 'LN' ? 'selected' : '' }}>
                                                    Lainnya
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No.</th>
                                            <th>No. Bukti</th>
                                            <th>Tanggal</th>
                                            <th>Jenis Pengeluaran</th>
                                            <th>Supplier</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($barangkeluar as $d)
                                            <tr>
                                                <td>{{ $loop->iteration + $barangkeluar->firstItem() - 1 }}</td>
                                                <td>{{ $d->no_bukti }}</td>
                                                <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                                <td>{{ $jenis_pengeluaran[$d->kode_jenis_pengeluaran] }}</td>
                                                <td>{{ $d->nama_supplier }}</td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('barangkeluarproduksi.edit')
                                                            <div>
                                                                <a href="{{ route('barangkeluarproduksi.edit', Crypt::encrypt($d->no_bukti)) }}"
                                                                    class="me-2"
                                                                    no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                    <i class="ti ti-edit text-success"></i>
                                                                </a>
                                                            </div>
                                                        @endcan
                                                        @can('barangkeluarproduksi.show')
                                                            <div>
                                                                <a href="#" class="me-2 showDetail"
                                                                    no_bukti="{{ Crypt::encrypt($d->no_bukti) }}">
                                                                    <i class="ti ti-file-description text-info"></i>
                                                                </a>
                                                            </div>
                                                        @endcan

                                                        @can('barangkeluarproduksi.delete')
                                                            <div>
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('barangkeluarproduksi.delete', Crypt::encrypt($d->no_bukti)) }}">
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
                                {{ $barangkeluar->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdldetail" size="modal-xl" show="loaddetail" title="Detail" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {

        $(".showDetail").click(function(e) {
            var no_bukti = $(this).attr("no_bukti");
            e.preventDefault();
            $('#mdldetail').modal("show");
            $("#loaddetail").load('/barangkeluarproduksi/' + no_bukti + '/show');
        });
    });
</script>
@endpush
