@extends('layouts.app')
@section('titlepage', 'Penilaian Karyawan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Penilaian Karyawan</h4>
            <small class="text-muted">Mengelola data penilaian kinerja karyawan.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>HRD</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-star me-1"></i>Penilaian Karyawan</li>
            </ol>
        </nav>
    </div>
@endsection

<style>
    .assessment-card {
        transition: all 0.2s ease-in-out;
        border-radius: 8px;
        overflow: hidden;
    }

    .assessment-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.2) !important;
        border-color: #002e65 !important;
    }

    .border-end-lg {
        border-right: 1px solid #eee;
    }

    @media (max-width: 991.98px) {
        .border-end-lg {
            border-right: none;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }
    }

    .btn-label-primary {
        color: #002e65;
        background: #eef1f6;
    }

    .btn-label-primary:hover {
        background: #002e65 !important;
        color: #fff !important;
    }
</style>

<div class="row">
    <div class="col-lg-12 col-md-12">
        {{-- Filter Section --}}
        <form action="{{ route('penilaiankaryawan.index') }}">
            <div class="row g-2 mb-2">
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <x-input-with-icon label="Nama Karyawan" value="{{ Request('nama_karyawan_search') }}" name="nama_karyawan_search" icon="ti ti-user" />
                </div>
                @if (!empty($listApprovepenilaian))
                    <div class="col-lg-2 col-md-4 col-sm-12">
                        <div class="form-group mb-2">
                            <select name="posisi_ajuan" id="posisi_ajuan" class="form-select">
                                <option value="">Posisi Ajuan</option>
                                @foreach ($listApprovepenilaian as $d)
                                    <option value="{{ $d }}" {{ Request('posisi_ajuan') == $d ? 'selected' : '' }}>
                                        {{ textUpperCase($d) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="col-lg-2 col-md-4 col-sm-12">
                    <div class="form-group mb-2">
                        <select name="status" id="status" class="form-select">
                            <option value="">Status</option>
                            <option value="pending" {{ Request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="disetujui" {{ Request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-1 col-md-4 col-sm-12">
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
            <h5 class="mb-0 fw-bold"><i class="ti ti-star me-2"></i>List Penilaian Karyawan</h5>
            <div class="d-flex gap-2">
                @can('penilaiankaryawan.index')
                    <a href="{{ route('penilaiankaryawanconfig.index') }}" class="btn btn-label-secondary">
                        <i class="ti ti-settings me-1"></i> Config Approval
                    </a>
                @endcan
                @can('penilaiankaryawan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="ti ti-plus me-1"></i> Buat Penilaian Karyawan</a>
                @endcan
            </div>
        </div>

        <div class="row" id="penilaian-list">
            @foreach ($penilaiankaryawan as $d)
                @php
                    $roles_approve = cekRoleapprove($d->kode_dept, $d->kode_cabang, $d->kategori_jabatan, $d->kode_jabatan);
                    $end_role = end($roles_approve);
                    if ($role != $end_role) {
                        $index_role = array_search($role, $roles_approve);
                        $next_role = $roles_approve[$index_role + 1];
                    } else {
                        $lastindex = count($roles_approve) - 1;
                        $next_role = $roles_approve[$lastindex];
                    }

                    // Action Visibility Flags
                    $is_creator = $d->id_user == auth()->user()->id;
                    $is_pending = $d->status === '0';
                    $first_role = !empty($roles_approve) ? $roles_approve[0] : null;
                    $is_at_first_step = $d->posisi_ajuan == $first_role;
                    $is_admin = auth()->user()->hasRole(['super admin', 'asst. manager hrd', 'spv presensi']);
                @endphp
                <div class="col-12 mb-3">
                    <div class="card shadow-none border assessment-card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center py-2 px-3 border-bottom" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-primary me-2">{{ $d->kode_penilaian }}</span>
                                <small class="text-muted"><i class="ti ti-calendar me-1"></i>{{ formatIndo($d->tanggal) }}</small>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                @if ($d->status == '1')
                                    <span class="badge bg-label-success"><i class="ti ti-check me-1"></i>Disetujui</span>
                                @else
                                    <span class="badge bg-label-warning"><i class="ti ti-hourglass-low me-1"></i>Pending</span>
                                @endif
                                <span class="badge bg-primary ms-1">
                                    {{ singkatString($d->posisi_ajuan) == 'AMH' ? 'HRD' : singkatString($d->posisi_ajuan) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body py-3 px-3">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 border-end-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md me-3">
                                            @if (!empty($d->foto))
                                                @if (Storage::disk('public')->exists('/karyawan/' . $d->foto))
                                                    <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded-circle bg-label-info">
                                                        <i class="ti ti-user fs-4"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    <i class="ti ti-user fs-4"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ formatName($d->nama_karyawan) }}</h6>
                                            <small class="text-muted">{{ $d->nik }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 border-end-lg">
                                    <div class="d-flex flex-column ms-lg-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="ti ti-briefcase text-muted me-2"></i>
                                            <span class="small fw-semibold">{{ !empty($d->alias_jabatan) ? $d->alias_jabatan : $d->nama_jabatan }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-building text-muted me-2"></i>
                                            <span class="small text-muted">{{ $d->kode_dept }} | {{ $d->kode_cabang }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="d-flex justify-content-between align-items-center ms-lg-3">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted d-block mb-1">Periode Kontrak:</small>
                                            <span class="small fw-bold text-primary">
                                                <i class="ti ti-clock me-1"></i>{{ formatIndo($d->kontrak_dari) }} - {{ formatIndo($d->kontrak_sampai) }}
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($d->status == 1)
                                                @if ($d->status_pemutihan == 1 && empty($d->no_kb))
                                                    @can('kb.create')
                                                        <a href="#" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}" class="btnCreatekb btn btn-icon btn-label-warning btn-sm" title="Buat KB">
                                                            <i class="ti ti-file-plus"></i>
                                                        </a>
                                                    @endcan
                                                @else
                                                    @if (!empty($d->no_kb))
                                                        <a href="{{ route('kesepakatanbersama.cetak', Crypt::encrypt($d->no_kb)) }}" target="_blank" class="btn btn-icon btn-label-warning btn-sm" title="Cetak KB">
                                                            <i class="ti ti-printer"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer py-2 px-3 border-top d-flex justify-content-end gap-2" style="background: #fcfdfe;">
                            @can('penilaiankaryawan.edit')
                                @if (($is_creator && $is_pending && $is_at_first_step) || $is_admin)
                                    <a href="{{ route('penilaiankaryawan.edit', Crypt::encrypt($d->kode_penilaian)) }}" class="btn btn-icon btn-label-success btn-sm" title="Edit">
                                        <i class="ti ti-edit"></i>
                                    </a>
                                @endif
                            @endcan

                            @can('penilaiankaryawan.show')
                                <a href="{{ route('penilaiankaryawan.cetak', Crypt::encrypt($d->kode_penilaian)) }}" class="btn btn-icon btn-label-primary btn-sm" target="_blank" title="Cetak">
                                    <i class="ti ti-printer"></i>
                                </a>
                            @endcan

                            @can('kontrakkerja.create')
                                @if ($d->status == '1' && empty($d->no_kontrak_baru) && $d->status_pemutihan === '0')
                                    <a href="#" class="btnCreatekontrak btn btn-icon btn-label-danger btn-sm" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}" title="Buat Kontrak">
                                        <i class="ti ti-file-plus"></i>
                                    </a>
                                @endif
                            @endcan

                            @can('kontrakkerja.show')
                                @if ($d->status == '1' && !empty($d->no_kontrak_baru))
                                    <a href="{{ route('kontrakkerja.cetak', Crypt::encrypt($d->no_kontrak_baru)) }}" class="btn btn-icon btn-label-success btn-sm" target="_blank" title="Cetak Kontrak">
                                        <i class="ti ti-printer"></i>
                                    </a>
                                @endif
                            @endcan

                            @can('penilaiankaryawan.approve')
                                @if ($role == $d->posisi_ajuan && $d->status === '0')
                                    <a href="#" class="btnApprove btn btn-icon btn-label-info btn-sm" kode_penilaian="{{ Crypt::encrypt($d->kode_penilaian) }}" title="Approve">
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                @elseif (($d->posisi_ajuan == $next_role && $d->status === '0') || ($role == $d->posisi_ajuan && $d->status === '1'))
                                    <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('penilaiankaryawan.cancel', Crypt::encrypt($d->kode_penilaian)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="cancel-confirm btn btn-icon btn-label-danger btn-sm" title="Batalkan">
                                            <i class="ti ti-square-rounded-x"></i>
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('penilaiankaryawan.delete')
                                @if (($is_creator && $is_pending && $is_at_first_step) || $is_admin)
                                    <form method="POST" name="deleteform" class="deleteform d-inline" action="{{ route('penilaiankaryawan.delete', Crypt::encrypt($d->kode_penilaian)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-confirm btn btn-icon btn-label-danger btn-sm" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-12 mt-2">
                <div class="d-flex justify-content-end">
                    {{ $penilaiankaryawan->links() }}
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
@endsection

@push('myscript')
<script>
    $(function() {
        const loading = () => {
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
            $(".modal-title").text("Buat Penilaian Karyawan");
            $("#loadmodal").load(`/penilaiankaryawan/create`);
            $("#modal").find(".modal-dialog").removeClass('modal-lg');
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            var kode_penilaian = $(this).attr("kode_penilaian");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Penilaian Karyawan");
            $("#loadmodal").load(`/penilaiankaryawan/${kode_penilaian}/approve`);
            $("#modal").find(".modal-dialog").addClass('modal-lg');
        });

        $(".btnCreatekontrak").click(function(e) {
            e.preventDefault();
            var kode_penilaian = $(this).attr("kode_penilaian");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Kontrak");
            $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/createkontrak`);
        });

        $(".btnCreatekb").click(function(e) {
            e.preventDefault();
            var kode_penilaian = $(this).attr("kode_penilaian");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Kesepakatan Bersama");
            $("#loadmodal").load(`/kesepakatanbersama/${kode_penilaian}/create`);
        });
    });
</script>
@endpush
