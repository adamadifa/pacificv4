@php
    $total_debet = 0;
    $total_kredit = 0;
    $saldo = 0;
    $saldo_awal_kredit = 0;
    $saldo_awal_debet = 0;
    $first = $bukubesar->first();
@endphp
@if ($first)
    <tr>
        <td colspan="7" style="height: 40px; border:none; background:none;"></td>
    </tr>
    <tr>
        <td style="text-align: left; font-size:18px; border:none; background:none; font-weight:bold;" colspan="7">
            Akun : {{ $first->kode_akun }} - {{ $first->nama_akun }}
        </td>
    </tr>
    <tr>
        <th style="font-size:12px; width: 10%;">TGL</th>
        <th style="font-size:12px; width: 15%;">NO BUKTI</th>
        <th style="font-size:12px; width: 15%;">SUMBER</th>
        <th style="font-size:12px; width: 30%;">KETERANGAN</th>
        <th style="font-size:12px; width: 10%;">DEBET</th>
        <th style="font-size:12px; width: 10%;">KREDIT</th>
        <th style="font-size:12px; width: 10%;">SALDO</th>
    </tr>

    @foreach ($bukubesar as $d)
        @php
            if ($d->jenis_akun == '1') {
                $saldo += $d->jml_kredit - $d->jml_debet;
            } else {
                $saldo += $d->jml_debet - $d->jml_kredit;
            }
            $total_debet += $d->jml_debet;
            $total_kredit += $d->jml_kredit;
        @endphp

        @if ($d->sumber == 'SALDO AWAL')
            @if ($d->jenis_akun == '1')
                @php
                    $saldo_awal_kredit = $saldo;
                    $saldo_awal_debet = 0;
                @endphp
            @else
                @php
                    $saldo_awal_kredit = 0;
                    $saldo_awal_debet = $saldo;
                @endphp
            @endif
            <tr class="thead-dark">
                <th colspan="6">SALDO AWAL</th>
                <th style="text-align: right;">{{ formatAngkaDesimal(abs($saldo)) }}</th>
            </tr>
        @else
            <tr>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ $d->no_bukti }}</td>
                <td>{{ textUpperCase($d->sumber) }}</td>
                <td>{{ textCamelCase($d->keterangan) }}</td>
                <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_debet) }}</td>
                <td style="text-align: right;">{{ formatAngkaDesimal($d->jml_kredit) }}</td>
                <td style="text-align: right;">{{ formatAngkaDesimal(abs($saldo)) }}</td>
            </tr>
        @endif
    @endforeach

    <tr class="thead-dark">
        <th colspan="4">TOTAL {{ $first->kode_akun }} - {{ $first->nama_akun }}</th>
        <th style="text-align: right;">{{ formatAngkaDesimal($total_debet - $saldo_awal_debet) }}</th>
        <th style="text-align: right;">{{ formatAngkaDesimal($total_kredit - $saldo_awal_kredit) }}</th>
        <th style="text-align: right;">{{ formatAngkaDesimal(abs($saldo)) }}</th>
    </tr>
@endif
