@extends('layouts.app')
@section('titlepage', 'Jadwal Shift')

@section('content')
@section('navigasi')
    <span>Jadwal Shift</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jadwalshift.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Buat Jadwal Shift</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th rowspan="2">Kode</th>
                                        <th colspan="2">Periode</th>
                                        <th rowspan="2">#</th>
                                    </tr>
                                    <tr>
                                        <th>Dari</th>
                                        <th>Sampai</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($jadwalshift as $d)
                                        <tr>
                                            <td>{{ $d->kode_jadwalshift }}</td>
                                            <td>{{ DateToIndo($d->dari) }}</td>
                                            <td>{{ DateToIndo($d->sampai) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jadwalshift.edit')
                                                        <a href="#" class="btnEdit me-1"
                                                            kode_jadwalshift="{{ Crypt::encrypt($d->kode_jadwalshift) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('jadwalshift.setjadwal')
                                                        <a href="{{ route('jadwalshift.aturjadwal', Crypt::encrypt($d->kode_jadwalshift)) }}"
                                                            class="me-1">
                                                            <i class="ti ti-settings-cog text-primary"></i>
                                                        </a>
                                                    @endcan
                                                    @can('jadwalshift.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('jadwalshift.delete', Crypt::encrypt($d->kode_jadwalshift)) }}">
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
                            {{ $jadwalshift->links() }}
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
            $(".modal-title").text("Buat Jadwal Shift");
            $("#loadmodal").load(`/jadwalshift/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_jadwalshift = $(this).attr("kode_jadwalshift");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Jadwal Shift");
            $("#loadmodal").load(`/jadwalshift/${kode_jadwalshift}/edit`);
        });
    });
</script>
@endpush
