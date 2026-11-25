@php
    $subtotal_level_0 = 0;
    $level_0_name = '';

    $subtotal_level_1 = 0;
    $level_1_name = '';

    $subtotal_level_2 = 0;
    $level_2_name = '';

    $kode_akun_kas_bank = ['1-11', '1-12'];
    $subtotal_akun_kas_bank = 0;

    $kode_akun_hutang = 2;
    $subtotal_akun_hutang = 0;

    $kode_akun_modal = 3;
    $subtotal_akun_modal = 0;
@endphp

@foreach ($neraca as $index => $d)
    @php
        $indent = ($d->level ?? 0) * 20;
        $next_level = $neraca[$index + 1]->level ?? null;
        $next_before_level = $neraca[$index - 1]->level ?? null;
        $next_kode_akun = $neraca[$index + 1]->kode_akun ?? null;

        if ($d->kode_akun == '3-2000') {
            $saldo_akhir = $d->saldo_akhir + $net_profit_loss;
        } else {
            $saldo_akhir = $d->saldo_akhir;
        }

        if ($d->level == 0) {
            $level_0_name = $d->nama_akun;
        }
        $subtotal_level_0 += $saldo_akhir;

        if ($d->level == 1) {
            $level_1_name = $d->nama_akun;
        }
        $subtotal_level_1 += $saldo_akhir;

        if ($d->level == 2) {
            $level_2_name = $d->nama_akun;
        }
        $subtotal_level_2 += $saldo_akhir;

        if (in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank)) {
            $subtotal_akun_kas_bank += $saldo_akhir;
        }

        if (substr($d->kode_akun, 0, 1) == $kode_akun_hutang) {
            $subtotal_akun_hutang += $saldo_akhir;
        }

        if (substr($d->kode_akun, 0, 1) == $kode_akun_modal) {
            $subtotal_akun_modal += $saldo_akhir;
        }
    @endphp

    @if (
        ($saldo_akhir == 0 && $d->level == 1 && $next_level == 1) ||
            ($saldo_akhir == 0 && $d->level == 2 && $next_level == 2) ||
            ($saldo_akhir == 0 && $d->level == 1 && $next_level == 0) ||
            ($saldo_akhir == 0 && $d->level == 3))
    @else
        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
            <td class="py-1.5 pr-3 text-slate-700 dark:text-slate-200" style="padding-left: {{ 12 + $indent }}px;">
                @if ($d->level == 0 || $d->level == 1 || $d->level == 2)
                    <span class="font-bold text-slate-900 dark:text-white">{{ $d->kode_akun }} {{ $d->nama_akun }}</span>
                @else
                    <span>{{ $d->kode_akun }} {{ $d->nama_akun }}</span>
                @endif
                <input type="hidden" name="kode_akun[]" value="{{ $d->kode_akun }}">
                <input type="hidden" name="jumlah[]" value="{{ $saldo_akhir }}">
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num text-slate-800 dark:text-slate-100">
                @if ($d->level == 0 || $d->level == 1)
                    <span class="font-bold">{{ formatAngka($saldo_akhir) }}</span>
                @else
                    {{ formatAngka($saldo_akhir) }}
                @endif
            </td>
        </tr>
    @endif

    @if (
        ($subtotal_level_2 != 0 && $next_level == 2 && $d->level == 2) ||
            ($subtotal_level_2 != 0 && $next_level == 2 && $d->level == 3) ||
            ($subtotal_level_2 != 0 && $next_level == 1 && $d->level == 3) ||
            ($subtotal_level_2 != 0 && $next_level == 1 && $d->level == 2) ||
            ($subtotal_level_2 != 0 && $next_level == 0 && $d->level == 3))
        <tr class="subtotal-row bg-slate-50/90 dark:bg-slate-800/50 font-bold text-slate-800 dark:text-slate-100">
            <td class="py-1.5 pr-3" style="padding-left: 52px;">
                SUBTOTAL {{ strtoupper($level_2_name) }}
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                {{ formatAngka($subtotal_level_2) }}
            </td>
        </tr>
        @php
            $subtotal_level_2 = 0;
            $level_2_name = '';
        @endphp
    @endif

    @if (
        ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 3) ||
            ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 3) ||
            ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 2) ||
            ($subtotal_level_1 != 0 && $next_level == 1 && $d->level == 1) ||
            ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 1) ||
            ($subtotal_level_1 != 0 && $next_level == 0 && $d->level == 2))
        <tr class="subtotal-row bg-slate-100/80 dark:bg-slate-800/70 font-bold text-slate-800 dark:text-slate-100">
            <td class="py-1.5 pr-3" style="padding-left: 32px;">
                SUBTOTAL {{ strtoupper($level_1_name) }}
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                {{ formatAngka($subtotal_level_1) }}
            </td>
        </tr>
        @php
            $subtotal_level_1 = 0;
            $level_1_name = '';
        @endphp
    @endif

    @if ($next_level == 0)
        <tr class="subtotal-row bg-blue-50/70 dark:bg-blue-950/30 font-bold text-[#284c9a] dark:text-blue-300">
            <td class="py-1.5 px-3">
                SUBTOTAL {{ strtoupper($level_0_name) }}
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                {{ formatAngka($subtotal_level_0) }}
            </td>
        </tr>
        @php
            $subtotal_level_0 = 0;
            $level_0_name = '';
        @endphp
    @endif

    @if (!in_array(substr($next_kode_akun, 0, 4), $kode_akun_kas_bank) && in_array(substr($d->kode_akun, 0, 4), $kode_akun_kas_bank))
        <tr class="subtotal-row bg-slate-100/80 dark:bg-slate-800/70 font-bold text-slate-800 dark:text-slate-100">
            <td class="py-1.5 px-3">
                SUBTOTAL KAS BANK
            </td>
            <td class="py-1.5 px-3 text-right font-mono-num">
                {{ formatAngka($subtotal_akun_kas_bank) }}
            </td>
        </tr>
    @endif
@endforeach

<tr class="subtotal-row bg-blue-50/90 dark:bg-blue-950/40 font-black text-[#284c9a] dark:text-blue-300">
    <td class="py-1.5 px-3">
        TOTAL PASIVA
    </td>
    <td class="py-1.5 px-3 text-right font-mono-num">
        @php
            $total_pasiva = $subtotal_akun_hutang + $subtotal_akun_modal;
        @endphp
        {{ formatAngka($total_pasiva) }}
    </td>
</tr>
