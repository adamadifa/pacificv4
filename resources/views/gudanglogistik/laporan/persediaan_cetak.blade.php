<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Logistik {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN PERSEDIAAN GUDANG LOGISTIK<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>{{ $kategori != null ? 'KATEGORI : ' . textUpperCase($kategori->nama_kategori) : '' }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr bgcolor="#024a75">
                    <th>NO</th>
                    <th>KODE</th>
                    <th>NAMA BARANG</th>
                    <th>SATUAN</th>
                    <th>GROUP</th>
                    <th>SALDO AWAL</th>
                    <th>MASUK</th>
                    <th>KELUAR</th>
                    <th>SALDO AKHIR</th>
                    <th>OPNAME</th>
                    <th>SELISIH</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_saldo_awal = 0;
                    $total_bm_jumlah = 0;
                    $total_bk_jumlah = 0;
                    $total_saldo_akhir = 0;
                    $total_opname = 0;
                    $total_selisih = 0;
                @endphp
                @foreach ($persediaan as $d)
                    @php
                        $saldo_akhir = $d->saldo_awal_qty + $d->bm_jumlah - $d->bk_jumlah;
                        $selisih = ROUND($d->opname_qty, 2) - ROUND($saldo_akhir, 2);
                        $total_saldo_awal += $d->saldo_awal_qty;
                        $total_bm_jumlah += $d->bm_jumlah;
                        $total_bk_jumlah += $d->bk_jumlah;
                        $total_saldo_akhir += $saldo_akhir;
                        $total_opname += $d->opname_qty;
                        $total_selisih += $selisih;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_barang }}</td>
                        <td>{{ textUpperCase($d->nama_barang) }}</td>
                        <td>{{ textUpperCase($d->satuan) }}</td>
                        <td>{{ textUpperCase($group[$d->kode_group]) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->saldo_awal_qty) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->bm_jumlah) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->bk_jumlah) }}</td>
                        <td class="right">{{ formatAngkaDesimal($saldo_akhir) }}</td>
                        <td class="right">{{ formatAngkaDesimal($d->opname_qty) }}</td>
                        <td class="right">{{ formatAngkaDesimal($selisih) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="4">TOTAL</th>
                <th class="right">{{ formatAngkaDesimal($total_saldo_awal) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_bm_jumlah) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_bk_jumlah) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_saldo_akhir) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_opname) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_selisih) }}</th>
            </tfoot>
        </table>
    </div>
</body>
