@php
    $total = 0;
    foreach ($detail as $d) {
        $total += $d->jumlah;
    }
@endphp

<div class="space-y-3.5">
    <!-- 1. DOKUMEN RINGKASAN TERPADU (EXECUTIVE SUMMARY STRIP V2) -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 p-3.5 shadow-2xs space-y-3">
        <!-- Header Strip -->
        <div class="flex flex-wrap items-center justify-between gap-2 pb-2.5 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-[#284c9a] dark:text-blue-400 flex items-center justify-center shrink-0">
                    <i data-lucide="receipt" class="w-4 h-4"></i>
                </div>
                <h5 class="text-xs font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">Informasi Pembayaran Transfer</h5>
            </div>
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold font-mono-num bg-blue-50 text-[#284c9a] dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/80 dark:border-blue-900/60">
                    {{ $transfer->kode_transfer }}
                </span>
                @if ($transfer->status == '1')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                        <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i> Disetujui
                    </span>
                @elseif ($transfer->status == '2')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                        <i data-lucide="x-circle" class="w-3 h-3 text-rose-600"></i> Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                        <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i> Pending
                    </span>
                @endif
            </div>
        </div>

        <!-- Tier 1: Pelanggan & Total Pembayaran Transfer -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Kolom Kiri: Pelanggan (Lebar & Bebas Truncate) -->
            <div class="sm:col-span-7">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Pelanggan</span>
                <div class="flex items-start gap-2.5">
                    <div class="p-1.5 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-[#284c9a] dark:text-blue-400 shrink-0 mt-0.5">
                        <i data-lucide="store" class="w-4 h-4"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-100 leading-snug">
                            {{ textUpperCase($transfer->nama_pelanggan) }}
                        </div>
                        <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono-num font-bold bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700">
                                {{ $transfer->kode_pelanggan }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Total Pembayaran Transfer -->
            <div class="sm:col-span-5 rounded-lg bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 p-2.5 flex items-center justify-between sm:flex-col sm:items-end sm:justify-center">
                <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Transfer</span>
                <span class="text-base sm:text-lg font-black font-mono-num text-[#284c9a] dark:text-blue-400 tracking-tight block">
                    Rp {{ formatAngka($total) }}
                </span>
                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 block mt-0.5">
                    {{ count($detail) }} Faktur Terbayar
                </span>
            </div>
        </div>

        <!-- Tier 2: Metadata Grid (Tanggal Transfer, Bank Pengirim, Jatuh Tempo, Status/Ledger) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-800">
            <!-- Tanggal Transfer -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Tanggal Bayar</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono-num text-xs mt-1 block truncate">
                        {{ DateToIndo($transfer->tanggal) }}
                    </span>
                </div>
            </div>

            <!-- Bank Pengirim -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="landmark" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Bank Pengirim</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs mt-1 block truncate" title="{{ textUpperCase($transfer->bank_pengirim) }}">
                        {{ textUpperCase($transfer->bank_pengirim) }}
                    </span>
                </div>
            </div>

            <!-- Tanggal Jatuh Tempo -->
            <div class="flex items-center gap-2">
                <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-400 shrink-0">
                    <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                </div>
                <div class="min-w-0">
                    <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Jatuh Tempo</span>
                    <span class="font-bold text-slate-800 dark:text-slate-200 font-mono-num text-xs mt-1 block truncate">
                        {{ DateToIndo($transfer->jatuh_tempo) }}
                    </span>
                </div>
            </div>

            <!-- Bukti Ledger / Status -->
            <div class="flex items-center gap-2">
                @if ($transfer->status == '1' && !empty($transfer->no_bukti))
                    <div class="p-1.5 rounded-md bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 shrink-0">
                        <i data-lucide="file-check-2" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Bukti Ledger</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200 font-mono-num text-xs mt-1 block truncate">
                            {{ $transfer->no_bukti }}
                        </span>
                    </div>
                @else
                    <div class="p-1.5 rounded-md bg-slate-50 dark:bg-slate-800 text-slate-300 dark:text-slate-600 shrink-0">
                        <i data-lucide="arrow-left-right" class="w-3.5 h-3.5"></i>
                    </div>
                    <div class="min-w-0">
                        <span class="text-[10px] font-medium text-slate-400 dark:text-slate-500 block leading-none">Metode</span>
                        <span class="text-xs text-slate-500 mt-1 block">Transfer Bank</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3. TABEL RINCIAN FAKTUR -->
    <div class="rounded-xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 overflow-hidden shadow-2xs">
        <div class="px-3.5 py-2 bg-[#284c9a] flex items-center justify-between text-white">
            <div class="flex items-center gap-2">
                <i data-lucide="layers" class="w-3.5 h-3.5 text-blue-200"></i>
                <h6 class="text-xs font-bold text-white uppercase tracking-wider">Rincian Faktur Terbayar</h6>
            </div>
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-white/10 text-white border border-white/20">
                {{ count($detail) }} Faktur
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead class="bg-[#1e3a78] text-white/90">
                    <tr>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider w-12 text-center">No</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider">No. Faktur</th>
                        <th class="py-2 px-3 text-[11px] font-bold uppercase tracking-wider text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse ($detail as $index => $d)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="py-1.5 px-3 text-center text-slate-400 font-mono-num">{{ $loop->iteration }}</td>
                            <td class="py-1.5 px-3 font-semibold text-brand-600 dark:text-brand-400 font-mono-num">
                                <a href="{{ route('penjualan.show', Crypt::encrypt($d->no_faktur)) }}" target="_blank" class="hover:underline inline-flex items-center gap-1">
                                    <span>{{ $d->no_faktur }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3 text-slate-400"></i>
                                </a>
                            </td>
                            <td class="py-1.5 px-3 text-right font-bold font-mono-num text-slate-900 dark:text-white">
                                {{ formatAngka($d->jumlah) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-slate-400">Tidak ada rincian faktur</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t border-slate-200/80 dark:border-slate-700">
                    <tr>
                        <td colspan="2" class="py-2 px-3 text-right text-xs uppercase tracking-wider text-slate-600 dark:text-slate-300">Total</td>
                        <td class="py-2 px-3 text-right font-mono-num text-sm text-[#284c9a] dark:text-blue-400">
                            {{ formatAngka($total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
