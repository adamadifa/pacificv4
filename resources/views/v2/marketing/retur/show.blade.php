<div class="space-y-4">
    <!-- EXECUTIVE SUMMARY STRIP (INFORMASI DOKUMEN RETUR) -->
    <div class="rounded-xl bg-slate-50/80 dark:bg-slate-800/40 border border-slate-200/80 dark:border-slate-700/60 p-3 sm:p-3.5">
        <div class="flex items-center justify-between pb-2 mb-2.5 border-b border-slate-200/80 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="file-text" class="w-4 h-4 text-[#284c9a] dark:text-blue-400"></i>
                <span class="text-xs font-bold text-slate-800 dark:text-slate-100">Informasi Dokumen Retur</span>
            </div>
            @if ($retur->jenis_retur == 'GB')
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    <span>GANTI BARANG</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                    <span>POTONG FAKTUR</span>
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs">
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">No. Retur</span>
                <span class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $retur->no_retur }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">Tanggal Retur</span>
                <span class="font-medium text-slate-700 dark:text-slate-200">{{ DateToIndo($retur->tanggal) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">No. Faktur Asal</span>
                <span class="font-mono-num font-semibold text-slate-800 dark:text-slate-100">{{ $retur->no_faktur }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">Cabang</span>
                <span class="font-semibold text-slate-800 dark:text-slate-100">{{ textUpperCase($retur->nama_cabang) }}</span>
            </div>
            <div class="sm:col-span-2">
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">Pelanggan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">{{ $retur->kode_pelanggan }} - {{ textUpperCase($retur->nama_pelanggan) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">Salesman</span>
                <span class="font-semibold text-slate-700 dark:text-slate-200">{{ textUpperCase($retur->nama_salesman) }}</span>
            </div>
            <div>
                <span class="text-[11px] font-medium text-slate-400 dark:text-slate-400 block mb-0.5">Alamat</span>
                <span class="text-slate-600 dark:text-slate-300 truncate block" title="{{ $retur->alamat_pelanggan }}">{{ ucwords(strtolower($retur->alamat_pelanggan)) }}</span>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN PRODUK RETUR (KOMPAK ~36px) -->
    <div class="rounded-xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left border-separate border-spacing-0 whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="9" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="package" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Rincian Barang Retur</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">
                                    Total: {{ count($detail) }} Item
                                </span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Header Kolom -->
                    <tr class="bg-[#1e3a78] text-white text-[11px]">
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 w-24">KODE</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-b border-blue-900 min-w-[200px]">NAMA PRODUK</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">DUS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA / DUS</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">PACK</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA / PACK</th>
                        <th class="py-2 px-3 text-center font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">PCS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">HARGA / PCS</th>
                        <th class="py-2 px-3 text-right font-bold uppercase tracking-wider text-white/90 border-b border-blue-900">TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($detail as $d)
                        @php
                            $jumlah = explode('|', convertToduspackpcsv2($d->isi_pcs_dus, $d->isi_pcs_pack, $d->jumlah));
                            $jumlah_dus = $jumlah[0];
                            $jumlah_pack = $jumlah[1];
                            $jumlah_pcs = $jumlah[2];
                            $total += $d->subtotal;
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num {{ $jumlah_dus > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300 dark:text-slate-600' }}">
                                {{ $jumlah_dus > 0 ? formatAngka($jumlah_dus) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-300">
                                {{ formatAngka($d->harga_dus) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num {{ $jumlah_pack > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300 dark:text-slate-600' }}">
                                {{ $jumlah_pack > 0 ? formatAngka($jumlah_pack) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-300">
                                {{ formatAngka($d->harga_pack) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num {{ $jumlah_pcs > 0 ? 'font-bold text-slate-800 dark:text-slate-200' : 'text-slate-300 dark:text-slate-600' }}">
                                {{ $jumlah_pcs > 0 ? formatAngka($jumlah_pcs) : '-' }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num text-slate-600 dark:text-slate-300">
                                {{ formatAngka($d->harga_pcs) }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-slate-100">
                                {{ formatAngka($d->subtotal) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <!-- Baris Subtotal / Total -->
                    <tr class="bg-slate-100 dark:bg-slate-800/80 font-bold border-t border-slate-200 dark:border-slate-700">
                        <td colspan="8" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-700 dark:text-slate-200">
                            TOTAL
                        </td>
                        <td class="py-2 px-3 text-right font-mono-num font-bold text-sm text-[#284c9a] dark:text-blue-400">
                            {{ formatAngka($total) }}
                        </td>
                    </tr>
                    
                    <!-- Baris Status Jenis Retur -->
                    <tr>
                        @php
                            if ($retur->jenis_retur == 'GB') {
                                $bannerBg = 'bg-emerald-600 text-white';
                                $bannerKet = 'GANTI BARANG';
                                $iconName = 'refresh-cw';
                            } else {
                                $bannerBg = 'bg-rose-600 text-white';
                                $bannerKet = 'POTONG FAKTUR';
                                $iconName = 'scissors';
                            }
                        @endphp
                        <td colspan="9" class="{{ $bannerBg }} py-2 px-4 text-center font-bold tracking-widest text-xs uppercase shadow-inner">
                            <div class="flex items-center justify-center gap-2">
                                <i data-lucide="{{ $iconName }}" class="w-4 h-4"></i>
                                <span>{{ $bannerKet }}</span>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
