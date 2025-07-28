<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Keterangan</th>
            <th>Bank</th>
            <th>Debet</th>
            <th>Kredit</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mutasi as $m)
            @php
                $debet = $m->debet_kredit == 'D' ? $m->jumlah : 0;
                $kredit = $m->debet_kredit == 'K' ? $m->jumlah : 0;
            @endphp
            <tr>
                <td>{{ $m->keterangan }}</td>
                <td>{{ $m->nama_bank }}</td>
                <td>{{ formatAngkaDesimal($debet) }}</td>
                <td>{{ formatAngkaDesimal($kredit) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
