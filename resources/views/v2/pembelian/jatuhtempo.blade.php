@extends('layouts.app')
@section('titlepage', 'Jatuh Tempo Pembelian')

@section('navigasi')
    <div class="flex items-center gap-1.5 text-xs">
        <span class="text-slate-400">Home</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-slate-400">Keuangan</span>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <a href="{{ route('pembelian.index') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Pembelian</a>
        <i data-lucide="chevron-right" class="w-3 h-3 text-slate-400"></i>
        <span class="text-brand-600 dark:text-brand-400 font-bold">Jatuh Tempo</span>
    </div>
@endsection

@section('content')
@php
    $totalPembelian = method_exists($pembelian, 'total') ? $pembelian->total() : $pembelian->count();
@endphp

<div class="space-y-3.5 w-full">

    <!-- PAGE TITLE BAR & ACTION BUTTONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5">
        <div>
            <h3 class="text-base font-black text-slate-900 dark:text-white leading-tight">Monitoring Jatuh Tempo</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Monitoring data jatuh tempo hutang pembelian dan jadwal pembayaran kontrabon.</p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('pembelian.index') }}"
                class="inline-flex items-center justify-center gap-1.5 h-[34px] px-3.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/60 text-slate-700 dark:text-slate-200 text-xs font-bold transition shadow-2xs cursor-pointer shrink-0">
                <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                <span>Kembali ke Pembelian</span>
            </a>
        </div>
    </div>

    <!-- FILTER SECTION (NO-LABEL, COMPACT H-[34PX], LUCIDE PREFIX, ROUNDED-LG) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 shadow-2xs transition-colors duration-200">
        <form action="{{ route('pembelian.jatuhtempo') }}" method="GET" id="formSearch" class="space-y-2">
            
            <!-- Baris 1: No Bukti & Jatuh Tempo Periode -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- No. Bukti (Col-span 6) -->
                <div class="lg:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="no_bukti_search" id="no_bukti_search" value="{{ Request('no_bukti_search') }}" placeholder="No. Bukti Pembelian"
                        class="w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition">
                </div>

                <!-- Jatuh Tempo Dari (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jatuhtempo_dari" id="jatuhtempo_dari" value="{{ Request('jatuhtempo_dari') }}" placeholder="Jatuh Tempo Dari" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

                <!-- Jatuh Tempo Sampai (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <input type="text" name="jatuhtempo_sampai" id="jatuhtempo_sampai" value="{{ Request('jatuhtempo_sampai') }}" placeholder="Jatuh Tempo Sampai" readonly
                        class="flatpickr-date w-full h-[34px] pl-8 pr-3 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition cursor-pointer">
                </div>

            </div>

            <!-- Baris 2: Asal Ajuan, Supplier, PPN, Tunai/Kredit -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-2 items-center">
                
                <!-- Asal Ajuan (Col-span 3) -->
                <div class="lg:col-span-3 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="folder-tree" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_asal_pengajuan_search" id="kode_asal_pengajuan_search"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Semua Asal Ajuan</option>
                        @foreach ($asal_ajuan as $d)
                            <option value="{{ $d['kode_group'] }}" {{ Request('kode_asal_pengajuan_search') == $d['kode_group'] ? 'selected' : '' }}>
                                {{ $d['nama_group'] }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Supplier (Col-span 3) -->
                <div class="lg:col-span-3 relative select2-container-v2">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-20">
                        <i data-lucide="truck" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="kode_supplier_search" id="kode_supplier_search" class="select2Kodesupplier w-full">
                        <option value="">Semua Supplier</option>
                        @foreach ($supplier as $d)
                            <option value="{{ $d->kode_supplier }}" {{ Request('kode_supplier_search') == $d->kode_supplier ? 'selected' : '' }}>
                                {{ textUpperCase($d->nama_supplier) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- PPN / Non PPN (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="receipt" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="ppn_search" id="ppn_search"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">PPN / Non PPN</option>
                        <option value="1" {{ Request('ppn_search') == '1' ? 'selected' : '' }}>PPN</option>
                        <option value="0" {{ Request('ppn_search') === '0' ? 'selected' : '' }}>Non PPN</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Tunai / Kredit (Col-span 2) -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400 z-10">
                        <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                    </div>
                    <select name="jenis_transaksi_search" id="jenis_transaksi_search"
                        class="w-full h-[34px] pl-8 pr-8 text-xs font-semibold rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 focus:outline-hidden focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition appearance-none cursor-pointer">
                        <option value="">Tunai / Kredit</option>
                        <option value="T" {{ Request('jenis_transaksi_search') == 'T' ? 'selected' : '' }}>Tunai</option>
                        <option value="K" {{ Request('jenis_transaksi_search') == 'K' ? 'selected' : '' }}>Kredit</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400">
                        <i data-lucide="chevron-down" class="w-3 h-3"></i>
                    </div>
                </div>

                <!-- Tombol Cari & Reset (Col-span 2) -->
                <div class="lg:col-span-2 flex items-center gap-1.5">
                    <button type="submit"
                        class="w-full h-[34px] px-3 rounded-lg bg-brand-600 hover:bg-brand-700 active:bg-brand-800 text-white text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i>
                        <span>Cari</span>
                    </button>
                    @if (request()->hasAny(['no_bukti_search', 'jatuhtempo_dari', 'jatuhtempo_sampai', 'kode_asal_pengajuan_search', 'kode_supplier_search', 'ppn_search', 'jenis_transaksi_search']))
                        <a href="{{ route('pembelian.jatuhtempo') }}"
                            class="h-[34px] px-2.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-medium transition flex items-center justify-center cursor-pointer shrink-0"
                            title="Reset Filter">
                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- DATA TABLE SECTION (KOMPAK ~36px, FROZEN NO. BUKTI & AKSI) -->
    <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto min-h-[280px]">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="12" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="calendar-clock" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Data Jatuh Tempo Pembelian</span>
                                </div>
                                <span class="text-[10.5px] text-white/80 font-mono-num font-semibold">
                                    Total: {{ number_format($totalPembelian, 0, ',', '.') }} Transaksi
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky left-0 z-20 bg-[#1e3a78] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.3)] min-w-[120px]">NO. BUKTI</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">TANGGAL</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">SUPPLIER</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[80px]">AJUAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">JATUH TEMPO</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[110px]">TOTAL</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[110px]">BAYAR</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[60px]">PPN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[70px]">KB</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[90px]">KET</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[70px]">T/K</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 sticky right-0 z-20 bg-[#1e3a78] shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.3)] min-w-[60px]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($pembelian as $d)
                        @php
                            $total = $d->subtotal + $d->penyesuaian_jk;
                            $rowBg = 'bg-white hover:bg-slate-50/80 dark:bg-slate-900 dark:hover:bg-slate-800/50 text-slate-700 dark:text-slate-200';
                            $cellStickyBg = 'bg-white dark:bg-slate-900 group-hover:bg-slate-50 dark:group-hover:bg-slate-800/50';
                        @endphp
                        <tr class="{{ $rowBg }} transition-colors duration-150 group">
                            
                            <!-- No. Bukti (Sticky Left) -->
                            <td class="py-1.5 px-3 font-bold font-mono-num text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800/80 sticky left-0 z-10 {{ $cellStickyBg }} shadow-[2px_0_5px_-2px_rgba(0,0,0,0.06)]">
                                {{ $d->no_bukti }}
                            </td>

                            <!-- Tanggal -->
                            <td class="py-1.5 px-3 border-b border-slate-100 dark:border-slate-800/80 text-slate-600 dark:text-slate-300">
                                {{ formatIndo($d->tanggal) }}
                            </td>

                            <!-- Supplier -->
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-800/80">
                                {{ $d->nama_supplier }}
                            </td>

                            <!-- Asal Ajuan -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-sky-50 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300 border border-sky-200/80 dark:border-sky-900/60 shadow-2xs">
                                    {{ $d->kode_asal_pengajuan }}
                                </span>
                            </td>

                            <!-- Jatuh Tempo (Merah Tegas) -->
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-rose-600 dark:text-rose-400 border-b border-slate-100 dark:border-slate-800/80">
                                {{ formatIndo($d->jatuh_tempo) }}
                            </td>

                            <!-- Total -->
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-slate-100 border-b border-slate-100 dark:border-slate-800/80">
                                {{ formatAngkaDesimal($total) }}
                            </td>

                            <!-- Bayar -->
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-emerald-600 dark:text-emerald-400 border-b border-slate-100 dark:border-slate-800/80">
                                {{ formatAngkaDesimal($d->totalbayar) }}
                            </td>

                            <!-- PPN -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80">
                                @if ($d->ppn == '1')
                                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400" title="PPN">
                                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                    </span>
                                @else
                                    <span class="text-slate-300 dark:text-slate-600 font-bold">-</span>
                                @endif
                            </td>

                            <!-- KB (Kontrabon) -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80">
                                @if ($d->cek_kontrabon > 0)
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60 shadow-2xs" title="Ada Kontrabon">
                                        <i data-lucide="check" class="w-3 h-3"></i> KB
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60 shadow-2xs" title="Belum Kontrabon">
                                        <i data-lucide="clock" class="w-3 h-3"></i> KB
                                    </span>
                                @endif
                            </td>

                            <!-- Status (Lunas / Belum Lunas) -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80">
                                @if ($total > 0 && $total == $d->totalbayar)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60 shadow-2xs">
                                        Lunas
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/80 dark:border-rose-900/60 shadow-2xs">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>

                            <!-- Jenis Transaksi (Tunai / Kredit) -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80">
                                @if ($d->jenis_transaksi == 'T')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60 shadow-2xs">
                                        Tunai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60 shadow-2xs">
                                        Kredit
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi (Sticky Right) -->
                            <td class="py-1.5 px-3 text-center border-b border-slate-100 dark:border-slate-800/80 sticky right-0 z-10 {{ $cellStickyBg }} shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.06)]">
                                <div class="flex items-center justify-center">
                                    @can('pembelian.show')
                                        <a href="#" no_bukti="{{ Crypt::encrypt($d->no_bukti) }}"
                                            class="btnShow w-6 h-6 rounded-md bg-sky-50 text-sky-600 hover:bg-sky-100 dark:bg-sky-950/50 dark:text-sky-400 flex items-center justify-center transition shadow-2xs cursor-pointer"
                                            title="Detail Pembelian">
                                            <i data-lucide="eye" class="w-3 h-3"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <i data-lucide="inbox" class="w-8 h-8 stroke-[1.2]"></i>
                                    <span class="text-xs font-semibold">Tidak ada data jatuh tempo pembelian ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION SECTION -->
        @if ($pembelian->hasPages())
            <div class="px-4 py-3 border-t border-slate-100 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-900/50">
                {{ $pembelian->links('v2.components.pagination') }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL FORM UNTUK DETAIL -->
<x-modal-form id="modal" show="loadmodal" title="Detail Pembelian" size="modal-xl" />

@endsection

@push('myscript')
<script>
    $(function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const $select2Supplier = $('.select2Kodesupplier');
        if ($select2Supplier.length) {
            $select2Supplier.select2({
                placeholder: 'Semua Supplier',
                allowClear: true,
                width: '100%'
            });
        }

        function showLoading() {
            $("#loadmodal").html(`
                <div class="flex flex-col items-center justify-center py-12 gap-3">
                    <div class="w-8 h-8 border-3 border-[#284c9a]/20 border-t-[#284c9a] rounded-full animate-spin"></div>
                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Memuat data...</span>
                </div>
            `);
        }

        $(document).on("click", ".btnShow", function(e) {
            e.preventDefault();
            const no_bukti = $(this).attr("no_bukti");
            showLoading();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Detail Pembelian");
            $("#modal").find("#loadmodal").load(`/pembelian/${no_bukti}/show`, function() {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        });
    });
</script>
@endpush
