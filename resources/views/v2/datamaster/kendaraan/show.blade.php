@extends('layouts.app')
@section('titlepage', 'Detail Kendaraan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('kendaraan.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">Kendaraan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail {{ $kendaraan->no_polisi }}</span>
    </div>
@endsection

@section('content')
<div class="space-y-4 max-w-4xl">

    <!-- TOP HEADER PROFILE CARD -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <!-- Banner Strip -->
        <div class="h-24 bg-gradient-to-r from-[#284c9a] to-[#1e3a78] relative overflow-hidden">
            <div class="absolute -right-6 -bottom-6 opacity-10">
                <i data-lucide="truck" class="w-36 h-36 text-white stroke-[1]"></i>
            </div>
        </div>

        <div class="px-5 pb-5 pt-3">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 -mt-10 mb-4">
                <div class="flex items-end gap-3.5">
                    <div class="w-16 h-16 rounded-xl bg-white dark:bg-slate-800 shadow-md border-2 border-white dark:border-slate-700 flex items-center justify-center text-brand-600 dark:text-brand-400 shrink-0">
                        <i data-lucide="truck" class="w-8 h-8"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="text-lg font-black text-slate-900 dark:text-white tracking-wide font-mono-num">
                                {{ textUppercase($kendaraan->no_polisi) }}
                            </h3>
                            @if ($kendaraan->status_aktif_kendaraan == 1)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">
                            {{ $kendaraan->merek ?? '-' }} {{ $kendaraan->tipe_kendaraan ? '• ' . $kendaraan->tipe_kendaraan : '' }} {{ $kendaraan->tipe ? '(' . $kendaraan->tipe . ')' : '' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('kendaraan.index') }}"
                        class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                        <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                        <span>Kembali</span>
                    </a>
                </div>
            </div>

            <!-- Metric Strip In Container -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Kapasitas</span>
                    <span class="text-xs font-black text-slate-800 dark:text-slate-100 font-mono-num">{{ formatRupiah($kendaraan->kapasitas) }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Cabang</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $kendaraan->nama_cabang ?? $kendaraan->kode_cabang }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Tahun</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->tahun_pembuatan ?? '-' }}</span>
                </div>
                <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                    <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Atas Nama</span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 truncate block">{{ $kendaraan->atas_nama ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SPECIFICATIONS & LEGAL DOCUMENTS -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Spesifikasi Kendaraan -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-[#284c9a] text-white flex items-center gap-2">
                <i data-lucide="cpu" class="w-4 h-4 text-blue-200"></i>
                <h4 class="text-xs font-bold uppercase tracking-wider">Spesifikasi Fisik & Mesin</h4>
            </div>
            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Kode Kendaraan</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->kode_kendaraan }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Merk / Brand</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $kendaraan->merek ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Tipe Kendaraan</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $kendaraan->tipe_kendaraan ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Sub Varian</span>
                    <span class="font-semibold text-slate-800 dark:text-slate-100">{{ $kendaraan->tipe ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Nomor Rangka</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->no_rangka ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Nomor Mesin</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->no_mesin ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Alamat Pemilik STNK</span>
                    <span class="font-medium text-slate-700 dark:text-slate-300 text-right max-w-xs">{{ $kendaraan->alamat ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Dokumen & Masa Berlaku Pajak -->
        <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
            <div class="px-4 py-3 bg-[#1e3a78] text-white flex items-center gap-2">
                <i data-lucide="file-check" class="w-4 h-4 text-blue-200"></i>
                <h4 class="text-xs font-bold uppercase tracking-wider">Dokumen & Masa Berlaku Pajak</h4>
            </div>
            <div class="p-4 divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Nomor STNK</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->no_stnk ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">Nomor Uji Berkala</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->no_uji ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between">
                    <span class="text-slate-400 font-medium">SIPA / Izin Trayek</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">{{ $kendaraan->sipa ?? '-' }}</span>
                </div>
                <div class="py-2 flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Jatuh Tempo Pajak 1 Th</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">
                        {{ !empty($kendaraan->jatuhtempo_pajak_satutahun) ? date('d-m-Y', strtotime($kendaraan->jatuhtempo_pajak_satutahun)) : '-' }}
                    </span>
                </div>
                <div class="py-2 flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Jatuh Tempo Pajak 5 Th</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">
                        {{ !empty($kendaraan->jatuhtempo_pajak_limatahun) ? date('d-m-Y', strtotime($kendaraan->jatuhtempo_pajak_limatahun)) : '-' }}
                    </span>
                </div>
                <div class="py-2 flex justify-between items-center">
                    <span class="text-slate-400 font-medium">Jatuh Tempo KIR</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100 font-mono-num">
                        {{ !empty($kendaraan->jatuhtempo_kir) ? date('d-m-Y', strtotime($kendaraan->jatuhtempo_kir)) : '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection