@php
    $canHarga = auth()->user()->can('pembelian.harga');
    $total_pembelian = 0;
    foreach ($detail as $d) {
        $subtotal = $d->jumlah * $d->harga;
        $total = $subtotal + $d->penyesuaian;
        $total_pembelian += $total;
    }
    $total_potongan = 0;
    if (isset($potongan)) {
        foreach ($potongan as $d) {
            $subtotal = $d->jumlah * $d->harga;
            $total_potongan += $subtotal;
        }
    }
    $penyesuaian_jk = $pembelian->penyesuaian_jk ?? 0;
    $grand_total = $total_pembelian - $total_potongan + $penyesuaian_jk;
@endphp

<div class="space-y-4">
    
    <!-- 1. EXECUTIVE SUMMARY STRIP / DOKUMEN RINGKASAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3 sm:p-3.5 shadow-2xs">
        <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Bukti Pembelian</h5>
            </div>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                {{ $pembelian->no_bukti }}
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Tanggal Pembelian</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block font-mono-num">{{ DateToIndo($pembelian->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Nama Supplier</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block truncate" title="{{ $pembelian->nama_supplier }}">{{ $pembelian->nama_supplier }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Asal Pengajuan</span>
                <span class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 block">
                    {{ array_key_exists($pembelian->kode_asal_pengajuan, $asal_pengajuan) ? $asal_pengajuan[$pembelian->kode_asal_pengajuan] : ($pembelian->kode_asal_pengajuan ?? '-') }}
                </span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-500 block">Faktur Pajak (PPN)</span>
                <div class="mt-0.5 flex items-center gap-1.5">
                    @if ($pembelian->ppn == '1')
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> PPN 11%
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-slate-500 dark:text-slate-400">
                            <i data-lucide="minus-circle" class="w-3.5 h-3.5"></i> Non PPN
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($canHarga)
        <!-- 2. METRIC STRIP REKAPITULASI (PACIFIC NAVY UNIFIED STRIP) -->
        <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white relative">
            <div class="absolute inset-0 bg-radial from-white/[0.04] to-transparent pointer-events-none"></div>

            <div class="grid grid-cols-2 sm:grid-cols-4 divide-y sm:divide-y-0 sm:divide-x divide-white/15">
                
                <!-- Total Pembelian -->
                <div class="p-3 sm:p-3.5 flex flex-col justify-between">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-white/60">Total Bruto</span>
                    <div class="mt-1 text-base sm:text-lg font-black text-white font-mono-num leading-tight">
                        {{ formatAngkaDesimal($total_pembelian) }}
                    </div>
                </div>

                <!-- Total Potongan -->
                <div class="p-3 sm:p-3.5 flex flex-col justify-between">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-rose-200/80">Potongan</span>
                    <div class="mt-1 text-base sm:text-lg font-black text-rose-200 font-mono-num leading-tight">
                        {{ formatAngkaDesimal($total_potongan) }}
                    </div>
                </div>

                <!-- Penyesuaian JK -->
                <div class="p-3 sm:p-3.5 flex flex-col justify-between">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-amber-200/80">Peny. Jurnal</span>
                    <div class="mt-1 text-base sm:text-lg font-black text-amber-200 font-mono-num leading-tight">
                        {{ formatAngkaDesimal($penyesuaian_jk) }}
                    </div>
                </div>

                <!-- Grand Total -->
                <div class="p-3 sm:p-3.5 flex flex-col justify-between bg-white/[0.08]">
                    <span class="text-[10px] uppercase font-bold tracking-wider text-emerald-200">Grand Total Netto</span>
                    <div class="mt-1 text-base sm:text-lg font-black text-emerald-300 font-mono-num leading-tight">
                        {{ formatAngkaDesimal($grand_total) }}
                    </div>
                </div>

            </div>
        </div>
    @endif

    <!-- 3. TABEL DATA BARANG PEMBELIAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Judul Tabel -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="{{ $canHarga ? 8 : 4 }}" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white uppercase tracking-wider">Rincian Barang Pembelian</span>
                                </div>
                                <span class="text-[10.5px] text-white/80 font-mono-num font-semibold">
                                    Total: {{ count($detail) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[180px]">NAMA BARANG</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[160px]">KETERANGAN</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-20">QTY</th>
                        @if ($canHarga)
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">HARGA</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[100px]">SUBTOTAL</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[90px]">PENY</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[110px]">TOTAL</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                    @forelse ($detail as $d)
                        @php
                            $subtotal = $d->jumlah * $d->harga;
                            $total = $subtotal + $d->penyesuaian;
                            $hasCR = !empty($d->kode_cr);
                        @endphp
                        <tr class="{{ $hasCR ? 'bg-sky-50/70 hover:bg-sky-100/70 dark:bg-sky-950/40 dark:hover:bg-sky-900/50' : 'hover:bg-slate-50/80 dark:hover:bg-slate-800/50' }} transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-semibold text-slate-800 dark:text-slate-200">
                                {{ $d->kode_barang }}
                                @if ($hasCR)
                                    <span class="ml-1 px-1 py-0.2 rounded text-[9px] font-bold bg-sky-200 text-sky-800">CR</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-slate-100">
                                {{ textCamelCase($d->nama_barang) }}
                            </td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ textCamelCase($d->keterangan) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                            @if ($canHarga)
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($d->harga) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($subtotal) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($d->penyesuaian) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                    {{ formatAngkaDesimal($total) }}
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canHarga ? 8 : 4 }}" class="py-6 text-center text-slate-400">
                                Tidak ada rincian barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if ($canHarga)
                    <tfoot>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 font-bold border-t border-slate-200 dark:border-slate-800">
                            <td colspan="7" class="py-2 px-3 text-right uppercase text-slate-700 dark:text-slate-300 text-[11px]">
                                Total Pembelian:
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-slate-100 text-xs">
                                {{ formatAngkaDesimal($total_pembelian) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if ($canHarga && isset($potongan) && count($potongan) > 0)
        <!-- 4. TABEL POTONGAN PEMBELIAN -->
        <div class="rounded-xl border border-rose-200/80 dark:border-rose-900/50 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                    <thead>
                        <tr class="bg-rose-700 text-white">
                            <th colspan="4" class="px-3.5 py-2 bg-rose-700 text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="tag" class="w-3.5 h-3.5 text-rose-200"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">Potongan Pembelian</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/80 font-mono-num font-semibold">
                                        Total: {{ count($potongan) }} Item
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-rose-800 text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-rose-900">KETERANGAN</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-rose-900 w-24">QTY</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-rose-900 min-w-[100px]">HARGA</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-rose-900 min-w-[110px]">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach ($potongan as $d)
                            @php
                                $subtotal = $d->jumlah * $d->harga;
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                                    {{ textCamelCase($d->keterangan_penjualan) }}
                                </td>
                                <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                    {{ formatAngkaDesimal($d->jumlah) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-300">
                                    {{ formatAngkaDesimal($d->harga) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-rose-600 dark:text-rose-400">
                                    {{ formatAngkaDesimal($subtotal) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-rose-50/50 dark:bg-rose-950/20 font-bold border-t border-rose-200 dark:border-rose-900/50">
                            <td colspan="3" class="py-2 px-3 text-right uppercase text-rose-800 dark:text-rose-300 text-[11px]">
                                Total Potongan:
                            </td>
                            <td class="py-2 px-3 text-right font-mono-num text-rose-700 dark:text-rose-300 text-xs">
                                {{ formatAngkaDesimal($total_potongan) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif

    @if ($canHarga && isset($kontrabon) && count($kontrabon) > 0)
        <!-- 5. TABEL HISTORI KONTRABON -->
        <div class="rounded-xl border border-emerald-200/80 dark:border-emerald-900/50 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                    <thead>
                        <tr class="bg-emerald-700 text-white">
                            <th colspan="5" class="px-3.5 py-2 bg-emerald-700 text-white border-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="history" class="w-3.5 h-3.5 text-emerald-200"></i>
                                        <span class="text-xs font-bold text-white uppercase tracking-wider">Histori Kontrabon</span>
                                    </div>
                                    <span class="text-[10.5px] text-white/80 font-mono-num font-semibold">
                                        Total: {{ count($kontrabon) }} Kontrabon
                                    </span>
                                </div>
                            </th>
                        </tr>
                        <tr class="bg-emerald-800 text-white text-[11px]">
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-emerald-900">NO. KONTRABON</th>
                            <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-emerald-900">TANGGAL</th>
                            <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-emerald-900">JUMLAH</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-emerald-900">KATEGORI</th>
                            <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-emerald-900">STATUS BAYAR</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @foreach ($kontrabon as $d)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="py-1.5 px-3 font-mono-num font-bold text-slate-800 dark:text-slate-200">
                                    {{ $d->no_kontrabon }}
                                </td>
                                <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">
                                    {{ DateToIndo($d->tanggal_kontrabon) }}
                                </td>
                                <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ formatAngkaDesimal($d->jumlah) }}
                                </td>
                                <td class="py-1.5 px-3 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300">
                                        {{ $d->kategori == 'TN' ? 'TUNAI' : textUpperCase($d->kategori) }}
                                    </span>
                                </td>
                                <td class="py-1.5 px-3 text-center">
                                    @if (!empty($d->tanggal_bayar))
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60">
                                            <i data-lucide="check" class="w-3 h-3"></i> {{ DateToIndo($d->tanggal_bayar) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/80 dark:border-rose-900/60">
                                            Belum Bayar
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
