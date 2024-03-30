<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Permintaan</th>
                <td>{{ $pp->no_permintaan }}</th>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$pp->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $pp->tahun }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($pp->status == 1)
                        <span class="badge bg-success">
                            Sudah Diproses Oleh Produksi
                        </span>
                    @else
                        <span class="badge bg-danger">Belum di Proses</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Oman Marketing</th>
                    <th>Stok Gudang</th>
                    <th>Buffer Stok</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    @php
                        $total = $d->oman_marketing - $d->stok_gudang + $d->buffer_stok;
                    @endphp
                    <tr>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->oman_marketing) }}</td>
                        <td class="text-end">{{ formatAngka($d->stok_gudang) }}</td>
                        <td class="text-end">{{ formatAngka($d->buffer_stok) }}</td>
                        <td class="text-end">{{ formatAngka($total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
