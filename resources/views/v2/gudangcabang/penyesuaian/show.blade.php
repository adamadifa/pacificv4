@php
    $in_out = $mutasi->jenis_mutasi == 'PY' ? $mutasi->in_out_good : $mutasi->in_out_bad;
@endphp

<div class="space-y-4">
    <!-- DOCUMENT INFO STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Nomor Mutasi</span>
                <span class="text-xs font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $mutasi->no_mutasi }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Tanggal</span>
                <span class="text-xs font-mono-num font-semibold text-slate-700 dark:text-slate-200">{{ DateToIndo($mutasi->tanggal) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Cabang</span>
                <span class="text-xs font-semibold text-slate-800 dark:text-slate-100 uppercase">{{ $mutasi->nama_cabang }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Jenis Mutasi</span>
                @if ($mutasi->jenis_mutasi == 'PY')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                        GOOD STOK
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                        BAD STOK
                    </span>
                @endif
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">IN / OUT</span>
                @if ($in_out == 'I')
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-800">
                        IN (Masuk)
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-800">
                        OUT (Keluar)
                    </span>
                @endif
            </div>
            <div>
                <span class="block text-[11px] font-medium text-slate-400">Keterangan</span>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300">{{ $mutasi->keterangan ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[400px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Barang Penyesuaian</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($detail) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-28">PCS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-700 dark:text-slate-200">
                                {{ formatAngka($jumlah_dus) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-700 dark:text-slate-200">
                                {{ formatAngka($jumlah_pack) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-700 dark:text-slate-200">
                                {{ formatAngka($jumlah_pcs) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada rincian produk
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ACTION FOOTER -->
    <div class="flex items-center justify-end pt-2">
        <button type="button" class="btn-close-modal h-[36px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>
