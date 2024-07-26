<div class="table-responsive">
    <table class="table">
        <thead class="table-dark">
            <tr>
                <th>No. Polisi</th>
                <th>Jenis Kendaraan</th>
                <th class="text-center">Keberangkatan</th>
                <th class="text-center">Penjualan</th>
                <th class="text-center">Rata Rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapkendaraan as $d)
                <tr>
                    <td>{{ $d->no_polisi }}</td>
                    <td>{{ $d->merek }} {{ $d->tipe }} {{ $d->tipe_kendaraan }}</td>
                    <td class="text-center">{{ $d->jml_berangkat }}</td>
                    <td class="text-end">{{ formatAngkaDesimal($d->jml_penjualan) }}</td>
                    <td class="text-center">
                        @php
                            $ratarata = $d->jml_penjualan / $d->jml_berangkat;
                        @endphp
                        {{ formatAngkaDesimal($ratarata) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
