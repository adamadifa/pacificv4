@extends('layouts.app')
@section('titlepage', 'Detail Form Permintaan Barang (FPB)')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('fpb.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">FPB</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $fpb->no_fpb }}</span>
    </div>
@endsection

@section('content')
@php
    $kenekNames = $fpb->helpers->map(fn($h) => $h->driverhelper->nama_driver_helper ?? $h->kode_driver_helper)->implode(', ');
@endphp
<div class="max-w-4xl mx-auto space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Form Permintaan Barang (FPB)</h3>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono-num">No. FPB: {{ $fpb->no_fpb }}</p>
        </div>
        <a href="{{ route('fpb.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- HEADER INFO STRIP -->
    <div class="rounded-xl bg-[#284c9a] text-white p-3.5 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <p class="text-[11px] text-white/70">Hari / Tanggal</p>
                <p class="font-bold font-mono-num mt-0.5">{{ DateToIndo($fpb->tanggal) }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Salesman</p>
                <p class="font-bold mt-0.5">{{ $fpb->salesman->nama_salesman ?? $fpb->kode_salesman }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Area / Rute</p>
                <p class="font-bold mt-0.5">{{ $fpb->area_rute ?: '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Sopir / Kenek</p>
                <p class="font-bold mt-0.5">
                    {{ $fpb->driver->nama_driver_helper ?? $fpb->kode_driver }}{{ $kenekNames ? ' / ' . $kenekNames : '' }}
                </p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">No. Kendaraan</p>
                <p class="font-bold font-mono-num mt-0.5">{{ $fpb->kendaraan->no_polisi ?? '-' }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Cabang</p>
                <p class="font-bold mt-0.5">{{ $fpb->cabang->nama_cabang ?? $fpb->kode_cabang }}</p>
            </div>
        </div>
    </div>

    <!-- TABEL BARANG PERMINTAAN -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a]">
                        <th colspan="4" class="px-3.5 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Barang Permintaan</span>
                                <span class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[10px] font-bold font-mono-num">{{ $items->count() }} Item</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78]">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center w-10">No</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Kode Barang</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Nama Barang</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right">Jumlah Permintaan (Dus)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                    @foreach ($items as $idx => $item)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold">{{ $idx + 1 }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $item->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ $item->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-black">
                                {{ rtrim(rtrim(number_format((float) $item->jml_permintaan, 2, ',', '.'), '0'), ',') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- DAFTAR FAKTUR REFERENSI -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a]">
                        <th colspan="4" class="px-3.5 py-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Faktur Penjualan Terkait</span>
                                <span class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[10px] font-bold font-mono-num">{{ $fakturs->count() }} Faktur</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78]">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center w-10">No</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Pelanggan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                    @foreach ($fakturs as $idx => $f)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold">{{ $idx + 1 }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $f->no_faktur }}</td>
                            <td class="py-1.5 px-3 font-mono-num">{{ DateToIndo($f->tanggal) }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ strtoupper($f->nama_pelanggan) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if (!empty($fpb->catatan))
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3 text-xs">
            <p class="font-bold text-slate-700 dark:text-slate-200 mb-1">Catatan:</p>
            <p class="text-slate-600 dark:text-slate-300">{{ $fpb->catatan }}</p>
        </div>
    @endif
</div>
@endsection
