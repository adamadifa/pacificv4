@extends('layouts.app')
@section('titlepage', 'Presensi Karyawan')

@section('content')
@section('navigasi')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Presensi Karyawan</h4>
            <small class="text-muted">Pantau detail kehadiran karyawan dan history.</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="font-size: 13px">
                <li class="breadcrumb-item">
                    <a href="#"><i class="ti ti-folder me-1"></i>Human Resources</a>
                </li>
                <li class="breadcrumb-item active"><i class="ti ti-calendar-user me-1"></i>Presensi Karyawan</li>
            </ol>
        </nav>
    </div>
@endsection

<div class="row">
    <div class="col-12">
        {{-- Filter Section --}}
        <form action="{{ route('presensi.presensikaryawan') }}" class="mb-3">
            <div class="row g-2">
                <div class="col-lg-3 col-md-4">
                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-3 col-md-4">
                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar" datepicker="flatpickr-date" />
                </div>
                <div class="col-lg-5 col-md-10">
                    <div class="form-group mb-3">
                        <select name="nik" id="nik" class="form-select select2Nik">
                            <option value="">Pilih Karyawan</option>
                            @foreach ($listkaryawan as $d)
                                <option {{ Request('nik') == $d->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-1 col-md-2">
                    <div class="form-group mb-3">
                        <button class="btn btn-primary w-100"><i class="ti ti-search"></i></button>
                    </div>
                </div>
            </div>
        </form>
        {{-- Data Cards Heading --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="ti ti-list me-2"></i>Daftar Kehadiran ({{ $karyawan->count() }})</h5>
            <div class="badge bg-label-primary fs-6">Periode: {{ Request('dari') }} - {{ Request('sampai') }}</div>
        </div>

        {{-- Grid of Cards --}}
        <div class="row g-3">
            @foreach ($karyawan as $d)
                @php
                    $potongan_pc = 0;
                    $potongan_jamkeluar = 0;
                    $potongan_terlambat = 0;
                    $potongan_sakit = 0;
                    $potongan_izin = 0;
                    //Tanggal Selesai Jam Kerja Jika Lintas Hari Maka Tanggal Presensi + 1 Hari
                    $tanggal_selesai =
                        $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;

                    $jam_in = !empty($d->jam_in) ? date('Y-m-d H:i', strtotime($d->jam_in)) : '';
                    $jam_out = !empty($d->jam_out) ? date('Y-m-d H:i', strtotime($d->jam_out)) : '';

                    //Jadwal Jam Kerja
                    $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                    $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                    //Jika SPG/SPB/SPM Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                    $is_spg_spm = in_array($d->kode_jabatan, ['J22', 'J23']) || (in_array($d->kode_jabatan, ['J31', 'J32']) && $d->tanggal >= '2026-02-21');
                    $jam_mulai = $is_spg_spm ? $jam_in : $j_mulai;
                    $jam_selesai = $is_spg_spm ? $jam_out : $j_selesai;

                    // Jam Istirahat
                    if ($d->istirahat == '1') {
                        if ($d->lintashari == '0') {
                            $jam_awal_istirahat = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_awal_istirahat));
                            $jam_akhir_istirahat = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_akhir_istirahat));
                        } else {
                            $jam_awal_istirahat = date(
                                'Y-m-d H:i',
                                strtotime($tanggal_selesai . ' ' . $d->jam_awal_istirahat),
                            );
                            $jam_akhir_istirahat = date(
                                'Y-m-d H:i',
                                strtotime($tanggal_selesai . ' ' . $d->jam_akhir_istirahat),
                            );
                        }
                    } else {
                        $jam_awal_istirahat = null;
                        $jam_akhir_istirahat = null;
                    }

                    $terlambat = hitungjamterlambat($jam_in, $jam_mulai, $d->kode_izin_terlambat);

                    if (!empty($jam_out) && $jam_out < $jam_selesai) {
                        $pc = hitungpulangcepat($jam_out, $jam_selesai, $jam_awal_istirahat, $jam_akhir_istirahat);
                        if (!empty($d->kode_izin_pulang) && $d->izin_pulang_direktur == '1') {
                            $potongan_pc = 0;
                        } else {
                            $potongan_pc = $pc['desimal_pulangcepat'];
                        }
                    }

                    if (!empty($d->kode_izin_keluar)) {
                        $jam_keluar_k = date('Y-m-d H:i', strtotime($d->jam_keluar));
                        $jam_kembali_k = !empty($d->jam_kembali) ? date('Y-m-d H:i', strtotime($d->jam_kembali)) : '';
                        $keluarkantor = hitungjamkeluarkantor(
                            $jam_keluar_k,
                            $jam_kembali_k,
                            $jam_selesai,
                            $jam_out,
                            $d->total_jam,
                            $d->istirahat,
                            $jam_awal_istirahat,
                            $jam_akhir_istirahat,
                        );
                        if ($d->izin_keluar_direktur == '1' || $d->keperluan == 'K') {
                            $potongan_jamkeluar = 0;
                        } else {
                            $potongan_jamkeluar = $keluarkantor['desimaljamkeluar'];
                        }
                    }

                    if (!empty($d->jam_in)) {
                        if (!empty($terlambat)) {
                            if (!empty($d->kode_izin_terlambat) && $d->izin_terlambat_direktur == '1') {
                                $potongan_terlambat = 0;
                            } else {
                                $potongan_terlambat = $terlambat['desimal_terlambat'];
                            }
                        }
                    }

                    if ($d->status_kehadiran == 'i') {
                        $potongan_izin = $d->izin_absen_direktur == '1' ? 0 : $d->total_jam;
                    } elseif ($d->status_kehadiran == 's') {
                        $potongan_sakit = (!empty($d->doc_sid) || $d->izin_sakit_direktur == '1') ? 0 : $d->total_jam;
                    }

                    $total_jam_kerja = $d->total_jam - $potongan_jamkeluar - $potongan_terlambat - $potongan_pc - $potongan_sakit - $potongan_izin;
                @endphp

                <div class="col-12">
                    <div class="card shadow-none border card-hover-shadow overflow-hidden">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column flex-md-row">
                                {{-- Section 1: Identity & Date --}}
                                <div class="p-3 border-end-md d-flex align-items-center" style="min-width: 280px; background-color: #002e65;">
                                    <div class="avatar avatar-md me-3 bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                        @if ($d->foto && Storage::disk('public')->exists('karyawan/' . $d->foto))
                                            <img src="{{ asset('storage/karyawan/' . $d->foto) }}" alt="Avatar" class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                        @else
                                            <span class="text-white fw-bold fs-4">{{ getInitials($d->nama_karyawan) }}</span>
                                        @endif
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0 text-truncate fw-bold text-white">{{ formatName($d->nama_karyawan) }}</h6>
                                        <div class="small fw-medium text-white-50 mb-1"><i class="ti ti-id me-1"></i>{{ $d->nik }}</div>
                                        <div class="small fw-medium text-white-50 mb-2"><i class="ti ti-building me-1"></i>{{ $d->kode_dept }} | {{ $d->kode_cabang }}</div>
                                        <span class="badge bg-white text-primary fw-bold" style="font-size: 0.7rem; width: fit-content;">{{ formatIndo($d->tanggal) }}</span>
                                    </div>
                                </div>

                                {{-- Section 2: Schedule & Status --}}
                                <div class="p-3 border-end-md flex-grow-1" style="min-width: 220px;">
                                    <div class="mb-3">
                                        <small class="text-muted d-block mb-1 small-caps tracking-wider">Jadwal & Shift</small>
                                        @if (!empty($d->kode_jadwal))
                                            <div class="fw-bold text-dark text-uppercase">{{ $d->nama_jadwal ?: 'Non-Shift' }}</div>
                                            <small class="text-primary fw-medium">{{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}</small>
                                        @else
                                            <span class="badge bg-label-secondary">Belum Absen</span>
                                        @endif
                                    </div>
                                    <div>
                                        <small class="text-muted d-block mb-1 small-caps tracking-wider">Status Kehadiran</small>
                                        @if ($d->status_kehadiran == 'h')
                                            <span class="badge bg-label-success">HADIR</span>
                                        @elseif ($d->status_kehadiran == 'i')
                                            <span class="badge bg-label-info">IZIN {{ $d->izin_absen_direktur == '1' ? '(D)' : '' }}</span>
                                        @elseif ($d->status_kehadiran == 's')
                                            <span class="badge bg-label-warning">SAKIT {{ $d->izin_sakit_direktur == '1' ? '(D)' : '' }}</span>
                                        @elseif ($d->status_kehadiran == 'a')
                                            <span class="badge bg-label-danger">ALFA</span>
                                        @elseif ($d->status_kehadiran == 'c')
                                            <span class="badge bg-label-primary">CUTI</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Section 3: Check In / Out Times --}}
                                <div class="p-3 border-end-md d-flex align-items-center justify-content-around bg-white" style="min-width: 250px;">
                                    <div class="text-center px-2">
                                        <small class="text-muted d-block text-uppercase small-caps tracking-wider mb-1">Masuk</small>
                                        @if (!empty($d->jam_in))
                                            <span class="fw-black text-success h4 mb-0 d-block">{{ date('H:i', strtotime($d->jam_in)) }}</span>
                                        @else
                                            <span class="text-muted h4 mb-0 d-block">--:--</span>
                                        @endif
                                    </div>
                                    <div class="border-start h-50 mx-2"></div>
                                    <div class="text-center px-2">
                                        <small class="text-muted d-block text-uppercase small-caps tracking-wider mb-1">Pulang</small>
                                        @if (!empty($d->jam_out))
                                            <span class="fw-black text-danger h4 mb-0 d-block">{{ date('H:i', strtotime($d->jam_out)) }}</span>
                                        @else
                                            <span class="text-muted h4 mb-0 d-block">--:--</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Section 4: Metrics --}}
                                <div class="p-3 border-end-md" style="min-width: 200px;">
                                    @if (!empty($d->jam_in))
                                        <div class="mb-2">
                                            <small class="text-muted d-block mb-1 small-caps tracking-wider">Keterlambatan</small>
                                            @if (!empty($terlambat))
                                                <span class="text-danger fw-bold"><i class="ti ti-alert-triangle me-1"></i>{{ $terlambat['keterangan_terlambat'] }} ({{ $terlambat['desimal_terlambat'] }})</span>
                                            @else
                                                <span class="text-success fw-bold"><i class="ti ti-circle-check me-1"></i>Tepat Waktu</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if (!empty($d->kode_izin_keluar))
                                        <div class="mb-2">
                                            <small class="text-muted d-block mb-1 small-caps tracking-wider">Keluar Kantor</small>
                                            <span class="{{ $keluarkantor['color'] }} fw-bold">{{ $keluarkantor['totaljamkeluar'] }} ({{ $keluarkantor['desimaljamkeluar'] }})</span>
                                        </div>
                                    @endif
                                    @if($d->status_kehadiran == 'h' && !empty($d->jam_in) && !empty($d->jam_out))
                                        <div>
                                            <small class="text-muted d-block mb-1 small-caps tracking-wider">Total Jam Kerja</small>
                                            <span class="fw-bold text-dark"><i class="ti ti-hourglass me-1 text-primary"></i>{{ $total_jam_kerja }} Jam</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Section 5: Actions --}}
                                <div class="p-3 bg-light d-flex flex-row flex-md-column justify-content-center align-items-center gap-2" style="min-width: 100px;">
                                    @if (in_array($d->status_kehadiran, ['h', 'i', 's', 'c']))
                                        <button class="btn btn-sm btn-label-success btn-icon btnKoreksi" nik="{{ $d->nik }}" tanggal="{{ $d->tanggal }}" title="Koreksi Presensi">
                                            <i class="ti ti-edit fs-5"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-label-primary btn-icon btngetDatamesin" pin="{{ $d->pin }}" tanggal="{{ $d->tanggal }}" kode_jadwal="{{ $d->kode_jadwal }}" title="Get Data Mesin">
                                        <i class="ti ti-device-desktop fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
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
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
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

        const select2Nik = $('.select2Nik');
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
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



    });
</script>
@endpush
