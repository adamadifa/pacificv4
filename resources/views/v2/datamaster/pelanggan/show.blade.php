@extends('layouts.app')
@section('titlepage', 'Detail Pelanggan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('pelanggan.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Pelanggan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail {{ textCamelCase($pelanggan->nama_pelanggan) }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 w-full">

    <!-- PROFILE HEADER CARD (Compact & Modern) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs p-4 sm:p-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <!-- Outlet Identity & Avatar -->
            <div class="flex items-center gap-3.5">
                <!-- Avatar Outlet -->
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                    @if (!empty($pelanggan->foto) && Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                        <img src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="{{ $pelanggan->nama_pelanggan }}"
                            class="w-full h-full object-cover">
                    @else
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <i data-lucide="store" class="w-7 h-7 text-brand-600 dark:text-brand-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Detail Info -->
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-tight">
                            {{ textCamelCase($pelanggan->nama_pelanggan) }}
                        </h2>

                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold font-mono-num bg-brand-50 dark:bg-brand-950/60 text-brand-700 dark:text-brand-300 border border-brand-200/60 dark:border-brand-800/60">
                            {{ $pelanggan->kode_pelanggan }}
                        </span>

                        @if ($pelanggan->status_aktif_pelanggan === '1' || $pelanggan->status_aktif_pelanggan == 1)
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
                    </div>

                    <!-- Metadata List -->
                    <div class="flex items-center gap-3 sm:gap-4 flex-wrap text-xs text-slate-500 dark:text-slate-400 font-medium mt-1">
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($pelanggan->nama_cabang) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($pelanggan->nama_salesman) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ textCamelCase($pelanggan->nama_wilayah) }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>Kunjungan: <strong class="text-slate-700 dark:text-slate-200">{{ textCamelCase($pelanggan->hari) }}</strong></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0 self-start md:self-center">
                <a href="{{ route('pelanggan.index') }}"
                    class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-2xs">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali</span>
                </a>

                @can('pelanggan.edit')
                    <button type="button" id="btnEditThisPelanggan"
                        data-kode="{{ Crypt::encrypt($pelanggan->kode_pelanggan) }}"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 text-amber-700 dark:text-amber-300 hover:bg-amber-100 dark:hover:bg-amber-900/60 text-xs font-bold transition shadow-2xs cursor-pointer">
                        <i data-lucide="edit-3" class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400"></i>
                        <span>Edit Pelanggan</span>
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
            <!-- 1. Limit Piutang -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Limit Piutang</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ formatRupiah($pelanggan->limit_pelanggan) }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Plafon kredit maksimal</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 2. Lama Jatuh Tempo (LJT) -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Jatuh Tempo (LJT)</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ $pelanggan->ljt ?? 0 }} <span class="text-xs font-normal text-white/80">Hari</span>
                    </span>
                    <span class="text-[10px] text-white/55 block">Masa tenggang pembayaran</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="calendar-clock" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 3. Omset Toko -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Omset Rata-Rata</span>
                    <span class="text-base sm:text-lg font-black text-white font-mono-num block leading-tight">
                        {{ formatRupiah($pelanggan->omset_toko) }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Kapasitas per bulan</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                </div>
            </div>

            <!-- 4. Cara Pembayaran -->
            <div class="p-3.5 sm:p-4 flex items-center justify-between group">
                <div class="space-y-0.5">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-white/75 block">Cara Pembayaran</span>
                    <span class="text-xs sm:text-sm font-black text-white block leading-tight truncate max-w-[150px]">
                        {{ !empty($pelanggan->cara_pembayaran) ? ($cara_pembayaran[$pelanggan->cara_pembayaran] ?? $pelanggan->cara_pembayaran) : '-' }}
                    </span>
                    <span class="text-[10px] text-white/55 block">Metode pelunasan faktur</span>
                </div>
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center text-white shrink-0 group-hover:scale-105 transition">
                    <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 items-start">

        <!-- LEFT COLUMN: PROFIL & DATA MASTER OUTLET (5 COLS) -->
        <div class="lg:col-span-5 space-y-3">

            <!-- 1. IDENTITAS & DOMISILI -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Identitas & Domisili</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Kode Pelanggan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $pelanggan->kode_pelanggan }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Nama Pelanggan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textCamelCase($pelanggan->nama_pelanggan) }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">NIK</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">{{ $pelanggan->nik ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">No. Kartu Keluarga</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">{{ $pelanggan->no_kk ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Tanggal Lahir</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">
                            {{ !empty($pelanggan->tanggal_lahir) ? DateToIndo($pelanggan->tanggal_lahir) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">No. Handphone</span>
                        @if (!empty($pelanggan->no_hp_pelanggan) && $pelanggan->no_hp_pelanggan !== 'NA')
                            <a href="tel:{{ $pelanggan->no_hp_pelanggan }}" class="font-bold text-brand-600 dark:text-brand-400 hover:underline flex items-center gap-1 font-mono-num">
                                <i data-lucide="phone-call" class="w-3 h-3"></i>
                                <span>{{ $pelanggan->no_hp_pelanggan }}</span>
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>
                    <div class="py-2 flex justify-between items-start gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Alamat Domisili</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 text-right">{{ textCamelCase($pelanggan->alamat_pelanggan) ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-start gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Alamat Toko</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300 text-right">{{ textCamelCase($pelanggan->alamat_toko) ?? '-' }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Koordinat GPS</span>
                        @if (!empty($pelanggan->latitude) && !empty($pelanggan->longitude))
                            <a href="https://www.google.com/maps?q={{ $pelanggan->latitude }},{{ $pelanggan->longitude }}"
                                target="_blank"
                                class="inline-flex items-center gap-1 font-bold text-brand-600 dark:text-brand-400 hover:underline font-mono-num text-[11px]">
                                <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                                <span>{{ $pelanggan->latitude }}, {{ $pelanggan->longitude }}</span>
                                <i data-lucide="external-link" class="w-2.5 h-2.5"></i>
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 2. WILAYAH & SALESMAN -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <i data-lucide="map-pinned" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Wilayah & Salesman</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Cabang</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textCamelCase($pelanggan->nama_cabang) }} ({{ $pelanggan->kode_cabang }})</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Salesman Pembina</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 text-right">{{ textCamelCase($pelanggan->nama_salesman) }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Wilayah / Rute</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 text-right">{{ textCamelCase($pelanggan->nama_wilayah) }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Jadwal Kunjungan</span>
                        <span class="inline-flex items-center gap-1 font-bold text-brand-600 dark:text-brand-400">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <span>{{ textCamelCase($pelanggan->hari) }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 3. PARAMETER KEUANGAN & KREDIT -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Parameter Keuangan & Kredit</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Limit Pelanggan</span>
                        <span class="font-black text-brand-600 dark:text-brand-400 font-mono-num">{{ formatRupiah($pelanggan->limit_pelanggan) }}</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Lama Jatuh Tempo (LJT)</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $pelanggan->ljt ?? 0 }} Hari</span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Cara Pembayaran</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ !empty($pelanggan->cara_pembayaran) ? ($cara_pembayaran[$pelanggan->cara_pembayaran] ?? $pelanggan->cara_pembayaran) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Jaminan</span>
                        @if ($pelanggan->jaminan == 1)
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                                <i data-lucide="check" class="w-3 h-3"></i>
                                <span>Ada Jaminan</span>
                            </span>
                        @else
                            <span class="text-slate-400">Tidak Ada</span>
                        @endif
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Omset Rata-Rata</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ formatRupiah($pelanggan->omset_toko) }}</span>
                    </div>
                    @if (!empty($pelanggan->no_rekening) || !empty($pelanggan->bank))
                        <div class="py-2 flex justify-between items-center gap-2">
                            <span class="text-slate-400 font-medium shrink-0">Rekening Bank</span>
                            <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num text-right">
                                {{ $pelanggan->bank }} {{ $pelanggan->no_rekening }}
                                @if (!empty($pelanggan->pemilik_rekening))
                                    <span class="block text-[10px] text-slate-400">a.n {{ $pelanggan->pemilik_rekening }}</span>
                                @endif
                            </span>
                        </div>
                    @endif
                    @if (!empty($pelanggan->metode_bayar))
                        <div class="py-2 flex justify-between items-center gap-2">
                            <span class="text-slate-400 font-medium shrink-0">Metode Bayar Program</span>
                            <span class="font-bold text-brand-600 dark:text-brand-400">
                                @if ($pelanggan->metode_bayar == 'TN')
                                    Tunai
                                @elseif ($pelanggan->metode_bayar == 'TF')
                                    Transfer
                                @elseif ($pelanggan->metode_bayar == 'VC')
                                    Voucher
                                @else
                                    {{ $pelanggan->metode_bayar }}
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- 4. PROFIL USAHA & OUTLET -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center shrink-0">
                        <i data-lucide="store" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Profil Usaha & Outlet</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800/70 text-xs">
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Status Kepemilikan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ !empty($pelanggan->kepemilikan) ? ($kepemilikan[$pelanggan->kepemilikan] ?? $pelanggan->kepemilikan) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Lama Berjualan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">
                            {{ !empty($pelanggan->lama_berjualan) ? ($lama_berjualan[$pelanggan->lama_berjualan] ?? $pelanggan->lama_berjualan) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Status Outlet</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ !empty($pelanggan->status_outlet) ? ($status_outlet[$pelanggan->status_outlet] ?? $pelanggan->status_outlet) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Tipe Outlet</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">
                            {{ !empty($pelanggan->type_outlet) ? ($type_outlet[$pelanggan->type_outlet] ?? $pelanggan->type_outlet) : '-' }}
                        </span>
                    </div>
                    <div class="py-2 flex justify-between items-center gap-2">
                        <span class="text-slate-400 font-medium shrink-0">Lama Langganan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 font-mono-num">
                            {{ !empty($pelanggan->lama_langganan) ? ($lama_langganan[$pelanggan->lama_langganan] ?? $pelanggan->lama_langganan) : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- 5. DOKUMENTASI FOTO (TOKO & OWNER) -->
            <div class="rounded-lg bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 p-3.5 shadow-2xs">
                <div class="flex items-center gap-2 pb-2.5 mb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-6 h-6 rounded-md bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center shrink-0">
                        <i data-lucide="camera" class="w-3.5 h-3.5"></i>
                    </div>
                    <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200">Dokumentasi Foto Toko & Owner</h3>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <!-- Foto Toko -->
                    <div class="space-y-1.5">
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block">Foto Toko / Outlet</span>
                        <div class="h-28 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 overflow-hidden flex items-center justify-center group relative">
                            @if (!empty($pelanggan->foto) && Storage::disk('public')->exists('/pelanggan/' . $pelanggan->foto))
                                <img src="{{ getfotoPelanggan($pelanggan->foto) }}" alt="Foto Toko" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <a href="{{ getfotoPelanggan($pelanggan->foto) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold gap-1 transition">
                                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                    <span>Lihat</span>
                                </a>
                            @else
                                <div class="text-center text-slate-400 p-2">
                                    <i data-lucide="image-off" class="w-6 h-6 mx-auto mb-1 opacity-50"></i>
                                    <span class="text-[10px] block">Belum ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Foto Owner -->
                    <div class="space-y-1.5">
                        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 block">Foto Owner / Pemilik</span>
                        <div class="h-28 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 overflow-hidden flex items-center justify-center group relative">
                            @if (!empty($pelanggan->foto_owner) && Storage::disk('public')->exists('/pelanggan/owner/' . $pelanggan->foto_owner))
                                <img src="{{ getfotoPelangganowner($pelanggan->foto_owner) }}" alt="Foto Owner" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <a href="{{ getfotoPelangganowner($pelanggan->foto_owner) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs font-semibold gap-1 transition">
                                    <i data-lucide="zoom-in" class="w-4 h-4"></i>
                                    <span>Lihat</span>
                                </a>
                            @else
                                <div class="text-center text-slate-400 p-2">
                                    <i data-lucide="user-x" class="w-6 h-6 mx-auto mb-1 opacity-50"></i>
                                    <span class="text-[10px] block">Belum ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: RIWAYAT TRANSAKSI PENJUALAN OUTLET (7 COLS) -->
        <div class="lg:col-span-7">
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/90 dark:border-slate-800 shadow-2xs overflow-hidden">

                <!-- Header Box Riwayat Penjualan -->
                <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-brand-50 dark:bg-brand-950/60 text-brand-600 dark:text-brand-400 flex items-center justify-center shrink-0">
                            <i data-lucide="receipt" class="w-4 h-4"></i>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Riwayat Transaksi Penjualan</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5">Daftar transaksi faktur penjualan outlet / pelanggan ini.</p>
                        </div>
                    </div>
                </div>

                <!-- FILTER FORM (Compact No-Label Style) -->
                <div class="p-3 bg-slate-50/60 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
                    <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                        <!-- Dari Tanggal -->
                        <div class="sm:col-span-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="dari" value="{{ request('dari') }}" readonly
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                                placeholder="Dari Tanggal">
                        </div>

                        <!-- Sampai Tanggal -->
                        <div class="sm:col-span-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="sampai" value="{{ request('sampai') }}" readonly
                                class="flatpickr-date w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer"
                                placeholder="Sampai Tanggal">
                        </div>

                        <!-- No Faktur Search -->
                        <div class="sm:col-span-4 relative">
                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                                <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                            </div>
                            <input type="text" name="no_faktur_search" value="{{ request('no_faktur_search') }}"
                                class="w-full h-[34px] pl-8 pr-2.5 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition placeholder:text-slate-400"
                                placeholder="Cari No. Faktur...">
                        </div>

                        <!-- Buttons Row -->
                        <div class="sm:col-span-12 flex items-center justify-end gap-1.5 pt-1">
                            @if (request('dari') || request('sampai') || request('no_faktur_search'))
                                <a href="{{ url()->current() }}"
                                    class="inline-flex items-center justify-center gap-1 h-[32px] px-3 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-semibold transition shadow-2xs">
                                    <i data-lucide="rotate-ccw" class="w-3 h-3 text-slate-400"></i>
                                    <span>Reset</span>
                                </a>
                            @endif

                            <button type="submit"
                                class="inline-flex items-center justify-center gap-1.5 h-[32px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition shadow-xs cursor-pointer">
                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                <span>Cari Faktur</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TABLE TRANSAKSI PENJUALAN (Kompak ~36px row height) -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead class="bg-[#284c9a] text-white/90">
                            <tr>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Tanggal</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">Salesman</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Total Netto</th>
                                <th class="py-2 px-2.5 text-[11px] font-bold uppercase tracking-wider text-center">JT</th>
                                <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-center">Status</th>
                                <th class="py-2 px-2.5 text-[11px] font-bold uppercase tracking-wider text-center w-10">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                            @forelse ($penjualan as $d)
                                @php
                                    $total_netto = $d->total_bruto - $d->total_retur - $d->potongan - $d->potongan_istimewa - $d->penyesuaian + $d->ppn;

                                    if ($d->status_batal == '1') {
                                        $row_style = 'bg-rose-50/70 dark:bg-rose-950/30 text-rose-800 dark:text-rose-200';
                                    } elseif (substr($d->no_faktur, 3, 2) == 'PR') {
                                        $row_style = 'bg-sky-50/70 dark:bg-sky-950/30 text-sky-800 dark:text-sky-200';
                                    } else {
                                        $row_style = 'hover:bg-slate-50/80 dark:hover:bg-slate-800/50 text-slate-700 dark:text-slate-200';
                                    }
                                @endphp
                                <tr class="transition {{ $row_style }}">
                                    <!-- No Faktur -->
                                    <td class="py-1.5 px-3 font-bold font-mono-num">
                                        <div class="flex items-center gap-1.5">
                                            @if ($d->status_batal == '1')
                                                <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-200 dark:bg-rose-900/80 text-rose-800 dark:text-rose-200">BATAL</span>
                                            @endif
                                            <span>{{ $d->no_faktur }}</span>
                                        </div>
                                    </td>

                                    <!-- Tanggal -->
                                    <td class="py-1.5 px-3 font-semibold font-mono-num text-slate-500 dark:text-slate-400">
                                        {{ date('d-m-Y', strtotime($d->tanggal)) }}
                                    </td>

                                    <!-- Salesman -->
                                    <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100 max-w-[140px] truncate" title="{{ $d->nama_salesman }}">
                                        {{ textCamelCase($d->nama_salesman) }}
                                    </td>

                                    <!-- Total Netto -->
                                    <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                                        {{ formatAngka($total_netto) }}
                                    </td>

                                    <!-- Jenis Transaksi (JT) -->
                                    <td class="py-1.5 px-2.5 text-center">
                                        @if ($d->jenis_transaksi == 'T')
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                                TUNAI
                                            </span>
                                        @elseif($d->jenis_transaksi == 'K')
                                            <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/60 dark:border-amber-800/60">
                                                KREDIT
                                            </span>
                                        @else
                                            <span class="text-slate-400">{{ $d->jenis_transaksi }}</span>
                                        @endif
                                    </td>

                                    <!-- Status Pembayaran -->
                                    <td class="py-1.5 px-3 text-center">
                                        @if ($d->status_batal == '1')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-100 dark:bg-rose-950/80 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                Batal
                                            </span>
                                        @elseif ($d->total_bayar >= $total_netto)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/60 dark:border-emerald-800/60">
                                                <i data-lucide="check" class="w-3 h-3 text-emerald-500"></i>
                                                <span>Lunas</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-rose-800/60">
                                                <i data-lucide="clock" class="w-3 h-3 text-rose-500"></i>
                                                <span>Belum Lunas</span>
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="py-1.5 px-2.5 text-center">
                                        @can('penjualan.show')
                                            <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}"
                                                class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/60 transition inline-flex items-center justify-center cursor-pointer shadow-2xs"
                                                title="Lihat Detail Faktur">
                                                <i data-lucide="file-text" class="w-3 h-3"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                                <i data-lucide="receipt-text" class="w-5 h-5"></i>
                                            </div>
                                            <span class="text-xs font-semibold">Belum ada riwayat transaksi penjualan untuk pelanggan ini</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- CURSOR PAGINATION -->
                @if ($penjualan->hasPages())
                    <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-medium">Navigasi Riwayat Transaksi</span>
                        <div class="flex items-center gap-1.5">
                            @if ($penjualan->previousPageUrl())
                                <a href="{{ $penjualan->previousPageUrl() }}"
                                    class="inline-flex items-center gap-1 h-7 px-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition shadow-2xs">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 h-7 px-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800/60 bg-slate-100/50 dark:bg-slate-800/30 text-slate-400 cursor-not-allowed text-xs font-medium">
                                    <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i>
                                    <span>Sebelumnya</span>
                                </span>
                            @endif

                            @if ($penjualan->nextPageUrl())
                                <a href="{{ $penjualan->nextPageUrl() }}"
                                    class="inline-flex items-center gap-1 h-7 px-2.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-semibold transition shadow-2xs">
                                    <span>Berikutnya</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 h-7 px-2.5 rounded-lg border border-slate-200/60 dark:border-slate-800/60 bg-slate-100/50 dark:bg-slate-800/30 text-slate-400 cursor-not-allowed text-xs font-medium">
                                    <span>Berikutnya</span>
                                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

</div>

<!-- MODAL EDIT PELANGGAN (Re-use V2 Edit Form) -->
<div id="modalEditPelanggan" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
    <div class="relative w-full max-w-4xl bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xl overflow-hidden transition-all duration-200">
        <!-- Modal Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
            <div class="flex items-center gap-2">
                <i data-lucide="edit-3" class="w-4 h-4 text-brand-600 dark:text-brand-400"></i>
                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wide">
                    Form Edit Data Pelanggan
                </h4>
            </div>
            <button type="button" class="btnCloseModalPelanggan w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Modal Body (AJAX Container) -->
        <div id="modalContentEditPelanggan" class="p-4 max-h-[85vh] overflow-y-auto">
            <!-- Dynamic content loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Flatpickr for Filter
        $(".flatpickr-date").flatpickr({
            dateFormat: "Y-m-d"
        });

        // Modal Helpers
        const $modalEdit = $("#modalEditPelanggan");

        function openModal($modal, $contentContainer, loadUrl) {
            $modal.removeClass("hidden").addClass("flex");
            $('body').addClass('overflow-hidden');
            $contentContainer.html(`
                <div class="p-8 flex flex-col items-center justify-center gap-3 text-slate-500">
                    <svg class="animate-spin h-6 w-6 text-brand-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold">Memuat formulir...</span>
                </div>
            `);

            $.get(loadUrl, function(response) {
                $contentContainer.html(response);
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }).fail(function() {
                $contentContainer.html(`
                    <div class="p-6 text-center text-rose-500 text-xs font-semibold">
                        Gagal memuat data. Silakan coba kembali.
                    </div>
                `);
            });
        }

        function closeModal($modal) {
            $modal.addClass("hidden").removeClass("flex");
            $('body').removeClass('overflow-hidden');
            $("#modalContentEditPelanggan").html("");
        }

        // Close on X icon button click
        $(".btnCloseModalPelanggan").on("click", function() {
            closeModal($modalEdit);
        });

        // Static Backdrop: Dilarang close saat klik backdrop / luar modal
        $modalEdit.on("click", function(e) {
            if (e.target === this) {
                const $modalBox = $(this).find("> div");
                $modalBox.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $modalBox.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        // Cegah tombol keyboard Escape menutup modal secara tidak sengaja
        $(document).on("keydown", function(e) {
            if (e.key === "Escape" || e.keyCode === 27) {
                if (!$modalEdit.hasClass("hidden")) {
                    e.preventDefault();
                    const $modalBox = $modalEdit.find("> div");
                    $modalBox.addClass("scale-[1.015] shadow-2xl");
                    setTimeout(function() {
                        $modalBox.removeClass("scale-[1.015] shadow-2xl");
                    }, 150);
                }
            }
        });

        $(document).on("click", ".btnCancelModal", function(e) {
            e.preventDefault();
            closeModal($modalEdit);
        });

        // Trigger Edit from Show Page
        $("#btnEditThisPelanggan").on("click", function(e) {
            e.preventDefault();
            const kode = $(this).data("kode");
            openModal($modalEdit, $("#modalContentEditPelanggan"), '/pelanggan/' + kode + '/edit');
        });
    });
</script>
@endpush
