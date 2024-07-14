@extends('layouts.app')
@section('titlepage', 'Surat Peringatan')

@section('content')
@section('navigasi')
    <span>Surat Peringatan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('suratperingatan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Surat Peringatan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('suratperingatan.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search"
                                        icon="ti ti-user" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.SP</th>
                                        <th>NIK</th>
                                        <th style="width: 15%">Nama</th>
                                        <th>Jabatan</th>
                                        <th>Dept.</th>
                                        <th>Cabang</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Kategori</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suratperingatan as $sp)
                                        <tr>
                                            <td>{{ $sp->no_sp }}</td>
                                            <td>{{ $sp->nik }}</td>
                                            <td>{{ $sp->nama_karyawan }}</td>
                                            <td>{{ $sp->nama_jabatan }}</td>
                                            <td>{{ $sp->kode_dept }}</td>
                                            <td>{{ $sp->kode_cabang }}</td>
                                            <td>{{ DateToIndo($sp->dari) }}</td>
                                            <td>{{ DateToIndo($sp->sampai) }}</td>
                                            <td>{{ $sp->jenis_sp }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('suratperingatan.edit')
                                                        <a href="#" class="btnEdit me-1" no_sp="{{ Crypt::encrypt($sp->no_sp) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('suratperingatan.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('suratperingatan.delete', Crypt::encrypt($sp->no_sp)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $suratperingatan->links() }}
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
        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Surat Peringatan");
            $("#loadmodal").load(`/suratperingatan/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var no_sp = $(this).attr("no_sp");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Surat Peringatan");
            $("#loadmodal").load(`/suratperingatan/${no_sp}/edit`);
        });
    });
</script>
@endpush
