@extends('layouts.app')
@section('titlepage', 'Lembur')

@section('content')
@section('navigasi')
    <span>Lembur</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('lembur.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Buat Lembur</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('lembur.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date"
                                        :value="Request('dari')" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date"
                                        :value="Request('sampai')" />
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Cabang</th>
                                        <th>Dept.</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Istirahat</th>
                                        <th>Posisi</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lembur as $l)
                                        <tr>
                                            <td>{{ $l->kode_lembur }}</td>
                                            <td>{{ formatIndo($l->tanggal) }}</td>
                                            <td>{{ textUpperCase($l->nama_cabang) }}</td>
                                            <td>{{ $l->kode_dept }}</td>
                                            <td>
                                                @if ($l->kategori == 1)
                                                    <span class="badge bg-success">Lembur Reguler</span>
                                                @else
                                                    <span class="badge bg-primary">Lembur Haril Libur</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($l->istirahat == 1)
                                                    <i class="ti ti-square-rounded-check text-success"></i>
                                                @else
                                                    <i class="ti ti-square-rounded-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td></td>
                                            <td class="text-center">
                                                @if ($l->status == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">

                                                    @can('lembur.edit')
                                                        @if ($l->status === '0')
                                                            <a href="#" kode_lembur="{{ Crypt::encrypt($l->kode_lembur) }}" class="btnEdit me-1">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan
                                                    @can('lembur.setlembur')
                                                        <a href="{{ route('lembur.aturlembur', Crypt::encrypt($l->kode_libur)) }}" class="me-1">
                                                            <i class="ti ti-settings-cog text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('lembur.delete')
                                                        @if ($l->status === '0')
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('lembur.delete', Crypt::encrypt($l->kode_lembur)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm me-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
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
                            {{ $lembur->links() }}
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
            $(".modal-title").text("Buat Lembur");
            $("#loadmodal").load(`/lembur/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_lembur = $(this).attr("kode_lembur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Lembur");
            $("#loadmodal").load(`/lembur/${kode_lembur}/edit`);
        });
    });
</script>
@endpush
