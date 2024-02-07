@extends('layouts.app')
@section('titlepage', 'Salesman')

@section('content')
@section('navigasi')
    <span>Salesman</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('salesman.create')
                    <a href="#" class="btn btn-primary" id="btncreateSalesman"><i class="fa fa-plus me-2"></i> Tambah
                        Salesman</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('salesman.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Salesman" value="{{ Request('nama_salesman') }}"
                                        name="nama_salesman" icon="ti ti-search" />
                                </div>
                                @hasanyrole($roles_show_cabang)
                                    <div class="col-lg-4 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang"
                                            textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                    </div>
                                @endhasanyrole
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
                                        <th>Kode Salesman</th>
                                        <th>Nama Salesman</th>
                                        <th>Alamat</th>
                                        <th>No. HP</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Komisi</th>
                                        <th>Cabang</th>
                                        <th>Marker</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesman as $d)
                                        <tr>
                                            <td> {{ $loop->iteration + $salesman->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_salesman }}</td>
                                            <td>{{ textCamelCase($d->nama_salesman) }}</td>
                                            <td>{{ textCamelCase($d->alamat_salesman) }}</td>
                                            <td>{{ $d->no_hp_salesman }}</td>
                                            <td>{{ $d->nama_kategori_salesman }}</td>
                                            <td>
                                                @if ($d->status_aktif_salesman == 1)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status_komisi_salesman == 1)
                                                    <i class="ti ti-checklist text-success"></i>
                                                @else
                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>
                                                @if (!empty($d->marker))
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ getdocMarker($d->marker) }}" alt=""
                                                            class="h-auto rounded-circle">
                                                    </div>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('salesman.edit')
                                                        <div>
                                                            <a href="#" class="me-2 editSalesman"
                                                                kode_salesman="{{ Crypt::encrypt($d->kode_salesman) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('salesman.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('salesman.delete', Crypt::encrypt($d->kode_salesman)) }}">
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
                            {{ $salesman->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateSalesman" size="" show="loadcreateSalesman" title="Tambah Salesman" />
<x-modal-form id="mdleditSalesman" size="" show="loadeditSalesman" title="Edit Salesman" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateSalesman").click(function(e) {
            $('#mdlcreateSalesman').modal("show");
            $("#loadcreateSalesman").load('/salesman/create');
        });

        $(".editSalesman").click(function(e) {
            var kode_salesman = $(this).attr("kode_salesman");
            e.preventDefault();
            $('#mdleditSalesman').modal("show");
            $("#loadeditSalesman").load('/salesman/' + kode_salesman + '/edit');
        });
    });
</script>
@endpush
