<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Penjualan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
        .freeze-table {
            height: auto;
            max-height: 830px;
            overflow: auto;
        }
    </style> --}}

    <style>
        .text-red {
            background-color: red;
            color: white;
        }

        .bg-terimauang {
            background-color: #199291 !important;
            color: white !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP PENJUALAN <br>
        </h4>
        <h4>PERIODE : {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        @if ($cabang != null)
            <h4>
                {{ textUpperCase($cabang->nama_cabang) }}
            </h4>
        @endif
        @if ($salesman != null)
            <h4>
                {{ textUpperCase($salesman->nama_salesman) }}
            </h4>
        @endif
    </div>
    <div class="content">
        <table class="datatable3" style="width: 200%">
            <thead>
                <tr>
                    <th rowspan="2">No.</th>
                    <th rowspan="2">Kode</th>
                    <th rowspan="2">Salesman</th>
                    <th colspan="{{ count($produk) }}">Produk</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Bruto</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Potongan</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Pot Istimewa</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Penyesuaian</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Dpp</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">PPN 11%</th>
                    <th rowspan="2" style="background-color: rgb(230, 143, 14)">Retur</th>
                    <th rowspan="2" style="background-color: rgba(3, 123, 21, 0.993)">Netto</th>
                    <th rowspan="2" style="background-color: rgba(3, 123, 21, 0.993)">Penerimaan Uang</th>
                    <th style="background-color: rgba(123, 3, 41, 0.993)" colspan="9">Voucher</th>
                    <th rowspan="2">Saldo Awal</th>
                    <th rowspan="2">Saldo Akhir</th>
                </tr>
                <tr>
                    @foreach ($produk as $p)
                        <th>{{ $p->kode_produk }}</th>
                    @endforeach
                    <th style="background-color:#cc2727">PP</th>
                    <th style="background-color:#cc2727">DP</th>
                    <th style="background-color:#cc2727">PPS</th>
                    <th style="background-color:#cc2727">PPHK</th>
                    <th style="background-color:#cc2727">SP</th>
                    <th style="background-color:#cc2727">KPBPB</th>
                    <th style="background-color:#cc2727">WAPU</th>
                    <th style="background-color:#cc2727">PPH22</th>
                    <th style="background-color:#cc2727">L</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $p)
                    @php
                        ${"subtotal_$p->kode_produk"} = 0;
                        ${"grandtotal_$p->kode_produk"} = 0;
                    @endphp
                @endforeach
                @php
                    $subtotal_bruto = 0;
                    $grandtotal_bruto = 0;
                    $subtotal_potongan = 0;
                    $grandtotal_potongan = 0;
                    $subtotal_penyesuaian = 0;
                    $grandtotal_penyesuaian = 0;
                    $subtotal_ppn = 0;
                    $grandtotal_ppn = 0;
                    $subtotal_potongan_istimewa = 0;
                    $grandtotal_potongan_istimewa = 0;
                    $subtotal_dpp = 0;
                    $grandtotal_dpp = 0;
                    $subtotal_retur = 0;
                    $grandtotal_retur = 0;
                    $subtotal_netto = 0;
                    $grandtotal_netto = 0;
                    $subtotal_penerimaanuang = 0;
                    $grandtotal_penerimaanuang = 0;

                    //Voucher
                    $subtotal_pp = 0;
                    $grandtotal_pp = 0;
                    $subtotal_dp = 0;
                    $grandtotal_dp = 0;
                    $subtotal_pps = 0;
                    $grandtotal_pps = 0;
                    $subtotal_pphk = 0;
                    $grandtotal_pphk = 0;
                    $subtotal_sp = 0;
                    $grandtotal_sp = 0;
                    $subtotal_kp = 0;
                    $grandtotal_kp = 0;
                    $subtotal_wapu = 0;
                    $grandtotal_wapu = 0;
                    $subtotal_pph22 = 0;
                    $grandtotal_pph22 = 0;
                    $subtotal_lain = 0;
                    $grandtotal_lain = 0;

                    $subtotal_saldoawal = 0;
                    $grandtotal_saldoawal = 0;
                    $subtotal_saldoakhir = 0;
                    $grandtotal_saldoakhir = 0;

                @endphp
                @foreach ($rekappenjualan as $key => $d)
                    @php
                        $cbg = @$rekappenjualan[$key + 1]['kode_cabang'];
                        $subtotal_bruto += $d['bruto'];
                        $grandtotal_bruto += $d['bruto'];

                        $subtotal_potongan += $d['potongan'];
                        $grandtotal_potongan += $d['potongan'];

                        $subtotal_penyesuaian += $d['penyesuaian'];
                        $grandtotal_penyesuaian += $d['penyesuaian'];

                        $subtotal_ppn += $d['ppn'];
                        $grandtotal_ppn += $d['ppn'];

                        $subtotal_potongan_istimewa += $d['potongan_istimewa'];
                        $grandtotal_potongan_istimewa += $d['potongan_istimewa'];

                        $dpp = $d['bruto'] - $d['potongan'] - $d['penyesuaian'] - $d['potongan_istimewa'];

                        $subtotal_dpp += $dpp;
                        $grandtotal_dpp += $dpp;

                        $subtotal_retur += $d['retur'];
                        $grandtotal_retur += $d['retur'];

                        $netto =
                            $d['bruto'] -
                            $d['potongan'] -
                            $d['penyesuaian'] -
                            $d['potongan_istimewa'] +
                            $d['ppn'] -
                            $d['retur'];

                        $subtotal_netto += $netto;
                        $grandtotal_netto += $netto;

                        $subtotal_penerimaanuang += $d['penerimaanuang'];
                        $grandtotal_penerimaanuang += $d['penerimaanuang'];

                        //Voucher
                        $subtotal_pp += $d['pp'];
                        $grandtotal_pp += $d['pp'];
                        $subtotal_dp += $d['dp'];
                        $grandtotal_dp += $d['dp'];
                        $subtotal_pps += $d['pps'];
                        $grandtotal_pps += $d['pps'];
                        $subtotal_pphk += $d['pphk'];
                        $grandtotal_pphk += $d['pphk'];
                        $subtotal_sp += $d['sp'];
                        $grandtotal_sp += $d['sp'];
                        $subtotal_kp += $d['kp'];
                        $grandtotal_kp += $d['kp'];
                        $subtotal_wapu += $d['wapu'];
                        $grandtotal_wapu += $d['wapu'];
                        $subtotal_pph22 += $d['pph22'];
                        $grandtotal_pph22 += $d['pph22'];
                        $subtotal_lain += $d['lain'];
                        $grandtotal_lain += $d['lain'];

                        $saldo_awal_piutang =
                            $d['saldoawalpiutang'] + $d['saldopiutangpindahan'] - $d['saldopiutangpindahkesaleslain'];
                        $saldo_akhir_piutang = $saldo_awal_piutang + $netto - $d['totalbayarpiutang'];
                        //$saldo_awal_piutang = $d['saldoawalpiutang'];
                        // $saldo_akhir_piutang = $d['totalbayarpiutang'];

                        $subtotal_saldoawal += $saldo_awal_piutang;
                        $grandtotal_saldoawal += $saldo_awal_piutang;

                        $subtotal_saldoakhir += $saldo_akhir_piutang;
                        $grandtotal_saldoakhir += $saldo_akhir_piutang;

                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d['kode_salesman'] }}</td>
                        <td>{{ textUpperCase($d['nama_salesman']) }}</td>
                        @foreach ($produk as $p)
                            <td class="right">
                                @php
                                    $bruto_produk = $d["bruto_$p->kode_produk"];
                                    ${"subtotal_$p->kode_produk"} += $bruto_produk;
                                    ${"grandtotal_$p->kode_produk"} += $bruto_produk;
                                @endphp
                                {{ formatAngka($bruto_produk) }}
                            </td>
                        @endforeach
                        <td class="right">{{ formatAngka($d['bruto']) }}</td>
                        <td class="right">{{ formatAngka($d['potongan']) }}</td>
                        <td class="right">{{ formatAngka($d['potongan_istimewa']) }}</td>
                        <td class="right">{{ formatAngka($d['penyesuaian']) }}</td>
                        <td class="right">{{ formatAngka($dpp) }}</td>
                        <td class="right">{{ formatAngka($d['ppn']) }}</td>
                        <td class="right">{{ formatAngka($d['retur']) }}</td>
                        <td class="right">{{ formatAngka($netto) }}</td>
                        <td class="right">{{ formatAngka($d['penerimaanuang']) }}</td>
                        <td class="right">{{ formatAngka($d['pp']) }}</td>
                        <td class="right">{{ formatAngka($d['dp']) }}</td>
                        <td class="right">{{ formatAngka($d['pps']) }}</td>
                        <td class="right">{{ formatAngka($d['pphk']) }}</td>
                        <td class="right">{{ formatAngka($d['sp']) }}</td>
                        <td class="right">{{ formatAngka($d['kp']) }}</td>
                        <td class="right">{{ formatAngka($d['wapu']) }}</td>
                        <td class="right">{{ formatAngka($d['pph22']) }}</td>
                        <td class="right">{{ formatAngka($d['lain']) }}</td>
                        <td class="right">
                            {{ formatAngka($saldo_awal_piutang) }}
                            {{-- ---
                            {{ $d['saldoawalpiutang'] . '+' . $d['saldopiutangpindahan'] . '-' . $d['saldopiutangpindahkesaleslain'] }} --}}
                        </td>
                        <td class="right">
                            {{-- {{ $d['saldoawalpiutang'] . '+' . $d['saldopiutangpindahan'] . '-' . $d['saldopiutangpindahkesaleslain'] . '+' . $netto . '-' . $d['totalbayarpiutang'] . '=' }} --}}

                            {{ formatAngka($saldo_akhir_piutang) }}
                            {{-- <br>
                            {{ $d['bruto'] . '-' . $d['potongan'] . '-' . $d['penyesuaian'] . '-' . $d['potongan_istimewa'] . '+' . $d['ppn'] . '-' . $d['retur'] . '=' . $netto }} --}}
                        </td>
                    </tr>
                    @if ($cbg != $d['kode_cabang'])
                        <tr>
                            <th colspan="3">TOTAL {{ $d['kode_cabang'] }}</th>
                            @foreach ($produk as $p)
                                <th class="right">{{ formatAngka(${"subtotal_$p->kode_produk"}) }}</th>
                                @php
                                    ${"subtotal_$p->kode_produk"} = 0;
                                @endphp
                            @endforeach
                            @php
                                $subtotals = [
                                    'bruto' => $subtotal_bruto,
                                    'potongan' => $subtotal_potongan,
                                    'potongan_istimewa' => $subtotal_potongan_istimewa,
                                    'penyesuaian' => $subtotal_penyesuaian,
                                    'dpp' => $subtotal_dpp,
                                    'ppn' => $subtotal_ppn,
                                    'retur' => $subtotal_retur,
                                    'netto' => $subtotal_netto,
                                    'penerimaan_uang' => $subtotal_penerimaanuang,
                                    'pp' => $subtotal_pp,
                                    'dp' => $subtotal_dp,
                                    'pps' => $subtotal_pps,
                                    'pphk' => $subtotal_pphk,
                                    'sp' => $subtotal_sp,
                                    'kp' => $subtotal_kp,
                                    'wapu' => $subtotal_wapu,
                                    'pph22' => $subtotal_pph22,
                                    'lain' => $subtotal_lain,
                                    'subtotal_saldoawal' => $subtotal_saldoawal,
                                    'subtotal_saldoakhir' => $subtotal_saldoakhir,
                                ];
                                $subtotal_bruto = 0;
                                $subtotal_potongan = 0;
                                $subtotal_potongan_istimewa = 0;
                                $subtotal_penyesuaian = 0;
                                $subtotal_dpp = 0;
                                $subtotal_ppn = 0;
                                $subtotal_retur = 0;
                                $subtotal_netto = 0;
                                $subtotal_penerimaanuang = 0;
                                $subtotal_pp = 0;
                                $subtotal_dp = 0;
                                $subtotal_pps = 0;
                                $subtotal_pphk = 0;
                                $subtotal_sp = 0;
                                $subtotal_kp = 0;
                                $subtotal_wapu = 0;
                                $subtotal_pph22 = 0;
                                $subtotal_lain = 0;
                                $subtotal_saldoawal = 0;
                                $subtotal_saldoakhir = 0;
                            @endphp
                            @foreach ($subtotals as $key => $subtotal)
                                <th class="right">{{ formatAngka($subtotal) }}</th>
                            @endforeach
                        </tr>
                    @endif
                @endforeach

            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">GRAND TOTAL</th>
                    @foreach ($produk as $p)
                        <th class="right">
                            {{ formatAngka(${"grandtotal_$p->kode_produk"}) }}
                        </th>
                    @endforeach
                    <th class="right">{{ formatAngka($grandtotal_bruto) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_potongan) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_potongan_istimewa) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_penyesuaian) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_dpp) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_ppn) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_retur) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_netto) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_penerimaanuang) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_pp) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_dp) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_pps) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_pphk) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_sp) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_kp) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_wapu) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_pph22) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_lain) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_saldoawal) }}</th>
                    <th class="right">{{ formatAngka($grandtotal_saldoakhir) }}</th>
                </tr>
            </tfoot>
        </table>

    </div>
</body>

</html>
