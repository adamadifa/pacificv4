@extends('v2.layouts.app')
@section('titlepage', 'Presensi Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Human Resources</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Presensi Karyawan</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Presensi Karyawan</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pantau detail kehadiran karyawan dan riwayat harian.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="h-[32px] px-3 rounded-lg bg-blue-50 dark:bg-blue-950/60 border border-blue-200/70 dark:border-blue-900/50 text-[#284c9a] dark:text-blue-300 text-xs font-bold inline-flex items-center gap-1.5">
                <i data-lucide="list" class="w-3.5 h-3.5"></i>
                <span>{{ $karyawan->count() }} Data</span>
            </span>
            @if (request('dari') || request('sampai'))
                <span class="h-[32px] px-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5">
                    <i data-lucide="calendar-range" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                    <span>{{ request('dari') }} - {{ request('sampai') }}</span>
                </span>
            @endif
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2, FULL WIDTH) -->
    <form action="{{ route('presensi.presensikaryawan') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Dari -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="dari" id="dari_pk_search" value="{{ request('dari') }}"
                    placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Sampai -->
            <div class="sm:col-span-3 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="sampai" id="sampai_pk_search" value="{{ request('sampai') }}"
                    placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Karyawan (Select2 V2) -->
            <div class="sm:col-span-5 relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <select name="nik" id="nik_pk_search" class="select2Nik w-full">
                    <option value="">Pilih Karyawan</option>
                    @foreach ($listkaryawan as $d)
                        <option value="{{ $d->nik }}" @selected(request('nik') == $d->nik)>
                            {{ $d->nik }} - {{ $d->nama_karyawan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cari -->
            <div class="sm:col-span-1">
                <button type="submit"
                    class="w-full h-[36px] px-3 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1 cursor-pointer">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span class="sm:hidden lg:inline">Cari</span>
                </button>
            </div>
        </div>
    </form>

    <!-- LIST PRESENSI KARYAWAN (FULL WIDTH) -->
    <div class="space-y-2.5">
        @forelse ($karyawan as $d)
            @php
                $potongan_pc = 0;
                $potongan_jamkeluar = 0;
                $potongan_terlambat = 0;
                $potongan_sakit = 0;
                $potongan_izin = 0;

                $tanggal_selesai = $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;
                $jam_in = !empty($d->jam_in) ? date('Y-m-d H:i', strtotime($d->jam_in)) : '';
                $jam_out = !empty($d->jam_out) ? date('Y-m-d H:i', strtotime($d->jam_out)) : '';

                $j_mulai = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                $j_selesai = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                $is_spg_spm = in_array($d->kode_jabatan, ['J22', 'J23']) || (in_array($d->kode_jabatan, ['J31', 'J32']) && $d->tanggal >= '2026-02-21');
                $jam_mulai = $is_spg_spm ? $jam_in : $j_mulai;
                $jam_selesai = $is_spg_spm ? $jam_out : $j_selesai;

                if ($d->istirahat == '1') {
                    if ($d->lintashari == '0') {
                        $jam_awal_istirahat = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_awal_istirahat));
                        $jam_akhir_istirahat = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_akhir_istirahat));
                    } else {
                        $jam_awal_istirahat = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_awal_istirahat));
                        $jam_akhir_istirahat = date('Y-m-d H:i', strtotime($tanggal_selesai . ' ' . $d->jam_akhir_istirahat));
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

                if (!empty($d->jam_in) && !empty($terlambat)) {
                    if (!empty($d->kode_izin_terlambat) && $d->izin_terlambat_direktur == '1') {
                        $potongan_terlambat = 0;
                    } else {
                        $potongan_terlambat = $terlambat['desimal_terlambat'];
                    }
                }

                if ($d->status_kehadiran == 'i') {
                    $potongan_izin = $d->izin_absen_direktur == '1' ? 0 : $d->total_jam;
                } elseif ($d->status_kehadiran == 's') {
                    $potongan_sakit = (!empty($d->doc_sid) || $d->izin_sakit_direktur == '1') ? 0 : $d->total_jam;
                }

                $total_jam_kerja = $d->total_jam - $potongan_jamkeluar - $potongan_terlambat - $potongan_pc - $potongan_sakit - $potongan_izin;
            @endphp

            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs hover:shadow-md hover:border-[#284c9a]/50 transition-all duration-150 overflow-hidden">
                <div class="flex flex-col lg:flex-row items-stretch">
                    <!-- Section 1: Identity & Date (bg-[#284c9a]) -->
                    <div class="p-3.5 bg-[#284c9a] text-white flex items-center gap-3 lg:w-72 shrink-0">
                        <div class="w-11 h-11 rounded-full overflow-hidden shrink-0 bg-white/15 border-2 border-white/40 flex items-center justify-center">
                            @if ($d->foto && Storage::disk('public')->exists('karyawan/' . ($d instanceof App\Models\Karyawan ? $d->getRawOriginal('foto') : $d->foto)))
                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-black text-white">{{ getInitials($d->nama_karyawan) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h6 class="text-xs font-bold text-white truncate">{{ formatName($d->nama_karyawan) }}</h6>
                            <p class="text-[11px] font-mono-num text-white/75 mt-0.5">{{ $d->nik }} • {{ $d->kode_dept }} | {{ $d->kode_cabang }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded bg-white text-[#284c9a] text-[10.5px] font-bold">
                                {{ formatIndo($d->tanggal) }}
                            </span>
                        </div>
                    </div>

                    <!-- Section 2: Jadwal & Status -->
                    <div class="py-3.5 px-2 flex-1 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 grid grid-cols-2 divide-x divide-slate-200 dark:divide-slate-800 items-center">
                        <div class="px-3 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Jadwal & Shift</p>
                            @if (!empty($d->kode_jadwal))
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase mt-0.5 truncate">{{ $d->nama_jadwal ?: 'Non-Shift' }}</p>
                                <p class="text-[11px] font-mono-num text-[#284c9a] dark:text-blue-400 font-semibold">{{ date('H:i', strtotime($jam_mulai)) }} - {{ date('H:i', strtotime($jam_selesai)) }}</p>
                            @else
                                <span class="inline-block mt-0.5 px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10.5px] font-bold">Belum Absen</span>
                            @endif
                        </div>
                        <div class="px-3 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Status Kehadiran</p>
                            @if ($d->status_kehadiran == 'h')
                                <span class="px-2 py-0.5 rounded bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">HADIR</span>
                            @elseif ($d->status_kehadiran == 'i')
                                <span class="px-2 py-0.5 rounded bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[10.5px] font-bold">IZIN {{ $d->izin_absen_direktur == '1' ? '(D)' : '' }}</span>
                            @elseif ($d->status_kehadiran == 's')
                                <span class="px-2 py-0.5 rounded bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10.5px] font-bold">SAKIT {{ $d->izin_sakit_direktur == '1' ? '(D)' : '' }}</span>
                            @elseif ($d->status_kehadiran == 'a')
                                <span class="px-2 py-0.5 rounded bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">ALFA</span>
                            @elseif ($d->status_kehadiran == 'c')
                                <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[10.5px] font-bold">CUTI</span>
                            @else
                                <span class="text-xs font-medium text-slate-400">-</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 3: Jam Masuk & Pulang -->
                    <div class="py-3.5 px-2 lg:w-60 shrink-0 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 grid grid-cols-2 divide-x divide-slate-200 dark:divide-slate-800 items-center">
                        <div class="text-center px-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Masuk</p>
                            @if (!empty($d->jam_in))
                                <span class="text-base font-black font-mono-num text-emerald-600">{{ date('H:i', strtotime($d->jam_in)) }}</span>
                            @else
                                <span class="text-base font-bold font-mono-num text-slate-300 dark:text-slate-600">--:--</span>
                            @endif
                        </div>
                        <div class="text-center px-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Pulang</p>
                            @if (!empty($d->jam_out))
                                <span class="text-base font-black font-mono-num text-rose-600">{{ date('H:i', strtotime($d->jam_out)) }}</span>
                            @else
                                <span class="text-base font-bold font-mono-num text-slate-300 dark:text-slate-600">--:--</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 4: Metrics -->
                    <div class="py-3.5 px-2 lg:w-72 shrink-0 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 grid grid-cols-2 divide-x divide-slate-200 dark:divide-slate-800 items-center">
                        <div class="px-3 min-w-0 space-y-1.5">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Keterlambatan</p>
                                @if (!empty($d->jam_in))
                                    @if (!empty($terlambat))
                                        <p class="text-xs font-bold text-rose-600 dark:text-rose-400 mt-0.5">
                                            {{ $terlambat['keterangan_terlambat'] }} ({{ $terlambat['desimal_terlambat'] }})
                                        </p>
                                    @else
                                        <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Tepat Waktu</p>
                                    @endif
                                @else
                                    <p class="text-xs font-medium text-slate-400 mt-0.5">-</p>
                                @endif
                            </div>
                            @if (!empty($d->kode_izin_keluar))
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Keluar Kantor</p>
                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mt-0.5">
                                        {{ $keluarkantor['totaljamkeluar'] }} ({{ $keluarkantor['desimaljamkeluar'] }})
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="px-3 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Jam Kerja</p>
                            @if ($d->status_kehadiran == 'h' && !empty($d->jam_in) && !empty($d->jam_out))
                                <p class="text-xs font-bold font-mono-num text-slate-800 dark:text-slate-100 mt-0.5">{{ $total_jam_kerja }} Jam</p>
                            @else
                                <p class="text-xs font-medium text-slate-400 mt-0.5">-</p>
                            @endif
                        </div>
                    </div>

                    <!-- Section 5: Actions -->
                    <div class="p-3 bg-slate-50/70 dark:bg-slate-800/40 flex flex-row lg:flex-col lg:w-24 shrink-0 items-center justify-center gap-1.5">
                        @if (in_array($d->status_kehadiran, ['h', 'i', 's', 'c']))
                            <button type="button" nik="{{ $d->nik }}" tanggal="{{ $d->tanggal }}"
                                class="btnKoreksi w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                title="Koreksi Presensi">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </button>
                        @endif
                        <button type="button" pin="{{ $d->pin }}" tanggal="{{ $d->tanggal }}" kode_jadwal="{{ $d->kode_jadwal }}"
                            class="btngetDatamesin w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                            title="Get Data Mesin">
                            <i data-lucide="monitor" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center">
                <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                    <i data-lucide="calendar-off" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
                    <h5 class="text-sm font-bold text-slate-700 dark:text-slate-200">Data Tidak Ditemukan</h5>
                    <p class="text-xs text-slate-400">Silakan pilih rentang tanggal dan karyawan untuk menampilkan data.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<!-- ================= MODAL PRESENSI (STATIC BACKDROP) ================= -->
<div id="modalPresensi" class="fixed inset-0 z-50 hidden">
    <div id="modalPresensiBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPresensiDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalPresensiTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Presensi Karyawan</h5>
                </div>
                <button type="button" id="btnCloseModalPresensi"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div id="loadmodalPresensi" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        $('.select2Nik').select2({
            placeholder: 'Pilih Karyawan',
            allowClear: true,
            dropdownCssClass: 'select2-dropdown-v2'
        });

        const $modal = $('#modalPresensi');
        const $dialog = $('#modalPresensiDialog');
        const $backdrop = $('#modalPresensiBackdrop');
        const $title = $('#modalPresensiTitle');
        const $content = $('#loadmodalPresensi');

        function openModal(sizeClass) {
            $dialog.removeClass('max-w-2xl max-w-4xl').addClass(sizeClass || 'max-w-4xl');
            $modal.removeClass('hidden');
            $('body').addClass('overflow-hidden');
        }

        function closeModal() {
            $modal.addClass('hidden');
            $('body').removeClass('overflow-hidden');
            $content.html('');
        }

        window.closeModalPresensi = closeModal;

        $('#btnCloseModalPresensi').on('click', closeModal);

        $backdrop.on('click', function(e) {
            e.stopPropagation();
            $dialog.addClass('scale-[1.015]');
            setTimeout(() => $dialog.removeClass('scale-[1.015]'), 150);
        });

        function showLoading() {
            $content.html(`
                <div class="py-12 flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500">Memuat data...</span>
                </div>
            `);
        }

        $(document).on('click', '.btngetDatamesin', function(e) {
            e.preventDefault();
            const pin = $(this).attr('pin');
            const tanggal = $(this).attr('tanggal');
            const kode_jadwal = $(this).attr('kode_jadwal');
            $title.text('Get Data Mesin');
            openModal('max-w-4xl');
            showLoading();
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
                    $content.html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });

        $(document).on('click', '.btnKoreksi', function(e) {
            e.preventDefault();
            const nik = $(this).attr('nik');
            const tanggal = $(this).attr('tanggal');
            $title.text('Koreksi Presensi');
            openModal('max-w-2xl');
            showLoading();
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
                    $content.html(respond);
                    if (typeof lucide !== 'undefined') lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush
