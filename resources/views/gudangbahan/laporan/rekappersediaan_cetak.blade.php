<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Rekap Persediaan Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/js/freeze-table.js') }}"></script>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAPITULASI PERSEDIAAN GUDANG BAHAN<br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
        <h4>KATEGORI {{ $kategori->nama_kategori }}</h4>
    </div>
    <div class="content">
        <div class="table-basic">
            <table class="datatable3 table" style="width: 200%">
                <thead>
                    <tr>
                        <th rowspan="4" style="width: 1%">NO</th>
                        <th rowspan="4" style="width:1%">KODE</th>
                        <th rowspan="4" style="width:5%">NAMA BARANG</th>
                        <th rowspan="4" style="width: 1%">SATUAN</th>
                    </tr>
                    <tr>
                        <th colspan="3" rowspan="2">SALDO AWAL</th>
                        <th colspan="9" class="green">PEMASUKAN</th>
                        <th colspan="18" class="red">PENGELUARAN</th>
                        <th colspan="3" rowspan="2">SALDO AKHIR
                        </th>
                        <th colspan="3" rowspan="2">OPNAME STOK
                        </th>
                        <th colspan="2" rowspan="2">SELISIH</th>
                    </tr>
                    <tr bgcolor="red">
                        <th colspan="3" class="green">PEMBELIAN</th>
                        <th colspan="3" class="green">LAINNYA</th>
                        <th colspan="3" class="green">RETUR PENGGANTI</th>
                        <th colspan="3" class="red">PRODUKSI</th>
                        <th colspan="3" class="red">SEASONING</th>
                        <th colspan="3" class="red">PDQC</th>
                        <th colspan="3" class="red">SUSUT</th>
                        <th colspan="3" class="red">CABANG</th>
                        <th colspan="3" class="red">LAINNYA</th>
                    </tr>
                    <tr bgcolor="red">
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                        <th class="green">QTY</th>
                        <th class="green">HARGA</th>
                        <th class="green">JUMLAH</th>
                        <th class="green">QTY</th>
                        <th class="green">HARGA</th>
                        <th class="green">JUMLAH</th>
                        <th class="green">QTY</th>
                        <th class="green">HARGA</th>
                        <th class="green">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th class="red">QTY</th>
                        <th class="red">HARGA</th>
                        <th class="red">JUMLAH</th>
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                        <th>QTY</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                        <th>QTY</th>
                        <th>JUMLAH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekappersediaan as $key => $d)
                        @php
                            $kode_jenis_barang = @$rekappersediaan[$key + 1]->kode_jenis_barang;
                        @endphp
                        @if ($d->satuan == 'KG')
                            @php
                                //Saldo Awal
                                $qty_saldo_awal = $d->saldo_awal_qty_berat * 1000;
                                if (!empty($qty_saldo_awal)) {
                                    $harga_saldo_awal = $d->saldo_awal_harga / $qty_saldo_awal;
                                } else {
                                    $harga_saldo_awal = 0;
                                }
                                $jumlah_saldo_awal = $d->saldo_awal_harga;

                                //Pembelian

                                $qty_pembelian = $d->bm_qty_berat_pembelian * 1000;
                                if (!empty($qty_pembelian)) {
                                    $harga_pembelian = $d->total_harga / $qty_pembelian;
                                } else {
                                    $harga_saldo_awal = $harga_saldo_awal;
                                }
                                $jumlah_pembelian = $d->total_harga;

                                //Lainnya
                                $qty_lainnya = $d->bm_qty_berat_lainnya * 1000;
                                if (!empty($qty_lainnya)) {
                                    $harga_lainnya = $harga_pembelian;
                                } else {
                                    $harga_lainnya = 0;
                                }
                                $jumlah_lainnya = $qty_lainnya * $harga_lainnya;

                                //Retur Pengganti
                                $qty_returpengganti = $d->bm_qty_berat_returpengganti * 1000;
                                if (!empty($qty_returpengganti)) {
                                    $harga_returpengganti = $harga_pembelian;
                                } else {
                                    $harga_returpengganti = 0;
                                }
                                $jumlah_returpengganti = $qty_returpengganti * $harga_returpengganti;

                                //Produksi
                                $qty_masuk = $qty_saldo_awal + $qty_pembelian + $qty_lainnya + $qty_returpengganti;
                                $jumlah_masuk =
                                    $jumlah_saldo_awal + $jumlah_pembelian + $jumlah_lainnya + $jumlah_returpengganti;

                                $qty_produksi = $d->bk_qty_berat_produksi * 1000;
                                if (!empty($qty_produksi)) {
                                    $harga_produksi = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_produksi = 0;
                                }
                                $jumlah_produksi = $qty_produksi * $harga_produksi;

                                //Seasoning
                                $qty_seasoning = $d->bk_qty_berat_seasoning * 1000;
                                if (!empty($qty_seasoning)) {
                                    $harga_seasoning = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_seasoning = 0;
                                }
                                $jumlah_seasoning = $qty_seasoning * $harga_seasoning;

                                //PDQC
                                $qty_pdqc = $d->bk_qty_berat_pdqc * 1000;
                                if (!empty($qty_pdqc)) {
                                    $harga_pdqc = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_pdqc = 0;
                                }
                                $jumlah_pdqc = $qty_pdqc * $harga_pdqc;

                                //SUSUT
                                $qty_susut = $d->bk_qty_berat_susut * 1000;
                                if (!empty($qty_susut)) {
                                    $harga_susut = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_susut = 0;
                                }
                                $jumlah_susut = $qty_susut * $harga_susut;

                                //Cabang
                                $qty_cabang = $d->bk_qty_berat_cabang * 1000;
                                if (!empty($qty_cabang)) {
                                    $harga_cabang = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_cabang = 0;
                                }
                                $jumlah_cabang = $qty_cabang * $harga_cabang;

                                //Lainnya
                                $qty_lainnya_keluar = $d->bk_qty_berat_lainnya * 1000;
                                if (!empty($qty_lainnya_keluar)) {
                                    $harga_lainnya_keluar = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_lainnya_keluar = 0;
                                }
                                $jumlah_lainnya_keluar = $qty_lainnya_keluar * $harga_lainnya_keluar;

                                $qty_keluar = $qty_produksi + $qty_seasoning + $qty_pdqc + $qty_susut + $qty_cabang + $qty_lainnya_keluar ;

                                //Saldo Akhir
                                $qty_saldo_akhir = $qty_masuk - $qty_keluar;
                                if (!empty($qty_saldo_akhir)) {
                                    $harga_saldo_akhir = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_saldo_akhir = 0;
                                }
                                $jumlah_saldo_akhir = $qty_saldo_akhir * $harga_saldo_akhir;

                                $qty_opname = $d->opname_qty_berat * 1000;
                                if (!empty($qty_opname)) {
                                    $harga_opname = !empty($qty_masuk) ? $jumlah_masuk / $qty_masuk : 0;
                                } else {
                                    $harga_opname = 0;
                                }
                                $jumlah_opname = $qty_opname * $harga_opname;
                            @endphp
                        @endif

                        @php
                            $selisih = ROUND()$qty_saldo_akhir - $qty_opname;
                        @endphp
                        <tr>
                            <td class="center">{{ $loop->iteration }}</td>
                            <td class="center">{{ $d->kode_barang }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td class="center">{{ $d->satuan }}</td>
                            <td class="right">{{ formatAngkaDesimal($qty_saldo_awal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_saldo_awal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_saldo_awal) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_pembelian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_pembelian) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_pembelian) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_lainnya) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_lainnya) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_lainnya) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_returpengganti) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_returpengganti) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_returpengganti) }}</td>

                            <!-- BARANG KELUAR -->
                            <td class="right">{{ formatAngkaDesimal($qty_produksi) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_produksi) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_produksi) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_seasoning) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_seasoning) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_seasoning) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_pdqc) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_pdqc) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_pdqc) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_susut) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_susut) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_susut) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_cabang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_cabang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_cabang) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_lainnya_keluar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_lainnya_keluar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_lainnya_keluar) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_saldo_akhir) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_saldo_akhir) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_saldo_akhir) }}</td>

                            <td class="right">{{ formatAngkaDesimal($qty_opname) }}</td>
                            <td class="right">{{ formatAngkaDesimal($harga_opname) }}</td>
                            <td class="right">{{ formatAngkaDesimal($jumlah_opname) }}</td>
                        </tr>
                        @if ($kode_jenis_barang != $d->kode_jenis_barang)
                            <tr>
                                <th colspan="4">SUBTOTAL {{ $jenis_barang[$d->kode_jenis_barang] }}</th>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
