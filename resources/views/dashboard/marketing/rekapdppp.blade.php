<div class="nav-align-top nav-tabs mb-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#omset" aria-controls="rekappenjualan"
                aria-selected="true">
                Berdasarkan Omset
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#sellingout" aria-controls="rekapkabesar"
                aria-selected="false" tabindex="-1">
                Selling Out
            </button>
        </li>
    </ul>
    <div class="tab-content" id="tab-content-main">
        <div class="tab-pane fade  active show" id="omset" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama Produk</th>
                            <th colspan="5" class="text-center">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                            <th colspan="5" class="text-center">Sampai Dengan Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                        </tr>
                        <tr>
                            <th>Real {{ $lastyear }}</th>
                            <th>Target</th>
                            <th>Real {{ $tahun }}</th>
                            <th>Ach%</th>
                            <th>Grw%</th>

                            <th>Real {{ $lastyear }}</th>
                            <th>Target</th>
                            <th>Real {{ $tahun }}</th>
                            <th>Ach%</th>
                            <th>Grw%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dppp as $d)
                            @php
                                $target = $d->target;
                                $realisasi_penjualan_lastyear = $d->realisasi_penjualan_lastyear / $d->isi_pcs_dus;
                                $realisasi_penjualan = $d->realisasi_penjualan / $d->isi_pcs_dus;
                                $grw = !empty($realisasi_penjualan_lastyear)
                                    ? (($realisasi_penjualan - $realisasi_penjualan_lastyear) / $realisasi_penjualan_lastyear) * 100
                                    : 0;

                                $ach = !empty($target) ? ($realisasi_penjualan / $target) * 100 : 0;

                                $target_sampaibulanini = $d->target_sampaibulanini;
                                $realisasi_penjualan_lastyear_sampaibulanini = $d->realisasi_penjualan_lastyear_sampaibulanini / $d->isi_pcs_dus;
                                $realisasi_penjualan_sampaibulanini = $d->realisasi_penjualan_sampaibulanini / $d->isi_pcs_dus;
                                $grw_sampaibulanini = !empty($realisasi_penjualan_lastyear_sampaibulanini)
                                    ? (($realisasi_penjualan_sampaibulanini - $realisasi_penjualan_lastyear_sampaibulanini) /
                                            $realisasi_penjualan_lastyear_sampaibulanini) *
                                        100
                                    : 0;
                                $ach_sampaibulanini = !empty($target_sampaibulanini)
                                    ? ($realisasi_penjualan_sampaibulanini / $target_sampaibulanini) * 100
                                    : 0;

                                $colorgrw = $grw < 0 ? 'text-danger' : 'text-success';
                                $colorgrw_sampaibulanini = $grw_sampaibulanini < 0 ? 'text-danger' : 'text-success';

                                $coloroach = $ach < 100 ? 'text-danger' : 'text-success';
                                $coloroach_sampaibulanini = $ach_sampaibulanini < 100 ? 'text-danger' : 'text-success';
                            @endphp
                            <tr>
                                <td>{{ $d->kode_produk }}</td>
                                <td>{{ $d->nama_produk }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($realisasi_penjualan_lastyear) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($target) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($realisasi_penjualan) }}</td>
                                <td class="text-center {{ $coloroach }}">
                                    {{ formatAngkaDesimal($ach) }}
                                </td>
                                <td class="{{ $colorgrw }} text-center">
                                    @if (!empty($grw))
                                        @if ($grw < 0)
                                            <i class="ti ti-arrow-bar-down"></i>
                                        @else
                                            <i class="ti ti-arrow-bar-up"></i>
                                        @endif
                                    @endif

                                    {{ formatAngkaDesimal($grw) }}
                                </td>
                                <td class="text-end">{{ formatAngkaDesimal($realisasi_penjualan_lastyear_sampaibulanini) }}</td>
                                <td class="text-end">
                                    {{ formatAngkaDesimal($target_sampaibulanini) }}
                                </td>
                                <td class="text-end">{{ formatAngkaDesimal($realisasi_penjualan_sampaibulanini) }}</td>
                                <td class="text-center {{ $coloroach_sampaibulanini }}">
                                    {{ formatAngkaDesimal($ach_sampaibulanini) }}
                                </td>
                                <td class="{{ $colorgrw_sampaibulanini }} text-center">
                                    @if (!empty($grw_sampaibulanini))
                                        @if ($grw_sampaibulanini < 0)
                                            <i class="ti ti-arrow-bar-down"></i>
                                        @else
                                            <i class="ti ti-arrow-bar-up"></i>
                                        @endif
                                    @endif
                                    {{ formatAngkaDesimal($grw_sampaibulanini) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="sellingout" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th rowspan="2">Kode</th>
                            <th rowspan="2">Nama Produk</th>
                            <th colspan="5" class="text-center">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                            <th colspan="5" class="text-center">Sampai Dengan Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                        </tr>
                        <tr>
                            <th>Real {{ $lastyear }}</th>
                            <th>Target</th>
                            <th>Real {{ $tahun }}</th>
                            <th>Ach%</th>
                            <th>Grw%</th>

                            <th>Real {{ $lastyear }}</th>
                            <th>Target</th>
                            <th>Real {{ $tahun }}</th>
                            <th>Ach%</th>
                            <th>Grw%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selling_out as $d)
                            @php
                                $selling_out_lastyear = $d->selling_out_lastyear / $d->isi_pcs_dus;
                                $target = $d->target;
                                $selling_out = $d->selling_out / $d->isi_pcs_dus;
                                $ach = !empty($target) ? ($selling_out / $target) * 100 : 0;
                                $grw = !empty($selling_out_lastyear) ? (($selling_out - $selling_out_lastyear) / $selling_out_lastyear) * 100 : 0;
                                $colorach = $ach < 100 ? 'text-danger' : 'text-success';
                                $colorgrw = $grw < 0 ? 'text-danger' : 'text-success';

                                $selling_out_lastyear_sampaibulanini = $d->selling_out_lastyear_sampaibulanini / $d->isi_pcs_dus;
                                $target_sampaibulanini = $d->target_sampaibulanini;
                                $selling_out_sampaibulanini = $d->selling_out_sampaibulanini / $d->isi_pcs_dus;
                                $ach_sampaibulanini = !empty($target_sampaibulanini)
                                    ? ($selling_out_sampaibulanini / $target_sampaibulanini) * 100
                                    : 0;
                                $grw_sampaibulanini = !empty($selling_out_lastyear_sampaibulanini)
                                    ? (($selling_out_sampaibulanini - $selling_out_lastyear_sampaibulanini) / $selling_out_lastyear_sampaibulanini) *
                                        100
                                    : 0;
                                $colorach_sampaibulanini = $ach_sampaibulanini < 100 ? 'text-danger' : 'text-success';
                                $colorgrw_sampaibulanini = $grw_sampaibulanini < 0 ? 'text-danger' : 'text-success';
                            @endphp
                            <tr>
                                <td>{{ $d->kode_produk }}</td>
                                <td>{{ $d->nama_produk }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($selling_out_lastyear) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($target) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($selling_out) }}</td>
                                <td class="text-center {{ $coloroach }}">
                                    {{ formatAngkaDesimal($ach) }}
                                </td>
                                <td class="{{ $colorgrw }} text-center">
                                    @if (!empty($grw))
                                        @if ($grw < 0)
                                            <i class="ti ti-arrow-bar-down"></i>
                                        @else
                                            <i class="ti ti-arrow-bar-up"></i>
                                        @endif
                                    @endif
                                    {{ formatAngkaDesimal($grw) }}
                                </td>

                                <td class="text-end">{{ formatAngkaDesimal($selling_out_lastyear_sampaibulanini) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($target_sampaibulanini) }}</td>
                                <td class="text-end">{{ formatAngkaDesimal($selling_out_sampaibulanini) }}</td>
                                <td class="text-center {{ $coloroach_sampaibulanini }}">
                                    {{ formatAngkaDesimal($ach_sampaibulanini) }}
                                </td>
                                <td class="{{ $colorgrw_sampaibulanini }} text-center">
                                    @if (!empty($grw_sampaibulanini))
                                        @if ($grw_sampaibulanini < 0)
                                            <i class="ti ti-arrow-bar-down"></i>
                                        @else
                                            <i class="ti ti-arrow-bar-up"></i>
                                        @endif
                                    @endif
                                    {{ formatAngkaDesimal($grw_sampaibulanini) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
