@extends('layouts.app')
@section('titlepage', 'Detail Karyawan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('karyawan.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Karyawan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail {{ textCamelCase($karyawan->nama_karyawan) }}</span>
    </div>
@endsection

@section('content')
@php
    $tgl_masuk = !empty($karyawan->tanggal_masuk) ? \Carbon\Carbon::parse($karyawan->tanggal_masuk) : null;
    $tgl_akhir = !empty($karyawan->tanggal_nonaktif) ? \Carbon\Carbon::parse($karyawan->tanggal_nonaktif) : \Carbon\Carbon::now();
    if ($tgl_masuk) {
        $diff = $tgl_masuk->diff($tgl_akhir);
        $masa_kerja = $diff->y . ' Th ' . $diff->m . ' Bln';
    } else {
        $masa_kerja = '-';
    }
@endphp

<div class="space-y-4 w-full">

    <!-- PROFILE HEADER CARD (Compact & Modern - Anti Banner Kosong) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs p-4 sm:p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            
            <!-- Avatar & Profile Info -->
            <div class="flex items-center gap-3.5">
                <!-- Avatar Karyawan -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                    @if (!empty($karyawan->foto) && Storage::disk('public')->exists('/karyawan/' . $karyawan->getRawOriginal('foto')))
                        <img src="{{ getfotoKaryawan($karyawan->foto) }}" alt="{{ $karyawan->nama_karyawan }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <i data-lucide="user" class="w-7 h-7 text-brand-600 dark:text-brand-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Detail Identitas -->
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-tight">
                            {{ textCamelCase($karyawan->nama_karyawan) }}
                        </h2>

                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                            {{ $karyawan->nik }}
                        </span>

                        <!-- Status Aktif / Nonaktif -->
                        @if ($karyawan->status_aktif_karyawan === '1' || $karyawan->status_aktif_karyawan == 1)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span>Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <span>Nonaktif</span>
                            </span>
                        @endif

                        <!-- Status Hubungan Kerja (T / K / O) -->
                        @if ($karyawan->status_karyawan == 'T')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200/60 dark:border-indigo-800/60">
                                Tetap
                            </span>
                        @elseif ($karyawan->status_karyawan == 'K')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200/60 dark:border-amber-800/60">
                                Kontrak (PKWT)
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                Outsourcing
                            </span>
                        @endif
                    </div>

                    <!-- Metadata List -->
                    <div class="flex items-center gap-3 sm:gap-4 flex-wrap text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($karyawan->nama_cabang) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="layers" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($karyawan->nama_dept) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="briefcase" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($karyawan->nama_jabatan) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Masa Kerja: <strong class="text-slate-700 dark:text-slate-200 font-mono-num">{{ $masa_kerja }}</strong></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0 self-start md:self-center">
                <a href="{{ route('karyawan.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>

                <a href="{{ route('karyawan.dokumen', Crypt::encrypt($karyawan->nik)) }}"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                    <i data-lucide="folder-archive" class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400"></i>
                    <span>Dokumen</span>
                </a>

                @can('karyawan.edit')
                    <button type="button" id="btnEditThisKaryawan"
                        data-nik="{{ Crypt::encrypt($karyawan->nik) }}"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 text-amber-700 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/60 text-xs font-bold transition shadow-2xs cursor-pointer">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"></i>
                        <span>Edit Karyawan</span>
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- METRIC STRIP (Standar V2 Pacific Navy) -->
    <div class="relative overflow-hidden rounded-xl bg-[#284c9a] text-white shadow-xs hover:shadow-md transition">
        <!-- Ambient Ornament SVG -->
        <div class="absolute -right-8 -bottom-8 pointer-events-none text-white/[0.06]">
            <svg class="w-48 h-48" viewBox="0 0 200 200" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="100" cy="100" r="80" stroke-dasharray="8 6" />
                <circle cx="100" cy="100" r="50" />
                <circle cx="100" cy="100" r="20" />
            </svg>
        </div>
        <div class="absolute left-1/3 -top-10 w-44 h-44 bg-white/[0.04] rounded-full blur-2xl pointer-events-none"></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-white/15 relative z-10">
            <!-- 1. Masa Kerja -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Masa Kerja</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ $masa_kerja }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Lama pengabdian kerja</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="history" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 2. Tanggal Bergabung -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Tanggal Masuk</span>
                    <span class="text-xs sm:text-sm font-black text-white font-mono-num block leading-tight">
                        {{ !empty($karyawan->tanggal_masuk) ? DateToIndo($karyawan->tanggal_masuk) : '-' }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Mulai aktif bertugas</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="calendar-check" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 3. Status Ikatan Kerja -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Ikatan Kerja</span>
                    <span class="text-xs sm:text-sm font-black text-white block leading-tight">
                        {{ $karyawan->status_karyawan == 'T' ? 'Karyawan Tetap' : ($karyawan->status_karyawan == 'K' ? 'Kontrak (PKWT)' : 'Outsourcing') }}
                    </span>
                    <span class="text-[10px] text-white/55 block">{{ $karyawan->klasifikasi ?? 'Klasifikasi Operasional' }}</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="award" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 4. Perusahaan & Kantor Cabang -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Entitas Perusahaan</span>
                    <span class="text-xs sm:text-sm font-black text-white block leading-tight truncate max-w-[150px]">
                        {{ $karyawan->kode_perusahaan == 'MP' ? 'Makmur Permata' : 'Pacific' }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Cabang {{ textCamelCase($karyawan->nama_cabang) }}</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="building" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">

        <!-- LEFT COLUMN: PROFIL PRIBADI & KEPENDUDUKAN (5 COLS) -->
        <div class="lg:col-span-5 space-y-3">

            <!-- 1. IDENTITAS KEPENDUDUKAN -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Data Pribadi & Kependudukan</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">NIK</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $karyawan->nik }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">No. KTP</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $karyawan->no_ktp ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Nama Lengkap</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textCamelCase($karyawan->nama_karyawan) }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Tempat Lahir</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ textCamelCase($karyawan->tempat_lahir) ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Tanggal Lahir</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">
                            {{ !empty($karyawan->tanggal_lahir) ? DateToIndo($karyawan->tanggal_lahir) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Jenis Kelamin</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ $karyawan->jenis_kelamin == 'L' ? 'Laki - Laki' : ($karyawan->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">No. Handphone</span>
                        @if (!empty($karyawan->no_hp))
                            <a href="tel:{{ $karyawan->no_hp }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1 font-mono-num">
                                <i data-lucide="phone-call" class="w-3 h-3"></i>
                                <span>{{ $karyawan->no_hp }}</span>
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Status Pernikahan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $karyawan->keterangan_status_kawin ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Pendidikan Terakhir</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $karyawan->pendidikan_terakhir ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-start gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Alamat Tempat Tinggal</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 text-right">{{ textCamelCase($karyawan->alamat) ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. KEAMANAN & AKSES PRESENSI -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Presensi & Penguncian Lokasi</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Lock Radius Cabang</span>
                        @if ($karyawan->lock_location === '0' || $karyawan->lock_location == 0)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/50">
                                <i data-lucide="lock" class="w-3 h-3 text-emerald-600"></i>
                                <span>Terkunci di Cabang</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/50">
                                <i data-lucide="unlock" class="w-3 h-3 text-amber-600"></i>
                                <span>Bebas Lokasi (Unlocked)</span>
                            </span>
                        @endif
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Kode Jadwal Kerja</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $karyawan->kode_jadwal ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">PIN Presensi Karyawan</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ !empty($karyawan->pin) ? '••••••' : '-' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: PENEMPATAN, KONTRAK & MENU TERKAIT (7 COLS) -->
        <div class="lg:col-span-7 space-y-3">

            <!-- 1. STRUKTUR & PENEMPATAN KERJA -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Penempatan & Struktur Kepegawaian</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-xs divide-y sm:divide-y-0 divide-slate-100 dark:divide-slate-800">
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Perusahaan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ $karyawan->kode_perusahaan == 'MP' ? 'Makmur Permata' : 'Pacific' }}</span>
                    </div>
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Kantor Cabang</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ textCamelCase($karyawan->nama_cabang) }}</span>
                    </div>
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Departemen</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ textCamelCase($karyawan->nama_dept) }}</span>
                    </div>
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Jabatan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">{{ textCamelCase($karyawan->nama_jabatan) }}</span>
                    </div>
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Group Kerja</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ textCamelCase($karyawan->nama_group) }}</span>
                    </div>
                    <div class="py-1.5 flex justify-between items-center sm:border-b sm:border-slate-100 dark:sm:border-slate-800 pb-2">
                        <span class="text-slate-400 font-medium">Klasifikasi</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ textCamelCase($karyawan->klasifikasi) }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. STATUS HUBUNGAN KERJA & MASA KERJA -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="file-badge" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Riwayat Ikatan Kerja</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Tanggal Masuk</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">
                            {{ !empty($karyawan->tanggal_masuk) ? DateToIndo($karyawan->tanggal_masuk) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Masa Kerja Berjalan</span>
                        <span class="font-bold text-brand-600 dark:text-brand-400 font-mono-num">{{ $masa_kerja }}</span>
                    </div>

                    @if ($karyawan->status_aktif_karyawan === '0' || $karyawan->status_aktif_karyawan == 0)
                        <div class="py-2 flex justify-between items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-2 rounded-md">
                            <span class="text-rose-600 dark:text-rose-400 font-medium shrink-0">Tanggal Nonaktif</span>
                            <span class="font-bold text-rose-700 dark:text-rose-300 font-mono-num">
                                {{ !empty($karyawan->tanggal_nonaktif) ? DateToIndo($karyawan->tanggal_nonaktif) : '-' }}
                            </span>
                        </div>
                        <div class="py-2 flex justify-between items-center gap-2 bg-rose-50/50 dark:bg-rose-950/20 px-2 rounded-md">
                            <span class="text-rose-600 dark:text-rose-400 font-medium shrink-0">Tanggal Off Gaji</span>
                            <span class="font-bold text-rose-700 dark:text-rose-300 font-mono-num">
                                {{ !empty($karyawan->tanggal_off_gaji) ? DateToIndo($karyawan->tanggal_off_gaji) : '-' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 3. MENU PINTAS & DOKUMEN -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-purple-50 dark:purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <i data-lucide="layers" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Akses Modul HRD Terkait</h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <a href="{{ route('karyawan.dokumen', Crypt::encrypt($karyawan->nik)) }}"
                        class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 hover:border-brand-500/30 transition text-center group">
                        <div class="w-7 h-7 mx-auto rounded-md bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition">
                            <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 block">Dokumen</span>
                        <span class="text-[9px] text-slate-400 block">KTP, KK, Ijazah</span>
                    </a>

                    <a href="{{ route('kontrakkaryawan.index', ['nik' => $karyawan->nik]) ?? 'javascript:void(0)' }}"
                    <a href="{{ Route::has('kontrakkerja.index') ? route('kontrakkerja.index', ['nik' => $karyawan->nik]) : 'javascript:void(0)' }}"
                        class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 hover:border-brand-500/30 transition text-center group">
                        <div class="w-7 h-7 mx-auto rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition">
                            <i data-lucide="file-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 block">Kontrak</span>
                        <span class="text-[9px] text-slate-400 block">Histori PKWT</span>
                    </a>

                    <a href="{{ route('gaji.index', ['nik' => $karyawan->nik]) ?? 'javascript:void(0)' }}"
                    <a href="{{ Route::has('gaji.index') ? route('gaji.index', ['nik' => $karyawan->nik]) : 'javascript:void(0)' }}"
                        class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 hover:border-brand-500/30 transition text-center group">
                        <div class="w-7 h-7 mx-auto rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition">
                            <i data-lucide="coins" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 block">Gaji Pokok</span>
                        <span class="text-[9px] text-slate-400 block">Riwayat Upah</span>
                    </a>

                    <a href="{{ route('penilaiankaryawan.index', ['nik' => $karyawan->nik]) ?? 'javascript:void(0)' }}"
                    <a href="{{ Route::has('penilaiankaryawan.index') ? route('penilaiankaryawan.index', ['nik' => $karyawan->nik]) : 'javascript:void(0)' }}"
                        class="p-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 hover:border-brand-500/30 transition text-center group">
                        <div class="w-7 h-7 mx-auto rounded-md bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mb-1.5 group-hover:scale-110 transition">
                            <i data-lucide="clipboard-check" class="w-3.5 h-3.5"></i>
                        </div>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-200 block">Penilaian</span>
                        <span class="text-[9px] text-slate-400 block">Evaluasi Kerja</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- MODAL EDIT KARYAWAN -->
<div id="modalKaryawan" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-900/60 backdrop-blur-xs hidden">
    <div class="modal-dialog-content bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden transition-all duration-200 transform scale-95 opacity-0">
        <!-- Header -->
        <div class="px-5 py-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-800/50">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shadow-xs">
                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Data Karyawan</h3>
            </div>
            <button type="button" class="btn-close-modal text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Body -->
        <div id="loadModalKaryawan" class="p-5 overflow-y-auto flex-1 text-xs">
            <div class="flex items-center justify-center py-12">
                <div class="animate-spin rounded-full h-7 w-7 border-2 border-brand-500 border-t-transparent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        const $modal = $('#modalKaryawan');
        const $modalDialog = $modal.find('.modal-dialog-content');
        const $modalContent = $('#loadModalKaryawan');

        function openModal() {
            $modal.removeClass('hidden');
            setTimeout(() => {
                $modalDialog.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
            }, 20);
        }

        function closeModal() {
            $modalDialog.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => {
                $modal.addClass('hidden');
                $modalContent.html(`
                    <div class="flex items-center justify-center py-12">
                        <div class="animate-spin rounded-full h-7 w-7 border-2 border-brand-500 border-t-transparent"></div>
                    </div>
                `);
            }, 150);
        }

        // Close on Explicit Action Only
        $modal.find('.btn-close-modal').on('click', closeModal);
        $(document).on('click', '#modalKaryawan .btn-batal', closeModal);

        // Edit Button Handler
        $('#btnEditThisKaryawan').on('click', function(e) {
            e.preventDefault();
            const nik = $(this).attr('data-nik');
            openModal();
            $modalContent.load(`/karyawan/${nik}/edit`, function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });

        // Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endpush
