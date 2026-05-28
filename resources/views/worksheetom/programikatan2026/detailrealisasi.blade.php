<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>No. Faktur</th>
            <th>Tanggal</th>
            <th>Nama Produk</th>
            <th>Qty (Dus)</th>
            <th>Jenis Transaksi</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_qty = 0;
        @endphp
        @forelse ($detailpenjualan as $d)
            @php
                $total_qty += $d->jml_dus;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->no_faktur }}</td>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ $d->nama_produk }}</td>
                <td class="text-end">{{ formatAngka($d->jml_dus) }}</td>
                <td class="text-center">
                    @if ($d->jenis_transaksi == 'T')
                        <span class="badge bg-success">Tunai</span>
                    @else
                        <span class="badge bg-danger">Kredit</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data realisasi.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="table-dark">
        <tr>
            <td colspan="4">TOTAL</td>
            <td class="text-end">{{ formatAngka($total_qty) }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
