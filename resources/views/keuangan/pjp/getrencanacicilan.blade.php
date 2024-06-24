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
    <tr>
        <td class="text-center">{{ $d->cicilan_ke }}</td>
        <td>{{ $namabulan[$d->bulan] }} {{ $d->tahun }}</td>
        <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
        <td class="text-end">{!! !empty($d->bayar) ? formatAngka($d->bayar) : ' <i class="ti ti-hourglass-empty text-warning"></i>' !!}</td>
        <td class="text-end">{{ formatAngka($sisatagihan) }}</td>
    </tr>
@endforeach
<tr class="table-dark">
    <td colspan="2">TOTAL</td>
    <td class="text-end">{{ formatAngka($totaltagihan) }}</td>
    <td class="text-end">{{ formatAngka($totalbayar) }}</td>
    <td class="text-end" id="sisatagihan">{{ formatAngka($totalsisatagihan) }}</td>
</tr>
