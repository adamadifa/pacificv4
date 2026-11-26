@extends('layouts.app')
@section('titlepage', 'Detail Pengiriman Driver')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <a href="{{ route('pengirimandriver.index') }}" class="text-slate-400 hover:text-[#284c9a] transition">Pengiriman Driver</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">{{ $pengirimandriver->no_pengiriman }}</span>
    </div>
@endsection

@section('content')
@php
    $helperNames = $pengirimandriver->helpers->map(fn($h) => $h->driverhelper->nama_driver_helper ?? $h->kode_driver_helper)->implode(', ');
@endphp
<div class="space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex items-center justify-between gap-2">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Lembar Kerja Pengiriman Driver</h3>
            <p class="text-[11px] text-slate-400 mt-0.5 font-mono-num">{{ $pengirimandriver->no_pengiriman }}</p>
        </div>
        <a href="{{ route('pengirimandriver.index') }}"
            class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center gap-1.5 shrink-0 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- HEADER INFO STRIP -->
    <div class="rounded-xl bg-[#284c9a] text-white p-3.5 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
            <div>
                <p class="text-[11px] text-white/70">Tanggal</p>
                <p class="font-bold font-mono-num mt-0.5">{{ DateToIndo($pengirimandriver->tanggal) }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Cabang</p>
                <p class="font-bold mt-0.5">{{ $pengirimandriver->cabang->nama_cabang ?? $pengirimandriver->kode_cabang }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Nama Sales</p>
                <p class="font-bold mt-0.5">{{ $pengirimandriver->salesman->nama_salesman ?? $pengirimandriver->kode_salesman }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Driver</p>
                <p class="font-bold mt-0.5">{{ $pengirimandriver->driver->nama_driver_helper ?? $pengirimandriver->kode_driver }}</p>
            </div>
            <div>
                <p class="text-[11px] text-white/70">Helper</p>
                <p class="font-bold mt-0.5">{{ $helperNames ?: '-' }}</p>
            </div>
        </div>
    </div>

    <!-- WORKSHEET TABLE -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#1e3a78]">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center">No</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Kode Pelanggan</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Nama Toko / Pelanggan</th>
                        @foreach ($produk as $p)
                            <th class="py-2 px-2 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center" title="{{ $p->nama_produk }}">{{ $p->kode_produk }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                    @php $colTotals = []; @endphp
                    @foreach ($rows as $r)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40">
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold">{{ $r->no_urut }}</td>
                            <td class="py-1.5 px-3 font-mono-num text-[#284c9a] dark:text-blue-400 font-bold">{{ $r->kode_pelanggan }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ strtoupper($r->nama_pelanggan) }}</td>
                            @foreach ($produk as $p)
                                @php
                                    $qty = $r->qty_map[$p->kode_produk] ?? null;
                                    $colTotals[$p->kode_produk] = ($colTotals[$p->kode_produk] ?? 0) + (float) ($qty ?? 0);
                                @endphp
                                <td class="py-1.5 px-2 text-center font-mono-num">
                                    {{ $qty !== null ? rtrim(rtrim(number_format((float) $qty, 2, ',', '.'), '0'), ',') : '' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700">
                        <td colspan="3" class="py-2 px-3 text-xs font-black text-slate-700 dark:text-slate-200 text-right uppercase">Total</td>
                        @foreach ($produk as $p)
                            @php $t = $colTotals[$p->kode_produk] ?? 0; @endphp
                            <td class="py-2 px-2 text-center font-mono-num text-xs font-black text-[#284c9a] dark:text-blue-400">
                                {{ $t > 0 ? rtrim(rtrim(number_format($t, 2, ',', '.'), '0'), ',') : '0' }}
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
