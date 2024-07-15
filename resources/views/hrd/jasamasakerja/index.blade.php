@extends('layouts.app')
@section('titlepage', 'Jasa Masa Kerja')

@section('content')
@section('navigasi')
    <span>Jasa Masa Kerja</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jasamasakerja.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Jasa Masa Kerja</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('jasamasakerja.index') }}">
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
                                        <th>No.Bukti</th>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th style="width: 15%">Nama</th>
                                        <th>Jabatan</th>
                                        <th>Dept.</th>
                                        <th>Cabang</th>
                                        <th>Jumlah</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jasamasakerja as $d)
                                        <tr>
                                            <td>{{ $d->kode_jmk }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td class="text-end">{{ formatRupiah($d->jumlah) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jasamasakerja.edit')
                                                        <a href="#" class="btnEdit me-1" kode_jmk ="{{ Crypt::encrypt($d->kode_jmk) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('jasamasakerja.delete')
                                                        <form method="POST" class="delete"
                                                            action="{{ route('jasamasakerja.delete', Crypt::encrypt($d->kode_jmk)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm">
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
                            {{ $jasamasakerja->links() }}
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
            $(".modal-title").text("Input Bayar Jasa Masa Kerja");
            $("#loadmodal").load(`/jasamasakerja/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_jmk = $(this).attr("kode_jmk");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Bayar Jasa Masa Kerja");
            $("#loadmodal").load(`/jasamasakerja/${kode_jmk}/edit`);
        });
    });
</script>
@endpush
