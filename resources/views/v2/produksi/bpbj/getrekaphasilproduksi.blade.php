@php
    $monthlyTotals = array_fill(1, 12, 0);
    $grandTotal = 0;
    $currentMonth = (int) date('n');
@endphp

@forelse ($rekap as $d)
    @php
        $rowTotal = 0;
        $rowValues = [];
        for ($m = 1; $m <= 12; $m++) {
            $v = (int) ($d->{$nama_bulan_singkat[$m]} ?? 0);
            $rowValues[$m] = $v;
            $rowTotal += $v;
            $monthlyTotals[$m] += $v;
        }
        $grandTotal += $rowTotal;
    @endphp
    <tr class="group hover:bg-brand-50/40 dark:hover:bg-slate-800/60 transition-colors duration-150">
        <!-- Sticky Produk Column (Left) -->
        <td class="sticky left-0 bg-white dark:bg-slate-900 group-hover:bg-brand-50/90 dark:group-hover:bg-slate-800 px-3 py-2 font-bold text-slate-800 dark:text-slate-100 border-r border-slate-200/80 dark:border-slate-800 z-10 whitespace-nowrap min-w-[125px] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.06)]">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-500 group-hover:scale-125 transition-transform shrink-0"></span>
                <span class="font-mono-num tracking-tight">{{ $d->kode_produk }}</span>
            </div>
        </td>

        <!-- 12 Bulan Output Values -->
        @for ($i = 1; $i <= 12; $i++)
            @php
                $currentVal = $rowValues[$i];
                $prevVal = $i > 1 ? $rowValues[$i - 1] : null;
                $isCurrentMonth = $i === $currentMonth;

                $color = 'text-slate-700 dark:text-slate-300';
                $trend = null;

                if ($prevVal !== null && $currentVal > 0 && $prevVal > 0) {
                    if ($currentVal > $prevVal) {
                        $color = 'text-emerald-600 dark:text-emerald-400 font-semibold';
                        $trend = 'up';
                    } elseif ($currentVal < $prevVal) {
                        $color = 'text-rose-600 dark:text-rose-400 font-semibold';
                        $trend = 'down';
                    }
                } elseif ($currentVal == 0) {
                    $color = 'text-slate-300 dark:text-slate-600 font-normal';
                }
            @endphp
            <td class="px-2 py-2 text-right border-r border-slate-100 dark:border-slate-800/70 font-mono-num text-[11px] min-w-[66px] {{ $isCurrentMonth ? 'bg-amber-500/[0.04] dark:bg-amber-400/[0.03]' : '' }}">
                <div class="flex items-center justify-end gap-1 leading-tight">
                    @if ($currentVal > 0)
                        <span class="{{ $color }}">{{ formatAngka($currentVal) }}</span>
                        @if ($trend === 'up')
                            <span class="text-[9px] text-emerald-500 font-black leading-none select-none">▲</span>
                        @elseif ($trend === 'down')
                            <span class="text-[9px] text-rose-500 font-black leading-none select-none">▼</span>
                        @endif
                    @else
                        <span class="text-slate-300 dark:text-slate-600 text-xs">—</span>
                    @endif
                </div>
            </td>
        @endfor

        <!-- Sticky Total Column (Right) -->
        <td class="sticky right-0 bg-slate-50/90 dark:bg-slate-900 group-hover:bg-brand-50/80 dark:group-hover:bg-slate-800 px-3 py-2 text-right font-mono-num font-bold text-brand-700 dark:text-brand-300 border-l border-slate-200/80 dark:border-slate-800 z-10 whitespace-nowrap min-w-[105px] shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.06)] text-[11px]">
            <div class="flex items-center justify-end gap-1.5">
                <span>{{ formatAngka($rowTotal) }}</span>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="14" class="py-10 text-center text-slate-400">
            <div class="flex flex-col items-center justify-center">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-300 mb-2"></i>
                <span class="text-xs">Tidak ada data hasil produksi pada tahun yang dipilih.</span>
            </div>
        </td>
    </tr>
@endforelse

@if (count($rekap) > 0)
    <!-- SUMMARY ROW: TOTAL KESELURUHAN (PINNED FOOTER STYLE) -->
    <tr class="bg-slate-100/90 dark:bg-slate-800/90 font-black text-slate-900 dark:text-white border-t-2 border-slate-300 dark:border-slate-700 sticky bottom-0 z-20 shadow-[0_-2px_6px_-2px_rgba(0,0,0,0.1)]">
        <!-- Sticky Title (Left) -->
        <td class="sticky left-0 bg-slate-100 dark:bg-slate-800 px-3 py-2.5 uppercase tracking-wider text-[11px] font-black border-r border-slate-300 dark:border-slate-700 z-30 shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)] whitespace-nowrap">
            <div class="flex items-center gap-1.5 text-[#284c9a] dark:text-brand-400">
                <i data-lucide="sigma" class="w-3.5 h-3.5"></i>
                <span>TOTAL</span>
            </div>
        </td>

        <!-- Monthly Column Totals -->
        @for ($i = 1; $i <= 12; $i++)
            @php
                $mTotal = $monthlyTotals[$i];
                $isCurrentMonth = $i === $currentMonth;
            @endphp
            <td class="px-2 py-2.5 text-right font-mono-num text-[11px] font-bold border-r border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 {{ $isCurrentMonth ? 'bg-amber-500/10 text-amber-700 dark:text-amber-300' : '' }}">
                {{ $mTotal > 0 ? formatAngka($mTotal) : '—' }}
            </td>
        @endfor

        <!-- Grand Total (Right) -->
        <td class="sticky right-0 bg-[#284c9a] text-white px-3 py-2.5 text-right font-mono-num text-xs font-black z-30 shadow-[-2px_0_6px_-2px_rgba(0,0,0,0.2)] whitespace-nowrap">
            {{ formatAngka($grandTotal) }}
        </td>
    </tr>
@endif
