<div class="nav-align-top nav-tabs mb-4">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#rekappenjualan"
                aria-controls="rekappenjualan" aria-selected="true">
                Rekap Penjualan
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#rekapkasbesar" aria-controls="rekapkasbesar"
                aria-selected="false" tabindex="-1">
                Rekap Kas Besar
            </button>
        </li>
    </ul>
    <div class="tab-content" id="tab-content-main">
        <div class="tab-pane fade  active show" id="rekappenjualan" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="10">Rekap Penjualan</th>
                        </tr>
                        <tr>
                            <th>Kode</th>
                            <th>Salesman</th>
                            <th>Bruto</th>
                            <th>Retur</th>
                            <th>Potongan</th>
                            <th>Istimewa</th>
                            <th>Penyesuaian</th>
                            <th>DPP</th>
                            <th>PPN</th>
                            <th>NETTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_bruto = 0;
                            $total_retur = 0;
                            $total_potongan = 0;
                            $total_potongan_istimewa = 0;
                            $total_penyesuaian = 0;
                            $total_dpp = 0;
                            $total_netto = 0;
                            $total_ppn = 0;
                        @endphp
                        @foreach ($rekappenjualan as $d)
                            @php
                                $total_bruto += $d->total_bruto;
                                $total_retur += $d->total_retur;
                                $total_potongan += $d->total_potongan;
                                $total_potongan_istimewa += $d->total_potongan_istimewa;
                                $total_penyesuaian += $d->total_penyesuaian;
                                $dpp = $d->total_bruto - $d->total_retur - $d->total_potongan - $d->total_potongan_istimewa - $d->total_penyesuaian;
                                $total_dpp += $dpp;
                                $total_ppn += $d->total_ppn;
                                $netto = $dpp + $d->total_ppn;
                                $total_netto += $netto;
                            @endphp
                            <tr>
                                <td>{{ $d->kode_salesman }}</td>
                                <td>{{ textUpperCase($d->nama_salesman) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_bruto) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_retur) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_potongan) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_potongan_istimewa) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_penyesuaian) }}</td>
                                <td class="text-end">{{ formatAngka($dpp) }}</td>
                                <td class="text-end">{{ formatAngka($d->total_ppn) }}</td>
                                <td class="text-end">{{ formatAngka($netto) }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="2">Total</td>
                            <td class="text-end">{{ formatAngka($total_bruto) }}</td>
                            <td class="text-end">{{ formatAngka($total_retur) }}</td>
                            <td class="text-end">{{ formatAngka($total_potongan) }}</td>
                            <td class="text-end">{{ formatAngka($total_potongan_istimewa) }}</td>
                            <td class="text-end">{{ formatAngka($total_penyesuaian) }}</td>
                            <td class="text-end">{{ formatAngka($total_dpp) }}</td>
                            <td class="text-end">{{ formatAngka($total_ppn) }}</td>
                            <td class="text-end">{{ formatAngka($total_netto) }}</td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>
        <div class="tab-pane fade" id="rekapkasbesar" role="tabpanel">
            <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode</th>
                                    <th>Salesman</th>
                                    <th>Voucher</th>
                                    <th>Cash IN</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_voucher = 0;
                                    $total_cashin = 0;
                                    $grandtotal = 0;
                                @endphp
                                @foreach ($rekapkasbesar as $d)
                                    @php
                                        $total = $d->total_voucher + $d->total_cashin;
                                        $total_voucher += $d->total_voucher;
                                        $total_cashin += $d->total_cashin;
                                        $grandtotal += $total;
                                    @endphp
                                    <tr>
                                        <td>{{ $d->kode_salesman }}</td>
                                        <td>{{ textUpperCase($d->nama_salesman) }}</td>
                                        <td class="text-end">{{ formatAngka($d->total_voucher) }}</td>
                                        <td class="text-end">{{ formatAngka($d->total_cashin) }}</td>
                                        <td class="text-end">{{ formatAngka($total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td class="text-end">{{ formatAngka($total_voucher) }}</td>
                                    <td class="text-end">{{ formatAngka($total_cashin) }}</td>
                                    <td class="text-end">{{ formatAngka($grandtotal) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
