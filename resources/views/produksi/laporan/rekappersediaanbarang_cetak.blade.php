<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Persediaan Barang Produksi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAP PERSEDIAAN BARANG PRODUKSI<br>
        </h4>
        <h4>PERIODE BULAN {{ $namabulan[$bulan] }} {{ $tahun }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">KODE BARANG</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th rowspan="2">KATEGORI BARANG</th>
                    <th rowspan="2">SALDO AWAL</th>
                    <th colspan="3" class="green">PEMASUKAN</th>
                    <th colspan="3" class="red">PENGELUARAN</th>
                    <th rowspan="3">SALDO AKHIR</th>
                </tr>
                <tr>
                    <th class="green">GUDANG</th>
                    <th class="green">SEASONING</th>
                    <th class="green">TRIAL</th>
                    <th class="red">PEMAKAIAN</th>
                    <th class="red">RETUR OUT</th>
                    <th class="red">LAINNYA</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_saldo_awal = 0;
                    $total_jml_in_gudang = 0;
                    $total_jml_in_seasoning = 0;
                    $total_jml_in_trial = 0;
                    $total_jml_out_pemakaian = 0;
                    $total_jml_out_retur = 0;
                    $total_jml_out_lainnya = 0;
                    $total_saldo_akhir = 0;
                @endphp
                @foreach ($rekappersediaanbarang as $d)
                    @php
                        $total_saldo_awal += $d->jml_saldo_awal;
                        $total_jml_in_gudang += $d->jml_in_gudang;
                        $total_jml_in_seasoning += $d->jml_in_seasoning;
                        $total_jml_in_trial += $d->jml_in_trial;
                        $total_jml_out_pemakaian += $d->jml_out_pemakaian;
                        $total_jml_out_retur += $d->jml_out_retur;
                        $total_jml_out_lainnya += $d->jml_out_lainnya;

                        $saldo_akhir =
                            $d->jml_saldo_awal +
                            $d->jml_in_gudang +
                            $d->jml_in_seasoning +
                            $d->jml_in_trial -
                            $d->jml_out_pemakaian -
                            $d->jml_out_retur -
                            $d->jml_out_lainnya;

                        $total_saldo_akhir += $saldo_akhir;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_barang_produksi }}</td>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->satuan }}</td>
                        <td>{{ $kategori_barang_produksi[$d->kode_kategori] }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_saldo_awal) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_in_gudang) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_in_seasoning) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_in_trial) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_out_pemakaian) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_out_retur) }}</td>
                        <td align="right">{{ formatAngkaDesimal($d->jml_out_lainnya) }}</td>
                        <td align="right">{{ formatAngkaDesimal($saldo_akhir) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <th colspan="5">TOTAL</th>
                <th class="right">{{ formatAngkaDesimal($total_saldo_awal) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_in_gudang) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_in_seasoning) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_in_trial) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_out_pemakaian) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_out_retur) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_jml_out_lainnya) }}</th>
                <th class="right">{{ formatAngkaDesimal($total_saldo_akhir) }}</th>
            </tfoot>
        </table>
    </div>
</body>
