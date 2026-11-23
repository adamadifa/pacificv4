@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="space-y-4">
    
    <!-- DOCUMENT SUMMARY CARD (CLEAN INTEGRATED STRIP) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3.5 sm:p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <!-- No. Permintaan -->
            <div class="flex flex-col justify-center">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">No. Permintaan</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                        <i data-lucide="barcode" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm tracking-tight truncate" title="{{ $pk->no_permintaan }}">
                        {{ $pk->no_permintaan }}
                    </span>
                </div>
            </div>

            <!-- Tanggal Permintaan -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Tanggal</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-bold text-slate-800 dark:text-slate-100 text-xs">
                        {{ DateToIndo($pk->tanggal) }}
                    </span>
                </div>
            </div>

            <!-- Cabang & Salesman -->
            <div class="flex flex-col justify-center lg:border-l lg:border-slate-200/80 lg:dark:border-slate-800 lg:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Cabang / Salesman</span>
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 font-bold text-xs border border-blue-200/60 dark:border-blue-900/50">
                        <i data-lucide="building-2" class="w-3 h-3"></i>
                        <span>{{ textUpperCase($pk->nama_cabang) }}</span>
                    </span>
                    @if (!empty($pk->kode_salesman))
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-200/70 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-[11px]">
                            <i data-lucide="user" class="w-3 h-3 text-slate-400"></i>
                            <span>{{ $pk->nama_salesman }}</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Keterangan -->
            <div class="flex flex-col justify-center sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-4">
                <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-1">Keterangan</span>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                    </div>
                    <span class="font-medium text-slate-700 dark:text-slate-300 text-xs truncate" title="{{ $pk->keterangan }}">
                        {{ $pk->keterangan ?: '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (PERMINTAAN KIRIMAN) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[300px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Header Strip Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="package-check" class="w-4 h-4 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Produk Permintaan</span>
                                </div>
                                <span class="text-[11px] text-white/80 font-mono-num font-semibold">{{ count($detail) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3.5 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3.5 w-36 font-bold uppercase tracking-wider text-white/90 border-none">KODE PRODUK</th>
                        <th class="py-2 px-4 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-4 text-right w-32 font-bold uppercase tracking-wider text-white/90 border-none">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php $totalQty = 0; @endphp
                    @forelse ($detail as $d)
                        @php $totalQty += (float)$d->jumlah; @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-2.5 px-3.5 text-center text-slate-400 font-mono-num text-[11px]">{{ $loop->iteration }}</td>
                            <td class="py-2.5 px-3.5">
                                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-2.5 px-4 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100 text-xs">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Tidak ada rincian produk</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-900/80 font-bold text-xs sticky bottom-0 z-10 backdrop-blur-xs">
                    <tr>
                        <td colspan="3" class="py-2.5 px-4 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">
                            Total Permintaan:
                        </td>
                        <td class="py-2.5 px-4 text-right font-mono-num text-sm font-black text-[#284c9a] dark:text-blue-400">
                            {{ $numFormat($totalQty) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- DATA SURAT JALAN GUDANG JADI -->
    <div class="space-y-2 pt-1">
        <div class="flex items-center gap-2">
            <i data-lucide="truck" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
            <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Surat Jalan Gudang Jadi</h5>
        </div>

        @if ($suratjalan != null)
            <!-- Surat Jalan Summary -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                    <div>
                        <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">No. Surat Jalan</span>
                        <span class="font-bold font-mono-num text-emerald-600 dark:text-emerald-400 text-sm">
                            {{ $suratjalan->no_mutasi }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Tanggal Surat Jalan</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">
                            {{ DateToIndo($suratjalan->tanggal) }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Status Pengiriman</span>
                        <div>
                            @if ($suratjalan->status_surat_jalan == 0)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 font-bold text-[11px] border border-rose-200/60 dark:border-rose-900/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Belum Diterima Cabang
                                </span>
                            @elseif($suratjalan->status_surat_jalan == 1)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 font-bold text-[11px] border border-emerald-200/60 dark:border-emerald-900/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Sudah Diterima Cabang
                                </span>
                            @elseif($suratjalan->status_surat_jalan == 2)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 font-bold text-[11px] border border-cyan-200/60 dark:border-cyan-900/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                    Transit Out
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Detail Surat Jalan -->
            <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
                <div class="overflow-x-auto max-h-[260px]">
                    <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                        <thead class="sticky top-0 z-10">
                            <tr class="bg-slate-800 text-white text-[11px]">
                                <th class="py-2 px-3.5 text-center w-12 font-bold uppercase tracking-wider text-white/90">NO</th>
                                <th class="py-2 px-3.5 w-36 font-bold uppercase tracking-wider text-white/90">KODE PRODUK</th>
                                <th class="py-2 px-4 font-bold uppercase tracking-wider text-white/90">NAMA PRODUK</th>
                                <th class="py-2 px-4 text-right w-32 font-bold uppercase tracking-wider text-white/90">JUMLAH DIKIRIM</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                            @php $totalSj = 0; @endphp
                            @foreach ($detailsuratjalan as $d)
                                @php $totalSj += (float)$d->jumlah; @endphp
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                                    <td class="py-2 px-3.5 text-center text-slate-400 font-mono-num text-[11px]">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-3.5">
                                        <span class="font-mono-num font-bold text-slate-700 dark:text-slate-300">{{ $d->kode_produk }}</span>
                                    </td>
                                    <td class="py-2 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $d->nama_produk }}</td>
                                    <td class="py-2 px-4 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">{{ $numFormat($d->jumlah) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t border-slate-200 dark:border-slate-800 bg-slate-50 font-bold text-xs sticky bottom-0 z-10">
                            <tr>
                                <td colspan="3" class="py-2 px-4 text-right uppercase tracking-wider text-slate-600 dark:text-slate-300">Total Dikirim:</td>
                                <td class="py-2 px-4 text-right font-mono-num text-sm font-black text-emerald-600 dark:text-emerald-400">{{ $numFormat($totalSj) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        @else
            <!-- Empty State Surat Jalan -->
            <div class="rounded-xl border border-amber-200/80 dark:border-amber-900/50 bg-amber-50/60 dark:bg-amber-950/30 p-3 sm:p-3.5 flex items-center gap-3">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <div class="text-xs">
                    <span class="font-bold text-amber-900 dark:text-amber-200 block">Surat Jalan Belum Diterbitkan</span>
                    <span class="text-amber-700 dark:text-amber-300 text-[11px] block mt-0.5">Permintaan kiriman ini masih menunggu verifikasi dan penerbitan surat jalan dari gudang jadi pusat.</span>
                </div>
            </div>
        @endif
    </div>

    <!-- ACTION CLOSE BUTTON -->
    <div class="flex items-center justify-end pt-2">
        <button type="button" class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>

</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
