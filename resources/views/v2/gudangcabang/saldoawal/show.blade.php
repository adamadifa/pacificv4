@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="space-y-4">
    <!-- INFO SUMMARY STRIP -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Kode Saldo Awal</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $saldo_awal->kode_saldo_awal }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $nama_bulan[$saldo_awal->bulan] ?? '-' }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tahun</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">{{ $saldo_awal->tahun }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Cabang</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ textUpperCase($saldo_awal->nama_cabang) }}</span>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Kondisi</span>
                <div class="mt-0.5">
                    @if ($saldo_awal->kondisi == 'GS')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            GOOD STOK
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-200/60 dark:border-rose-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            BAD STOK
                        </span>
                    @endif
                </div>
            </div>
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Input</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ DateToIndo($saldo_awal->tanggal) }}</span>
            </div>
        </div>
    </div>

    <!-- DETAIL ITEMS TABLE (Dua Baris Thead V2 Standard) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[440px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Row 1: Title Strip (colspan=6) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="6" class="px-4 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="boxes" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-white">Rincian Saldo Awal Gudang Cabang</span>
                                </div>
                                <span class="text-[11px] text-white/70 font-mono-num">{{ count($detail) }} produk</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Row 2: Column Headers -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">NO</th>
                        <th class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-none w-24">PCS</th>
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
                            <td class="py-1.5 px-3 text-center font-bold font-mono-num text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_produk }}</td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ $numFormat($jumlah_dus) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ $numFormat($jumlah_pack) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-800 dark:text-slate-100">
                                {{ $numFormat($jumlah_pcs) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Tidak ada detail produk</td>
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
