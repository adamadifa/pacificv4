@extends('layouts.app')
@section('titlepage', 'Monitoring Presensi')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0">Monitoring Presensi</h4>
            <small class="text-muted">Pantau kehadiran karyawan secara real-time.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-calendar-check me-1"></i>Presensi</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Data Cards Heading --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-list me-2"></i>Daftar Kehadiran ({{ $karyawan->total() }})</h5>
            <div class="badge bg-label-primary fs-6">Tanggal: {{ formatIndo($tanggal) }}</div>
        </div>

        {{-- Filter Section --}}
        <form action="{{ route('presensi.index') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-lg-2 col-md-4">
                    <x-input-with-icon label="Tanggal" value="{{ Request('tanggal') }}" name="tanggal" icon="ti ti-calendar" datepicker="flatpickr-date" hideLabel="true" />
                </div>
                @hasanyrole($roles_access_all_karyawan)
                    <div class="col-lg-2 col-md-4">
                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang" key="kode_cabang" textShow="nama_cabang" selected="{{ Request('kode_cabang_search') }}" upperCase="true" select2="select2Kodecabangsearch" hideLabel="true" />
                    </div>
                @endhasanyrole
                <div class="col-lg-2 col-md-4">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true" select2="select2Kodedeptsearch" hideLabel="true" />
                </div>
                <div class="col-lg-2 col-md-4">
                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group" textShow="nama_group" selected="{{ Request('kode_group') }}" upperCase="true" hideLabel="true" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-input-with-icon label="Cari Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan" icon="ti ti-user" hideLabel="true" />
                </div>
                <div class="col-lg-1 col-md-6">
                    <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                </div>
            </div>
        </form>

        {{-- Grid of Cards --}}
        <div class="row g-3">
            @foreach ($karyawan as $d)
                @php
                    $search = ['nik' => $d->nik, 'tanggal' => $tanggal];
                    $cekliburnasional = ceklibur($dataliburnasional, $search);
                    $cekdirumahkan = ceklibur($datadirumahkan, $search);
                    $cekliburpengganti = ceklibur($dataliburpengganti, $search);
                    $cektanggallimajam = ceklibur($datatanggallimajam, $search);

                    $tanggal_selesai = $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;
                    $jam_in = !empty($d->jam_in) ? date('H:i', strtotime($d->jam_in)) : null;
                    $jam_out = !empty($d->jam_out) ? date('H:i', strtotime($d->jam_out)) : null;

                    $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                    $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                    $is_spg_spm = in_array($d->kode_jabatan, ['J22', 'J23']) || (in_array($d->kode_jabatan, ['J31', 'J32']) && $tanggal >= '2026-02-21');
                    $jam_mulai_jadwal = $is_spg_spm ? (!empty($d->jam_in) ? date('H:i', strtotime($d->jam_in)) : date('H:i', strtotime($j_mulai))) : date('H:i', strtotime($j_mulai));
                    $jam_selesai_jadwal = $is_spg_spm ? (!empty($d->jam_out) ? date('H:i', strtotime($d->jam_out)) : date('H:i', strtotime($j_selesai))) : date('H:i', strtotime($j_selesai));

                    $terlambat = hitungjamterlambat($d->jam_in, $d->tanggal . ' ' . $d->jam_mulai, $d->kode_izin_terlambat);

                    // Avatar Background Color based on status
                    $avatar_bg = 'bg-label-secondary';
                    if($d->status_kehadiran == 'h') $avatar_bg = 'bg-label-success';
                    elseif($d->status_kehadiran == 'a') $avatar_bg = 'bg-label-danger';
                    elseif($d->status_kehadiran == 'i' || $d->status_kehadiran == 's') $avatar_bg = 'bg-label-info';
                    elseif($d->status_kehadiran == 'c') $avatar_bg = 'bg-label-primary';
                @endphp

                <div class="col-12">
                    <div class="card shadow-none border mb-2 card-hover-shadow overflow-hidden">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-md-row align-items-stretch">
                                {{-- Section 1: Employee Avatar & Identity --}}
                                <div class="p-3 border-end-md d-flex align-items-center" style="min-width: 250px; background-color: #002e65;">
                                    <div class="avatar avatar-lg me-3 border border-2 border-white-50 rounded-circle">
                                        @if (!empty($d->foto) && file_exists(public_path('storage/karyawan/' . $d->foto)))
                                            <img src="{{ asset('storage/karyawan/' . $d->foto) }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded-circle {{ $avatar_bg }} fw-bold" style="font-size: 1.4rem;">
                                                {{ getInitials($d->nama_karyawan) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0 text-truncate fw-bold text-white">{{ formatName($d->nama_karyawan) }}</h6>
                                        <div class="small text-white-50">{{ $d->nik }}</div>
                                        <div class="small fw-medium text-white-50">{{ $d->kode_dept }} • {{ $d->kode_cabang }}</div>
                                    </div>
                                </div>

                                {{-- Section 2: Attendance Status & Schedule --}}
                                <div class="p-3 border-end-md flex-grow-1" style="min-width: 250px;">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="small fw-bold text-muted text-uppercase tracking-wider">Status & Jadwal</div>
                                        <div class="ms-2">
                                            @if ($d->status_kehadiran == 'h')
                                                <span class="badge bg-label-success">Hadir</span>
                                            @elseif ($d->status_kehadiran == 'i')
                                                <span class="badge bg-label-info">Izin</span>
                                            @elseif ($d->status_kehadiran == 's')
                                                <span class="badge bg-label-warning">Sakit</span>
                                            @elseif ($d->status_kehadiran == 'a')
                                                <span class="badge bg-label-danger">Alfa</span>
                                            @elseif ($d->status_kehadiran == 'c')
                                                <span class="badge bg-label-primary">Cuti</span>
                                            @else
                                                <span class="badge bg-label-danger">Belum Absen</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if (!empty($d->status_kehadiran))
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="badge bg-label-primary p-2 rounded">
                                                    <i class="ti ti-clock-play fs-4"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $d->nama_jadwal ?: 'Non-Shift' }}</div>
                                                <div class="small text-muted">{{ $jam_mulai_jadwal }} - {{ $jam_selesai_jadwal }}</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-2 text-muted fst-italic small">
                                            <i class="ti ti-info-circle me-1"></i> Menunggu Presensi
                                        </div>
                                    @endif
                                </div>

                                {{-- Section 3: Check In / Out Times --}}
                                <div class="p-3 border-end-md d-flex align-items-center justify-content-around bg-white" style="min-width: 300px;">
                                    <div class="text-center px-2">
                                        <small class="text-muted d-block text-uppercase small-caps mb-1">Masuk</small>
                                        @if ($jam_in)
                                            <a href="#" class="btnShowpresensi_in fw-black text-success h4 mb-0 d-block" id="{{ $d->id }}" status="in">{{ $jam_in }}</a>
                                        @else
                                            <span class="text-muted h4 mb-0 d-block">--:--</span>
                                        @endif
                                    </div>
                                    <div class="border-start h-50 mx-2"></div>
                                    <div class="text-center px-2">
                                        <small class="text-muted d-block text-uppercase small-caps mb-1">Pulang</small>
                                        @if ($jam_out)
                                            <a href="#" class="btnShowpresensi_out fw-black text-danger h4 mb-0 d-block" id="{{ $d->id }}" status="out">{{ $jam_out }}</a>
                                        @else
                                            <span class="text-muted h4 mb-0 d-block">--:--</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Section 4: Metrics (Late, Hours) --}}
                                <div class="p-3 border-end-md" style="min-width: 180px;">
                                    @if ($jam_in)
                                        <div class="mb-2">
                                            <small class="text-muted d-block mb-1 small-caps">Keterlambatan</small>
                                            @if (!empty($terlambat))
                                                <span class="text-danger fw-bold"><i class="ti ti-alert-triangle me-1"></i>{{ $terlambat['keterangan_terlambat'] }}</span>
                                            @else
                                                <span class="text-success fw-bold"><i class="ti ti-circle-check me-1"></i>Tepat Waktu</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($d->status_kehadiran == 'h' && $jam_in && $jam_out)
                                        <div>
                                            <small class="text-muted d-block mb-1 small-caps">Durasi Kerja</small>
                                            <span class="fw-bold text-dark"><i class="ti ti-hourglass me-1 text-primary"></i>{{ $d->total_jam }} Jam</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Section 5: Actions --}}
                                <div class="p-3 bg-light d-flex flex-row justify-content-center align-items-center gap-2" style="width: 140px;">
                                    @if ($d->status_kehadiran == 'h')
                                        <button class="btn btn-sm btn-label-success btn-icon btnKoreksi" nik="{{ $d->nik }}" tanggal="{{ $tanggal }}" title="Koreksi Presensi">
                                            <i class="ti ti-edit fs-5"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-label-primary btn-icon btngetDatamesin" pin="{{ $d->pin }}" tanggal="{{ $tanggal }}" kode_jadwal="{{ $d->kode_jadwal }}" title="Get Data Mesin">
                                        <i class="ti ti-device-desktop fs-5"></i>
                                    </button>
                                    @hasanyrole(['super admin', 'spv presensi'])
                                        <form action="{{ route('presensi.delete', Crypt::encrypt($d->id)) }}" method="POST" class="deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-label-danger btn-icon delete-confirm" title="Hapus Presensi">
                                                <i class="ti ti-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endhasanyrole
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $karyawan->links() }}
        </div>
    </div>
</div>
<style>
    .card-hover-shadow {
        transition: all 0.3s ease;
    }
    .card-hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: #002e65 !important;
    }
    .small-caps {
        font-variant: all-small-caps;
        letter-spacing: 1px;
    }
    .fw-black {
        font-weight: 900;
    }
    @media (min-width: 768px) {
        .border-end-md {
            border-right: 1px solid #e6e6e6 !important;
        }
    }
    .btn-icon {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }
</style>

<x-modal-form id="modal" size="modal-xl" show="loadmodal" title="" />
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

        const select2Kodecabangsearch = $('.select2Kodecabangsearch');
        if (select2Kodecabangsearch.length) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2Kodedeptsearch = $('.select2Kodedeptsearch');
        if (select2Kodedeptsearch.length) {
            select2Kodedeptsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }


        $(".btngetDatamesin").click(function(e) {
            e.preventDefault();
            var pin = $(this).attr("pin");
            var tanggal = $(this).attr("tanggal");
            var kode_jadwal = $(this).attr("kode_jadwal");
            loading();
            //alert(kode_jadwal);
            $("#modal").modal("show");
            $(".modal-title").text("Get Data Mesin");
            $.ajax({
                type: 'POST',
                url: '/presensi/getdatamesin',
                data: {
                    _token: "{{ csrf_token() }}",
                    pin: pin,
                    tanggal: tanggal,
                    kode_jadwal: kode_jadwal
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#loadmodal").html(respond);
                }
            });
        });

        $(".btnKoreksi").click(function(e) {
            e.preventDefault();
            const nik = $(this).attr("nik");
            const tanggal = $(this).attr("tanggal");
            loading();
            //alert(kode_jadwal);
            $("#modal").modal("show");
            $(".modal-title").text("Koreksi Presensi");
            $.ajax({
                type: 'POST',
                url: '/presensi/koreksipresensi',
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#loadmodal").html(respond);
                }
            });
        });

        $(".btnShowpresensi_in, .btnShowpresensi_out").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            const status = $(this).attr("status");
            loading();
            //alert(kode_jadwal);
            $("#modal").modal("show");
            $(".modal-title").text("Data Presensi Masuk");
            $("#loadmodal").load(`/presensi/${id}/${status}/show`);
        });

        $(".delete-confirm").click(function(e) {
            var form = $(this).closest("form");
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data Presensi Akan Dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
