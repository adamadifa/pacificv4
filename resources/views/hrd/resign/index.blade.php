@extends('layouts.app')
@section('titlepage', 'Resign')

@section('content')
@section('navigasi')
    <span>Resign</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('resign.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Resign</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('resign.index') }}">
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
                                        <th rowspan="2">Kode</th>
                                        <th rowspan="2">Tanggal</th>
                                        <th rowspan="2">NIK</th>
                                        <th rowspan="2" style="width: 15%">Nama</th>
                                        <th rowspan="2">Jabatan</th>
                                        <th rowspan="2">Dept.</th>
                                        <th rowspan="2">Cabang</th>
                                        <th colspan="3" class="text-center">Piutang</th>
                                        <th rowspan="2">Kategori</th>
                                        <th rowspan="2">#</th>
                                    </tr>
                                    <tr>
                                        <th>PJP</th>
                                        <th>Kasbon</th>
                                        <th>Lainnya</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resign as $d)
                                        <tr>
                                            <td>{{ $d->kode_resign }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->nama_jabatan }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td class="text-center">{!! $d->pjp ? '<i class="ti ti-check text-success"></i>' : '<i class="ti ti-x text-danger"></i> ' !!}</td>
                                            <td class="text-center">{!! $d->kasbon ? '<i class="ti ti-check text-success"></i>' : '<i class="ti ti-x text-danger"></i>' !!}</td>
                                            <td class="text-center">{!! $d->piutang ? '<i class="ti ti-check text-success"></i>' : '<i class="ti ti-x text-danger"></i>' !!}</td>
                                            <td>{{ $d->nama_kategori }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('resign.edit')
                                                        <a href="#" class="btnEdit me-1" kode_resign ="{{ Crypt::encrypt($d->kode_resign) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('resign.show')
                                                        <a href="{{ route('resign.cetak', Crypt::encrypt($d->kode_resign)) }}" class="btnShow me-1">
                                                            <i class="ti ti-printer text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('resign.delete')
                                                        <form method="POST" class="delete"
                                                            action="{{ route('resign.delete', Crypt::encrypt($d->kode_resign)) }}">
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
                            {{ $resign->links() }}
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
            $(".modal-title").text("Input Resign");
            $("#loadmodal").load(`/resign/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_resign = $(this).attr("kode_resign");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Resign");
            $("#loadmodal").load(`/resign/${kode_resign}/edit`);
        });
    });
</script>
@endpush
