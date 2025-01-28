<div class="row mt-2 mb-2">
    <div class="col">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>No. Faktur</th>
                    <th>Tanggal</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_qty = 0;
                @endphp
                @foreach ($detailpenjualan as $d)
                    @php
                        $total_qty += $d->qty;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->no_faktur }}</td>
                        <td>{{ formatIndo($d->tanggal) }}</td>
                        <td class="text-end">{{ $d->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">TOTAL</th>
                    <td class="text-end">{{ formatAngka($total_qty) }}</td>
                </tr>
                <tr>
                    <th colspan="3">TOTAL PENJUALAN 1 TAHUN</th>
                    <td class="text-end">{{ formatAngka(ROUND($total_qty)) }}</td>
                </tr>
            </tfoot>
        </table>

    </div>
</div>
