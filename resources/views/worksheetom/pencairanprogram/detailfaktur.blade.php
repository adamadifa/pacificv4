<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>No. Faktur</th>
            <th>Tanggal</th>
            <th>Pelunasan</th>
            <th>TOP</th>
            <th>Qty</th>
            <th>Diskon</th>
            <th>Jml Diskon</th>
            <th>JT</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_qty = 0;
            $total_diskon = 0;
        @endphp
        @foreach ($detailpenjualan as $d)
            @php
                $total_qty += $d->jml_dus;
                $total_diskon += $d->diskon_reguler;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->no_faktur }}</td>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ formatIndo($d->tanggal_pelunasan) }}</td>
                <td>
                    {{ hitungHari($d->tanggal, $d->tanggal_pelunasan) - 1 }} hari
                </td>
                <td class="text-end">{{ formatAngka($d->jml_dus) }}</td>
                <td class="text-end">{{ formatAngka($d->diskon) }}</td>
                <td class="text-end">{{ formatAngka($d->diskon_reguler) }}</td>
                <td class="text-end">
                    @if ($d->jenis_transaksi == 'T')
                        <span class="badge bg-success">Tunai</span>
                    @else
                        <span class="badge bg-danger">Kredit</span>
                    @endif
                </td>

            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-dark">
        <tr>
            <td colspan="5">TOTAL</td>
            <td class="text-end">{{ formatAngka($total_qty) }}</td>
            <td></td>
            <td class="text-end">{{ formatAngka($total_diskon) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
