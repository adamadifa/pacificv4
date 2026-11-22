@php
    $numFormat = function($val) {
        return number_format((float)($val ?? 0), 2, ',', '.');
    };
    $totalItem = count($detail);
    $totalKuantiti = 0;
    foreach ($detail as $d) {
        $totalKuantiti += (float)($d->jumlah ?? 0);
    }
@endphp

<div class="space-y-4">
    <!-- STRIP INFORMASI DOKUMEN SURAT JALAN -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-800/40 p-3 sm:p-4">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- No. Surat Jalan -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Surat Jalan</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm">
                    {{ $surat_jalan->no_mutasi }}
                </span>
            </div>

            <!-- No. Dokumen -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Dokumen</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ $surat_jalan->no_dok }}
                </span>
            </div>

            <!-- Tanggal -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tanggal Kirim</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100">
                    {{ DateToIndo($surat_jalan->tanggal) }}
                </span>
            </div>

            <!-- Status -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Status</span>
                <div class="mt-0.5">
                    @if ($surat_jalan->status_surat_jalan == 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">
                            Belum Diterima Cabang
                        </span>
                    @elseif($surat_jalan->status_surat_jalan == 1)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">
                            Sudah Diterima Cabang
                        </span>
                    @elseif($surat_jalan->status_surat_jalan == 2)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400 border border-sky-200 dark:border-sky-800/50">
                            Transit Out
                        </span>
                    @endif
                </div>
            </div>

            <!-- No. Permintaan -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">No. Permintaan</span>
                <span class="font-bold font-mono-num text-slate-700 dark:text-slate-300">
                    {{ $surat_jalan->no_permintaan ?? '-' }}
                </span>
            </div>

            <!-- Tanggal Permintaan -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Tgl Permintaan</span>
                <span class="font-semibold text-slate-700 dark:text-slate-300">
                    {{ !empty($surat_jalan->tanggal_permintaan) ? DateToIndo($surat_jalan->tanggal_permintaan) : '-' }}
                </span>
            </div>

            <!-- Cabang Tujuan -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Cabang Tujuan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100">
                    {{ textUpperCase($surat_jalan->nama_cabang) }}
                </span>
            </div>

            <!-- Keterangan -->
            <div>
                <span class="block text-[11px] font-semibold text-slate-400">Keterangan</span>
                <span class="font-medium text-slate-600 dark:text-slate-400 truncate block" title="{{ $surat_jalan->keterangan }}">
                    {{ !empty($surat_jalan->keterangan) ? $surat_jalan->keterangan : '-' }}
                </span>
            </div>
        </div>
    </div>

    <!-- METRIC STRIP PACIFIC NAVY -->
    <div class="rounded-xl overflow-hidden shadow-xs bg-[#284c9a] text-white p-3.5 flex flex-wrap items-center justify-around gap-4">
        <div class="text-center">
            <span class="block text-[11px] font-semibold text-white/75 uppercase tracking-wider">Total Item Barang</span>
            <span class="text-lg font-black font-mono-num">{{ $totalItem }} Item</span>
        </div>
        <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
        <div class="text-center">
            <span class="block text-[11px] font-semibold text-white/75 uppercase tracking-wider">Total Kuantiti Kirim</span>
            <span class="text-lg font-black font-mono-num">{{ $numFormat($totalKuantiti) }}</span>
        </div>
    </div>

    <!-- TABEL RINCIAN PRODUK DUAL-ROW HEADER -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <!-- Baris 1: Strip Pacific Navy (#284c9a) -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="3" class="py-2 px-3.5">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                                    <i data-lucide="package" class="w-4 h-4 text-white/80"></i>
                                    <span>Rincian Produk Kiriman</span>
                                </div>
                                <span class="text-[11px] font-semibold text-white/80 font-mono-num">{{ $totalItem }} Item</span>
                            </div>
                        </th>
                    </tr>
                    <!-- Baris 2: Header Kolom Pacific Navy Pekat (#1e3a78) -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] font-bold uppercase tracking-wider">
                        <th class="py-1.5 px-3 w-32">Kode Produk</th>
                        <th class="py-1.5 px-3">Nama Produk</th>
                        <th class="py-1.5 px-3 text-right w-36">Jumlah Kirim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-700 dark:text-slate-300">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="py-1.5 px-3 font-mono-num font-bold text-[#284c9a] dark:text-blue-400">
                                {{ $d->kode_produk }}
                            </td>
                            <td class="py-1.5 px-3 font-medium text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-right font-mono-num font-bold text-slate-900 dark:text-white">
                                {{ $numFormat($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-6 text-center text-slate-400">
                                Tidak ada rincian produk kiriman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200 dark:border-slate-700 text-xs font-bold">
                    <tr>
                        <td colspan="2" class="py-2 px-3 text-slate-600 dark:text-slate-400 uppercase tracking-wider">Total Kuantiti</td>
                        <td class="py-2 px-3 text-right font-mono-num text-slate-900 dark:text-white">{{ $numFormat($totalKuantiti) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
