@php $namabulan = config('global.nama_bulan'); @endphp
<div class="space-y-3.5">
    <!-- INFO CARDS STRIP -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">No. Permintaan</span>
            <span class="text-xs font-black text-brand-600 dark:text-brand-400 font-mono-num block mt-0.5">
                {{ $pp->no_permintaan }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Kode OMAN</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ $pp->kode_oman }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Periode</span>
            <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block mt-0.5">
                {{ $namabulan[$pp->bulan] ?? $pp->bulan }} {{ $pp->tahun }}
            </span>
        </div>
        <div class="p-2.5 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Status Dokumen</span>
            <div class="mt-0.5">
                @if ($pp->status == 1)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Diproses Produksi
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Belum Diproses
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE -->
    <div class="rounded-lg border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap font-sans">
                <thead>
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="7" class="px-3.5 py-2.5 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Produk Permintaan Produksi</span>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ count($detail) }} Produk</span>
                            </div>
                        </th>
                    </tr>
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 text-center w-10 font-bold uppercase tracking-wider text-white/90">NO</th>
                        <th class="py-2 px-3 w-28 font-bold uppercase tracking-wider text-white/90">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">OMAN</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">STOK GUDANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">BUFFER STOK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        @php
                            $total = $d->oman_marketing - $d->stok_gudang + $d->buffer_stok;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition duration-150">
                            <td class="py-1.5 px-3 text-center text-slate-500 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-bold text-slate-700 dark:text-slate-300 font-mono-num">{{ $d->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-semibold text-slate-900 dark:text-white">{{ $d->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-200">{{ formatAngka($d->oman_marketing) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-200">{{ formatAngka($d->stok_gudang) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-700 dark:text-slate-200">{{ formatAngka($d->buffer_stok) }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-brand-600 dark:text-brand-400">{{ formatAngka($total) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400 font-medium">Tidak ada data rincian</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
