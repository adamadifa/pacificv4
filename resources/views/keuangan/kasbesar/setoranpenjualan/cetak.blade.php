<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setoran Penjualan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4>REKAP HARIAN KAS BESAR LAPORAN HARIAN PENJUALAN</h4>
        <h4>{{ $cabang != null ? textUpperCase($cabang->nama_pt) : '' }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="body">
        <table class="datatable3" border="1">
            <thead>
                <tr>
                    <th rowspan="2">TGL LHP</th>
                    <th rowspan="2">SALES</th>
                    <th rowspan="2" class="green">PENJUALAN TUNAI</th>
                    <th rowspan="2" class="green">TAGIHAN</th>
                    <th rowspan="2" class="green">TOTAL LHP</th>
                    <th colspan="5" class="red">SETORAN</th>
                    <th rowspan="2" class="red">TOTAL SETORAN</th>
                    <th rowspan="2" class="red">SELISIH</th>
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th class="red">U.KERTAS</th>
                    <th class="red">U.LOGAM</th>
                    <th class="red">BG/CEK</th>
                    <th class="red">TRANSFER</th>
                    <th class="red">LAINNYA</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subtotal_lhp_tunai = 0;
                    $subtotal_lhp_tagihan = 0;
                    $subtotal_total_lhp = 0;

                    $subtotal_setoran_kertas = 0;
                    $subtotal_setoran_logam = 0;
                    $subtotal_setoran_lainnya = 0;
                    $subtotal_setoran_transfer = 0;
                    $subtotal_setoran_giro = 0;
                    $subtotal_total_setoran = 0;
                    $subtotal_selisih = 0;

                @endphp
                @foreach ($setoran_penjualan as $key => $d)
                    @php
                        $next_tanggal = @$setoran_penjualan[$key + 1]->tanggal;
                        $total_lhp = $d->lhp_tunai + $d->lhp_tagihan;
                        $uk = $d->kurangsetorkertas - $d->lebihsetorkertas;
                        $ul = $d->kurangsetorlogam - $d->lebihsetorlogam;
                        $setoran_kertas = $d->setoran_kertas + $uk;
                        $setoran_logam = $d->setoran_logam + $ul;
                        $total_setoran = $setoran_kertas + $setoran_logam + $d->setoran_giro + $d->setoran_transfer + $d->setoran_lainnya;

                        $subtotal_lhp_tunai += $d->lhp_tunai;
                        $subtotal_lhp_tagihan += $d->lhp_tagihan;
                        $subtotal_total_lhp += $total_lhp;

                        $subtotal_setoran_kertas += $setoran_kertas;
                        $subtotal_setoran_logam += $setoran_logam;
                        $subtotal_setoran_lainnya += $d->setoran_lainnya;
                        $subtotal_setoran_transfer += $d->setoran_transfer;
                        $subtotal_setoran_giro += $d->setoran_giro;
                        $subtotal_total_setoran += $total_setoran;

                        $cek_tagihan = $d->cek_lhp_tagihan + $d->cek_lhp_giro + $d->cek_lhp_transfer;
                        $color_setoran_tunai = $d->lhp_tunai == $d->cek_lhp_tunai ? 'bg-success' : 'bg-danger';
                        $color_setoran_tagihan = $d->lhp_tagihan == $cek_tagihan ? 'bg-success' : 'bg-danger';
                        $cek_giro_to_cash_transfer = $d->cek_giro_to_cash_transfer;
                        $giro_to_cash_transfer = $d->giro_to_cash + $d->giro_to_transfer;

                        if (
                            $d->lhp_tunai == $d->cek_lhp_tunai &&
                            $d->lhp_tagihan == $cek_tagihan &&
                            $giro_to_cash_transfer == $cek_giro_to_cash_transfer
                        ) {
                            $color_total_lhp = 'bg-success';
                        } else {
                            $color_total_lhp = 'bg-danger';
                        }

                        if ($uk > 0) {
                            $opkertas = '+';
                        } else {
                            $opkertas = '+';
                        }

                        if ($ul > 0) {
                            $oplogam = '+';
                        } else {
                            $oplogam = '+';
                        }

                        $selisih = $total_setoran - $total_lhp;
                        $subtotal_selisih += $selisih;
                        $kontenkertas = formatRupiah($d->setoran_kertas) . $opkertas . formatRupiah($uk);
                        $kontenlogam = formatRupiah($d->setoran_logam) . $opkertas . formatRupiah($ul);
                    @endphp
                    <tr>
                        <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                        <td>
                            @php
                                $nama_salesman = explode(' ', $d->nama_salesman);
                                $nama_depan = $d->nama_salesman != 'NON SALES' ? $nama_salesman[0] : $d->nama_salesman;
                            @endphp
                            {{ $nama_depan }}
                        </td>
                        <td class="right">{{ formatAngka($d->lhp_tunai) }}</td>
                        <td class="right">{{ formatAngka($d->lhp_tagihan) }}</td>
                        <td class="right">{{ formatAngka($total_lhp) }}</td>
                        <td class="right">{{ formatAngka($setoran_kertas) }}</td>
                        <td class="right">{{ formatAngka($setoran_logam) }}</td>
                        <td class="right">{{ formatAngka($d->setoran_giro) }}</td>
                        <td class="right">{{ formatAngka($d->setoran_transfer) }}</td>
                        <td class="right">{{ formatAngka($d->setoran_lainnya) }}</td>
                        <td class="right">{{ formatAngka($total_setoran) }}</td>
                        <td class="right">{{ formatAngka($selisih) }}</td>
                        <td>{{ $d->keterangan }}</td>
                    </tr>
                    @if ($d->tanggal != $next_tanggal)
                        <tr>
                            <th colspan='2'>TOTAL</th>
                            <th class="right">{{ formatAngka($subtotal_lhp_tunai) }}</th>
                            <th class="right">{{ formatAngka($subtotal_lhp_tagihan) }}</th>
                            <th class="right">{{ formatAngka($subtotal_total_lhp) }}</th>
                            <th class="right">{{ formatAngka($subtotal_setoran_kertas) }}</th>
                            <th class="right">{{ formatAngka($subtotal_setoran_logam) }}</th>
                            <th class="right">{{ formatAngka($subtotal_setoran_giro) }}</th>
                            <th class="right">{{ formatAngka($subtotal_setoran_transfer) }}</th>
                            <th class="right">{{ formatAngka($subtotal_setoran_lainnya) }}
                            <th class="right">{{ formatAngka($subtotal_total_setoran) }}</th>
                            <th class="right">{{ formatAngka($subtotal_selisih) }}</th>
                            <th></th>

                        </tr>
                        @php
                            $subtotal_lhp_tunai = 0;
                            $subtotal_lhp_tagihan = 0;
                            $subtotal_total_lhp = 0;

                            $subtotal_setoran_kertas = 0;
                            $subtotal_setoran_logam = 0;
                            $subtotal_setoran_lainnya = 0;
                            $subtotal_setoran_transfer = 0;
                            $subtotal_setoran_giro = 0;
                            $subtotal_total_setoran = 0;
                        @endphp
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
