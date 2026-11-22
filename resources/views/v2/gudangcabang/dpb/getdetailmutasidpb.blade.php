@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 0, ',', '.');
    };
@endphp

<div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
    <div class="overflow-x-auto max-h-[380px]">
        <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
            <thead class="sticky top-0 z-10">
                <!-- Row 1: Header Category Strip -->
                <tr class="bg-[#284c9a] text-white">
                    <th rowspan="2" class="py-2 px-3 text-center w-24 font-bold uppercase tracking-wider text-white border-r border-blue-800/60">KODE</th>
                    <th rowspan="2" class="py-2 px-3 font-bold uppercase tracking-wider text-white border-r border-blue-800/60 min-w-[140px]">NAMA PRODUK</th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-emerald-800">
                        RETUR
                    </th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-emerald-800">
                        HUTANG KIRIM
                    </th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-rose-900">
                        PENJUALAN
                    </th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-rose-900">
                        PELUNASAN HTG
                    </th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white border-r border-blue-800/60 bg-rose-900">
                        PROMOSI
                    </th>
                    <th colspan="3" class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white bg-rose-900">
                        GANTI BARANG
                    </th>
                </tr>
                <!-- Row 2: Sub-units (Dus, Pack, Pcs) -->
                <tr class="bg-[#1e3a78] text-white text-[10.5px] border-none font-bold">
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900 border-r border-blue-800/60">PCS</th>
                    
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-emerald-900 border-r border-blue-800/60">PCS</th>
                    
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950 border-r border-blue-800/60">PCS</th>
                    
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950 border-r border-blue-800/60">PCS</th>
                    
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950 border-r border-blue-800/60">PCS</th>
                    
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">DUS</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">PACK</th>
                    <th class="py-1 px-2 text-right w-14 bg-rose-950">PCS</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                @forelse ($mutasi_dpb as $d)
                    @php
                        // Retur
                        $retur = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_retur));
                        $retur_dus = $retur[0];
                        $retur_pack = $retur[1];
                        $retur_pcs = $retur[2];

                        // Hutang Kirim
                        $hutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_hutangkirim));
                        $hutangkirim_dus = $hutangkirim[0];
                        $hutangkirim_pack = $hutangkirim[1];
                        $hutangkirim_pcs = $hutangkirim[2];

                        // Penjualan
                        $penjualan = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_penjualan));
                        $penjualan_dus = $penjualan[0];
                        $penjualan_pack = $penjualan[1];
                        $penjualan_pcs = $penjualan[2];

                        // Pelunasan Hutang Kirim
                        $pelunasanhutangkirim = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_pelunasanhutangkirim));
                        $pelunasanhutangkirim_dus = $pelunasanhutangkirim[0];
                        $pelunasanhutangkirim_pack = $pelunasanhutangkirim[1];
                        $pelunasanhutangkirim_pcs = $pelunasanhutangkirim[2];

                        // Promosi
                        $promosi = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_promosi));
                        $promosi_dus = $promosi[0];
                        $promosi_pack = $promosi[1];
                        $promosi_pcs = $promosi[2];

                        // Ganti Barang
                        $gantibarang = explode('|', convertToduspackpcs($d->kode_produk, $d->jml_gantibarang));
                        $gantibarang_dus = $gantibarang[0];
                        $gantibarang_pack = $gantibarang[1];
                        $gantibarang_pcs = $gantibarang[2];
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                        <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</td>
                        <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-200">{{ $d->nama_produk }}</td>
                        
                        <!-- Retur -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($retur_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($retur_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($retur_pcs) }}</td>
                        
                        <!-- Hutang Kirim -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($hutangkirim_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">{{ $numFormat($hutangkirim_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($hutangkirim_pcs) }}</td>
                        
                        <!-- Penjualan -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($penjualan_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($penjualan_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($penjualan_pcs) }}</td>
                        
                        <!-- Pelunasan Hutang Kirim -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($pelunasanhutangkirim_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($pelunasanhutangkirim_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($pelunasanhutangkirim_pcs) }}</td>
                        
                        <!-- Promo -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($promosi_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($promosi_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10 border-r border-slate-200 dark:border-slate-800">{{ $numFormat($promosi_pcs) }}</td>
                        
                        <!-- Ganti Barang -->
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($gantibarang_dus) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($gantibarang_pack) }}</td>
                        <td class="py-1.5 px-2 text-right font-mono-num font-bold text-rose-700 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">{{ $numFormat($gantibarang_pcs) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="20" class="py-6 text-center text-slate-400">Belum ada transaksi mutasi untuk DPB ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
