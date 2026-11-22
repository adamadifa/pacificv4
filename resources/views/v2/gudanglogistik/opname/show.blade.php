<div class="space-y-4">
    @php
        $numFormat = function($val) {
            return number_format((float)($val ?? 0), 2, ',', '.');
        };
    @endphp
    <!-- INFO SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Kode Opname</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $opname->kode_opname }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $nama_bulan[$opname->bulan] }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tahun</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ $opname->tahun }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Kategori</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-[#284c9a] dark:bg-blue-900/40 dark:text-blue-300">
                    {{ $opname->nama_kategori }}
                </span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Input</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ DateToIndo($opname->tanggal) }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (Dua Baris Thead V2 Standard) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[440px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Title Strip (colspan=4) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="4" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Fisik Barang Opname</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($detail) }} item barang</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA BARANG</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-36">JUMLAH HASIL OPNAME</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $d->kode_barang }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_barang }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $numFormat($d->jumlah) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Tidak ada detail barang</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>
