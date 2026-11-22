<div class="space-y-3.5">
    @php
        $numFormat = function($val) {
            return number_format((float)($val ?? 0), 2, ',', '.');
        };
    @endphp
    <!-- INFO STRIP / METRICS -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-900/40 p-3">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Bukti</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $barangkeluar->no_bukti }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Pengeluaran</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ DateToIndo($barangkeluar->tanggal) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Jenis Pengeluaran</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ $jenis_pengeluaran[$barangkeluar->kode_jenis_pengeluaran] ?? $barangkeluar->kode_jenis_pengeluaran }}
                </span>
            </div>
            @if ($barangkeluar->kode_jenis_pengeluaran == 'CBG')
                <div>
                    <span class="block text-[11px] font-semibold text-slate-400">Cabang Tujuan Utama</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($barangkeluar->nama_cabang) }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Row 1: Title Strip (colspan=7) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-4 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center gap-2">
                                <i data-lucide="list" class="w-4 h-4 text-blue-200"></i>
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Keluar</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Titles -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2.5 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-28">KODE</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2.5 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-none w-20">SATUAN</th>
                        <th class="py-2.5 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">JUMLAH</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none w-36">CABANG</th>
                        <th class="py-2.5 px-3 font-bold uppercase tracking-wider text-white/90 border-none">KETERANGAN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-500">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold font-mono-num text-[#284c9a]">{{ $d->kode_barang }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">{{ textCamelCase($d->nama_barang) }}</td>
                            <td class="py-1.5 px-3 text-center text-slate-500 dark:text-slate-400 uppercase">{{ $d->satuan }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">{{ $numFormat($d->jumlah) }}</td>
                            <td class="py-1.5 px-3 text-slate-700 dark:text-slate-300">{{ $d->nama_cabang ? textUpperCase($d->nama_cabang) : '-' }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-400">{{ $d->keterangan ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">
                                Tidak ada rincian barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>