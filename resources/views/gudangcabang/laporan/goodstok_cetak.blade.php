<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Persediaan Gudang Bahan {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script src="{{ asset('assets/vendor/libs/freeze/js/freeze-table.min.js') }}"></script>
    <style>
        .freeze-table {
            height: auto;
            max-height: 795px;
            overflow: auto;
        }
    </style>
</head>

<body>
    <div class="header">
        <h4 class="title">
            LAPORAN BARANG PERSEDIAAN GOOD STOK <br>
        </h4>
        <h4>CABANG {{ textUpperCase($cabang->nama_cabang) }}</h4>
        <h4>{{ textUpperCase($produk->nama_produk) }}</h4>
        <h4>PERIODE {{ DateToIndo($dari) }} s/d {{ DateToIndo($sampai) }}</h4>
    </div>
    <div class="content">
        <div class="freeze-table">
            <table class="datatable3" style="width: 180%">
                <thead>
                    <tr>
                        <th rowspan="2">TANGGAL</th>
                        <th colspan="3">BUKTI</th>
                        <th rowspan="2">SALESMAN</th>
                        <th rowspan="2">JENIS MUTASI</th>
                        <th rowspan="2" style="width:5%">KETERANGAN</th>
                        <th colspan="6" class="green">PENERIMAAN</th>
                        <th colspan="8" class="red">PENGELUARAN</th>
                        <th rowspan="2">SALDO AKHIR</th>
                        <th colspan="3" rowspan="2">SALDO AKHIR</th>
                        <th rowspan="3">TANGGAL INPUT</th>
                        <th rowspan="3">TANGGAL UPDATE</th>
                    </tr>
                    <tr>
                        <th>SURAT JALAN / NO FAKTUR</th>
                        <th>TGL KIRIM</th>
                        <th>NO BUKTI</th>
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
                    </tr>
                    <tr>
                        <th colspan="6"></th>
                        <th>SALDO AWAL</th>
                        <th colspan="14"></th>
                        <th class="right">
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
                        $total_pusat = 0;
                        $total_transit_in = 0;
                        $total_retur = 0;
                        $total_lainlain_in = 0;
                        $total_repack = 0;
                        $total_penyesuaian_in = 0;

                        $total_penjualan = 0;
                        $total_promosi = 0;
                        $total_reject_pasar = 0;
                        $total_reject_mobil = 0;
                        $total_reject_gudang = 0;
                        $total_transit_out = 0;
                        $total_lainlain_out = 0;
                        $total_penyesuaian_out = 0;

                        $saldo_akhir_jumlah = $saldo_awal_pcs;
                        $saldo_akhir_real = $saldo_awal_pcs;
                        $saldo_akhir_desimal = 0;

                        $saldo_akhir_dus = 0;
                        $saldo_akhir_pack = 0;
                        $saldo_akhir_pcs = 0;
                    @endphp
                    @foreach ($mutasi as $d)
                        @php
                            $pusat = $d->pusat / $d->isi_pcs_dus;
                            $transit_in = $d->transit_in / $d->isi_pcs_dus;
                            $retur = $d->retur / $d->isi_pcs_dus;
                            //Lain Lain IN
                            if ($d->jenis_mutasi == 'HK') {
                                $jml_lainlain_in = $d->hutang_kirim;
                            } elseif ($d->jenis_mutasi == 'PT') {
                                $jml_lainlain_in = $d->pelunasan_ttr;
                            } elseif ($d->jenis_mutasi == 'PB') {
                                $jml_lainlain_in = $d->penyesuaian_bad;
                            } else {
                                $jml_lainlain_in = 0;
                            }

                            $lainlain_in = $jml_lainlain_in / $d->isi_pcs_dus;
                            $repack = $d->repack / $d->isi_pcs_dus;
                            $penyesuaian_in = $d->penyesuaian_in / $d->isi_pcs_dus;

                            $jml_penerimaan = $d->pusat + $d->transit_in + $d->retur + $jml_lainlain_in + $d->repack + $d->penyesuaian_in;

                            //Pengeluaran
                            $penjualan = $d->penjualan / $d->isi_pcs_dus;
                            $promosi = $d->promosi / $d->isi_pcs_dus;
                            $reject_pasar = $d->reject_pasar / $d->isi_pcs_dus;
                            $reject_mobil = $d->reject_mobil / $d->isi_pcs_dus;
                            $reject_gudang = $d->reject_gudang / $d->isi_pcs_dus;
                            $transit_out = $d->transit_out / $d->isi_pcs_dus;

                            //Lain Lain OUT
                            if ($d->jenis_mutasi == 'TR') {
                                $jml_lainlain_out = $d->ttr;
                            } elseif ($d->jenis_mutasi == 'GB') {
                                $jml_lainlain_out = $d->ganti_barang;
                            } elseif ($d->jenis_mutasi == 'PH') {
                                $jml_lainlain_out = $d->pelunasan_hutangkirim;
                            } else {
                                $jml_lainlain_out = 0;
                            }

                            $lainlain_out = $jml_lainlain_out / $d->isi_pcs_dus;
                            $penyesuaian_out = $d->penyesuaian_out / $d->isi_pcs_dus;

                            $jml_pengeluaran =
                                $d->penjualan +
                                $d->promosi +
                                $d->reject_pasar +
                                $d->reject_mobil +
                                $d->reject_gudang +
                                $d->transit_out +
                                $jml_lainlain_out +
                                $d->penyesuaian_out;

                            $saldo_akhir_jumlah = $saldo_akhir_jumlah + $jml_penerimaan - $jml_pengeluaran;
                            $saldo_akhir_real = $saldo_akhir_real + $jml_penerimaan - $jml_pengeluaran;
                            // $saldo_akhir_real = $saldo_akhir_jumlah;
                            $saldo_akhir_jumlah = $saldo_akhir_jumlah < 0 ? $saldo_akhir_jumlah * -1 : $saldo_akhir_jumlah;
                            $saldo_akhir_desimal = $saldo_akhir_real / $d->isi_pcs_dus;

                            $saldo_akhir_konversi = convertToduspackpcsv3($d->isi_pcs_dus, $d->isi_pcs_pack, $saldo_akhir_jumlah);
                            $saldo_akhir_dus = $saldo_akhir_konversi[0];
                            $saldo_akhir_pack = $saldo_akhir_konversi[1];
                            $saldo_akhir_pcs = $saldo_akhir_konversi[2];
                            if ($d->in_out_good == 'I') {
                                $color_sa = 'green';
                            } else {
                                $color_sa = 'red';
                            }

                            //Hitung Grand Total
                            $total_pusat += $d->pusat;
                            $total_transit_in += $d->transit_in;
                            $total_retur += $d->retur;
                            $total_lainlain_in += $jml_lainlain_in;
                            $total_repack += $d->repack;
                            $total_penyesuaian_in += $d->penyesuaian_in;

                            $total_penjualan += $d->penjualan;
                            $total_promosi += $d->promosi;
                            $total_reject_pasar += $d->reject_pasar;
                            $total_reject_mobil += $d->reject_mobil;
                            $total_reject_gudang += $d->reject_gudang;
                            $total_transit_out += $d->transit_out;
                            $total_lainlain_out += $jml_lainlain_out;
                            $total_penyesuaian_out = $d->penyesuaian_out;
                        @endphp
                        <tr>
                            <td>{{ DateToIndo($d->tanggal) }}</td>
                            <!-- Surat Jalan-->
                            <td>
                                @if (in_array($d->jenis_mutasi, ['RG', 'TI', 'TO']))
                                    @if (!empty($d->no_dok) && !empty($d->no_surat_jalan))
                                        {{ $d->no_surat_jalan }} / {{ $d->no_dok }}
                                    @else
                                        @if (!empty($d->no_surat_jalan))
                                            {{ $d->no_surat_jalan }}
                                        @else
                                            {{ $d->no_dok }}
                                        @endif
                                    @endif
                                @elseif ($d->jenis_mutasi == 'SJ')
                                    {{ $d->no_mutasi }} / {{ $d->no_dok }}
                                @endif
                            </td>
                            <!--Tanggal Kirim-->
                            <td>{{ DateToIndo($d->tanggal_kirim) }}</td>
                            <!-- No. Bukti -->
                            <td>
                                @if (in_array($d->jenis_mutasi, ['RG', 'RM', 'RP', 'PY', 'PB', 'RK']))
                                    {{ $d->no_mutasi }}
                                @else
                                    {{ $d->no_dpb }}
                                @endif
                            </td>
                            <!-- Nama Selsman-->
                            <td>{{ $d->nama_salesman }}</td>
                            <!-- Jenis Mutasi-->
                            <td>
                                {{ $d->jenis_mutasi == 'SJ' ? 'PENERIMAAN PUSAT' : $d->nama_jenis_mutasi }}
                                @if (in_array($d->jenis_mutasi, ['TI', 'TO']))
                                    <b style="color:#23a7e0">{{ $d->no_mutasi }}</b>
                                @endif
                                @if ($d->jenis_mutasi == 'RG')
                                    <b style="color:#7d0303">{{ $d->no_surat_jalan }}</b>
                                @endif
                            </td>
                            <td>{{ $d->keterangan }}</td>
                            <!-- Penerimaan-->
                            <td class="right">{{ !empty($d->pusat) ? formatAngkaDesimal($pusat) : '' }}</td>
                            <td class="right">{{ !empty($d->transit_in) ? formatAngkaDesimal($transit_in) : '' }}</td>
                            <td class="right">{{ !empty($d->retur) ? formatAngkaDesimal($retur) : '' }}</td>
                            <td class="right">{{ !empty($jml_lainlain_in) ? formatAngkaDesimal($lainlain_in) : '' }}</td>
                            <td class="right">{{ !empty($d->repack) ? formatAngkaDesimal($repack) : '' }}</td>
                            <td class="right">{{ !empty($d->penyesuaian_in) ? formatAngkaDesimal($penyesuaian_in) : '' }}
                            </td>

                            <td class="right">{{ !empty($d->penjualan) ? formatAngkaDesimal($penjualan) : '' }}</td>
                            <td class="right">{{ !empty($d->promosi) ? formatAngkaDesimal($promosi) : '' }}</td>
                            <td class="right">{{ !empty($d->reject_pasar) ? formatAngkaDesimal($reject_pasar) : '' }}</td>
                            <td class="right">{{ !empty($d->reject_mobil) ? formatAngkaDesimal($reject_mobil) : '' }}</td>
                            <td class="right">{{ !empty($d->reject_gudang) ? formatAngkaDesimal($reject_gudang) : '' }}
                            </td>
                            <td class="right">{{ !empty($d->transit_out) ? formatAngkaDesimal($transit_out) : '' }}</td>
                            <td class="right">{{ !empty($jml_lainlain_out) ? formatAngkaDesimal($lainlain_out) : '' }}</td>
                            <td class="right">
                                {{ !empty($d->penyesuaian_out) ? formatAngkaDesimal($penyesuaian_out) : '' }}</td>
                            <td class="right {{ $color_sa }}">
                                {{ !empty($saldo_akhir_jumlah) ? formatAngkaDesimal($saldo_akhir_desimal) : '' }}</td>
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
                        //Hitung Grand Total Desimal
                        $total_pusat_desimal = $total_pusat / $produk->isi_pcs_dus;
                        $total_transit_in_desimal = $total_transit_in / $produk->isi_pcs_dus;
                        $total_retur_desimal = $total_retur / $produk->isi_pcs_dus;
                        $total_lainlain_in_desimal = $total_lainlain_in / $produk->isi_pcs_dus;
                        $total_repack_desimal = $total_repack / $produk->isi_pcs_dus;
                        $total_penyesuaian_in_desimal = $total_penyesuaian_in / $produk->isi_pcs_dus;

                        $total_penjualan_desimal = $total_penjualan / $produk->isi_pcs_dus;
                        $total_promosi_desimal = $total_promosi / $produk->isi_pcs_dus;
                        $total_reject_pasar_desimal = $total_reject_pasar / $produk->isi_pcs_dus;
                        $total_reject_mobil_desimal = $total_reject_mobil / $produk->isi_pcs_dus;
                        $total_reject_gudang_desimal = $total_reject_gudang / $produk->isi_pcs_dus;
                        $total_transit_out_desimal = $total_transit_out / $produk->isi_pcs_dus;
                        $total_lainlain_out_desimal = $total_lainlain_out / $produk->isi_pcs_dus;
                        $total_penyesuaian_out_desimal = $total_penyesuaian_out / $produk->isi_pcs_dus;
                    @endphp
                    <tr>
                        <th colspan="7">TOTAL</th>
                        <th class="right">{{ formatAngkaDesimal($total_pusat_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_transit_in_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_retur_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_lainlain_in_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_repack_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_in_desimal) }}</th>

                        <th class="right">{{ formatAngkaDesimal($total_penjualan_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_promosi_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_pasar_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_mobil_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_reject_gudang_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_transit_out_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_lainlain_out_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($total_penyesuaian_out_desimal) }}</th>
                        <th class="right">{{ formatAngkaDesimal($saldo_akhir_desimal) }}</th>
                        <th class="right">{{ formatAngka($saldo_akhir_dus) }}</th>
                        <th class="right">{{ formatAngka($saldo_akhir_pack) }}</th>
                        <th class="right">{{ formatAngka($saldo_akhir_pcs) }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</body>
<script>
    $(".freeze-table").freezeTable({
        'scrollable': true,
        'columnNum': 5,
        'shadow': true,
    });
</script>
