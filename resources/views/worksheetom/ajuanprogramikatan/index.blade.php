@extends('layouts.app')
@section('titlepage', 'Monitoring Program')

@section('content')
@section('navigasi')
    <span>Monitoring Program</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_monitoringprogram')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('barangmasukgl.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('monitoringprogram.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
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
                                            <th rowspan="2">No.</th>
                                            <th rowspan="2">No Pengajuan</th>
                                            <th rowspan="2">No. Dok</th>
                                            <th rowspan="2">Tanggal</th>
                                            <th rowspan="2">Program</th>
                                            <th rowspan="2">Cabang</th>
                                            <th rowspan="2">Periode</th>
                                            <th colspan="3">Persetujuan</th>
                                            <th rowspan="2">#</th>
                                        </tr>
                                        <tr>
                                            <th>RSM</th>
                                            <th>GM</th>
                                            <th>Direktur</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($ajuanprogramikatan as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->no_pengajuan }}</td>
                                                <td>{{ $d->nomor_dokumen }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nama_program }}</td>
                                                <td>{{ $d->nama_cabang }}</td>
                                                <td>{{ $d->periode_dari }} - {{ $d->periode_sampai }}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <a href="{{ route('ajuanprogramikatan.setajuanprogramikatan', Crypt::encrypt($d->no_pengajuan)) }}"
                                                            class="me-1">
                                                            <i class="ti ti-settings text-primary"></i>
                                                        </a>

                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('ajuanprogramikatan.delete', Crypt::encrypt($d->no_pengajuan)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </td>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{-- {{ $barangmasuk->links() }} --}}
                            </div>
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
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Ajuan Program");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodal").load("/ajuanprogramikatan/create");
        });
    });
</script>
@endpush
