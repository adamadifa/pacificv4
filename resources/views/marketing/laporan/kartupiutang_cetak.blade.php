<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Kartu Piutang {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    {{-- <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script> --}}
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
            LAPORAN KARTU PIUTANG <br>
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
                <th>No</th>
                <th>Tanggal</th>
                <th>Last Payment</th>
                <th>Usia</th>
                <th>Kategori AUP</th>
                <th>No Faktur</th>
                <th>Kode Pel.</th>
                <th>Nama Pelanggan</th>
                <th>Nama Sales</th>
                <th>Cabang</th>
                <th>Pasar/Daerah</th>
                <th>Hari</th>
                <th>Jatuh Tempo</th>
                <th>Total Piutang</th>
                <th>Saldo Awal</th>
                <th>Penjualan Bruto</th>
                {{-- <th>Pembelian Botol/Peti</th> --}}
                <th>Penyesuaian Harga</th>
                <th>Potongan Harga</th>
                <th>Potongan Istimewa</th>
                <th>PPN 11%</th>
                <th>Retur Penjualan</th>
                <th>Penjualan Netto</th>
                <th>Pembayaran</th>
                <th>Saldo Akhir</th>
            </thead>
            <tbody>
                @php
                    $total_piutang = 0;
                    $total_saldoawal = 0;
                    $total_bruto = 0;
                    $total_penyesuaian = 0;
                    $total_potongan = 0;
                    $total_potongan_istimewa = 0;
                    $total_ppn = 0;
                    $total_retur = 0;
                    $total_netto = 0;
                    $total_jmlbayar = 0;
                    $total_saldoakhir = 0;

                @endphp
                @foreach ($kartupiutang as $d)
                    @php
                        $penjualanbulanini = $d->bruto - $d->penyesuaian - $d->potongan - $d->potongan_istimewa + $d->ppn - $d->retur;
                        $saldo_akhir = $d->saldo_awal + $penjualanbulanini - $d->jmlbayar;

                        $total_piutang += $d->total_piutang;
                        $total_saldoawal += $d->saldo_awal;
                        $total_bruto += $d->bruto;
                        $total_penyesuaian += $d->penyesuaian;
                        $total_potongan += $d->potongan;
                        $total_potongan_istimewa += $d->potongan_istimewa;
                        $total_ppn += $d->ppn;
                        $total_retur += $d->retur;
                        $total_netto += $d->netto;
                        $total_jmlbayar += $d->jmlbayar;
                        $total_saldoakhir += $saldo_akhir;
                    @endphp
                    @php
                        if ($d->usia_piutang <= 15) {
                            $kategori = '1 s/d 15 Hari';
                        } elseif ($d->usia_piutang <= 30 and $d->usia_piutang > 15) {
                            $kategori = '16 Hari s/d 1 Bulan';
                        } elseif ($d->usia_piutang <= 60 and $d->usia_piutang > 30) {
                            $kategori = '> 1 Bulan s/d 2 Bulan';
                        } elseif ($d->usia_piutang <= 90 and $d->usia_piutang > 60) {
                            $kategori = '> 2 Bulan s/d 3 Bulan';
                        } elseif ($d->usia_piutang <= 180 and $d->usia_piutang > 90) {
                            $kategori = '> 3 Bulan s/d 6 Bulan';
                        } elseif ($d->usia_piutang > 180 and $d->usia_piutang <= 360) {
                            $kategori = '> 6 Bulan s/d 1 Tahun';
                        } elseif ($d->usia_piutang > 360 and $d->usia_piutang <= 720) {
                            $kategori = '> 1 Tahun s/d 2 Tahun';
                        } elseif ($d->usia_piutang > 360 and $d->usia_piutang <= 720) {
                            $kategori = '> 1 Tahun s/d 2 Tahun';
                        } elseif ($d->usia_piutang > 720) {
                            $kategori = 'Lebih 2 Tahun';
                        }
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td>{{ formatIndo($d->last_payment) }}</td>
                        <td class="center">{{ $d->usia_piutang }}</td>
                        <td class="center">{{ $kategori }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->kode_pelanggan }}</td>
                        <td>{{ textupperCase($d->nama_pelanggan) }}</td>
                        <td>{{ formatName($d->nama_salesman) }}</td>
                        <td>{{ textupperCase($d->kode_cabang_baru) }}</td>
                        <td>{{ textupperCase($d->nama_wilayah) }}</td>
                        <td>{{ textupperCase($d->hari) }}</td>
                        <td class="center">{{ $d->ljt }}</td>
                        <td class="right">{{ formatAngka($d->total_piutang) }}</td>
                        <td class="right">{{ formatAngka($d->saldo_awal) }}</td>
                        <td class="right">{{ formatAngka($d->bruto) }}</td>
                        <td class="right">{{ formatAngka($d->penyesuaian) }}</td>
                        <td class="right">{{ formatAngka($d->potongan) }}</td>
                        <td class="right">{{ formatAngka($d->potongan_istimewa) }}</td>
                        <td class="right">{{ formatAngka($d->ppn) }}</td>
                        <td class="right">{{ formatAngka($d->retur) }}</td>
                        <td class="right">{{ formatAngka($d->netto) }}</td>
                        <td class="right">{{ formatAngka($d->jmlbayar) }}</td>
                        <td class="right">{{ formatAngka($saldo_akhir) }}</td>

                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="13">TOTAL</th>
                    <th class="right">{{ formatAngka($total_piutang) }}</th>
                    <th class="right">{{ formatAngka($total_saldoawal) }}</th>
                    <th class="right">{{ formatAngka($total_bruto) }}</th>
                    <th class="right">{{ formatAngka($total_penyesuaian) }}</th>
                    <th class="right">{{ formatAngka($total_potongan) }}</th>
                    <th class="right">{{ formatAngka($total_potongan_istimewa) }}</th>
                    <th class="right">{{ formatAngka($total_ppn) }}</th>
                    <th class="right">{{ formatAngka($total_retur) }}</th>
                    <th class="right">{{ formatAngka($total_netto) }}</th>
                    <th class="right">{{ formatAngka($total_jmlbayar) }}</th>
                    <th class="right">{{ formatAngka($total_saldoakhir) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
{{-- <script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script> --}}
