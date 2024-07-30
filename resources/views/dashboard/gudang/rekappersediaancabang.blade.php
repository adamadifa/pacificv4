<div class="row">
    <div class="col-lg-6 col-md-12 col-sm-12">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>PRODUK</th>
                    <th>BUFFER</th>
                    <th>MAX</th>
                    <th>STOK</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekappersediaan as $d)
                    <tr>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->buffer_stok) }}</td>
                        <td class="text-end">{{ formatAngka($d->max_stok) }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
