@extends('layouts.app')
@section('titlepage', 'Pengiriman Driver')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Marketing</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Pengiriman Driver</span>
    </div>
@endsection

@section('content')
<div class="space-y-3">
    <!-- PAGE HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Pencatatan Pengiriman Driver / Helper</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Catatan produk yang dikirim oleh driver dan helper ke pelanggan.</p>
        </div>
        @can('pengirimandriver.create')
            <a href="{{ route('pengirimandriver.create') }}"
                class="h-[34px] px-3.5 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5 shrink-0">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                <span>Input Pengiriman</span>
            </a>
        @endcan
    </div>

    <!-- FILTER BAR (NO-LABEL COMPACT V2) -->
    <form action="{{ route('pengirimandriver.index') }}" method="GET"
        class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs p-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="dari" value="{{ request('dari') }}" placeholder="Dari Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <input type="text" name="sampai" value="{{ request('sampai') }}" placeholder="Sampai Tanggal" readonly
                    class="flatpickr-date w-full h-[34px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition cursor-pointer">
            </div>

            @hasanyrole($roles_show_cabang)
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_cabang"
                        class="w-full h-[34px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ textUpperCase($c->nama_cabang) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endhasanyrole

            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                </div>
                <select name="kode_salesman"
                    class="w-full h-[34px] pl-8 pr-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:border-[#284c9a] focus:ring-2 focus:ring-[#284c9a]/15 transition">
                    <option value="">Semua Salesman</option>
                    @foreach ($salesman as $s)
                        <option value="{{ $s->kode_salesman }}" {{ request('kode_salesman') == $s->kode_salesman ? 'selected' : '' }}>
                            {{ $s->nama_salesman }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="flex-1 h-[34px] px-4 rounded-lg bg-[#284c9a] hover:bg-[#1e3a78] text-white text-xs font-bold shadow-xs transition inline-flex items-center justify-center gap-1.5">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                    <span>Cari</span>
                </button>
                <a href="{{ route('pengirimandriver.index') }}"
                    class="h-[34px] px-3 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 text-xs font-bold inline-flex items-center justify-center transition"
                    title="Reset Filter">
                    <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </form>

    <!-- DATA TABLE V2 (KOMPAK ~36PX) -->
    @php $totalData = method_exists($pengirimandriver, 'total') ? $pengirimandriver->total() : $pengirimandriver->count(); @endphp
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-[#284c9a]">
                        <th colspan="8" class="px-3.5 py-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="truck" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Daftar Pengiriman Driver / Helper</span>
                                </div>
                                <span class="px-2 py-0.5 rounded-md bg-white/15 text-white text-[10px] font-bold font-mono-num">
                                    {{ $totalData }} Data
                                </span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78]">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">No. Pengiriman</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Tanggal</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Cabang</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Nama Sales</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Driver</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90">Helper</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center">Jml Toko</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-200">
                    @forelse ($pengirimandriver as $d)
                        @php
                            $helperNames = $d->helpers->map(fn($h) => $h->driverhelper->nama_driver_helper ?? $h->kode_driver_helper)->implode(', ');
                            $jmlToko = $d->details->pluck('kode_pelanggan')->unique()->count();
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->no_pengiriman }}
                            </td>
                            <td class="py-1.5 px-3 font-mono-num">{{ DateToIndo($d->tanggal) }}</td>
                            <td class="py-1.5 px-3">{{ $d->cabang->nama_cabang ?? $d->kode_cabang }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ $d->salesman->nama_salesman ?? $d->kode_salesman }}</td>
                            <td class="py-1.5 px-3 font-semibold">{{ $d->driver->nama_driver_helper ?? $d->kode_driver }}</td>
                            <td class="py-1.5 px-3">{{ $helperNames ?: '-' }}</td>
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold">{{ $jmlToko }}</td>
                            <td class="py-1.5 px-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('pengirimandriver.show', Crypt::encrypt($d->no_pengiriman)) }}"
                                        class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 hover:bg-blue-100 flex items-center justify-center transition"
                                        title="Detail Lembar Kerja">
                                        <i data-lucide="eye" class="w-3 h-3"></i>
                                    </a>
                                    @can('pengirimandriver.delete')
                                        <form action="{{ route('pengirimandriver.delete', Crypt::encrypt($d->no_pengiriman)) }}" method="POST"
                                            class="inline-flex m-0 p-0 deleteform">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-6 h-6 rounded-md bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-100 flex items-center justify-center transition delete-confirm"
                                                title="Hapus">
                                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-xs text-slate-400">
                                Belum ada data pencatatan pengiriman driver/helper.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($pengirimandriver->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $pengirimandriver->links('v2.components.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') lucide.createIcons();
        if (typeof flatpickr !== 'undefined') {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: false
            });
        }
    });
</script>
@endpush
