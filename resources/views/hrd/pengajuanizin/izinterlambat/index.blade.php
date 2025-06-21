@extends('layouts.app')
@section('titlepage', 'Izin terlambat')

@section('content')
@section('navigasi')
    <span>Izin terlambat</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation_pengajuanizin')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('izinterlambat.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('izinterlambat.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <x-input-with-icon label="Nama Karyawan" name="nama_karyawan"
                                            value="{{ Request('nama_karyawan') }}" icon="ti ti-user" />
                                    </div>
                                </div>
                                @if (in_array($level_user, ['super admin', 'asst. manager hrd', 'spv presensi']))
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12">
                                            <x-select label="Cabang" name="kode_cabang" :data="$cabang"
                                                key="kode_cabang" textShow="nama_cabang" select2="select2Kodecabang"
                                                upperCase="true" selected="{{ Request('kode_cabang') }}" />
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-12">
                                            <x-select label="Departemen" name="kode_dept" :data="$departemen"
                                                key="kode_dept" textShow="nama_dept" select2="select2KodeDept"
                                                upperCase="true" selected="{{ Request('kode_dept') }}" />
                                        </div>
                                    </div>
                                @endif

                                {{-- @if ($level_user != 'direktur')
                                    <div class="form-group mb-3">
                                        <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                            <option value="">Posisi Ajuan</option>
                                            @foreach ($listApprove as $d)
                                                <option value="{{ $d }}" {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                                    {{ textUpperCase($d) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif --}}

                                <div class="form-group mb-3">
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Status</option>
                                        <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="disetujui"
                                            {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                        @if ($level_user == 'asst. manager hrd')
                                            <option value="direktur"
                                                {{ Request('status') === 'direktur' ? 'selected' : '' }}>Disetujui
                                                Direktur
                                            </option>
                                            <option value="pendingdirektur"
                                                {{ Request('status') === 'pendingdirektur' ? 'selected' : '' }}>Pending
                                                Direktur
                                            </option>
                                        @endif
                                    </select>
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
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Nik</th>
                                            <th>Nama Karyawan</th>
                                            {{-- <th>Jabatan</th> --}}
                                            {{-- <th>Dept</th> --}}
                                            <th>Cabang</th>
                                            <th>Jam terlambat</th>
                                            <th>Posisi</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izinterlambat as $d)
                                            <tr>
                                                <td>{{ $d->kode_izin_terlambat }}</td>
                                                <td>{{ formatIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ formatName($d->nama_karyawan) }}</td>
                                                {{-- <td>{{ $d->nama_jabatan }}</td> --}}
                                                {{-- <td>{{ $d->kode_dept }}</td> --}}
                                                <td>{{ $d->kode_cabang }}</td>
                                                <td>
                                                    {{ date('H:i', strtotime($d->jam_terlambat)) }}
                                                </td>

                                                <td>
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
                                                    <div class="d-flex">
                                                        <a href="#" class="btnShow me-1"
                                                            kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                            <i class="ti ti-file-description text-info"></i>
                                                        </a>
                                                        @can('izinterlambat.edit')
                                                            @if (in_array($level_user, $level_hrd))
                                                                @if ($d->status == 0)
                                                                    <a href="#" class="btnEdit me-1"
                                                                        kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                @endif
                                                            @else
                                                                @if ($d->status == 0 && empty($d->head) && $d->status == 0)
                                                                    <a href="#" class="btnEdit me-1"
                                                                        kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                                        <i class="ti ti-edit text-success"></i>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                        @can('izinterlambat.delete')
                                                            @if (in_array($level_user, $level_hrd))
                                                                @if ($d->status == 0)
                                                                    <form class="delete-form me-1"
                                                                        action="{{ route('izinterlambat.delete', Crypt::encrypt($d->kode_izin_terlambat)) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="delete-confirm">
                                                                            <i class="ti ti-trash text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @else
                                                                @if ($d->status == 0 && empty($d->head) && $d->status == 0)
                                                                    <form class="delete-form me-1"
                                                                        action="{{ route('izinterlambat.delete', Crypt::encrypt($d->kode_izin_terlambat)) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="delete-confirm">
                                                                            <i class="ti ti-trash text-danger"></i>
                                                                        </a>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                        @can('izinterlambat.approve')
                                                            @if (in_array($level_user, $level_hrd))
                                                                @if (
                                                                    (!empty($d->head) && empty($d->hrd) && $d->status == 0) ||
                                                                        (empty($d->head) && $d->kode_dept == 'HRD') ||
                                                                        (empty($d->head) && $d->kode_jabatan == 'J02'))
                                                                    <a href="#" class="btnApprove me-1"
                                                                        kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                                        <i class="ti ti-external-link text-success"></i>
                                                                    </a>
                                                                @else
                                                                    @if (!empty($d->hrd) && empty($d->direktur))
                                                                        <form method="POST" name="deleteform"
                                                                            class="deleteform"
                                                                            action="{{ route('izinterlambat.cancel', Crypt::encrypt($d->kode_izin_terlambat)) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="cancel-confirm me-1">
                                                                                <i
                                                                                    class="ti ti-square-rounded-x text-danger"></i>
                                                                            </a>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @php
                                                                    $dept_access =
                                                                        $roles_can_approve[$level_user]['dept'] ?? [];
                                                                    $dept_acess_2 =
                                                                        $roles_can_approve[$level_user]['dept2'] ?? [];
                                                                    $jabatan_access =
                                                                        $roles_can_approve[$level_user]['jabatan'] ??
                                                                        [];
                                                                    $jabatan_access_2 =
                                                                        $roles_can_approve[$level_user]['jabatan2'] ??
                                                                        [];
                                                                @endphp
                                                                @if (in_array($d->kode_dept, $dept_access) ||
                                                                        in_array($d->kode_dept, $dept_acess_2) ||
                                                                        empty($dept_access) ||
                                                                        empty($dept_acess_2))
                                                                    @if (in_array($d->kode_jabatan, $jabatan_access) ||
                                                                            empty($jabatan_access) ||
                                                                            in_array($d->kode_jabatan, $jabatan_access_2) ||
                                                                            empty($jabatan_access_2))
                                                                        @if (empty($d->head) && empty($d->hrd) && $d->status == 0)
                                                                            <a href="#" class="btnApprove me-1"
                                                                                kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                                                <i
                                                                                    class="ti ti-external-link text-success"></i>
                                                                            </a>
                                                                        @else
                                                                            @if (empty($d->hrd) && $d->status == 0)
                                                                                <form method="POST" name="deleteform"
                                                                                    class="deleteform"
                                                                                    action="{{ route('izinterlambat.cancel', Crypt::encrypt($d->kode_izin_terlambat)) }}">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <a href="#"
                                                                                        class="cancel-confirm me-1">
                                                                                        <i
                                                                                            class="ti ti-square-rounded-x text-danger"></i>
                                                                                    </a>
                                                                                </form>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endif
                                                            @if ($level_user == 'direktur')
                                                                @if ($d->direktur == 0 && !empty($d->hrd) && $d->forward_to_direktur == '1')
                                                                    <a href="#" class="btnApprove me-1"
                                                                        kode_izin_terlambat="{{ Crypt::encrypt($d->kode_izin_terlambat) }}">
                                                                        <i class="ti ti-external-link text-success"></i>
                                                                    </a>
                                                                @else
                                                                    <form method="POST" name="deleteform"
                                                                        class="deleteform"
                                                                        action="{{ route('izinterlambat.cancel', Crypt::encrypt($d->kode_izin_terlambat)) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="cancel-confirm me-1">
                                                                            <i
                                                                                class="ti ti-square-rounded-x text-danger"></i>
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
                            <div style="float: right;">
                                {{ $izinterlambat->links() }}
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
            $("#modal").find(".modal-title").text("Buat Izin terlambat");
            $("#loadmodal").load("/izinterlambat/create");
        });

        $(".btnEdit").click(function() {
            const kode_izin_terlambat = $(this).attr("kode_izin_terlambat");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Izin terlambat");
            $("#loadmodal").load(`/izinterlambat/${kode_izin_terlambat}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_izin_terlambat = $(this).attr("kode_izin_terlambat");
            //alert(kode_izin_terlambat);
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Izin terlambat");
            $("#loadmodal").load(`/izinterlambat/${kode_izin_terlambat}/approve`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_izin_terlambat = $(this).attr("kode_izin_terlambat");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Izin terlambat");
            $("#loadmodal").load(`/izinterlambat/${kode_izin_terlambat}/show`);
        });
    });
</script>
@endpush
