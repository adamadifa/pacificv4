<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Bad Stok Gudang Cabang {{ date('Y-m-d H:i:s') }}</title>
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
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG PERSEDIAAN BAD STOK <br>
        </h4>
        <h4>CABANG {{ textUpperCase($cabang->nama_cabang) }}</h4>
        <h4>{{ textUpperCase($produk->nama_produk) }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3">
                <thead>
                    <tr>
                        <th rowspan="2">TANGGAL</th>
                        <th rowspan="2"> NO. BUKTI</th>
                        <th rowspan="2">JENIS MUTASI</th>
                        <th rowspan="2">KETERANGAN</th>
                        <th colspan="4" class="green">PENERIMAAN</th>
                        <th colspan="3" class="red">PENGELUARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                        <th colspan="3" rowspan="2">SALDO AKHIR</th>
                        <th rowspan="3">TANGGAL INPUT</th>
                        <th rowspan="3">TANGGAL UPDATE</th>
                    </tr>
                    <tr>
                        <th class="green">REJECT PASAR</th>
                        <th class="green">REJECT MOBIL</th>
                        <th class="green">REJECT GUDANG</th>
                        <th class="green">PENYESUAIAN</th>
                        <th class="red">KIRIM KE PUSAT</th>
                        <th class="red">REPACK</th>
                        <th class="red">PENYESUAIAN</th>
                    </tr>
                    <tr>
                        <th colspan="4"></th>
                        <th>SALDO AWAL</th>
                        <th colspan="6"></th>
                        <th style="text-align: right">
                            @if ($ceksaldo != null)
                                {{ formatAngkaDesimal($saldo_awal) }}
                            @else
                                <span class="red">Belum Di Set</span>
                            @endif
                        </th>
                        <th>DUS</th>
                        <th>PACK</th>
                        <th>PCS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total_reject_pasar = 0;
                        $total_reject_mobil = 0;
                        $total_reject_gudang = 0;
                        $total_penyesuaian_in = 0;
                        $total_kirim_pusat = 0;
                        $total_repack = 0;
                        $total_penyesuaian_out = 0;

                        $saldo_akhir_jumlah = $saldo_awal_jumlah;
                        $saldo_akhir_real = $saldo_awal_jumlah;
                        $saldo_akhir_desimal = 0;
                    @endphp
                    @foreach ($mutasi as $d)
                        @php
                            $reject_pasar = $d->reject_pasar / $produk->isi_pcs_dus;
                            $reject_mobil = $d->reject_mobil / $produk->isi_pcs_dus;
                            $reject_gudang = $d->reject_gudang / $produk->isi_pcs_dus;
                            $penyesuaian_in = $d->penyesuaian_bad_in / $produk->isi_pcs_dus;
                            $kirim_pusat = $d->kirim_pusat / $produk->isi_pcs_dus;
                            $repack = $d->repack / $produk->isi_pcs_dus;
                            $penyesuaian_out = $d->penyesuaian_bad_out / $produk->isi_pcs_dus;

                            $jml_penerimaan = $d->reject_pasar + $d->reject_mobil + $d->reject_gudang + $d->penyesuaian_bad_in;
                            $jml_pengeluaran = $d->kirim_pusat + $d->repack + $d->penyesuaian_bad_out;
                            // echo $saldo_akhir_jumlah .
                            //     '+' .
                            //     $jml_penerimaan .
                            //     '-' .
                            //     $jml_pengeluaran .
                            //     '=' .
                            //     $saldo_akhir_jumlah +
                            //     $jml_penerimaan -
                            //     $jml_pengeluaran .
                            //     '<br>';
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_real = $saldo_akhir_real + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah < 0 ? $saldo_akhir_jumlah * -1 : $saldo_akhir_jumlah;
                            $saldo_akhir_desimal = $saldo_akhir_real / $produk->isi_pcs_dus;

                            $saldo_akhir_konversi = convertToduspackpcsv3($produk->isi_pcs_dus, $produk->isi_pcs_pack, $saldo_akhir_jumlah);
                            $saldo_akhir_dus = $saldo_akhir_konversi[0];
                            $saldo_akhir_pack = $saldo_akhir_konversi[1];
                            $saldo_akhir_pcs = $saldo_akhir_konversi[2];

                            if ($d->in_out_bad == 'I') {
                                $color_sa = 'green';
                            } else {
                                $color_sa = 'red';
                            }

                            $total_reject_pasar += $d->reject_pasar;
                            $total_reject_mobil += $d->reject_mobil;
                            $total_reject_gudang += $d->reject_gudang;
                            $total_penyesuaian_in += $d->penyesuaian_bad_in;
                            $total_kirim_pusat += $d->kirim_pusat;
                            $total_repack += $d->repack;
                            $total_penyesuaian_out += $d->penyesuaian_bad_out;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <td>{{ $d->no_mutasi }}</td>
                            <td>{{ $d->nama_jenis_mutasi }}</td>
                            <td>{{ $d->keterangan }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_pasar) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_mobil) }}</td>
                            <td class="right">{{ formatAngkaDesimal($reject_gudang) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_in) }}</td>
                            <td class="right">{{ formatAngkaDesimal($kirim_pusat) }}</td>
                            <td class="right">{{ formatAngkaDesimal($repack) }}</td>
                            <td class="right">{{ formatAngkaDesimal($penyesuaian_out) }}</td>
                            <td class="right {{ $color_sa }}">
                                {{ !empty($saldo_akhir_jumlah) ? formatAngkaDesimal($saldo_akhir_desimal) : '' }}
                            </td>
                            <td class="right {{ $color_sa }}">{{ formatAngka($saldo_akhir_dus) }}</td>
                            <td class="right {{ $color_sa }}">{{ formatAngka($saldo_akhir_pack) }}</td>
                            <td class="right {{ $color_sa }}">{{ formatAngka($saldo_akhir_pcs) }}</td>
                            <td>{{ !empty($d->created_at) ? date('d-m-Y H:i:s', strtotime($d->created_at)) : '' }}</td>
                            <td>{{ !empty($d->updated_at) ? date('d-m-Y H:i:s', strtotime($d->updated_at)) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $total_reject_pasar_desimal = $total_reject_pasar / $produk->isi_pcs_dus;
                        $total_reject_mobil_desimal = $total_reject_mobil / $produk->isi_pcs_dus;
                        $total_reject_gudang_desimal = $total_reject_gudang / $produk->isi_pcs_dus;
                        $total_penyesuaian_in_desimal = $total_penyesuaian_in / $produk->isi_pcs_dus;
                        $total_kirim_pusat_desimal = $total_kirim_pusat / $produk->isi_pcs_dus;
                        $total_repack_desimal = $total_repack / $produk->isi_pcs_dus;
                        $total_penyesuaian_out_desimal = $total_penyesuaian_out / $produk->isi_pcs_dus;
                    @endphp
                    <tr>
                        <th colspan="4">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_pasar_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_mobil_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_gudang_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_in_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_kirim_pusat_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_repack_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_out_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</th>
                        <th colspan="5"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
