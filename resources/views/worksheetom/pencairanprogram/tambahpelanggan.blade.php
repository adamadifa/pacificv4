<table class="table">
    <thead class="table-dark">
        <tr>
            <th rowspan="2">Kode Pelanggan</th>
            <th rowspan="2">Nama Pelanggan</th>
            <th rowspan="2">Qty</th>
            <th colspan="2">Diskon</th>
        </tr>
        <tr>
            <th>Reguler</th>
            <th>Kumulatif</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detail as $d)
            <tr>
                <td>{{ $d['kode_pelanggan'] }}</td>
                <td>{{ $d['nama_pelanggan'] }}</td>
                <td>{{ $d['jml_dus'] }}</td>
                <td class="text-end">{{ formatAngka($d['diskon_reguler']) }}</td>
                <td>
                    <?php
                    $diskon_value = 0;
                    foreach ($diskon as $item) {
                        if ($d['jml_dus'] >= $item->min_qty && $d['jml_dus'] <= $item->max_qty) {
                            $diskon_value = $item->diskon;
                            break;
                        }
                    }
                    ?>
                    {{ formatAngka($diskon_value) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
