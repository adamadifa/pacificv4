@extends('v2.layouts.app')
@section('titlepage', 'Monitoring Presensi')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Human Resources</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Monitoring Presensi</span>
    </div>
@endsection

@section('content')
<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Monitoring Presensi</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Pantau kehadiran karyawan secara real-time.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <span class="h-[32px] px-3 rounded-lg bg-blue-50 dark:bg-blue-950/60 border border-blue-200/70 dark:border-blue-900/50 text-[#284c9a] dark:text-blue-300 text-xs font-bold inline-flex items-center gap-1.5">
                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                <span>{{ method_exists($karyawan, 'total') ? $karyawan->total() : $karyawan->count() }} Karyawan</span>
            </span>
            <span class="h-[32px] px-3 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold inline-flex items-center gap-1.5">
                <i data-lucide="calendar" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400"></i>
                <span>{{ formatIndo($tanggal) }}</span>
            </span>
        </div>
    </div>

    <!-- FILTER BAR (COMPACT NO-LABEL V2, FULL WIDTH) -->
    <form action="{{ route('presensi.index') }}" method="GET"
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3 shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
            <!-- Tanggal -->
            <div class="sm:col-span-2 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                </div>
                <input type="text" name="tanggal" id="tanggal_search" value="{{ request('tanggal') }}"
                    placeholder="Pilih Tanggal" readonly
                    class="flatpickr-date w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <!-- Cabang -->
            @hasanyrole($roles_access_all_karyawan)
                <div class="sm:col-span-2 relative select2-container-v2">
                    <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="building" class="w-4 h-4"></i>
                    </div>
                    <select name="kode_cabang_search" id="kode_cabang_search" class="select2Kodecabangsearch w-full">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $d)
                            <option value="{{ $d->kode_cabang }}" @selected(request('kode_cabang_search') == $d->kode_cabang)>
                                {{ textUpperCase($d->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endhasanyrole

            <!-- Departemen -->
            <div class="sm:col-span-2 relative select2-container-v2">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                    <i data-lucide="briefcase" class="w-4 h-4"></i>
                </div>
                <select name="kode_dept" id="kode_dept_search" class="select2Kodedeptsearch w-full">
                    <option value="">Semua Departemen</option>
                    @foreach ($departemen as $d)
                        <option value="{{ $d->kode_dept }}" @selected(request('kode_dept') == $d->kode_dept)>
                            {{ textUpperCase($d->nama_dept) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Group -->
            <div class="sm:col-span-2 relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="users" class="w-4 h-4"></i>
                </div>
                <select name="kode_group" id="kode_group_search"
                    class="w-full h-[36px] pl-9 pr-8 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Group</option>
                    @foreach ($group as $d)
                        <option value="{{ $d->kode_group }}" @selected(request('kode_group') == $d->kode_group)>
                            {{ textUpperCase($d->nama_group) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Cari Karyawan -->
            <div class="@hasanyrole($roles_access_all_karyawan) sm:col-span-3 @else sm:col-span-5 @endhasanyrole relative">
                <div class="prefix-icon absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user" class="w-4 h-4"></i>
                </div>
                <input type="text" name="nama_karyawan" id="nama_karyawan_search" value="{{ request('nama_karyawan') }}"
                    placeholder="Cari Nama Karyawan..."
                    class="w-full h-[36px] pl-9 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-950 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
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

    <!-- LIST PRESENSI CARDS (FULL WIDTH) -->
    <div class="space-y-2.5">
        @forelse ($karyawan as $d)
            @php
                $search = ['nik' => $d->nik, 'tanggal' => $tanggal];
                $cekliburnasional = ceklibur($dataliburnasional, $search);
                $cekdirumahkan = ceklibur($datadirumahkan, $search);
                $cekmarketingimpact = ceklibur($datamarketingimpact, $search);
                $cekliburpengganti = ceklibur($dataliburpengganti, $search);
                $cektanggallimajam = ceklibur($datatanggallimajam, $search);

                $tanggal_selesai = $d->lintashari == '1' ? date('Y-m-d', strtotime('+1 day', strtotime($d->tanggal))) : $d->tanggal;
                $jam_in = !empty($d->jam_in) ? date('H:i:s', strtotime($d->jam_in)) : null;
                $jam_out = !empty($d->jam_out) ? date('H:i:s', strtotime($d->jam_out)) : null;

                $j_mulai = date('Y-m-d H:i:s', strtotime($d->tanggal . ' ' . $d->jam_mulai));
                $j_selesai = date('Y-m-d H:i:s', strtotime($tanggal_selesai . ' ' . $d->jam_selesai));

                $is_spg_spm = in_array($d->kode_jabatan, ['J22', 'J23']) || (in_array($d->kode_jabatan, ['J31', 'J32']) && $tanggal >= '2026-02-21');
                $jam_mulai_jadwal = $is_spg_spm ? (!empty($d->jam_in) ? date('H:i:s', strtotime($d->jam_in)) : date('H:i:s', strtotime($j_mulai))) : date('H:i:s', strtotime($j_mulai));
                $jam_selesai_jadwal = $is_spg_spm ? (!empty($d->jam_out) ? date('H:i:s', strtotime($d->jam_out)) : date('H:i:s', strtotime($j_selesai))) : date('H:i:s', strtotime($j_selesai));

                $terlambat = hitungjamterlambat($d->jam_in, $d->tanggal . ' ' . $d->jam_mulai, $d->kode_izin_terlambat);
            @endphp

            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs hover:shadow-md hover:border-[#284c9a]/50 transition-all duration-150 overflow-hidden">
                <div class="flex flex-col lg:flex-row items-stretch">
                    <!-- Section 1: Employee Avatar & Identity (bg-[#284c9a]) -->
                    <div class="p-3.5 bg-[#284c9a] text-white flex items-center gap-3 lg:w-64 shrink-0">
                        <div class="w-11 h-11 rounded-full overflow-hidden shrink-0 bg-white/15 border-2 border-white/40 flex items-center justify-center">
                            @if (!empty($d->foto) && file_exists(public_path('storage/karyawan/' . ($d instanceof App\Models\Karyawan ? $d->getRawOriginal('foto') : $d->foto))))
                                <img src="{{ getfotoKaryawan($d->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-xs font-black text-white">{{ getInitials($d->nama_karyawan) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h6 class="text-xs font-bold text-white truncate">{{ formatName($d->nama_karyawan) }}</h6>
                            <p class="text-[11px] font-mono-num text-white/75 mt-0.5">{{ $d->nik }}</p>
                            <p class="text-[10.5px] text-white/70 mt-0.5">{{ $d->kode_dept }} • {{ $d->kode_cabang }}</p>
                        </div>
                    </div>

                    <!-- Section 2: Status & Jadwal -->
                    <div class="p-3.5 flex-1 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 flex flex-col justify-center">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status & Jadwal</span>
                            @if ($d->status_kehadiran == 'h')
                                <span class="px-2 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 text-[10.5px] font-bold">Hadir</span>
                            @elseif ($d->status_kehadiran == 'i')
                                <span class="px-2 py-0.5 rounded-md bg-sky-50 dark:bg-sky-950/60 text-sky-700 dark:text-sky-300 text-[10.5px] font-bold">Izin</span>
                            @elseif ($d->status_kehadiran == 's')
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10.5px] font-bold">Sakit</span>
                            @elseif ($d->status_kehadiran == 'a')
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">Alfa</span>
                            @elseif ($d->status_kehadiran == 'c')
                                <span class="px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-300 text-[10.5px] font-bold">Cuti</span>
                            @elseif (!empty($cekmarketingimpact))
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10.5px] font-bold">Marketing Impact</span>
                            @elseif (!empty($cekdirumahkan))
                                <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 text-[10.5px] font-bold">Dirumahkan</span>
                            @else
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 text-[10.5px] font-bold">Belum Absen</span>
                            @endif
                        </div>

                        @if (!empty($d->status_kehadiran))
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $d->nama_jadwal ?: 'Non-Shift' }}</p>
                                    <p class="text-[11px] font-mono-num text-slate-500 dark:text-slate-400">{{ $jam_mulai_jadwal }} - {{ $jam_selesai_jadwal }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-xs text-slate-400 italic flex items-center gap-1.5">
                                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                                <span>Menunggu Presensi</span>
                            </div>
                        @endif
                    </div>

                    <!-- Section 3: Jam Masuk & Pulang -->
                    <div class="py-3.5 px-2 lg:w-72 shrink-0 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 grid grid-cols-2 divide-x divide-slate-200 dark:divide-slate-800 items-center">
                        <div class="text-center px-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Masuk</p>
                            @if ($jam_in)
                                <button type="button" class="btnShowpresensi_in text-base font-black font-mono-num text-emerald-600 hover:underline cursor-pointer tracking-tight"
                                    id="{{ $d->id }}" status="in">{{ $jam_in }}</button>
                            @else
                                <span class="text-base font-bold font-mono-num text-slate-300 dark:text-slate-600">--:--:--</span>
                            @endif
                        </div>
                        <div class="text-center px-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Pulang</p>
                            @if ($jam_out)
                                <button type="button" class="btnShowpresensi_out text-base font-black font-mono-num text-rose-600 hover:underline cursor-pointer tracking-tight"
                                    id="{{ $d->id }}" status="out">{{ $jam_out }}</button>
                            @else
                                <span class="text-base font-bold font-mono-num text-slate-300 dark:text-slate-600">--:--:--</span>
                            @endif
                        </div>
                    </div>

                    <!-- Section 4: Keterlambatan & Durasi -->
                    <div class="py-3.5 px-2 lg:w-72 shrink-0 border-b lg:border-b-0 lg:border-r border-slate-100 dark:border-slate-800 grid grid-cols-2 divide-x divide-slate-200 dark:divide-slate-800 items-center">
                        <div class="px-3 min-w-0">
                            @if ($jam_in)
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Keterlambatan</p>
                                @if (!empty($terlambat))
                                    <p class="text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1 mt-0.5">
                                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span class="truncate">{{ $terlambat['keterangan_terlambat'] }}</span>
                                    </p>
                                    @php
                                        $denda = presensiHitungDenda(
                                            $terlambat['jamterlambat'],
                                            $terlambat['menitterlambat'],
                                            $d->kode_izin_terlambat,
                                            $d->kode_dept,
                                            $d->kode_jabatan,
                                            $d->tanggal,
                                            $terlambat['diffterlambat']
                                        );
                                    @endphp
                                    @if (!empty($denda['denda']))
                                        <p class="text-[11px] font-mono-num font-bold text-rose-600 dark:text-rose-400">Rp {{ formatAngka($denda['denda']) }}</p>
                                    @endif
                                @else
                                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1 mt-0.5">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 shrink-0"></i>
                                        <span>Tepat Waktu</span>
                                    </p>
                                @endif
                            @else
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Keterangan</p>
                                <p class="text-xs font-medium text-slate-400 mt-0.5">-</p>
                            @endif
                        </div>

                        <div class="px-3 min-w-0">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Durasi Kerja</p>
                            @if ($d->status_kehadiran == 'h' && $jam_in && $jam_out)
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-100 flex items-center gap-1 mt-0.5">
                                    <i data-lucide="hourglass" class="w-3.5 h-3.5 text-[#284c9a] dark:text-blue-400 shrink-0"></i>
                                    <span class="font-mono-num">{{ $d->total_jam }} Jam</span>
                                </p>
                            @else
                                <p class="text-xs font-medium text-slate-400 mt-0.5">-</p>
                            @endif
                        </div>
                    </div>

                    <!-- Section 5: Actions -->
                    <div class="p-3 bg-slate-50/70 dark:bg-slate-800/40 flex flex-row lg:w-32 shrink-0 items-center justify-center gap-1.5">
                        <button type="button" nik="{{ $d->nik }}" tanggal="{{ $tanggal }}"
                            class="btnKoreksi w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:hover:bg-emerald-900/60 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                            title="Koreksi Presensi">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                        </button>
                        <button type="button" pin="{{ $d->pin }}" tanggal="{{ $tanggal }}" kode_jadwal="{{ $d->kode_jadwal }}"
                            class="btngetDatamesin w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#284c9a] dark:bg-blue-950/50 dark:hover:bg-blue-900/60 dark:text-blue-400 flex items-center justify-center transition cursor-pointer"
                            title="Get Data Mesin">
                            <i data-lucide="monitor" class="w-4 h-4"></i>
                        </button>
                        @hasanyrole(['super admin', 'spv presensi'])
                            <form action="{{ route('presensi.delete', Crypt::encrypt($d->id)) }}" method="POST" class="inline-flex m-0 p-0 deleteform">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="delete-confirm w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/50 dark:hover:bg-rose-900/60 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                    title="Hapus Presensi">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @endhasanyrole
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center">
                <div class="flex flex-col items-center justify-center gap-2 text-slate-400">
                    <i data-lucide="calendar-off" class="w-10 h-10 text-slate-300 dark:text-slate-600"></i>
                    <h5 class="text-sm font-bold text-slate-700 dark:text-slate-200">Data Tidak Ditemukan</h5>
                    <p class="text-xs text-slate-400">Belum ada data presensi untuk tanggal atau filter yang dipilih.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    @if ($karyawan->hasPages())
        <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 py-3 shadow-2xs">
            {{ $karyawan->links('v2.components.pagination') }}
        </div>
    @endif
</div>

<!-- ================= MODAL PRESENSI (STATIC BACKDROP) ================= -->
<div id="modalPresensi" class="fixed inset-0 z-50 hidden">
    <div id="modalPresensiBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
        <div id="modalPresensiDialog"
            class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 transform transition-all duration-150 my-auto">
            <!-- Modal Header Bersih -->
            <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800 rounded-t-xl">
                <div class="flex items-center gap-2">
                    <i data-lucide="calendar-check" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h5 id="modalPresensiTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100">Monitoring Presensi</h5>
                </div>
                <button type="button" id="btnCloseModalPresensi"
                    class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <!-- Modal Body -->
            <div id="loadmodalPresensi" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}?v=1.0.1" />
<script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}?v=1.0.1"></script>
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        const $selectCabang = $('.select2Kodecabangsearch');
        if ($selectCabang.length) {
            $selectCabang.select2({ placeholder: 'Semua Cabang', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });
        }
        $('.select2Kodedeptsearch').select2({ placeholder: 'Semua Departemen', allowClear: true, dropdownCssClass: 'select2-dropdown-v2' });

        const $modal = $('#modalPresensi');
        const $dialog = $('#modalPresensiDialog');
        const $backdrop = $('#modalPresensiBackdrop');
        const $title = $('#modalPresensiTitle');
        const $content = $('#loadmodalPresensi');

        function openModal(sizeClass) {
            $dialog.removeClass('max-w-2xl max-w-4xl max-w-5xl').addClass(sizeClass || 'max-w-4xl');
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

        $(document).on('click', '.btnShowpresensi_in, .btnShowpresensi_out', function(e) {
            e.preventDefault();
            const id = $(this).attr('id');
            const status = $(this).attr('status');
            $title.text(status === 'in' ? 'Data Presensi Masuk' : 'Data Presensi Pulang');
            openModal('max-w-4xl');
            showLoading();
            $content.load(`/presensi/${id}/${status}/show`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });
    });
</script>
@endpush
