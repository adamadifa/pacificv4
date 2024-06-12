<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>Kode Salesman</th>
                <td>{{ $salesman->kode_salesman }}</td>
            </tr>
            <tr>
                <th>Nama Salesman</th>
                <td>{{ $salesman->nama_salesman }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($tanggal) }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="7">KAS BESAR PENJUALAN</th>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Bukti</th>
                    <th>No. Faktur</th>
                    <th>Nama Pelanggan</th>
                    <th>Tunai</th>
                    <th>Tagihan</th>
                    <th>Giro ke Cash</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_lhp_tunai = 0;
                    $total_lhp_tagihan = 0;
                    $total_gtct = 0;
                @endphp
                @foreach ($lhp as $d)
                    @php
                        $total_lhp_tunai += $d->lhp_tunai;
                        $total_lhp_tagihan += $d->lhp_tagihan;
                        $total_gtct += $d->giro_to_cash_transfer;
                    @endphp
                    <tr>
                        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                        <td>{{ $d->no_bukti }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td class="text-end">{{ formatAngka($d->lhp_tunai) }}</td>
                        <td class="text-end">{{ formatAngka($d->lhp_tagihan) }}</td>
                        <td class="text-end">{{ formatAngka($d->giro_to_cash_transfer) }}</td>
                    </tr>
                @endforeach
                <tr>
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-end">{{ formatAngka($total_lhp_tunai) }}</th>
                    <td class="text-end">{{ formatAngka($total_lhp_tagihan) }}</th>
                    <td class="text-end">{{ formatAngka($total_gtct) }}</th>
                </tr>
            </tfoot>

        </table>

    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="7">LIST GIRO</th>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <th>No. Giro</th>
                    <th>No. Faktur</th>
                    <th>Nama Pelanggan</th>
                    <th>Jatuh Tempo</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_lhp_giro = 0;
                @endphp
                @foreach ($giro as $d)
                    @php
                        $total_lhp_giro += $d->lhp_giro;
                        $bgcolor = $d->status == 1 ? 'bg-success text-white' : '';
                    @endphp
                    <tr class="{{ $bgcolor }}">
                        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                        <td>{{ $d->no_giro }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->jatuh_tempo)) }}</td>
                        <td class="text-end">{{ formatAngka($d->lhp_giro) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="5">TOTAL</td>
                    <td class="text-end">{{ formatAngka($total_lhp_giro) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th colspan="7">LIST TRANSFER</th>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode Transfer</th>
                    <th>No. Faktur</th>
                    <th>Nama Pelanggan</th>
                    <th>Jumlah</th>
                    <th>Giro ke Transfer</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_lhp_transfer = 0;
                    $total_giro_to_transfer = 0;
                @endphp
                @foreach ($transfer as $d)
                    @php
                        $total_lhp_transfer += $d->lhp_transfer;
                        $total_giro_to_transfer += $d->giro_to_transfer;
                        $bgcolor = $d->status == 1 ? 'bg-success text-white' : '';
                    @endphp
                    <tr class="{{ $bgcolor }}">
                        <td>{{ date('d-m-Y', strtotime($d->tanggal)) }}</td>
                        <td>{{ $d->kode_transfer }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ $d->nama_pelanggan }}</td>
                        <td class="text-end">{{ formatAngka($d->lhp_transfer) }}</td>
                        <td class="text-end">{{ formatAngka($d->giro_to_transfer) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-dark">
                <tr>
                    <td colspan="4">TOTAL</td>
                    <td class="text-end">{{ formatAngka($total_lhp_transfer) }}</td>
                    <td class="text-end">{{ formatAngka($total_giro_to_transfer) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
