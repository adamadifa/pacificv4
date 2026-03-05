@extends('layouts.app')
@section('titlepage', 'Lembur')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Lembur</h4>
            <small class="text-muted">Mengelola pengajuan lembur karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-clipboard-list me-1"></i>Lembur</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-clipboard-list me-2"></i>Data Lembur</h5>
            <div class="d-flex gap-2">
                @can('lembur.config.index')
                    <a href="{{ route('lemburconfig.index') }}" class="btn btn-label-secondary">
                        <i class="ti ti-settings me-1"></i> Config Approval
                    </a>
                @endcan
                @can('lembur.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="ti ti-plus me-1"></i> Tambah Lembur</a>
                @endcan
            </div>
        </div>
        {{-- Filter Section (No Card) --}}
        <form action="{{ route('lembur.index') }}" method="GET">
            <div class="row g-2 mb-1 align-items-end">
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date"
                        :value="Request('dari')" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date"
                        :value="Request('sampai')" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <div class="form-group mb-1">
                        <select name="kategori" id="kategori" class="form-select">
                            <option value="">Kategori Lembur</option>
                            <option value="1" @if (Request('kategori') == 1) selected @endif>Lembur Reguler</option>
                            <option value="2" @if (Request('kategori') == 2) selected @endif>Lembur Hari Libur</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-12 col-sm-12">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        upperCase="true" selected="{{ Request('kode_dept') }}" hideLabel="true" />
                </div>
            </div>
            <div class="row g-2 mb-1 align-items-end">
                @if (!empty($listApprovelembur))
                    <div class="col">
                        <div class="form-group mb-1">
                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                <option value="">Posisi Ajuan</option>
                                @foreach ($listApprovelembur as $d)
                                    <option value="{{ $d }}" {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                        {{ textUpperCase($d) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group mb-1">
                            <select name="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                        </div>
                    </div>
                @else
                    <div class="col">
                        <div class="form-group mb-1">
                            <select name="status" id="status" class="form-select">
                                <option value="">Status</option>
                                <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-auto">
                    <div class="form-group mb-1">
                        <button class="btn btn-primary" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="card shadow-sm border mt-2">
            <div class="card-header border-bottom py-3" style="background-color: #002e65; border-radius: 0.375rem 0.375rem 0 0;">
                <h6 class="m-0 fw-bold text-white"><i class="ti ti-clipboard-list me-2"></i>Data Lembur</h6>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-hover table-striped">
                    <thead class="text-white">
                        <tr style="background-color: #002e65;">
                            <th class="text-white">Kode</th>
                            <th class="text-white">Tanggal</th>
                            <th class="text-white">Cabang</th>
                            <th class="text-white">Dept.</th>
                            <th class="text-white">Kategori</th>
                            <th class="text-white text-center">Istirahat</th>
                            <th class="text-white text-center">Posisi</th>
                            <th class="text-white text-center">Status</th>
                            <th class="text-white text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($lembur as $l)
                            @php
                                $roles_approve = cekRoleapprovelembur($l->kode_dept, $l->kode_cabang);
                                $end_role = end($roles_approve);
                                if ($level_user != $end_role) {
                                    $index_role = array_search($level_user, $roles_approve);
                                    $next_role = $roles_approve[$index_role + 1];
                                } else {
                                    $lastindex = count($roles_approve) - 1;
                                    $next_role = $roles_approve[$lastindex];
                                }
                            @endphp
                            <tr>
                                <td><span class="fw-semibold">{{ $l->kode_lembur }}</span></td>
                                <td>{{ formatIndo($l->tanggal) }}</td>
                                <td>{{ textUpperCase($l->nama_cabang) }}</td>
                                <td>{{ $l->kode_dept }}</td>
                                <td>
                                    @if ($l->kategori == 1)
                                        <span class="badge bg-success">Reguler</span>
                                    @else
                                        <span class="badge bg-primary">Hari Libur</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($l->istirahat == 1)
                                        <i class="ti ti-square-rounded-check text-success"></i>
                                    @else
                                        <i class="ti ti-square-rounded-x text-danger"></i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if (!empty($l->posisi_ajuan))
                                        <span
                                            class="badge bg-primary">{{ singkatString($l->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($l->posisi_ajuan) }}
                                        </span>
                                    @elseif ($l->status == '1')
                                        <span class="badge bg-success">DIREKTUR</span>
                                    @else
                                        <span class="badge bg-danger">Belum di Konfigurasi</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($l->status == '1')
                                        <i class="ti ti-checks text-success"></i>
                                    @else
                                        <i class="ti ti-hourglass-low text-warning"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        @can('lembur.edit')
                                            @if ($l->status === '0')
                                                <a href="#" kode_lembur="{{ Crypt::encrypt($l->kode_lembur) }}"
                                                    class="btnEdit text-primary" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('lembur.setlembur')
                                            <a href="{{ route('lembur.aturlembur', Crypt::encrypt($l->kode_lembur)) }}"
                                                class="text-info" data-bs-toggle="tooltip" title="Atur Lembur">
                                                <i class="ti ti-settings-cog"></i>
                                            </a>
                                        @endcan
                                        @can('lembur.approve')
                                            @if ($level_user == $l->posisi_ajuan && $l->status === '0')
                                                <a href="#" class="btnApprove text-success" data-bs-toggle="tooltip" title="Approve"
                                                    kode_lembur="{{ Crypt::encrypt($l->kode_lembur) }}">
                                                    <i class="ti ti-external-link"></i>
                                                </a>
                                            @elseif ($l->posisi_ajuan == $next_role && $l->status === '0')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('lembur.cancel', Crypt::encrypt($l->kode_lembur)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm text-warning" data-bs-toggle="tooltip"
                                                        title="Batalkan Approve">
                                                        <i class="ti ti-square-rounded-x"></i>
                                                    </a>
                                                </form>
                                            @elseif ($level_user == $l->posisi_ajuan && $l->status === '1')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('lembur.cancel', Crypt::encrypt($l->kode_lembur)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="cancel-confirm text-warning" data-bs-toggle="tooltip"
                                                        title="Batalkan Approve">
                                                        <i class="ti ti-square-rounded-x"></i>
                                                    </a>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('lembur.delete')
                                            @if ($l->status === '0' || $level_user == 'asst. manager hrd' || $level_user == 'super admin')
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('lembur.delete', Crypt::encrypt($l->kode_lembur)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="delete-confirm bg-transparent border-0 text-danger p-0"
                                                        data-bs-toggle="tooltip" title="Hapus">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
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
            <div class="card-footer py-2">
                <div style="float: right;">
                    {{ $lembur->links() }}
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

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            var kode_lembur = $(this).attr("kode_lembur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Lembur");
            $("#loadmodal").load(`/lembur/${kode_lembur}/approve`);
        });
    });
</script>
@endpush
