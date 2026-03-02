@extends('layouts.app')
@section('titlepage', 'Jadwal Shift')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Jadwal Shift</h4>
            <small class="text-muted">Mengelola data jadwal shift kerja.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-calendar me-1"></i>Jadwal Shift</li>
            </ol>
        </nav>
    </div>
@endsection
<div class="row">
    <div class="col-lg-8 col-md-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-calendar me-2"></i>Data Jadwal Shift</h6>
                    @can('jadwalshift.create')
                        <a href="#" id="btnCreate" class="btn btn-primary btn-sm"><i class="ti ti-plus me-1"></i> Tambah</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">Kode</th>
                            <th class="text-white text-center" colspan="2">Periode</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                        <tr style="background-color: #002e65;">
                            <th style="background-color: #002e65;"></th>
                            <th class="text-white text-center" style="background-color: #002e65;">Dari</th>
                            <th class="text-white text-center" style="background-color: #002e65;">Sampai</th>
                            <th style="background-color: #002e65;"></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($jadwalshift as $d)
                            <tr>
                                <td><span class="fw-semibold">{{ $d->kode_jadwalshift }}</span></td>
                                <td class="text-center">{{ DateToIndo($d->dari) }}</td>
                                <td class="text-center">{{ DateToIndo($d->sampai) }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('jadwalshift.edit')
                                            <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                                kode_jadwalshift="{{ Crypt::encrypt($d->kode_jadwalshift) }}">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('jadwalshift.setjadwal')
                                            <a href="{{ route('jadwalshift.aturjadwal', Crypt::encrypt($d->kode_jadwalshift)) }}"
                                                class="text-info" data-bs-toggle="tooltip" title="Atur Jadwal">
                                                <i class="ti ti-settings-cog"></i>
                                            </a>
                                        @endcan
                                        @can('jadwalshift.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('jadwalshift.delete', Crypt::encrypt($d->kode_jadwalshift)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                    data-bs-toggle="tooltip" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $jadwalshift->links() }}
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
