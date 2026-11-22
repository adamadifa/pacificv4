<div class="space-y-3.5">
    <!-- INFO CARDS STRIP -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">No. Bukti</span>
            <span class="text-xs font-black text-brand-600 dark:text-brand-400 font-mono-num block mt-0.5">
                {{ $barangkeluarproduksi->no_bukti }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Tanggal Keluar</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ DateToIndo($barangkeluarproduksi->tanggal) }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Jenis Pengeluaran</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ $jenis_pengeluaran[$barangkeluarproduksi->kode_jenis_pengeluaran] ?? $barangkeluarproduksi->kode_jenis_pengeluaran }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Supplier</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ $barangkeluarproduksi->nama_supplier ?? '-' }}
            </span>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap font-sans">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-3.5 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Keluar</span>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ count($detail) }} Barang</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 text-center w-10 font-bold uppercase tracking-wider text-white/90">NO</th>
                        <th class="py-2 px-3 w-32 font-bold uppercase tracking-wider text-white/90">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA BARANG</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90">SATUAN</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">KETERANGAN</th>
                        <th class="py-2 px-3 text-right w-32 font-bold uppercase tracking-wider text-white/90">JUMLAH</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <td class="py-1.5 px-3 text-center text-slate-500 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $d->kode_barang_produksi }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $d->nama_barang }}</td>
                            <td class="py-1.5 px-3 text-center text-slate-600 dark:text-slate-300">{{ $d->satuan }}</td>
                            <td class="py-1.5 px-3 text-slate-600 dark:text-slate-300">{{ $d->keterangan ?? '-' }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">
                                {{ formatAngkaDesimal($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Tidak ada rincian data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
