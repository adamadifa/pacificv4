@extends('layouts.app')
@section('titlepage', 'Surat Peringatan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Surat Peringatan</h4>
            <small class="text-muted">Mengelola data surat peringatan karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-file-description me-1"></i>Surat Peringatan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('suratperingatan.index') }}" class="mb-2">
            <div class="row g-2">
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                <div class="col-lg-5 col-md-6 col-sm-12">
                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search" icon="ti ti-user" hideLabel="true" />
                </div>
                <div class="col-lg-1 col-md-6 col-sm-12">
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-file-description me-2"></i>Data Surat Peringatan</h6>
                    @can('suratperingatan.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat SP</a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">No.SP</th>
                            <th class="text-white">NIK</th>
                            <th class="text-white" style="width: 15%">Nama</th>
                            <th class="text-white">Jabatan</th>
                            <th class="text-white">Dept.</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Mulai</th>
                            <th class="text-white">Selesai</th>
                            <th class="text-white">Kategori</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($suratperingatan as $sp)
                            <tr>
                                <td>{{ $sp->no_sp }}</td>
                                <td>{{ $sp->nik }}</td>
                                <td><span class="fw-bold">{{ $sp->nama_karyawan }}</span></td>
                                <td>{{ $sp->nama_jabatan }}</td>
                                <td>{{ $sp->kode_dept }}</td>
                                <td>{{ $sp->kode_cabang }}</td>
                                <td>{{ DateToIndo($sp->dari) }}</td>
                                <td>{{ DateToIndo($sp->sampai) }}</td>
                                <td>
                                    @php
                                        $badgeClass = 'bg-label-primary';
                                        if (str_contains(strtolower($sp->jenis_sp), '1')) $badgeClass = 'bg-label-warning';
                                        elseif (str_contains(strtolower($sp->jenis_sp), '2')) $badgeClass = 'bg-label-danger';
                                        elseif (str_contains(strtolower($sp->jenis_sp), '3')) $badgeClass = 'bg-label-dark';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $sp->jenis_sp }}</span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('suratperingatan.edit')
                                            <a href="#" class="btnEdit text-success" no_sp="{{ Crypt::encrypt($sp->no_sp) }}" title="Edit">
                                                <i class="ti ti-edit fs-5"></i>
                                            </a>
                                        @endcan
                                        @can('suratperingatan.delete')
                                            <form method="POST" name="deleteform" class="deleteform d-inline"
                                                action="{{ route('suratperingatan.delete', Crypt::encrypt($sp->no_sp)) }}">
                                                @csrf
                                                @method('DELETE')
                                                <a href="#" class="delete-confirm text-danger" title="Hapus">
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $suratperingatan->links() }}
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
