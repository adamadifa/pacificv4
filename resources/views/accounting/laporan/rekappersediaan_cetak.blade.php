<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    {{-- <style>
    .freeze-table {
      height: auto;
      max-height: 795px;
      overflow: auto;
    }
  </style> --}}
    <style>
        .datatable3 th {
            font-size: 11px !important;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            REKAPITULASI PERSEDIAAN GOOD STOK <br>
        </h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">

            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">KODE</th>
                        <th rowspan="2">PRODUK</th>
                        <th rowspan="2">Saldo Awal</th>
                        <th colspan="6" class="green">PENERIMAAN</th>
                        <th colspan="8" class="red">PENGELUARAN</th>
                        <th rowspan="2" colspan="4">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th class="green" colspan="2">PRODUKSI</th>
                        <th class="green" colspan="2">REPACK</th>
                        <th class="green" colspan="2">LAIN LAIN</th>
                        <th class="red" colspan="3">KIRIM KE CABANG</th>
                        <th class="red" colspan="3">REJECT</th>
                        <th class="red" colspan="2">LAIN LAIN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekapgudang as $d)
                        @php
                            $saldo_awal = $d['saldo_awal'];
                            $saldo_akhir = $d['saldo_awal'] + $d['jml_mutasi'];

                        @endphp
                        <tr>
                            <td>{{ $d['kode_produk'] }}</td>
                            <td>{{ $d['nama_produk'] }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_awal) }}</td>
                            <td class="right" colspan="2">{{ formatAngka($d['jml_fsthp']) }}</td>
                            <td class="right" colspan="2">{{ formatAngka($d['jml_repack']) }}</td>
                            <td class="right" colspan="2">{{ formatAngka($d['jml_lainlain_in']) }}</td>
                            <td class="right" colspan="3">{{ formatAngka($d['jml_surat_jalan']) }}</td>
                            <td class="right" colspan="3">{{ formatAngka($d['jml_reject']) }}</td>
                            <td class="right" colspan="2">{{ formatAngka($d['jml_lainlain_out']) }}</td>
                            <td class="right" colspan="4">{{ formatAngka($saldo_akhir) }}</td>

                        </tr>
                    @endforeach
                </tbody>
                <thead>
                    <tr>
                        <th rowspan="2">KODE</th>
                        <th rowspan="2">PRODUK</th>
                        <th rowspan="2">SALDO AWAL</th>
                        <th colspan="6" class="green">PENERIMAAN</th>
                        <th colspan="8" class="red">PENGELUARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                        <th colspan="3">SALDO AKHIR</th>
                    </tr>
                    <tr>
                        <th class="green">PUSAT</th>
                        <th class="green">TRANSIT IN</th>
                        <th class="green">RETUR</th>
                        <th class="green">LAIN LAIN</th>
                        <th class="green">REPACK</th>
                        <th class="green">PENYESUAIAN</th>
                        <th class="red">PENJUALAN</th>
                        <th class="red">PROMOSI</th>
                        <th class="red">REJECT PASAR</th>
                        <th class="red">REJECT MOBIL</th>
                        <th class="red">REJECT GUDANG</th>
                        <th class="red">TRANSIT OUT</th>
                        <th class="red">LAIN LAIN</th>
                        <th class="red">PENYESUAIAN</th>
                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekappersediaan as $key => $d)
                        @php
                            $cbg = @$rekappersediaan[$key + 1]['kode_cabang'];
                            $saldo_awal = $d['saldo_awal'] / $d['isi_pcs_dus'];
                            $pusat = $d['pusat'] / $d['isi_pcs_dus'];
                            $transit_in = $d['transit_in'] / $d['isi_pcs_dus'];
                            $retur = $d['retur'] / $d['isi_pcs_dus'];
                            $jml_lainlain_in = $d['hutang_kirim'] + $d['pelunasan_ttr'] + $d['penyesuaian_bad'];
                            $lainlain_in = $jml_lainlain_in / $d['isi_pcs_dus'];
                            $repack = $d['repack'] / $d['isi_pcs_dus'];
                            $penyesuaian_in = $d['penyesuaian_in'] / $d['isi_pcs_dus'];
                            $jml_penerimaan = $d['pusat'] + $d['transit_in'] + $d['retur'] + $jml_lainlain_in + $d['repack'] + $d['penyesuaian_in'];

                            $penjualan = $d['penjualan'] / $d['isi_pcs_dus'];
                            $promosi = $d['promosi'] / $d['isi_pcs_dus'];
                            $reject_pasar = $d['reject_pasar'] / $d['isi_pcs_dus'];
                            $reject_mobil = $d['reject_mobil'] / $d['isi_pcs_dus'];
                            $reject_gudang = $d['reject_gudang'] / $d['isi_pcs_dus'];
                            $transit_out = $d['transit_out'] / $d['isi_pcs_dus'];
                            $jml_lainlain_out = $d['pelunasan_hutangkirim'] + $d['ganti_barang'] + $d['ttr'];
                            $lainlain_out = $jml_lainlain_out / $d['isi_pcs_dus'];
                            $penyesuaian_out = $d['penyesuaian_out'] / $d['isi_pcs_dus'];
                            $jml_pengeluaran =
                                $d['penjualan'] +
                                $d['promosi'] +
                                $d['reject_pasar'] +
                                $d['reject_mobil'] +
                                $d['reject_gudang'] +
                                $d['transit_out'] +
                                $jml_lainlain_out +
                                $d['penyesuaian_out'];

                            $saldo_akhir_jumlah = $d['saldo_awal'] + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_real = $saldo_akhir_jumlah;
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah < 0 ? $saldo_akhir_jumlah * -1 : $saldo_akhir_jumlah;
                            $saldo_akhir_desimal = $saldo_akhir_real / $d['isi_pcs_dus'];

                            $saldo_akhir_konversi = convertToduspackpcsv3($d['isi_pcs_dus'], $d['isi_pcs_pack'], $saldo_akhir_jumlah);
                            $saldo_akhir_dus = $saldo_akhir_konversi[0];
                            $saldo_akhir_pack = $saldo_akhir_konversi[1];
                            $saldo_akhir_pcs = $saldo_akhir_konversi[2];
                        @endphp
                        <tr>
                            <td>{{ $d['kode_produk'] }} </td>
                            <td>{{ $d['nama_produk'] }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_awal) }}</td>
                            <td class="right">{{ formatAngkaDesimal($pusat) }}</td>
                            <td class="right">{{ formatAngkaDesimal($transit_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($retur) }}</td>
                            <td class="right">{{ formatAngkaDesimal($lainlain_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($repack) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_in) }}</td>

                            <td class="right">{{ formatAngkaDesimal($penjualan) }}</td>
                            <td class="right">{{ formatAngkaDesimal($promosi) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_pasar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_mobil) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_gudang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($transit_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($lainlain_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_out) }}</td>
                            <td class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_dus) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_pack) }}</td>
                            <td class="right">{{ formatAngka($saldo_akhir_pcs) }}</td>
                        </tr>
                        @if ($d['kode_cabang'] != $cbg)
                            <tr>
                                <th colspan="2">{{ $d['kode_cabang'] }}</th>
                                <th colspan="19"></th>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>
