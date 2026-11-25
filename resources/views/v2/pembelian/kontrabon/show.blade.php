@php
    $total = 0;
    foreach ($detail as $d) {
        $total += $d->jumlah;
    }
@endphp

<div class="space-y-3.5">
    <!-- 1. DOKUMEN RINGKASAN TERPADU (EXECUTIVE SUMMARY STRIP V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Dokumen Kontra Bon</h5>
            </div>
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                {{ $kontrabon->no_kontrabon }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal Dokumen</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block font-mono-num">{{ DateToIndo($kontrabon->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Terima Dari (Supplier)</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate" title="{{ textUpperCase($kontrabon->nama_supplier) }}">
                    {{ textUpperCase($kontrabon->nama_supplier) }}
                </span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Jenis Pengajuan</span>
                <div class="mt-0.5 flex items-center gap-1.5">
                    @if ($kontrabon->kategori == 'KB')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                            Kontra Bon (KB)
                        </span>
                    @elseif ($kontrabon->kategori == 'IM')
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200 dark:border-purple-800/60">
                            Internal Memo (IM)
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                            Tunai (TN)
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Status Pembayaran</span>
                <div class="mt-0.5 flex items-center gap-1.5">
                    @if (!empty($kontrabon->tglbayar))
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400" title="{{ $kontrabon->nama_bank ? 'Via ' . $kontrabon->nama_bank : '' }}">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Lunas ({{ DateToIndo($kontrabon->tglbayar) }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-600 dark:text-amber-400">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> Belum Diproses
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if (!empty($kontrabon->no_dokumen) || !empty($kontrabon->jenis_bayar))
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs pt-2.5 mt-2.5 border-t border-slate-200/60 dark:border-slate-800/60">
                @if (!empty($kontrabon->no_dokumen))
                    <div>
                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">No. Dokumen</span>
                        <span class="font-bold text-slate-700 dark:text-slate-300 mt-0.5 block font-mono-num">{{ $kontrabon->no_dokumen }}</span>
                    </div>
                @endif
                @if (!empty($kontrabon->jenis_bayar))
                    <div>
                        <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Metode Pembayaran</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300 mt-0.5 block">
                            {{ $kontrabon->jenis_bayar == 'TN' ? 'Tunai' : 'Transfer Bank' }}
                        </span>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- 2. METRIC STRIP REKAPITULASI (PACIFIC NAVY UNIFIED STRIP) -->
    <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white relative">
        <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

        <div class="grid grid-cols-2 divide-x divide-white/15">
            <!-- Total Faktur -->
            <div class="p-3 sm:p-3.5 flex flex-col justify-between">
                <span class="text-[10px] uppercase font-bold tracking-wider text-white/70">Total Faktur Pembelian</span>
                <div class="mt-1 text-base sm:text-lg font-black text-white font-mono-num leading-tight flex items-baseline gap-1.5">
                    <span>{{ count($detail) }}</span>
                    <span class="text-xs font-semibold text-white/75">Dokumen Terkait</span>
                </div>
            </div>

            <!-- Total Nilai Kontra Bon -->
            <div class="p-3 sm:p-3.5 flex flex-col justify-between bg-white/[0.08]">
                <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-200">Total Nilai Kontra Bon</span>
                <div class="mt-1 text-base sm:text-lg font-black text-emerald-300 font-mono-num leading-tight">
                    <span class="text-xs font-bold text-emerald-200/80 mr-1">Rp</span>{{ formatAngkaDesimal($total) }}
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABEL DETAIL FAKTUR PEMBELIAN (~36px Compact) -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="receipt" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Rincian Faktur Pembelian</span>
                                </div>
                                <span class="text-[10.5px] text-white/80 font-mono-num">
                                    Total: {{ count($detail) }} Faktur Terlampir
                                </span>
                            </div>
                        </th>
                    </tr>
                    <!-- Baris 2: Kolom Header -->
                    <tr class="bg-[#1e3a78] text-white">
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-center border-r border-[#284c9a]/40" style="width: 8%">NO</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40" style="width: 25%">TANGGAL</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 border-r border-[#284c9a]/40">NO. BUKTI</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-white/90 text-right" style="width: 28%">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($detail as $d)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition cursor-pointer btnShowpembelian group"
                            no_bukti="{{ Crypt::encrypt($d->no_bukti) }}" title="Klik untuk lihat detail rincian pembelian">
                            <td class="py-1.5 px-3 text-center font-bold text-slate-500 border-r border-slate-100 dark:border-slate-800">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-mono-num text-slate-700 dark:text-slate-300">
                                {{ DateToIndo($d->tanggal) }}
                            </td>
                            <td class="py-1.5 px-3 border-r border-slate-100 dark:border-slate-800 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                <div class="inline-flex items-center gap-1.5 group-hover:underline">
                                    <span>{{ $d->no_bukti }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-400 group-hover:text-[#284c9a]"></i>
                                </div>
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-700">
                    <tr>
                        <td colspan="3" class="py-2 px-3 text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">TOTAL PEMBAYARAN</td>
                        <td class="py-2 px-3 text-right text-xs font-mono-num font-black text-[#284c9a] dark:text-blue-400">
                            {{ formatAngkaDesimal($total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
