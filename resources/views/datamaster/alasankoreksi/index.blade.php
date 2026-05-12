@extends('layouts.app')
@section('titlepage', 'Alasan Koreksi')

@section('content')
@section('navigasi')
    <span>Alasan Koreksi</span>
@endsection
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold"><i class="ti ti-list me-2"></i>Data Alasan Koreksi</h6>
                    @can('alasankoreksi.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Data
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white">No.</th>
                            <th class="text-white">Alasan</th>
                            <th class="text-white">Status Denda</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($alasankoreksi as $d)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $d->alasan }}</td>
                                <td>
                                    @if ($d->status_denda == 1)
                                        <span class="badge bg-danger">Ya</span>
                                    @else
                                        <span class="badge bg-success">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        @can('alasankoreksi.edit')
                                            <a href="#" class="btnEdit text-success"
                                                id_alasan = "{{ $d->id }}" data-bs-toggle="tooltip" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('alasankoreksi.delete')
                                            <form class="delete-form"
                                                action="{{ route('alasankoreksi.delete', $d->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip"
                                                    title="Hapus">
                                                    <i class="ti ti-trash fs-5"></i>
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
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        function loading() {
            $("#loadmodal").html(
                `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`
            );
        }
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Tambah Alasan Koreksi");
            $("#loadmodal").load("/alasankoreksi/create");
        });

        $(".btnEdit").click(function() {
            const id_alasan = $(this).attr("id_alasan");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Alasan Koreksi");
            $("#loadmodal").load(`/alasankoreksi/${id_alasan}/edit`);
        });
    });
</script>
@endpush
