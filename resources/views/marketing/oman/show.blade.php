<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode OMAN</th>
                <td>{{ $oman->kode_oman }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $namabulan[$oman->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $oman->tahun }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @if ($oman->status_oman === '1')
                        <span class="badge bg-primary">Sudah di Proses Gudang</span>
                    @elseif ($oman->status_oman === '2')
                        <span class="badge bg-success">Sudah di Proses Produksi</span>
                    @else
                        <span class="badge bg-danger">Belum di Proses</span>
                    @endif
                </td>
            </tr>
        </table>

    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="table table-hover table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th rowspan="3" class="align-middle">No.</th>
                    <th rowspan="3" class="align-middle">Kode Produk</th>
                    <th rowspan="3" class="align-middle">Nama Produk</th>
                    <th colspan="4" class="text-center">Jumlah Permintaan</th>
                    <th rowspan="3" class="align-middle">Total</th>
                </tr>
                <tr>
                    <th class="text-center">Minggu ke 1</th>
                    <th class="text-center">Minggu ke 2</th>
                    <th class="text-center">Minggu ke 3</th>
                    <th class="text-center">Minggu ke 4</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->kode_produk }}</td>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-center">
                            {{ formatAngka($d->minggu_1) }}
                        </td>
                        <td class="text-center">{{ formatAngka($d->minggu_2) }}</td>
                        <td class="text-center">{{ formatAngka($d->minggu_3) }}</td>
                        <td class="text-center">{{ formatAngka($d->minggu_4) }}</td>
                        <td class="text-center">{{ formatAngka($d->total) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
