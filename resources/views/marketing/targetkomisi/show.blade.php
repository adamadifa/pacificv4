<style>
    .table-modal {
        height: auto;
        max-height: 550px;
        overflow-y: scroll;

    }
</style>
<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th style="width: 20%">Kode Target</th>
                <td>{{ $targetkomisi->kode_target }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$targetkomisi->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $targetkomisi->tahun }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ $targetkomisi->nama_cabang }}</td>
            </tr>
        </table>

    </div>
</div>
<div class="row mt-2">
    <div class="col">
        <div class="table-modal">
            <table class="table table-bordered  table-hover" style="width: 600%">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="4" align="middle" style="width: 1%">Kode</th>
                        <th rowspan="4" align="middle" style="width: 1%">NIK</th>
                        <th rowspan="4" align="middle" style="width: 3%">Salesman</th>
                        <th rowspan="4" align="middle" style="width: 2%">Masa Kerja</th>
                        <th colspan="{{ count($produk) * 10 }}" class="text-center">Produk</th>
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th class="text-center" colspan="10">
                                {{ $d->kode_produk }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th rowspan="2">AVG</th>
                            <th colspan="3">Realisasi</th>
                            <th rowspan="2">Last</th>
                            <th colspan="5" class="text-center">Target</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th>{{ getMonthName2($lasttigabulan) }}</th>
                            <th>{{ getMonthName2($lastduabulan) }}</th>
                            <th>{{ getMonthName2($lastbulan) }}</th>
                            <th>AWAL</th>
                            <th style="width: 1%">RSM</th>
                            <th style="width: 1%">GM</th>
                            <th style="width: 1%">DIRUT</th>
                            <th style="width: 1%">AKHIR</th>
                        @endforeach



                    </tr>

                </thead>
                <tbody>
                    @foreach ($produk as $p)
                        @php
                            ${"total_rata_rata_penjualan_$p->kode_produk"} = 0;
                            ${"total_penjualan_tigabulan_$p->kode_produk"} = 0;
                            ${"total_penjualan_duabulan_$p->kode_produk"} = 0;
                            ${"total_penjualan_lastbulan_$p->kode_produk"} = 0;
                            ${"total_last_target_$p->kode_produk"} = 0;
                            ${"total_target_awal_$p->kode_produk"} = 0;
                            ${"total_target_rsm_$p->kode_produk"} = 0;
                            ${"total_target_gm_$p->kode_produk"} = 0;
                            ${"total_target_dirut_$p->kode_produk"} = 0;
                            ${"total_target_$p->kode_produk"} = 0;
                        @endphp
                    @endforeach
                    @foreach ($detail as $d)
                        <tr>
                            <td>{{ $d->kode_salesman }}</td>
                            <td>{{ $d->nik }}</td>
                            <td>{{ $d->nama_salesman }}</td>
                            <td>
                                @php
                                    $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                                    $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
                                @endphp
                                @if (!empty($d->tanggal_masuk))
                                    {{ $masakerja['tahun'] }} Tahun {{ $masakerja['bulan'] }} Bulan
                                @endif
                            </td>

                            @foreach ($produk as $p)
                                @php
                                    $rata_rata_penjualan = $d->{"penjualan_$p->kode_produk"} / $p->isi_pcs_dus / 3;
                                    $jml_penjualan_tigabulan = $d->{"penjualan_tiga_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                                    $jml_penjualan_duabulan = $d->{"penjualan_dua_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                                    $jml_penjualan_lastbulan = $d->{"penjualan_last_bulan_$p->kode_produk"} / $p->isi_pcs_dus;
                                    $jml_last_target = $d->{"target_last_$p->kode_produk"};

                                    ${"total_rata_rata_penjualan_$p->kode_produk"} += $rata_rata_penjualan;
                                    ${"total_penjualan_tigabulan_$p->kode_produk"} += $jml_penjualan_tigabulan;
                                    ${"total_penjualan_duabulan_$p->kode_produk"} += $jml_penjualan_duabulan;
                                    ${"total_penjualan_lastbulan_$p->kode_produk"} += $jml_penjualan_lastbulan;
                                    ${"total_last_target_$p->kode_produk"} += $jml_last_target;
                                    ${"total_target_awal_$p->kode_produk"} += $d->{"target_awal_$p->kode_produk"};
                                    ${"total_target_rsm_$p->kode_produk"} += $d->{"target_rsm_$p->kode_produk"};
                                    ${"total_target_gm_$p->kode_produk"} += $d->{"target_gm_$p->kode_produk"};
                                    ${"total_target_dirut_$p->kode_produk"} += $d->{"target_dirut_$p->kode_produk"};
                                    ${"total_target_$p->kode_produk"} += $d->{"target_$p->kode_produk"};
                                @endphp
                                <td class="text-end bg-success text-white"> {{ formatAngka($rata_rata_penjualan) }}</td>
                                <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_tigabulan) }}</td>
                                <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_duabulan) }}</td>
                                <td class="text-end bg-info text-white">{{ formatAngka($jml_penjualan_lastbulan) }}</td>
                                <td class="text-end bg-primary text-white">{{ formatAngka($jml_last_target) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_rsm_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_gm_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_dirut_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_$p->kode_produk"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4">TOTAL</td>
                        @foreach ($produk as $d)
                            <td class="text-end"></td>
                            <td class="text-end">{{ formatAngka(${"total_penjualan_tigabulan_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_penjualan_duabulan_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_penjualan_lastbulan_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_last_target_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_awal_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_rsm_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_gm_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_dirut_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_$d->kode_produk"}) }}</td>
                        @endforeach
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <td class="bg-success"></td>
                <td>Rata Rata Penjualan 3 Bulan Terakhir</td>
            </tr>
            <tr>
                <td class="bg-info"></td>
                <td>Realisasi Selama 3 Bulan</td>
            </tr>
        </table>
    </div>
</div>
<script>
    $(".table-modal").freezeTable({
        'scrollable': true,
        'columnNum': 4,
        'shadow': true,
    });
</script>
