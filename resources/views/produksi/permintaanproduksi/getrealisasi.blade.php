<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>No. Permintaan</th>
                <td>{{ $pp->no_permintaan }}</td>
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
                <th>Kode Oman</th>
                <td>{{ $pp->kode_oman }}</td>
            </tr>
        </table>

    </div>
</div>
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Produk</th>
            <th>Permintaan</th>
            <th>Realisasi</th>
            <th>%</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $d)
            @php
                $permintaan = $d->oman_marketing - $d->stok_gudang + $d->buffer_stok;
                $realisasi = $d->jml_realisasi;
                if ($permintaan != 0) {
                    $ratio = ($realisasi / $permintaan) * 100;
                } else {
                    $ratio = 0;
                }

                if ($ratio < 50) {
                    $color = 'danger';
                } elseif ($ratio < 70) {
                    $color = 'warning';
                } elseif ($ratio < 90) {
                    $color = 'info';
                } else {
                    $color = 'success';
                }
            @endphp
            <tr>
                <td>{{ $d->kode_produk }}</td>
                <td class="text-end">{{ formatAngka($permintaan) }}</td>
                <td class="text-end">{{ formatAngka($realisasi) }}</td>
                <td>
                    <span class="badge bg-{{ $color }}">{{ ROUND($ratio) }} %</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
