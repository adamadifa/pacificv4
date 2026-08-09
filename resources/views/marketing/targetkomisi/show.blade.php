<style>
    .table-modal {
        height: auto;
        max-height: 520px;
        overflow-y: auto;
        border-radius: 8px;
        border: 1px solid #e6e8eb;
    }
    .table-target-detail {
        font-family: inherit;
    }
    .table-target-detail th {
        font-size: 0.72rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        vertical-align: middle !important;
        padding: 8px 10px !important;
        font-weight: 600;
    }
    .table-target-detail td {
        font-size: 0.78rem;
        padding: 6px 10px !important;
        vertical-align: middle !important;
    }
    .cell-avg {
        background-color: rgba(40, 199, 111, 0.08) !important;
        color: #28c76f !important;
        font-weight: 600;
    }
    .cell-realisasi {
        background-color: rgba(0, 207, 232, 0.08) !important;
        color: #00cfe8 !important;
        font-weight: 500;
    }
    .cell-last-target {
        background-color: rgba(115, 103, 240, 0.08) !important;
        color: #7367f0 !important;
        font-weight: 600;
    }
    .cell-target-akhir {
        background-color: rgba(253, 172, 52, 0.08) !important;
        color: #fdac34 !important;
        font-weight: 600;
    }
</style>

<div class="card bg-label-primary border-0 shadow-none mb-3">
    <div class="card-body p-3">
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <small class="text-muted d-block mb-1">KODE TARGET</small>
                <span class="fw-bold text-primary">{{ $targetkomisi->kode_target }}</span>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block mb-1">BULAN</small>
                <span class="fw-bold text-dark">{{ $namabulan[$targetkomisi->bulan] }}</span>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block mb-1">TAHUN</small>
                <span class="fw-bold text-dark">{{ $targetkomisi->tahun }}</span>
            </div>
            <div class="col-6 col-md-3">
                <small class="text-muted d-block mb-1">CABANG</small>
                <span class="fw-bold text-dark">{{ $targetkomisi->nama_cabang }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col col-sm-12">
        <div class="table-modal">
            <table class="table table-bordered table-hover table-target-detail" style="width: 600%">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="4" align="middle" class="text-center" style="width: 1%">Kode</th>
                        <th rowspan="4" align="middle" class="text-center" style="width: 1%">NIK</th>
                        <th rowspan="4" align="middle" class="text-center" style="width: 3%">Salesman</th>
                        <th rowspan="4" align="middle" class="text-center" style="width: 2%">Masa Kerja</th>
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
                            <th rowspan="2" class="text-center cell-avg">AVG</th>
                            <th colspan="3" class="text-center cell-realisasi">Realisasi</th>
                            <th rowspan="2" class="text-center cell-last-target">Last</th>
                            <th colspan="5" class="text-center">Target</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($produk as $d)
                            <th class="text-center cell-realisasi">{{ getMonthName2($lasttigabulan) }}</th>
                            <th class="text-center cell-realisasi">{{ getMonthName2($lastduabulan) }}</th>
                            <th class="text-center cell-realisasi">{{ getMonthName2($lastbulan) }}</th>
                            <th class="text-center">AWAL</th>
                            <th style="width: 1%" class="text-center">RSM</th>
                            <th style="width: 1%" class="text-center">GM</th>
                            <th style="width: 1%" class="text-center">DIRUT</th>
                            <th style="width: 1%" class="text-center cell-target-akhir">AKHIR</th>
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
                            <td class="text-center fw-semibold text-muted">{{ $d->kode_salesman }}</td>
                            <td class="text-center">{{ $d->nik }}</td>
                            <td class="fw-semibold text-dark">{{ $d->nama_salesman }}</td>
                            <td class="text-center small">
                                @php
                                    $end_date = $targetkomisi->tahun . '-' . $targetkomisi->bulan . '-01';
                                    $masakerja = hitungMasakerja($d->tanggal_masuk, $end_date);
                                @endphp
                                @if (!empty($d->tanggal_masuk))
                                    {{ $masakerja['tahun'] }} Thn {{ $masakerja['bulan'] }} Bln
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
                                <td class="text-end cell-avg">{{ formatAngka($rata_rata_penjualan) }}</td>
                                <td class="text-end cell-realisasi">{{ formatAngka($jml_penjualan_tigabulan) }}</td>
                                <td class="text-end cell-realisasi">{{ formatAngka($jml_penjualan_duabulan) }}</td>
                                <td class="text-end cell-realisasi">{{ formatAngka($jml_penjualan_lastbulan) }}</td>
                                <td class="text-end cell-last-target">{{ formatAngka($jml_last_target) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_awal_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_rsm_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_gm_$p->kode_produk"}) }}</td>
                                <td class="text-end">{{ formatAngka($d->{"target_dirut_$p->kode_produk"}) }}</td>
                                <td class="text-end fw-bold cell-target-akhir">{{ formatAngka($d->{"target_$p->kode_produk"}) }}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td colspan="4" class="text-center">TOTAL</td>
                        @foreach ($produk as $d)
                            <td class="text-end cell-avg">{{ formatAngka(${"total_rata_rata_penjualan_$d->kode_produk"}) }}</td>
                            <td class="text-end cell-realisasi">{{ formatAngka(${"total_penjualan_tigabulan_$d->kode_produk"}) }}</td>
                            <td class="text-end cell-realisasi">{{ formatAngka(${"total_penjualan_duabulan_$d->kode_produk"}) }}</td>
                            <td class="text-end cell-realisasi">{{ formatAngka(${"total_penjualan_lastbulan_$d->kode_produk"}) }}</td>
                            <td class="text-end cell-last-target">{{ formatAngka(${"total_last_target_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_awal_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_rsm_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_gm_$d->kode_produk"}) }}</td>
                            <td class="text-end">{{ formatAngka(${"total_target_dirut_$d->kode_produk"}) }}</td>
                            <td class="text-end cell-target-akhir">{{ formatAngka(${"total_target_$d->kode_produk"}) }}</td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col d-flex gap-3 flex-wrap align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-dot bg-success"></span>
            <span class="text-muted small">Rata-Rata Penjualan 3 Bulan</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-dot bg-info"></span>
            <span class="text-muted small">Realisasi 3 Bulan</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-dot bg-primary"></span>
            <span class="text-muted small">Target Terakhir (Last Target)</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge badge-dot bg-warning"></span>
            <span class="text-muted small">Target Akhir</span>
        </div>
    </div>
</div>

<script>
    $(".table-modal").freezeTable({
        'scrollable': true,
        'columnNum': 4,
        'shadow': true,
    });
</script>
