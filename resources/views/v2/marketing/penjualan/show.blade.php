@extends('layouts.app')
@section('titlepage', 'Detail Penjualan')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('penjualan.index') }}" class="text-slate-400 hover:text-brand-600 transition">Penjualan</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Detail Faktur</span>
    </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/leaflet/leaflet.css') }}" />
<style>
    #map {
        height: 200px;
        width: 100%;
        z-index: 1;
    }
    .leaflet-pane {
        z-index: 2 !important;
    }
    .leaflet-top,
    .leaflet-bottom {
        z-index: 5 !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 10px !important;
        font-family: inherit !important;
        font-size: 12px !important;
    }
    .custom-div-icon {
        background: transparent !important;
        border: none !important;
    }
</style>
@endpush

@section('content')
@php
    $total_netto = $penjualan->total_bruto - $penjualan->total_retur - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian + $penjualan->ppn;
@endphp

<div class="space-y-4 w-full">

    <!-- CUSTOMER PROFILE BANNER & TOP ACTION BAR -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
        
        <!-- Top Map Preview Banner (Height 200px) -->
        <div class="relative w-full">
            <div id="map" class="w-full h-[200px] bg-slate-100 dark:bg-slate-800 border-b border-slate-200/80 dark:border-slate-800 z-0"></div>

            <!-- GPS Status & Map Legend Pills (Top-Right of Map) -->
            <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 flex-wrap justify-end pointer-events-none">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs border border-slate-200/80 dark:border-slate-700 text-[11px] font-bold text-slate-700 dark:text-slate-200 shadow-xs pointer-events-auto">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#284c9a] ring-2 ring-blue-200 dark:ring-blue-900"></span> Lokasi Toko
                </span>

                @if ($checkin != null && !empty($checkin->latitude))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs border border-slate-200/80 dark:border-slate-700 text-[11px] font-bold text-slate-700 dark:text-slate-200 shadow-xs pointer-events-auto">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-600 ring-2 ring-rose-200 dark:ring-rose-900"></span> Titik Transaksi
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white/95 dark:bg-slate-900/95 backdrop-blur-xs border border-slate-200/80 dark:border-slate-700 text-[11px] font-bold text-slate-500 dark:text-slate-400 shadow-xs pointer-events-auto" title="Transaksi dibuat dari input manual web tanpa check-in SFA">
                        <i data-lucide="laptop" class="w-3.5 h-3.5"></i> Input Manual Web
                    </span>
                @endif
            </div>
        </div>

        <!-- Customer Identity & Action Bar -->
        <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-end justify-between gap-4 -mt-10 relative z-10">
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-3.5 text-center sm:text-left">
                <!-- Foto Pelanggan -->
                <div class="w-24 h-24 rounded-xl border-4 border-white dark:border-slate-900 overflow-hidden shadow-md bg-white dark:bg-slate-800 shrink-0">
                    @if (Storage::disk('public')->exists('/pelanggan/' . $penjualan->foto) && !empty($penjualan->foto))
                        <img src="{{ getfotoPelanggan($penjualan->foto) }}" alt="Foto Pelanggan" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50 dark:bg-slate-800">
                            <i data-lucide="user" class="w-8 h-8"></i>
                            <span class="text-[9px] mt-1 font-semibold">No Image</span>
                        </div>
                    @endif
                </div>

                <!-- Info Header -->
                <div class="space-y-1">
                    <div class="flex items-center justify-center sm:justify-start gap-2 flex-wrap">
                        <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white leading-tight">
                            {{ textCamelCase($penjualan->nama_pelanggan) }}
                        </h2>
                        @if ($penjualan->status_aktif_pelanggan === '1')
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span>Aktif</span>
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                <span>Nonaktif</span>
                            </span>
                        @endif
                    </div>

                    <!-- Meta Tags -->
                    <div class="flex items-center justify-center sm:justify-start gap-2 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ textCamelCase($penjualan->nama_cabang) }}</span>
                        </span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span class="font-bold text-[#284c9a] dark:text-blue-400">{{ textCamelCase($penjualan->nama_salesman) }}</span>
                        </span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="inline-flex items-center gap-1">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                            <span>{{ textCamelCase($penjualan->nama_wilayah) }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center sm:justify-end gap-2 flex-wrap">
                <a href="{{ route('penjualan.create') }}"
                    class="h-[34px] px-3.5 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                    <span>Input Penjualan Baru</span>
                </a>

                @can('penjualan.updatelockprint')
                    @if ($penjualan->lock_print == 0)
                        <a href="{{ route('penjualan.updatelockprint', Crypt::encrypt($penjualan->no_faktur)) }}"
                            class="h-[34px] px-3.5 rounded-lg border border-rose-200 dark:border-rose-900 bg-rose-50 hover:bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="printer-x" class="w-3.5 h-3.5"></i>
                            <span>Buka Kunci Print</span>
                        </a>
                    @else
                        <a href="{{ route('penjualan.updatelockprint', Crypt::encrypt($penjualan->no_faktur)) }}"
                            class="h-[34px] px-3.5 rounded-lg border border-emerald-200 dark:border-emerald-900 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                            <i data-lucide="printer-check" class="w-3.5 h-3.5"></i>
                            <span>Kunci Print</span>
                        </a>
                    @endif
                @endcan
            </div>
        </div>

    </div>

    <!-- MAIN GRID LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-start">
        
        <!-- LEFT COLUMN: PROFIL & DATA PELANGGAN (Col-span 4) -->
        <div class="lg:col-span-4 space-y-4">
            
            <!-- Card Data Pelanggan -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-2xs space-y-3">
                <div class="flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-slate-800">
                    <i data-lucide="user-check" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                    <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                        Informasi Pelanggan
                    </h4>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Kode Pelanggan</span>
                        <span class="font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $penjualan->kode_pelanggan }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">NIK</span>
                        <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $penjualan->nik ?: '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">No. KK</span>
                        <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ $penjualan->no_kk ?: '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Tanggal Lahir</span>
                        <span class="font-medium text-slate-700 dark:text-slate-300">{{ !empty($penjualan->tanggal_lahir) ? DateToIndo($penjualan->tanggal_lahir) : '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">No. HP</span>
                        <span class="font-mono-num font-semibold text-brand-600 dark:text-brand-400">{{ $penjualan->no_hp_pelanggan ?: '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Hari Kunjungan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ textCamelCase($penjualan->hari) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Limit Piutang</span>
                        <span class="font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatRupiah($penjualan->limit_pelanggan) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Lama Jatuh Tempo (LJT)</span>
                        <span class="font-mono-num font-bold text-amber-600 dark:text-amber-400">{{ $penjualan->ljt }} Hari</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Omset Toko</span>
                        <span class="font-mono-num font-semibold text-slate-700 dark:text-slate-300">{{ formatRupiah($penjualan->omset_toko) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-slate-400">Jaminan</span>
                        <span class="font-bold {{ $penjualan->jaminan == 1 ? 'text-emerald-600' : 'text-slate-400' }}">{{ $penjualan->jaminan == 1 ? 'Ada' : 'Tidak Ada' }}</span>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 space-y-1">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Alamat Toko</span>
                        <p class="text-slate-700 dark:text-slate-300 text-[11.5px] leading-relaxed">
                            {{ $penjualan->alamat_toko ?: $penjualan->alamat_pelanggan }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Signature (Jika Ada) -->
            @if (Storage::disk('public')->exists('/signature/' . $penjualan->signature) && !empty($penjualan->signature))
                <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-4 shadow-2xs space-y-2.5">
                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <i data-lucide="pen-tool" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                            Tanda Tangan Pelanggan
                        </h4>
                    </div>
                    <div class="rounded-lg bg-slate-50 dark:bg-slate-800/50 p-2 flex items-center justify-center border border-slate-100 dark:border-slate-800">
                        <img src="{{ getSignature($penjualan->signature) }}" alt="Signature" class="max-h-28 object-contain">
                    </div>
                </div>
            @endif

        </div>

        <!-- RIGHT COLUMN: TRANSAKSI & RINCIAN BARANG (Col-span 8) -->
        <div class="lg:col-span-8 space-y-4">
            
            <!-- EXECUTIVE SUMMARY STRIP PACIFIC NAVY -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs overflow-hidden">
                <div class="bg-[#284c9a] text-white p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="space-y-1">
                            <span class="text-[10.5px] uppercase tracking-wider font-bold text-white/70 block">Nomor Faktur Penjualan</span>
                            <div class="flex items-center gap-2">
                                <span class="text-base sm:text-lg font-black font-mono-num">{{ $penjualan->no_faktur }}</span>
                                @if (substr($penjualan->no_faktur, 3, 2) == 'PR')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-500/30 text-sky-200 border border-sky-400/40">
                                        PO (Pre-Order)
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-left sm:text-right">
                            <span class="text-[10.5px] uppercase tracking-wider font-bold text-white/70 block">Total Netto Transaksi</span>
                            <span class="text-xl sm:text-2xl font-black font-mono-num text-white">
                                Rp {{ formatAngka($total_netto) }}
                            </span>
                        </div>
                    </div>

                    <!-- Inner Strip Divider -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-3 mt-3 border-t border-white/15 text-xs">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-white/60 block">Tanggal Faktur</span>
                            <span class="font-semibold text-white/95">{{ DateToIndo($penjualan->tanggal) }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-white/60 block">Jenis Transaksi</span>
                            <span class="inline-flex items-center px-1.5 py-0.2 rounded font-bold text-[10.5px] {{ $penjualan->jenis_transaksi == 'T' ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/30' : 'bg-amber-500/20 text-amber-200 border border-amber-400/30' }}">
                                {{ $penjualan->jenis_transaksi == 'T' ? 'TUNAI' : 'KREDIT' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-white/60 block">Metode Pembayaran</span>
                            <span class="font-semibold text-white/95">{{ $jenis_bayar[$penjualan->jenis_bayar] ?? $penjualan->jenis_bayar }}</span>
                        </div>
                        <div>
                            <span class="text-[10px] uppercase font-bold text-white/60 block">Status Faktur</span>
                            @if ($penjualan->status_batal == 0)
                                @if ($penjualan->total_bayar == $total_netto)
                                    <span class="font-bold text-emerald-300">LUNAS</span>
                                @elseif ($penjualan->total_bayar > $total_netto)
                                    <span class="font-bold text-sky-300">LEBIH BAYAR</span>
                                @else
                                    <span class="font-bold text-rose-300">BELUM LUNAS</span>
                                @endif
                            @else
                                <span class="font-bold text-rose-300">FAKTUR BATAL</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAIL PRODUK PENJUALAN TABLE (~36px) -->
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <!-- Header Row 1 -->
                            <tr class="bg-[#284c9a] text-white">
                                <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                            <span class="text-xs font-bold text-white">Rincian Barang Penjualan</span>
                                        </div>
                                        <span class="text-[10.5px] text-white/70 font-mono-num">
                                            {{ count($detail) }} Item Produk
                                        </span>
                                    </div>
                                </th>
                            </tr>

                            <!-- Header Row 2 -->
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2 px-3 w-20 font-bold uppercase tracking-wider text-white/90">KODE</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA PRODUK</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">DUS</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA DUS</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">PACK</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA PACK</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">PCS</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA PCS</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            @php $subtotal = 0; @endphp
                            @foreach ($detail as $d)
                                @php
                                    $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                                    $jumlah_dus = $jumlah[0];
                                    $jumlah_pack = $jumlah[1];
                                    $jumlah_pcs = $jumlah[2];
                                    $subtotal += $d->subtotal;
                                    $isPromosi = $d->status_promosi == '1';
                                @endphp
                                <tr class="{{ $isPromosi ? 'bg-amber-50/60 dark:bg-amber-950/20' : 'hover:bg-slate-50/80 dark:hover:bg-slate-800/50' }} transition">
                                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                        {{ $d->kode_produk }}
                                    </td>
                                    <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                        {{ $d->nama_produk }}
                                        @if ($isPromosi)
                                            <span class="inline-flex items-center px-1.5 py-0.2 rounded text-[9.5px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300 ml-1">
                                                Promosi
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                        {{ formatAngka($jumlah_dus) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">
                                        {{ formatAngka($d->harga_dus) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                        {{ formatAngka($jumlah_pack) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">
                                        {{ formatAngka($d->harga_pack) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                        {{ formatAngka($jumlah_pcs) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">
                                        {{ formatAngka($d->harga_pcs) }}
                                    </td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                        {{ formatAngka($d->subtotal) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-semibold text-xs divide-y divide-slate-200 dark:divide-slate-700">
                            <tr>
                                <td colspan="8" class="py-2 px-3 text-right uppercase font-bold text-slate-600 dark:text-slate-300">SUBTOTAL</td>
                                <td class="py-2 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ formatAngka($subtotal) }}</td>
                            </tr>
                            @if ($penjualan->potongan > 0)
                                <tr>
                                    <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">POTONGAN</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-rose-600 dark:text-rose-400">-{{ formatAngka($penjualan->potongan) }}</td>
                                </tr>
                            @endif
                            @if ($penjualan->potongan_istimewa > 0)
                                <tr>
                                    <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">POTONGAN ISTIMEWA</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-rose-600 dark:text-rose-400">-{{ formatAngka($penjualan->potongan_istimewa) }}</td>
                                </tr>
                            @endif
                            @if ($penjualan->penyesuaian > 0)
                                <tr>
                                    <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">PENYESUAIAN</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-400">{{ formatAngka($penjualan->penyesuaian) }}</td>
                                </tr>
                            @endif
                            @php
                                $total_dpp = $penjualan->total_bruto - $penjualan->potongan - $penjualan->potongan_istimewa - $penjualan->penyesuaian;
                            @endphp
                            <tr>
                                <td colspan="8" class="py-1.5 px-3 text-right uppercase font-bold text-slate-700 dark:text-slate-200">TOTAL</td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ formatAngka($total_dpp) }}</td>
                            </tr>
                            @if ($penjualan->ppn > 0)
                                <tr>
                                    <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">PPN</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">{{ formatAngka($penjualan->ppn) }}</td>
                                </tr>
                            @endif
                            @if ($penjualan->total_retur > 0)
                                <tr>
                                    <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">RETUR</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-amber-600 dark:text-amber-400">-{{ formatAngka($penjualan->total_retur) }}</td>
                                </tr>
                            @endif
                            <tr class="bg-blue-50/50 dark:bg-blue-950/30">
                                <td colspan="8" class="py-2.5 px-3 text-right uppercase font-bold text-[#284c9a] dark:text-blue-400">GRAND TOTAL</td>
                                <td class="py-2.5 px-3 text-right font-mono-num font-black text-sm text-[#284c9a] dark:text-blue-400">Rp {{ formatAngka($total_netto) }}</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">JUMLAH BAYAR</td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($penjualan->total_bayar) }}</td>
                            </tr>
                            <tr>
                                <td colspan="8" class="py-1.5 px-3 text-right uppercase text-slate-500">SISA BAYAR</td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ ($total_netto - $penjualan->total_bayar) > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500' }}" id="sisabayar">
                                    {{ formatAngka($total_netto - $penjualan->total_bayar) }}
                                </td>
                            </tr>
                            @php
                                if ($penjualan->status_batal == 0) {
                                    if ($penjualan->total_bayar == $total_netto) {
                                        $badgeBg = 'bg-emerald-600 text-white';
                                        $ket = 'LUNAS';
                                    } elseif ($penjualan->total_bayar > $total_netto) {
                                        $badgeBg = 'bg-blue-600 text-white';
                                        $ket = 'LEBIH BAYAR';
                                    } else {
                                        $badgeBg = 'bg-rose-600 text-white';
                                        $ket = 'BELUM LUNAS';
                                    }
                                } else {
                                    $badgeBg = 'bg-rose-600 text-white';
                                    $ket = 'FAKTUR BATAL';
                                }
                            @endphp
                            <tr>
                                <td colspan="9" class="py-2 px-3 text-center font-bold text-xs uppercase tracking-wider {{ $badgeBg }}">
                                    {{ $ket }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- DETAIL RETUR (JIKA ADA) -->
            @if (count($retur) > 0)
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#284c9a] text-white">
                                    <th colspan="11" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                                        <div class="flex items-center gap-1.5">
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 text-blue-200"></i>
                                            <span class="text-xs font-bold text-white">Data Retur Penjualan</span>
                                        </div>
                                    </th>
                                </tr>
                                <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">TANGGAL</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">KODE</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA PRODUK</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">JENIS</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">DUS</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">PACK</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">PCS</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">HARGA</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                                @php
                                    $subtotal_retur = 0;
                                    $subtotal_retur_gb = 0;
                                @endphp
                                @foreach ($retur as $d)
                                    @php
                                        $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                                        $jumlah_dus = $jumlah[0];
                                        $jumlah_pack = $jumlah[1];
                                        $jumlah_pcs = $jumlah[2];
                                        $subtotal_retur += $d->subtotal;
                                        if ($d->jenis_retur == 'GB') {
                                            $subtotal_retur_gb += $d->subtotal;
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                        <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-200">{{ $d->kode_produk }}</td>
                                        <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $d->jenis_retur == 'GB' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300' }}">
                                                {{ $d->jenis_retur }}
                                            </span>
                                        </td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($jumlah_dus) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->harga_dus) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($jumlah_pack) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->harga_pack) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($jumlah_pcs) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num">{{ formatAngka($d->harga_pcs) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ formatAngka($d->subtotal) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-semibold text-xs divide-y divide-slate-200 dark:divide-slate-700">
                                <tr>
                                    <td colspan="10" class="py-2 px-3 text-right font-bold text-slate-700 dark:text-slate-300">TOTAL</td>
                                    <td class="py-2 px-3 text-right font-mono-num font-bold">{{ formatAngka($subtotal_retur) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="py-1.5 px-3 text-right text-slate-500">GANTI BARANG</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num text-amber-600 dark:text-amber-400">{{ formatAngka($subtotal_retur_gb) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="10" class="py-1.5 px-3 text-right font-bold text-slate-800 dark:text-slate-200">POTONG FAKTUR</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($subtotal_retur - $subtotal_retur_gb) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            <!-- HISTORI PEMBAYARAN TUNAI / CASH -->
            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between">
                    <div class="flex items-center gap-1.5">
                        <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Histori Pembayaran</h4>
                    </div>

                    @if ($penjualan->status_batal == 0)
                        @can('pembayaranpenjualan.create')
                            <button type="button" id="btnCreateBayar"
                                class="h-[28px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs cursor-pointer">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                <span>Input Pembayaran</span>
                            </button>
                        @endcan
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NO. BUKTI</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">TANGGAL</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">JENIS BAYAR</th>
                                <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">JUMLAH</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">KETERANGAN</th>
                                <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">SALESMAN</th>
                                <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            @php $total_bayar_tunai = 0; @endphp
                            @forelse ($historibayar as $d)
                                @php $total_bayar_tunai += $d->jumlah; @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->no_bukti }}</td>
                                    <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                    <td class="py-1.5 px-3 font-semibold text-slate-700 dark:text-slate-300">{{ $jenis_bayar[$d->jenis_bayar] ?? $d->jenis_bayar }}</td>
                                    <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($d->jumlah) }}</td>
                                    <td class="py-1.5 px-3">
                                        @if ($d->voucher == '1')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                {{ $d->nama_voucher }} {{ $d->voucher_reward == '1' ? '(Reward)' : '' }}
                                            </span>
                                        @elseif ($d->giro_to_cash == '1')
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300">
                                                Ganti Giro Ke Cash {{ $d->no_giro }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">{{ textUpperCase($d->nama_salesman) }}</td>
                                    <td class="py-1.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if (in_array($d->jenis_bayar, ['TN', 'TP']))
                                                @if ($d->voucher == 0)
                                                    @can('pembayaranpenjualan.edit')
                                                        <button type="button" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                                            class="btnEditBayar w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                                            title="Edit Pembayaran">
                                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                        </button>
                                                    @endcan
                                                @endif
                                                @can('pembayaranpenjualan.delete')
                                                    <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                        action="{{ route('pembayaranpenjualan.delete', Crypt::encrypt($d->no_bukti)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                            title="Hapus Pembayaran">
                                                            <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-4 px-3 text-center text-slate-400">Belum ada catatan pembayaran langsung</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if (count($historibayar) > 0)
                            <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-semibold text-xs border-t border-slate-200 dark:border-slate-700">
                                <tr>
                                    <td colspan="3" class="py-2 px-3 text-right uppercase font-bold text-slate-700 dark:text-slate-300">TOTAL</td>
                                    <td class="py-2 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">{{ formatAngka($total_bayar_tunai) }}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- HISTORI PEMBAYARAN GIRO & TRANSFER -->
            <div class="grid grid-cols-1 gap-4">
                
                <!-- Histori Giro -->
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="wallet" class="w-3.5 h-3.5 text-blue-200"></i>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Histori Pembayaran Giro</h4>
                        </div>
                        @if ($penjualan->status_batal == 0)
                            @can('pembayarangiro.create')
                                <button type="button" id="btnCreategiro"
                                    class="h-[28px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs cursor-pointer">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Input Giro</span>
                                </button>
                            @endcan
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NO. GIRO</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">TANGGAL</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">BANK</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">JUMLAH</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">JATUH TEMPO</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">DOKUMEN</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">STATUS</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">SALESMAN</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                                @forelse ($giro as $d)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->no_giro }}</td>
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                        <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">{{ textUpperCase($d->bank_pengirim) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ formatAngka($d->jumlah) }}</td>
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            @if (!empty($d->foto) && Storage::disk('public')->exists('/giro/' . $d->foto))
                                                <a href="{{ getfotoGiro($d->foto) }}" target="_blank" class="inline-block">
                                                    <img src="{{ getfotoGiro($d->foto) }}" alt="Giro" class="w-7 h-7 rounded object-cover border border-slate-200 shadow-xs">
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-xs">-</span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-3 text-center">
                                            @if ($d->status == '0')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                                                    Pending
                                                </span>
                                            @elseif ($d->status == '1')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                    Diterima ({{ date('d-m-y', strtotime($d->tanggal_diterima)) }})
                                                </span>
                                            @elseif ($d->status == '2')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ textUpperCase($d->nama_salesman) }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            @if ($d->status == '0')
                                                <div class="flex items-center justify-center gap-1">
                                                    @can('pembayarangiro.edit')
                                                        <button type="button" kode_giro="{{ Crypt::encrypt($d->kode_giro) }}"
                                                            class="btnEditgiro w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                                            title="Edit Giro">
                                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                        </button>
                                                    @endcan
                                                    @can('pembayarangiro.delete')
                                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                            action="{{ route('pembayarangiro.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_giro)]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                                title="Hapus Giro">
                                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                    Keuangan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="py-4 px-3 text-center text-slate-400">Tidak ada data pembayaran giro</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Histori Transfer -->
                <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                    <div class="px-3.5 py-2 bg-[#284c9a] text-white flex items-center justify-between">
                        <div class="flex items-center gap-1.5">
                            <i data-lucide="arrow-left-right" class="w-3.5 h-3.5 text-blue-200"></i>
                            <h4 class="text-xs font-bold text-white uppercase tracking-wider">Histori Pembayaran Transfer</h4>
                        </div>
                        @if ($penjualan->status_batal == 0)
                            @can('pembayarantransfer.create')
                                <button type="button" id="btnCreatetransfer"
                                    class="h-[28px] px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1 shadow-xs cursor-pointer">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Input Transfer</span>
                                </button>
                            @endcan
                        @endif
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                            <thead>
                                <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">KODE TRANSFER</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">TANGGAL</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">BANK</th>
                                    <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">JUMLAH</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">JATUH TEMPO</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">STATUS</th>
                                    <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">SALESMAN</th>
                                    <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90">AKSI</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                                @forelse ($transfer as $d)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_transfer }}</td>
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                                        <td class="py-1.5 px-3 font-medium text-slate-700 dark:text-slate-300">{{ textUpperCase($d->bank_pengirim) }}</td>
                                        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ formatAngka($d->jumlah) }}</td>
                                        <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            @if ($d->status == '0')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300">
                                                    Pending
                                                </span>
                                            @elseif ($d->status == '1')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                    Diterima ({{ date('d-m-y', strtotime($d->tanggal_diterima)) }})
                                                </span>
                                            @elseif ($d->status == '2')
                                                <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ textUpperCase($d->nama_salesman) }}</td>
                                        <td class="py-1.5 px-3 text-center">
                                            @if ($d->status == '0')
                                                <div class="flex items-center justify-center gap-1">
                                                    @can('pembayarantransfer.edit')
                                                        <button type="button" kode_transfer="{{ Crypt::encrypt($d->kode_transfer) }}"
                                                            class="btnEdittransfer w-6 h-6 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 flex items-center justify-center transition cursor-pointer"
                                                            title="Edit Transfer">
                                                            <i data-lucide="edit-3" class="w-3 h-3"></i>
                                                        </button>
                                                    @endcan
                                                    @can('pembayarantransfer.delete')
                                                        <form method="POST" name="deleteform" class="inline-flex m-0 p-0 deleteform"
                                                            action="{{ route('pembayarantransfer.delete', [Crypt::encrypt($d->no_faktur), Crypt::encrypt($d->kode_transfer)]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="delete-confirm w-6 h-6 rounded-md bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 flex items-center justify-center transition cursor-pointer"
                                                                title="Hapus Transfer">
                                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300">
                                                    Keuangan
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-3 text-center text-slate-400">Tidak ada data pembayaran transfer</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- MODAL CONTAINER (STATIC BACKDROP & CLEAN HEADER) -->
<div id="modalDetailPenjualan" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4 overflow-y-auto">
    <div id="modalDetailPenjualanDialog" class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden transform transition-all duration-150 my-auto">
        
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
                <h3 id="modalDetailPenjualanTitle" class="text-sm font-bold text-slate-800 dark:text-slate-100 leading-none">
                    Form Pembayaran
                </h3>
            </div>
            <button type="button" id="btnModalDetailClose"
                class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition cursor-pointer">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div id="modalDetailPenjualanContent" class="p-4 sm:p-5 max-h-[85vh] overflow-y-auto"></div>

    </div>
</div>

@endsection

@push('myscript')
<script src="{{ asset('assets/vendor/libs/leaflet/leaflet.js') }}"></script>
<!-- LEAFLET MAP SCRIPT -->
<script>
    $(document).ready(function() {
        var hasPelangganCoords = {{ (!empty($penjualan->latitude) && !empty($penjualan->longitude)) ? 'true' : 'false' }};
        var hasCheckinCoords = {{ ($checkin != null && !empty($checkin->latitude) && !empty($checkin->longitude)) ? 'true' : 'false' }};

        var latRaw = "{{ !empty($penjualan->latitude) ? str_replace(',', '.', $penjualan->latitude) : '-7.3665114' }}";
        var lngRaw = "{{ !empty($penjualan->longitude) ? str_replace(',', '.', $penjualan->longitude) : '108.2148793' }}";
        var latitude = parseFloat(latRaw) || -7.3665114;
        var longitude = parseFloat(lngRaw) || 108.2148793;

        var checkinLatRaw = "{{ $checkin != null && !empty($checkin->latitude) ? str_replace(',', '.', $checkin->latitude) : '-7.3665114' }}";
        var checkinLngRaw = "{{ $checkin != null && !empty($checkin->longitude) ? str_replace(',', '.', $checkin->longitude) : '108.2148793' }}";
        var latitudecheckin = parseFloat(checkinLatRaw) || -7.3665114;
        var longitudecheckin = parseFloat(checkinLngRaw) || 108.2148793;

        if (typeof L !== 'undefined' && document.getElementById('map')) {
            try {
                var map = L.map('map', {
                    zoomControl: true,
                    scrollWheelZoom: false
                }).setView([latitude, longitude], 17);

                L.tileLayer('https://{s}.google.com/vt?lyrs=m&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                }).addTo(map);

                // Custom Pin Icon: Toko Pelanggan (Pacific Navy #284c9a dengan Icon Toko)
                var iconPelanggan = L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div style="position: relative; width: 34px; height: 42px; display: flex; flex-direction: column; align-items: center; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.3)); cursor: pointer;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #284c9a; color: white; display: flex; align-items: center; justify-content: center; border: 2.5px solid white;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/></svg>
                            </div>
                            <div style="width: 10px; height: 10px; background-color: #284c9a; transform: rotate(45deg); margin-top: -6px; border-right: 2px solid white; border-bottom: 2px solid white;"></div>
                        </div>
                    `,
                    iconSize: [34, 42],
                    iconAnchor: [17, 40],
                    popupAnchor: [0, -40]
                });

                // Custom Pin Icon: Titik Transaksi Salesman (Rose Merah #e11d48 dengan Icon Keranjang)
                var iconTransaksi = L.divIcon({
                    className: 'custom-div-icon',
                    html: `
                        <div style="position: relative; width: 34px; height: 42px; display: flex; flex-direction: column; align-items: center; filter: drop-shadow(0 3px 5px rgba(0,0,0,0.3)); cursor: pointer;">
                            <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #e11d48; color: white; display: flex; align-items: center; justify-content: center; border: 2.5px solid white;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            </div>
                            <div style="width: 10px; height: 10px; background-color: #e11d48; transform: rotate(45deg); margin-top: -6px; border-right: 2px solid white; border-bottom: 2px solid white;"></div>
                        </div>
                    `,
                    iconSize: [34, 42],
                    iconAnchor: [17, 40],
                    popupAnchor: [0, -40]
                });

                // 1. Titik Lokasi Pelanggan (Toko Asli di Tabel Pelanggan)
                var markerPelanggan = L.marker([latitude, longitude], { icon: iconPelanggan }).addTo(map)
                    .bindPopup('<b>Lokasi Asli Pelanggan (Toko)</b><br>{{ addslashes($penjualan->nama_pelanggan) }}<br><span class="text-xs text-slate-500">{{ addslashes($penjualan->alamat_pelanggan ?? "") }}</span>');

                var circlePelanggan = L.circle([latitude, longitude], {
                    color: '#284c9a',
                    fillColor: '#284c9a',
                    fillOpacity: 0.15,
                    radius: 25
                }).addTo(map);

                // 2. Cek apakah ada koordinat transaksi (Check-in Salesman) yang berbeda dengan lokasi toko
                var isDifferent = (Math.abs(latitude - latitudecheckin) > 0.00001 || Math.abs(longitude - longitudecheckin) > 0.00001);

                if (isDifferent) {
                    // Titik Lokasi Transaksi (Check-in Salesman) - Pin Merah
                    var markerTransaksi = L.marker([latitudecheckin, longitudecheckin], { icon: iconTransaksi }).addTo(map)
                        .bindPopup('<b>Titik Transaksi (Check-in SFA)</b><br>{{ addslashes($penjualan->nama_salesman) }}<br><span class="text-xs text-slate-500">Tgl: {{ $penjualan->tanggal }}</span>');

                    // Garis Penghubung dari Titik Transaksi ke Titik Pelanggan Asli
                    var line = L.polyline([
                        [latitude, longitude],
                        [latitudecheckin, longitudecheckin]
                    ], {
                        color: '#284c9a',
                        weight: 4,
                        opacity: 0.9,
                        dashArray: '6, 6'
                    }).addTo(map);

                    // Auto-fit agar garis dan kedua titik terlihat jelas dalam layar
                    var bounds = L.latLngBounds([
                        [latitude, longitude],
                        [latitudecheckin, longitudecheckin]
                    ]);
                    map.fitBounds(bounds, { padding: [45, 45], maxZoom: 17 });
                } else if (hasPelangganCoords && hasCheckinCoords) {
                    markerPelanggan.bindPopup('<b>Lokasi Transaksi Tepat di Toko Pelanggan</b><br>{{ addslashes($penjualan->nama_pelanggan) }}');
                }

                // Invalidate size on load to guarantee proper tile rendering
                setTimeout(function() {
                    map.invalidateSize();
                }, 300);
            } catch (e) {
                console.warn("Leaflet map initialization skipped or failed:", e);
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $modal = $("#modalDetailPenjualan");
        const $dialog = $("#modalDetailPenjualanDialog");
        const $title = $("#modalDetailPenjualanTitle");
        const $content = $("#modalDetailPenjualanContent");

        function showLoading() {
            $content.html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <svg class="animate-spin h-6 w-6 text-[#284c9a] dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat Data...</span>
                </div>
            `);
        }

        function openModal(titleText) {
            $title.text(titleText);
            showLoading();
            $modal.removeClass("hidden").addClass("flex");
        }

        function closeModal() {
            $modal.addClass("hidden").removeClass("flex");
        }

        $("#btnModalDetailClose").on('click', function() {
            closeModal();
        });

        // Static Backdrop (Anti-Tutup Sembarangan & Bounce Feedback)
        $modal.on('click', function(e) {
            if ($(e.target).is($modal)) {
                $dialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $dialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$modal.hasClass('hidden')) {
                e.preventDefault();
                $dialog.addClass("scale-[1.015] shadow-2xl");
                setTimeout(function() {
                    $dialog.removeClass("scale-[1.015] shadow-2xl");
                }, 150);
            }
        });

        // Trigger Input Bayar
        $("#btnCreateBayar").on('click', function(e) {
            e.preventDefault();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            openModal("Input Pembayaran");
            $content.load(`/pembayaranpenjualan/${no_faktur}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit Bayar
        $(document).on('click', '.btnEditBayar', function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr('no_bukti');
            openModal("Edit Pembayaran");
            $content.load(`/pembayaranpenjualan/${no_bukti}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Input Giro
        $("#btnCreategiro").on('click', function(e) {
            e.preventDefault();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            openModal("Input Giro");
            $content.load(`/pembayarangiro/${no_faktur}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit Giro
        $(document).on('click', '.btnEditgiro', function(e) {
            e.preventDefault();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            const kode_giro = $(this).attr("kode_giro");
            openModal("Edit Giro");
            $content.load(`/pembayarangiro/${no_faktur}/${kode_giro}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Input Transfer
        $("#btnCreatetransfer").on('click', function(e) {
            e.preventDefault();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            openModal("Input Transfer");
            $content.load(`/pembayarantransfer/${no_faktur}/create`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

        // Trigger Edit Transfer
        $(document).on('click', '.btnEdittransfer', function(e) {
            e.preventDefault();
            const no_faktur = "{{ Crypt::encrypt($penjualan->no_faktur) }}";
            const kode_transfer = $(this).attr("kode_transfer");
            openModal("Edit Transfer");
            $content.load(`/pembayarantransfer/${no_faktur}/${kode_transfer}/edit`, function() {
                if (typeof lucide !== 'undefined') lucide.createIcons();
            });
        });

    });
</script>
@endpush
