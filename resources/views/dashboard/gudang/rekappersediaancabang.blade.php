<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>PRODUK</th>
                    <th>BUFFER</th>
                    <th>MAX</th>
                    <th>GOOD STOK</th>
                    <th>BAD STOK</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekappersediaan as $d)
                    @php
                        $saldo_akhir = ($d->saldo_awal + $d->sisa_mutasi - $d->dpb_ambil + $d->dpb_kembali) / $d->isi_pcs_dus;
                        if ($saldo_akhir <= $d->buffer_stok) {
                            $color = 'bg-danger text-white opacity-60';
                        } elseif ($saldo_akhir >= $d->max_stok) {
                            $color = 'bg-info text-white';
                        } else {
                            $color = '';
                        }

                        $saldo_akhir_bs = ($d->saldo_awal_bs + $d->sisa_mutasi_bs) / $d->isi_pcs_dus;
                        if ($saldo_akhir_bs > 0) {
                            $color_bs = 'bg-danger text-white opacity-60';
                        } else {
                            $color_bs = 'bg-success text-white';
                        }
                    @endphp
                    <tr>
                        <td>{{ $d->nama_produk }}</td>
                        <td class="text-end">{{ formatAngka($d->buffer_stok) }}</td>
                        <td class="text-end">{{ formatAngka($d->max_stok) }}</td>
                        <td class="text-end {{ $color }}">{{ formatAngkaDesimal($saldo_akhir) }}</td>
                        <td class="text-end ">{{ formatAngkaDesimal($saldo_akhir_bs) }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
