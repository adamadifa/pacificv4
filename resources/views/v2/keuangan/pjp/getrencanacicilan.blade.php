@php
    $totaltagihan = 0;
    $totalbayar = 0;
    $totalsisatagihan = 0;
@endphp
@foreach ($rencanacicilan as $d)
    @php
        $totaltagihan += $d->jumlah;
        $totalbayar += $d->bayar;
        $sisatagihan = $d->jumlah - $d->bayar;
        $totalsisatagihan += $sisatagihan;
    @endphp
    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
        <td class="py-1.5 px-3 text-center font-mono-num font-bold text-slate-800 dark:text-slate-100">{{ $d->cicilan_ke }}</td>
        <td class="py-1.5 px-3 font-semibold text-slate-700 dark:text-slate-300">{{ $namabulan[$d->bulan] }} {{ $d->tahun }}</td>
        <td class="py-1.5 px-3 text-right font-mono-num font-semibold text-slate-800 dark:text-slate-200">{{ formatAngka($d->jumlah) }}</td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold text-emerald-600 dark:text-emerald-400">
            @if (!empty($d->bayar))
                {{ formatAngka($d->bayar) }}
            @else
                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-500">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    <span>-</span>
                </span>
            @endif
        </td>
        <td class="py-1.5 px-3 text-right font-mono-num font-bold {{ $sisatagihan > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-400' }}">
            {{ formatAngka($sisatagihan) }}
        </td>
    </tr>
@endforeach
<tr class="bg-slate-100 dark:bg-slate-800/60 font-bold">
    <td colspan="2" class="py-2 px-3 text-center uppercase tracking-wider text-xs text-slate-800 dark:text-slate-100">TOTAL</td>
    <td class="py-2 px-3 text-right font-mono-num text-xs text-slate-900 dark:text-white">{{ formatAngka($totaltagihan) }}</td>
    <td class="py-2 px-3 text-right font-mono-num text-xs text-emerald-600 dark:text-emerald-400">{{ formatAngka($totalbayar) }}</td>
    <td class="py-2 px-3 text-right font-mono-num text-xs text-rose-600 dark:text-rose-400" id="sisatagihan">{{ formatAngka($totalsisatagihan) }}</td>
</tr>
