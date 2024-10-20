@extends('layouts.app')
@section('titlepage', 'Presensi Karyawan')

@section('content')
@section('navigasi')
    <span>Presensi Karyawan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('presensi.presensikaryawan') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                        datepicker="flatpickr-date" />
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <select name="nik" id="nik" class="form-select select2Nik">
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($listkaryawan as $d)
                                        <option {{ Request('nik') == $d->nik ? 'selected' : '' }} value="{{ $d->nik }}">{{ $d->nik }} -
                                            {{ $d->nama_karyawan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Cbg</th>
                                        <th>Jadwal</th>
                                        <th class="text-center">Masuk</th>
                                        <th class="text-center">Pulang</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Keluar</th>
                                        <th class="text-center">Terlambat</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php

                                    @endphp
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

                                            //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                            $jam_mulai = $d->kode_jabatan == 'J22' ? $d->jam_in : $j_mulai;
                                            $jam_selesai = $d->kode_jabatan == 'J22' ? $d->jam_out : $j_selesai;

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
                                        @endphp
                                        <tr>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ formatName($d->nama_karyawan) }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>
                                                @if (!empty($d->kode_jadwal))
                                                    {{ $d->nama_jadwal }}
                                                    ({{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }})
                                                @else
                                                    <span class="badge bg-danger">Belum Absen</span>
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_jadwal) && $d->status_kehadiran == 'h' && !empty($d->jam_in))
                                                    {{ date('H:i', strtotime($d->jam_in)) }}
                                                @else
                                                    <i class="ti ti-hourglass-empty text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_jadwal) && $d->status_kehadiran == 'h' && !empty($d->jam_out))
                                                    {{ date('H:i', strtotime($d->jam_out)) }}
                                                @else
                                                    <i class="ti ti-hourglass-empty text-danger"></i>
                                                @endif

                                                @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                                    @php
                                                        $pc = hitungpulangcepat($jam_out, $jam_selesai, $jam_awal_istirahat, $jam_akhir_istirahat);
                                                    @endphp
                                                    @if (!empty($d->kode_izin_pulang) && $d->izin_pulang_direktur == '1')
                                                        @php
                                                            $potongan_pc = 0;
                                                        @endphp
                                                        <span class="text-success">(PC : {{ $pc['desimal_pulangcepat'] }})</span>
                                                    @else
                                                        @php
                                                            $potongan_pc = $pc['desimal_pulangcepat'];
                                                        @endphp
                                                        <span class="text-danger">(PC : {{ $pc['desimal_pulangcepat'] }})</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_jadwal))
                                                    @if ($d->status_kehadiran == 'h')
                                                        <span class="badge bg-success">H</span>
                                                    @elseif ($d->status_kehadiran == 'i')
                                                        @if ($d->izin_absen_direktur == '1')
                                                            @php
                                                                $potongan_izin = 0;
                                                            @endphp
                                                            <span class="badge bg-info">I(D)</span>
                                                        @else
                                                            @php
                                                                $potongan_izin = $d->total_jam;
                                                            @endphp
                                                            <span class="badge bg-info">I</span>
                                                        @endif
                                                    @elseif ($d->status_kehadiran == 's')
                                                        @if (!empty($d->doc_sid))
                                                            <span class="badge bg-info">SID</span>
                                                        @else
                                                            @if ($d->izin_sakit_direktur == '1')
                                                                @php
                                                                    $potongan_sakit = 0;
                                                                @endphp
                                                                <span class="badge bg-warning">S(D)</span>
                                                            @else
                                                                @php
                                                                    $potongan_sakit = $d->total_jam;
                                                                @endphp
                                                                <span class="badge bg-warning">S</span>
                                                            @endif
                                                        @endif
                                                    @elseif ($d->status_kehadiran == 'a')
                                                        <span class="badge bg-danger">A</span>
                                                    @elseif ($d->status_kehadiran == 'c')
                                                        <span class="badge bg-primary">C</span>
                                                    @endif
                                                @else
                                                    <i class="ti ti-hourglass-empty text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_izin_keluar))
                                                    @php
                                                        $jam_keluar = date('Y-m-d H:i', strtotime($d->jam_keluar));
                                                        $jam_kembali = !empty($d->jam_kembali) ? date('Y-m-d H:i', strtotime($d->jam_kembali)) : '';

                                                        $keluarkantor = hitungjamkeluarkantor(
                                                            $jam_keluar,
                                                            $jam_kembali,
                                                            $jam_selesai,
                                                            $jam_out,
                                                            $d->total_jam,
                                                            $d->istirahat,
                                                            $jam_awal_istirahat,
                                                            $jam_akhir_istirahat,
                                                        );
                                                    @endphp
                                                    @if ($d->izin_keluar_direktur == '1')
                                                        <span class="text-success">
                                                            {{ $keluarkantor['totaljamkeluar'] }} ({{ $keluarkantor['desimaljamkeluar'] }})
                                                        </span>
                                                        @php
                                                            $potongan_jamkeluar = 0;
                                                        @endphp
                                                    @else
                                                        @if ($d->keperluan == 'K')
                                                            <span class="text-success">
                                                                {{ $keluarkantor['totaljamkeluar'] }} ({{ $keluarkantor['desimaljamkeluar'] }})
                                                            </span>
                                                            @php
                                                                $potongan_jamkeluar = 0;
                                                            @endphp
                                                        @else
                                                            @php
                                                                $potongan_jamkeluar = $keluarkantor['desimaljamkeluar'];
                                                            @endphp
                                                            {{-- {{ $jam_kembali }} --}}
                                                            <span class="{{ $keluarkantor['color'] }}">
                                                                {{ $keluarkantor['totaljamkeluar'] }} ({{ $keluarkantor['desimaljamkeluar'] }})
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $terlambat = hitungjamterlambat($jam_in, $jam_mulai, $d->kode_izin_terlambat);
                                                @endphp
                                                @if (!empty($d->jam_in))
                                                    @if (!empty($terlambat))
                                                        @if (!empty($d->kode_izin_terlambat) && $d->izin_terlambat_direktur == '1')
                                                            @php
                                                                $potongan_terlambat = 0;
                                                                $potongan_denda = 0;
                                                            @endphp
                                                            <span class="text-success">
                                                                {{ $terlambat['keterangan_terlambat'] }}
                                                                {{ !empty($terlambat['desimal_terlambat']) ? '(' . $terlambat['desimal_terlambat'] . ')' : '' }}
                                                                {{-- {{ '(' . formatAngka($potongan_denda) . ')' }} --}}
                                                            </span>
                                                        @else
                                                            @php
                                                                $denda = hitungdenda(
                                                                    $terlambat['jamterlambat'],
                                                                    $terlambat['menitterlambat'],
                                                                    $d->kode_izin_terlambat,
                                                                    $d->kode_dept,
                                                                    $d->kode_jabatan,
                                                                );
                                                                $potongan_terlambat = $terlambat['desimal_terlambat'];
                                                            @endphp
                                                            <span class="{{ $terlambat['color_terlambat'] }}">
                                                                {{ $terlambat['keterangan_terlambat'] }}
                                                                {{ !empty($terlambat['desimal_terlambat']) ? '(' . $terlambat['desimal_terlambat'] . ')' : '' }}
                                                                {{ !empty($denda['denda']) ? '(' . formatAngka($denda['denda']) . ')' : '' }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-success">Tepat Waktu</span>
                                                    @endif
                                                @endif

                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $total_jam =
                                                        $d->total_jam -
                                                        $potongan_jamkeluar -
                                                        $potongan_terlambat -
                                                        $potongan_pc -
                                                        $potongan_sakit -
                                                        $potongan_izin;
                                                @endphp
                                                {{ $total_jam }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if (in_array($d->status_kehadiran, ['h', 'i', 's', 'c']))
                                                        <a href="#" class="btnKoreksi me-2" nik="{{ $d->nik }}"
                                                            tanggal="{{ $d->tanggal }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endif


                                                    <a href="#" class="btngetDatamesin" pin="{{ $d->pin }}"
                                                        tanggal="{{ $d->tanggal }}" kode_jadwal="{{ $d->kode_jadwal }}">
                                                        <i class="ti ti-device-desktop text-primary"></i>
                                                    </a>
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
