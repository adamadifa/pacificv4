@php
    $namaBulan = !empty($namabulan[$oman->bulan]) ? $namabulan[$oman->bulan] : $oman->bulan;
    $totM1 = 0;
    $totM2 = 0;
    $totM3 = 0;
    $totM4 = 0;
    $grandTotal = 0;
    foreach ($detail as $d) {
        $totM1 += (float) $d->minggu_1;
        $totM2 += (float) $d->minggu_2;
        $totM3 += (float) $d->minggu_3;
        $totM4 += (float) $d->minggu_4;
        $grandTotal += (float) $d->total;
    }
@endphp

<div class="space-y-4">
    <!-- HEADER INFORMASI TERPADU (STANDAR V2) -->
    <div
        class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-900/40 p-3 sm:p-3.5">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
            <!-- 1. Kode OMAN -->
            <div>
                <span
                    class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Kode
                    OMAN</span>
                <span class="font-bold font-mono-num text-[#284c9a] dark:text-blue-400 text-sm block">
                    {{ $oman->kode_oman }}
                </span>
            </div>

            <!-- 2. Periode Bulan -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span
                    class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Bulan</span>
                <span class="font-bold text-slate-800 dark:text-slate-100 text-xs block">
                    {{ $namaBulan }}
                </span>
            </div>

            <!-- 3. Tahun -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span
                    class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Tahun</span>
                <span class="font-bold font-mono-num text-slate-800 dark:text-slate-100 text-xs block">
                    {{ $oman->tahun }}
                </span>
            </div>

            <!-- 4. Status Dokumen -->
            <div class="sm:border-l sm:border-slate-200/80 sm:dark:border-slate-800 sm:pl-3.5">
                <span
                    class="block text-[10.5px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-0.5">Status</span>
                <div>
                    @if ($oman->status_oman === '1')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Sudah di Proses Gudang
                        </span>
                    @elseif ($oman->status_oman === '2')
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-900/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Sudah di Proses Produksi
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10.5px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/80 dark:border-amber-900/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Belum di Proses
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL RINCIAN PRODUK (STANDAR ~36px) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto max-h-[420px]">
            <table class="w-full text-xs text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 z-10">
                    <!-- Baris 1: Strip Header Pacific Navy -->
                    <tr class="bg-[#284c9a] text-white">
                        <th colspan="8" class="px-3.5 py-2 bg-[#284c9a] text-white border-0">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                                    <span class="text-xs font-bold text-white">Daftar Permintaan OMAN Marketing</span>
                                </div>
                                <span class="text-[10.5px] text-white/70 font-mono-num">{{ count($detail) }} Item
                                    Produk</span>
                            </div>
                        </th>
                    </tr>

                    <!-- Baris 2: Kolom Header -->
                    <tr class="bg-[#1e3a78] text-white text-[11px] border-none">
                        <th
                            class="py-2 px-3 text-center w-12 font-bold uppercase tracking-wider text-white/90 border-none">
                            NO</th>
                        <th
                            class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">
                            KODE PRODUK</th>
                        <th class="py-2 px-3 font-bold uppercase tracking-wider text-white/90 border-none">NAMA PRODUK
                        </th>
                        <th
                            class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">
                            MINGGU 1</th>
                        <th
                            class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">
                            MINGGU 2</th>
                        <th
                            class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">
                            MINGGU 3</th>
                        <th
                            class="py-2 px-3 text-center w-28 font-bold uppercase tracking-wider text-white/90 border-none">
                            MINGGU 4</th>
                        <th
                            class="py-2 px-3 text-center w-32 font-bold uppercase tracking-wider text-amber-300 border-none">
                            TOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900">
                    @forelse ($detail as $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 text-center">
                                <span
                                    class="font-mono-num font-bold text-[#284c9a] dark:text-blue-400">{{ $d->kode_produk }}</span>
                            </td>
                            <td class="py-1.5 px-3 font-semibold text-slate-800 dark:text-slate-100">
                                {{ $d->nama_produk }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->minggu_1) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->minggu_2) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->minggu_3) }}
                            </td>
                            <td class="py-1.5 px-3 text-center font-mono-num text-slate-700 dark:text-slate-300">
                                {{ formatAngka($d->minggu_4) }}
                            </td>
                            <td
                                class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-900 dark:text-slate-100 bg-slate-50/50 dark:bg-slate-800/30">
                                {{ formatAngka($d->total) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <div class="flex flex-col items-center justify-center gap-1.5">
                                    <i data-lucide="inbox" class="w-6 h-6 stroke-1 text-slate-300 dark:text-slate-600"></i>
                                    <span class="text-xs font-medium">Data rincian produk OMAN tidak ditemukan</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if (count($detail) > 0)
                    <tfoot
                        class="bg-slate-50 dark:bg-slate-800/90 font-bold border-t-2 border-slate-200 dark:border-slate-700 text-xs">
                        <tr>
                            <td colspan="3"
                                class="py-2 px-3 text-right text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                                Total Permintaan:
                            </td>
                            <td class="py-2 px-3 text-center font-mono-num text-slate-900 dark:text-slate-100">
                                {{ formatAngka($totM1) }}
                            </td>
                            <td class="py-2 px-3 text-center font-mono-num text-slate-900 dark:text-slate-100">
                                {{ formatAngka($totM2) }}
                            </td>
                            <td class="py-2 px-3 text-center font-mono-num text-slate-900 dark:text-slate-100">
                                {{ formatAngka($totM3) }}
                            </td>
                            <td class="py-2 px-3 text-center font-mono-num text-slate-900 dark:text-slate-100">
                                {{ formatAngka($totM4) }}
                            </td>
                            <td
                                class="py-2 px-3 text-center font-mono-num font-black text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/30">
                                {{ formatAngka($grandTotal) }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- ACTION FOOTER -->
    <div class="pt-2 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end">
        <button type="button"
            class="btn-close-modal h-[34px] px-4 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 text-xs font-bold transition cursor-pointer">
            Tutup
        </button>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>