@extends('layouts.app')
@section('titlepage', 'Monitoring Presensi')

@section('content')
@section('navigasi')
    <span>Monitoring Presensi</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('presensi.index') }}">
                            <x-input-with-icon label="Tanggal" value="{{ Request('tanggal') }}" name="tanggal"
                                icon="ti ti-calendar" datepicker="flatpickr-date" />
                            @hasanyrole($roles_access_all_karyawan)
                                <div class="row">
                                    <div class="col-lg-12 col-sm-12 col-md-12">
                                        <x-select label="Cabang" name="kode_cabang_search" :data="$cabang"
                                            key="kode_cabang" textShow="nama_cabang"
                                            selected="{{ Request('kode_cabang_search') }}" upperCase="true"
                                            select2="select2Kodecabangsearch" />
                                    </div>
                                </div>
                            @endhasanyrole
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                                        name="nama_karyawan" icon="ti ti-search" />
                                </div>

                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept"
                                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true"
                                        select2="select2Kodedeptsearch" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Group" name="kode_group" :data="$group" key="kode_group"
                                        textShow="nama_group" selected="{{ Request('kode_group') }}" upperCase="true" />
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary w-100"><i
                                            class="ti ti-icons ti-search me-1"></i>Cari</button>
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
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Cbg</th>
                                        <th>Jadwal</th>
                                        <th class="text-center">Jam Masuk</th>
                                        <th class="text-center">Jam Pulang</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Keluar</th>
                                        <th class="text-center">Terlambat</th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_jam_libur = 0;
                                    @endphp
                                    @foreach ($karyawan as $d)

                                        @php
                                            $potongan_pc = 0;
                                            $potongan_jamkeluar = 0;
                                            $potongan_terlambat = 0;
                                            $potongan_sakit = 0;
                                            $potongan_izin = 0;
                                            $total_jam_kerja = $d->total_jam;

                                            $search = [
                                                'nik' => $d->nik,
                                                'tanggal' => $tanggal,
                                            ];

                                            $search_minggumasuk = [
                                                'nik' => $d->nik,
                                                'tanggal' => $tanggal,
                                            ];

                                            //Cek Hari Libur , Dirumahkan , Lbur  Pengganti, atau Tanggal 5 Jam
                                            $cekliburnasional = ceklibur($dataliburnasional, $search); // Cek Libur Nasional
                                            $cekdirumahkan = ceklibur($datadirumahkan, $search); // Cek Dirumahkan
                                            $cekliburpengganti = ceklibur($dataliburpengganti, $search); // Cek Libur Pengganti
                                            $cektanggallimajam = ceklibur($datatanggallimajam, $search);
                                            $cekminggumasuk = ceklibur($dataminggumasuk, $search_minggumasuk);
                                            //Tanggal Selesai Jam Kerja Jika Lintas Hari Maka Tanggal Presensi + 1 Hari
                                            $tanggal_selesai =
                                                $d->lintashari == '1'
                                                    ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal)))
                                                    : $d->tanggal;

                                            //Jam Absen Masuk dan Pulang
                                            $jam_in = !empty($d->jam_in)
                                                ? date('Y-m-d H:i', strtotime($d->jam_in))
                                                : '';
                                            $jam_out = !empty($d->jam_out)
                                                ? date('Y-m-d H:i', strtotime($d->jam_out))
                                                : '';

                                            //Jadwal Jam Kerja
                                            $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                                            $j_selesai = date(
                                                'Y-m-d H:i',
                                                strtotime($tanggal_selesai . ' ' . $d->jam_selesai),
                                            );

                                            //Jadwal SPG
                                            //Jika SPG Jam Mulai Kerja nya adalah Saat Dia Absen  Jika Tidak Sesuai Jadwal
                                            $jam_mulai = in_array($d->kode_jabatan, ['J22', 'J23'])
                                                ? $jam_in
                                                : $j_mulai;
                                            $jam_selesai = in_array($d->kode_jabatan, ['J22', 'J23'])
                                                ? $jam_out
                                                : $j_selesai;

                                            if (getNamahari($tanggal) == 'Minggu') {
                                                if ($d->kode_jabatan != 'J20') {
                                                    $jam_mulai = $jam_in;
                                                    $jam_selesai = $jam_out;
                                                }
                                            }

                                            // Jam Istirahat
                                            if ($d->istirahat == '1') {
                                                if ($d->lintashari == '0') {
                                                    $jam_awal_istirahat = date(
                                                        'Y-m-d H:i',
                                                        strtotime($d->tanggal . ' ' . $d->jam_awal_istirahat),
                                                    );
                                                    $jam_akhir_istirahat = date(
                                                        'Y-m-d H:i',
                                                        strtotime($d->tanggal . ' ' . $d->jam_akhir_istirahat),
                                                    );
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
                                            <td>{{ $d->nik }} </td>
                                            <td>{{ formatName($d->nama_karyawan) }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>

                                                <!--Jika Jadwal Tidak Kosong Tampilkan Nama Jadawal dan Jadwal Masuk dan Jadwal Pulang-->
                                                @if (!empty($d->kode_jadwal))
                                                    @php
                                                        $total_jam_libur = 0;
                                                    @endphp
                                                    {{ $d->nama_jadwal }}
                                                    ({{ date('H:i', strtotime($jam_mulai)) }} -
                                                    {{ date('H:i', strtotime($jam_selesai)) }})
                                                @else
                                                    <!-- Jika Jadwal Kosong Maka Cek Apakah tanggal Tersebut Libur-->
                                                    @if (!empty($cekliburnasional))
                                                        @php
                                                            //Jika Libur Nasional Cek Nama Hari Jika Hari Sabtu Maka Total Jam Kerja 5 Jam Selain Itu 7 jam
                                                            $total_jam_libur = 7;
                                                        @endphp
                                                        <span class="badge bg-success">Libur Nasional</span>
                                                    @elseif(!empty($cekdirumahkan))
                                                        @php
                                                            //Jika Dirumahkan
                                                            if (getNamahari($tanggal) == 'Sabtu') {
                                                                //Jika Hari Sabtu Maka Total Jam adalah 2.5 Jam
                                                                $total_jam_libur = 2.5;
                                                            } else {
                                                                //Jika Bukan Hari Sabtu, Maka Cek Apakah Tanggal Tersebut Adalah Tanggal Yang Di Ubah Menjadi 5 Jam Karena Besoknya Libur Nasional
                                                                if (!empty($cektanggallimajam)) {
                                                                    //Jika Tanggal Yang di  Ubah Menjadi 5 Jam Kerja Maka Total Jam Menjadi 2.5
                                                                    $total_jam_libur = 2.5;
                                                                } else {
                                                                    $total_jam_libur = 3.5;
                                                                }
                                                            }
                                                        @endphp
                                                        <span class="badge bg-info">Dirumahkan
                                                            {{ $total_jam_libur }}</span>
                                                    @elseif(!empty($cekliburpengganti))
                                                        @php
                                                            //Jika Hari ini Libur , menggantikan Libur hari Minggu Maka Total Jam adalah 0
                                                            $total_jam_libur = 0;
                                                        @endphp
                                                        <span class="badge bg-info">Libur Pengganti Tgl
                                                            {{ formatIndo($cekliburpengganti[0]['tanggal_diganti']) }}</span>
                                                    @else
                                                        @php
                                                            $total_jam_libur = 0;
                                                        @endphp
                                                        <span class="badge bg-danger">Belum Absen</span>
                                                    @endif
                                                @endif
                                                {{-- {{ $total_jam_libur }} --}}
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_jadwal) && $d->status_kehadiran == 'h' && !empty($d->jam_in))
                                                    <a href="#" class="btnShowpresensi_in"
                                                        id="{{ $d->id }}" status="in">
                                                        {{ date('H:i', strtotime($d->jam_in)) }}
                                                    </a>
                                                @else
                                                    <i class="ti ti-hourglass-empty text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if (!empty($d->kode_jadwal) && $d->status_kehadiran == 'h' && !empty($d->jam_out))
                                                    <a href="#" class="btnShowpresensi_out"
                                                        id="{{ $d->id }}" status="out">
                                                        {{ date('H:i', strtotime($d->jam_out)) }}
                                                    </a>
                                                @else
                                                    <i class="ti ti-hourglass-empty text-danger"></i>
                                                @endif

                                                @if (!empty($jam_out) && $jam_out < $jam_selesai)
                                                    @php
                                                        $pc = hitungpulangcepat(
                                                            $jam_out,
                                                            $jam_selesai,
                                                            $jam_awal_istirahat,
                                                            $jam_akhir_istirahat,
                                                        );
                                                    @endphp
                                                    @if (!empty($d->kode_izin_pulang) && $d->izin_pulang_direktur == '1')
                                                        @php
                                                            $potongan_pc = 0;
                                                        @endphp
                                                        <span class="text-success">(PC :
                                                            {{ $pc['desimal_pulangcepat'] }})</span>
                                                    @else
                                                        @php
                                                            $potongan_pc = $pc['desimal_pulangcepat'];
                                                        @endphp
                                                        <span class="text-danger">(PC :
                                                            {{ $pc['desimal_pulangcepat'] }})</span>
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
                                                        $jam_kembali = !empty($d->jam_kembali)
                                                            ? date('Y-m-d H:i', strtotime($d->jam_kembali))
                                                            : '';

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
                                                            {{ $keluarkantor['totaljamkeluar'] }}
                                                            ({{ $keluarkantor['desimaljamkeluar'] }})
                                                        </span>
                                                        @php
                                                            $potongan_jamkeluar = 0;
                                                        @endphp
                                                    @else
                                                        @if ($d->keperluan == 'K')
                                                            <span class="text-success">
                                                                {{ $keluarkantor['totaljamkeluar'] }}
                                                                ({{ $keluarkantor['desimaljamkeluar'] }})
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
                                                                {{ $keluarkantor['totaljamkeluar'] }}
                                                                ({{ $keluarkantor['desimaljamkeluar'] }})
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $terlambat = hitungjamterlambat(
                                                        $jam_in,
                                                        $jam_mulai,
                                                        $d->kode_izin_terlambat,
                                                    );
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
                                                {{-- {{ $total_jam_libur }} --}}
                                                @php
                                                    $total_jam =
                                                        $d->total_jam +
                                                        $total_jam_libur -
                                                        $potongan_jamkeluar -
                                                        $potongan_terlambat -
                                                        $potongan_pc -
                                                        $potongan_sakit -
                                                        $potongan_izin;
                                                    if (
                                                        ($d->status_kehadiran == 'h' && empty($d->jam_out)) ||
                                                        ($d->status_kehadiran == 'h' && empty($d->jam_in))
                                                    ) {
                                                        $total_jam = 0;
                                                    }
                                                @endphp
                                                {{ $total_jam }}
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @if ($d->status_kehadiran == 'h')
                                                        <a href="#" class="btnKoreksi" nik="{{ $d->nik }}"
                                                            tanggal="{{ !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d') }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endif


                                                    <a href="#" class="btngetDatamesin"
                                                        pin="{{ $d->pin }}"
                                                        tanggal="{{ !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d') }}"
                                                        kode_jadwal="{{ $d->kode_jadwal }}">
                                                        <i class="ti ti-device-desktop text-primary"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $karyawan->links() }}
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
    });
</script>
@endpush
