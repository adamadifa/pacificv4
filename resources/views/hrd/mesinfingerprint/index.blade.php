@extends('layouts.app')
@section('titlepage', 'Mesin Fingerprint')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Mesin Fingerprint</h4>
            <small class="text-muted">Mengelola daftar mesin fingerprint (ADMS).</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Utilities</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-device-floppy me-1"></i>Mesin Fingerprint</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-device-laptop me-2"></i>Data Mesin</h6>
                    <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah</a>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">Nama Mesin</th>
                            <th class="text-white">Serial Number (SN)</th>
                            <th class="text-white">Status</th>
                            <th class="text-white">Titik Koordinat</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($mesin as $d)
                            <tr>
                                <td><span class="fw-semibold">{{ $d->nama_mesin }}</span></td>
                                <td>{{ $d->sn }}</td>
                                <td>
                                    <span class="badge {{ $d->status == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $d->status }}
                                    </span>
                                </td>
                                <td>{{ $d->titik_koordinat ?? '-' }}</td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="#" class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit"
                                            id_mesin="{{ Crypt::encrypt($d->id) }}">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('mesinfingerprint.destroy', Crypt::encrypt($d->id)) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                data-bs-toggle="tooltip" title="Hapus">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </form>
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
            $(".modal-title").text("Tambah Mesin Fingerprint");
            $("#loadmodal").load(`/mesinfingerprint/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id_mesin");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Mesin Fingerprint");
            $("#loadmodal").load(`/mesinfingerprint/${id}/edit`);
        });
    });
</script>
@endpush
