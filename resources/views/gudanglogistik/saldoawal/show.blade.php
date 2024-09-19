<style>
    .table-modal {
        height: auto;
        max-height: 550px;
        overflow-y: scroll;

    }
</style>
<div class="row">
    <div class="col-12">
        <table class="table">
            <tr>
                <th>Kode</th>
                <td>{{ $saldo_awal->kode_saldo_awal }}</td>
            </tr>
            <tr>
                <th>Bulan</th>
                <td>{{ $nama_bulan[$saldo_awal->bulan] }}</td>
            </tr>
            <tr>
                <th>Tahun</th>
                <td>{{ $saldo_awal->tahun }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ DateToIndo($saldo_awal->tanggal) }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $saldo_awal->nama_kategori }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="table-modal">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No.</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detail as $d)
                        @php
                            $total_harga = $d->jumlah * $d->harga;
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $d->kode_barang }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->jumlah) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($d->harga) }}</td>
                            <td class="text-end">{{ formatAngkaDesimal($total_harga) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(function() {
        $(".table-modal").freezeTable({
            'scrollable': true,
            'freezeColumn': false,
        });





    });
</script>
