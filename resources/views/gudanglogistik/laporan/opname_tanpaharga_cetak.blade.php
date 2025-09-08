<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Opname Gudang Logistik {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN OPNAME GUDANG LOGISTIK<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>{{ $kategori != null ? 'KATEGORI : ' . textUpperCase($kategori->nama_kategori) : '' }}</h4>
    </div>
    <div class="content">
        <table class="datatable3">
            <thead>
                <tr bgcolor="#024a75">
                    <th rowspan="2">NO</th>
                    <th rowspan="2">KODE</th>
                    <th rowspan="2">NAMA BARANG</th>
                    <th rowspan="2">SATUAN</th>
                    <th rowspan="2">SALDO AWAL</th>
                    <th rowspan="2">MASUK</th>
                    <th rowspan="2">KELUAR</th>
                    <th rowspan="2">SALDO AKHIR</th>
                    <th rowspan="2">OPNAME</th>
                    <th rowspan="2">SELISIH</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_saldo_awal = 0;
                    $total_saldo_awal_totalharga = 0;

                    $total_bm_jumlah = 0;
                    $total_bm_totalharga = 0;

                    $total_bk_jumlah = 0;
                    $total_bk_totalharga = 0;

                    $total_saldo_akhir = 0;
                    $total_saldo_akhir_totalharga = 0;

                    $total_opname = 0;
                    $total_selisih = 0;
                    $no = 0;
                @endphp
                @foreach ($persediaan as $d)
                    @if (!empty($d->bm_jumlah) || !empty($d->bk_jumlah))
                        @php
                            $jml_barang_masuk = $d->saldo_awal_qty + $d->bm_jumlah;
                            $jml_barang_masuk_qty = !empty($jml_barang_masuk) ? $jml_barang_masuk : 1;

                            if (empty($d->bm_harga)) {
                                $harga_masuk = $d->bm_harga;
                            } else {
                                $harga_masuk = $d->bm_totalharga / $d->bm_jumlah;
                            }
                            if (empty($d->saldo_awal_harga)) {
                                $harga_keluar = $harga_masuk;
                                $cek = 1;
                            } elseif (empty($d->bm_harga)) {
                                $harga_keluar = $d->saldo_awal_harga;
                                $cek = 2;
                            } else {
                                $cek = 3;
                                $harga_keluar =
                                    ($d->saldo_awal_totalharga + $d->bm_totalharga + $d->bm_penyesuaian) /
                                    $jml_barang_masuk_qty;
                            }

                            $saldo_akhir = $d->saldo_awal_qty + $d->bm_jumlah - $d->bk_jumlah;
                            $selisih = ROUND($saldo_akhir, 2) - ROUND($d->opname_qty, 2);
                            $total_saldo_awal += $d->saldo_awal_qty;
                            $total_saldo_awal_totalharga += $d->saldo_awal_totalharga;

                            $bm_totalharga = $d->bm_totalharga + $d->bm_penyesuaian;
                            $total_bm_jumlah += $d->bm_jumlah;
                            $total_bm_totalharga += $bm_totalharga;

                            $bk_totalharga = $d->bk_jumlah * $harga_keluar;
                            $total_bk_jumlah += $d->bk_jumlah;
                            $total_bk_totalharga += $bk_totalharga;

                            $total_saldo_akhir += $saldo_akhir;
                            $saldo_akhir_totalharga = $saldo_akhir * $harga_keluar;
                            $total_saldo_akhir_totalharga += $saldo_akhir_totalharga;

                            $total_opname += $d->opname_qty;
                            $total_selisih += $selisih;
                            $no++;
                        @endphp
                        <tr>
                            <td>{{ $no }}</td>
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ textUpperCase($d->nama_barang) }}</td>
                            <td>{{ textUpperCase($d->satuan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->saldo_awal_qty) }}</td>


                            <td class="right">{{ formatAngkaDesimal($d->bm_jumlah) }}</td>


                            <td class="right">{{ formatAngkaDesimal($d->bk_jumlah) }}</td>


                            <td class="right">{{ formatAngkaDesimal($saldo_akhir) }}</td>
                            <td class="right">{{ formatAngkaDesimal($d->opname_qty) }}</td>
                            <td class="right">{{ formatAngkaDesimal($selisih) }}</td>
                        </tr>
                    @endif
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
