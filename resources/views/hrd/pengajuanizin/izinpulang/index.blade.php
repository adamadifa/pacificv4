@extends('layouts.app')
@section('titlepage', 'Izin Pulang')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Izin Pulang</h4>
            <small class="text-muted">Pengajuan Izin Pulang Kantor</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-file-description me-1"></i>Pengajuan Izin</a>
                </li>
                <li class="breadcrumb-item active">Izin Pulang</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Modern Navigation Header --}}
        <div class="mb-3">
            @include('layouts.navigation_pengajuanizin')
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('izinpulang.index') }}">
            <div class="card shadow-none border-0 bg-transparent mb-3">
                <div class="card-body p-0">
                    <div class="row g-2 mb-1">
                        <div class="col-lg-3 col-sm-12 col-md-12">
                            <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12">
                            <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <x-input-with-icon label="Nama Karyawan" name="nama_karyawan" value="{{ Request('nama_karyawan') }}"
                                icon="ti ti-user" />
                        </div>
                    </div>
                    <div class="row g-2 align-items-end">
                        @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                    select2="select2Kodecabang" upperCase="true" selected="{{ Request('kode_cabang') }}" />
                            </div>
                            <div class="col-lg-3 col-sm-12 col-md-12">
                                <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                                    select2="select2KodeDept" upperCase="true" selected="{{ Request('kode_dept') }}" />
                            </div>
                        @endif
                        <div class="col">
                            <div class="form-group mb-1">
                                <select name="status" id="status" class="form-select">
                                    <option value="">Status</option>
                                    <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui
                                    </option>
                                    @if ($level_user == 'asst. manager hrd')
                                        <option value="direktur" {{ Request('status') === 'direktur' ? 'selected' : '' }}>Disetujui
                                            Direktur
                                        </option>
                                        <option value="pendingdirektur" {{ Request('status') === 'pendingdirektur' ? 'selected' : '' }}>Pending
                                            Direktur
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="form-group mb-1 text-end">
                                <button class="btn btn-primary" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Data Card --}}
        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-white"><i class="ti ti-home-log-out me-2"></i>Data Izin Pulang</h6>
                    @can('izinpulang.create')
                        <a href="#" class="btn btn-primary btn-sm" id="btnCreate">
                            <i class="ti ti-plus me-1"></i> Tambah Data
                        </a>
                    @endcan
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped text-nowrap">
                    <thead style="background-color: #002e65;">
                        <tr>
                            <th class="text-white">Kode</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Nik</th>
                            <th class="text-white">Nama Karyawan</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Jam Pulang</th>
                            <th class="text-white text-center">Posisi</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($izinpulang as $d)
                            <tr>
                                <td>{{ $d->kode_izin_pulang }}</td>
                                <td>{{ formatIndo($d->tanggal) }}</td>
                                <td>{{ $d->nik }}</td>
                                <td>{{ formatName($d->nama_karyawan) }}</td>
                                <td>{{ $d->kode_cabang }}</td>
                                <td>{{ date('H:i', strtotime($d->jam_pulang)) }}</td>
                                <td class="text-center">
                                    @if (empty($d->head))
                                        <span class="badge bg-warning">
                                            HEAD
                                        </span>
                                    @elseif(!empty($d->head) && empty($d->hrd))
                                        <span class="badge bg-info">
                                            HRD
                                        </span>
                                    @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '0')
                                        <span class="badge bg-success">
                                            HRD
                                        </span>
                                    @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '1' && $d->direktur == '0')
                                        <span class="badge bg-warning">
                                            DIREKTUR
                                        </span>
                                    @elseif(!empty($d->head) && !empty($d->hrd) && $d->forward_to_direktur == '1' && $d->direktur == '1')
                                        <span class="badge bg-success">
                                            DIREKTUR
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($level_user == 'direktur')
                                        @if ($d->direktur == '1')
                                            <i class="ti ti-checks text-success"></i>
                                        @else
                                            <i class="ti ti-hourglass-low text-warning"></i>
                                        @endif
                                    @else
                                        @if ($d->status == '1')
                                            @if ($d->direktur == '1')
                                                <i class="ti ti-checks text-success"></i>
                                            @else
                                                <i class="ti ti-checkbox text-success"></i>
                                            @endif
                                        @else
                                            <i class="ti ti-hourglass-low text-warning"></i>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="#" class="btnShow text-info"
                                            kode_izin_pulang="{{ Crypt::encrypt($d->kode_izin_pulang) }}" data-bs-toggle="tooltip"
                                            title="Detail">
                                            <i class="ti ti-file-description fs-5"></i>
                                        </a>
                                        @can('izinpulang.edit')
                                            @if (in_array($level_user, $level_hrd))
                                                @if ($d->status == 0)
                                                    <a href="#" class="btnEdit text-success"
                                                        kode_izin_pulang = "{{ Crypt::encrypt($d->kode_izin_pulang) }}"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ti ti-edit fs-5"></i>
                                                    </a>
                                                @endif
                                            @else
                                                @if ($d->status == 0 && empty($d->head) && $d->status == 0)
                                                    <a href="#" class="btnEdit text-success"
                                                        kode_izin_pulang = "{{ Crypt::encrypt($d->kode_izin_pulang) }}"
                                                        data-bs-toggle="tooltip" title="Edit">
                                                        <i class="ti ti-edit fs-5"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('izinpulang.delete')
                                            @if (in_array($level_user, $level_hrd))
                                                @if ($d->status == 0)
                                                    <form class="delete-form"
                                                        action="{{ route('izinpulang.delete', Crypt::encrypt($d->kode_izin_pulang)) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @else
                                                @if ($d->status == 0 && empty($d->head) && $d->status == 0)
                                                    <form class="delete-form"
                                                        action="{{ route('izinpulang.delete', Crypt::encrypt($d->kode_izin_pulang)) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Hapus">
                                                            <i class="ti ti-trash fs-5"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
                                        @endcan
                                        @can('izinpulang.approve')
                                            @if (in_array($level_user, $level_hrd))
                                                @if (!empty($d->head) && empty($d->hrd) && $d->status == 0)
                                                    <a href="#" class="btnApprove text-success"
                                                        kode_izin_pulang="{{ Crypt::encrypt($d->kode_izin_pulang) }}"
                                                        data-bs-toggle="tooltip" title="Approve">
                                                        <i class="ti ti-external-link fs-5"></i>
                                                    </a>
                                                @else
                                                    @if (!empty($d->hrd) && empty($d->direktur))
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('izinpulang.cancel', Crypt::encrypt($d->kode_izin_pulang)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                                title="Batalkan">
                                                                <i class="ti ti-square-rounded-x fs-5"></i>
                                                            </a>
                                                        </form>
                                                    @endif
                                                @endif
                                            @else
                                                @php
                                                    $dept_access = $roles_can_approve[$level_user]['dept'] ?? [];
                                                    $dept_acess_2 = $roles_can_approve[$level_user]['dept2'] ?? [];
                                                    $jabatan_access = $roles_can_approve[$level_user]['jabatan'] ?? [];
                                                    $jabatan_access_2 = $roles_can_approve[$level_user]['jabatan2'] ?? [];
                                                @endphp
                                                @if (in_array($d->kode_dept, $dept_access) || in_array($d->kode_dept, $dept_acess_2) || empty($dept_access) || empty($dept_acess_2))
                                                    @if (in_array($d->kode_jabatan, $jabatan_access) ||
                                                            empty($jabatan_access) ||
                                                            in_array($d->kode_jabatan, $jabatan_access_2) ||
                                                            empty($jabatan_access_2))
                                                        @if (empty($d->head) && empty($d->hrd) && $d->status == 0)
                                                            <a href="#" class="btnApprove text-success"
                                                                kode_izin_pulang="{{ Crypt::encrypt($d->kode_izin_pulang) }}"
                                                                data-bs-toggle="tooltip" title="Approve">
                                                                <i class="ti ti-external-link fs-5"></i>
                                                            </a>
                                                        @else
                                                            @if (empty($d->hrd) && $d->status == 0)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('izinpulang.cancel', Crypt::encrypt($d->kode_izin_pulang)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm text-danger"
                                                                        data-bs-toggle="tooltip" title="Batalkan">
                                                                        <i class="ti ti-square-rounded-x fs-5"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif
                                            @if ($level_user == 'direktur')
                                                @if ($d->direktur == 0 && !empty($d->hrd) && $d->forward_to_direktur == '1')
                                                    <a href="#" class="btnApprove text-success"
                                                        kode_izin_pulang="{{ Crypt::encrypt($d->kode_izin_pulang) }}"
                                                        data-bs-toggle="tooltip" title="Approve">
                                                        <i class="ti ti-external-link fs-5"></i>
                                                    </a>
                                                @else
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('izinpulang.cancel', Crypt::encrypt($d->kode_izin_pulang)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="cancel-confirm text-danger" data-bs-toggle="tooltip"
                                                            title="Batalkan">
                                                            <i class="ti ti-square-rounded-x fs-5"></i>
                                                        </a>
                                                    </form>
                                                @endif
                                            @endif
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
                    {{ $izinpulang->links() }}
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

        const select2Kodecabang = $('.select2Kodecabang');

        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeDept = $('.select2KodeDept');

        if (select2KodeDept.length) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

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
            $("#modal").find(".modal-title").text("Buat Izin Pulang");
            $("#loadmodal").load("/izinpulang/create");
        });

        $(".btnEdit").click(function() {
            const kode_izin_pulang = $(this).attr("kode_izin_pulang");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Izin Pulang");
            $("#loadmodal").load(`/izinpulang/${kode_izin_pulang}/edit`);
        });

        $(".btnApprove").click(function(e) {
            const kode_izin_pulang = $(this).attr("kode_izin_pulang");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Izin Pulang");
            $("#loadmodal").load(`/izinpulang/${kode_izin_pulang}/approve`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_izin_pulang = $(this).attr("kode_izin_pulang");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Izin Pulang");
            $("#loadmodal").load(`/izinpulang/${kode_izin_pulang}/show`);
        });
    });
</script>
@endpush
