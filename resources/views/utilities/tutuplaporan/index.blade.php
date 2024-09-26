@extends('layouts.app')
@section('titlepage', 'Tutup Laporan')

@section('content')
@section('navigasi')
    <span>Tutup Laporan</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tutup Laporan</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('tutuplaporan.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @foreach ($list_bulan as $d)
                                                <option value="{{ $d['kode_bulan'] }}" {{ Request('bulan') == $d['kode_bulan'] ? 'selected' : '' }}>
                                                    {{ $d['nama_bulan'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option value="{{ $t }}" {{ Request('tahun') == $t ? 'selected' : '' }}>{{ $t }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <button class="btn btn-primary"><i class="ti ti-search"></i></button>
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
                                        <th>Jenis Laporan</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tutup_laporan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ textCamelCase($d->jenis_laporan) }}</td>
                                            <td>{{ $namabulan[$d->bulan] }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>
                                                @if ($d->status == '1')
                                                    <a href="{{ route('tutuplaporan.lockunlock', Crypt::encrypt($d->kode_tutup_laporan)) }}">
                                                        <i class="ti ti-lock text-danger"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('tutuplaporan.lockunlock', Crypt::encrypt($d->kode_tutup_laporan)) }}">
                                                        <i class="ti ti-lock-open text-success"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlCreate" size="" show="loadCreate" title="Tutup Laporan" />


@endsection

@push('myscript')
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#mdlCreate').modal("show");
            $("#loadCreate").load('/tutuplaporan/create');
        });

        $(".btnApprove").click(function(e) {
            const kode_kirim_lpc = $(this).attr('kode_kirim_lpc');
            e.preventDefault();
            $('#mdlCreate').modal("show");
            $("#loadCreate").load(`/tutuplaporan/${kode_kirim_lpc}/approve`);
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/tutuplaporan/' + id + '/edit');
        });
    });
</script>
@endpush
