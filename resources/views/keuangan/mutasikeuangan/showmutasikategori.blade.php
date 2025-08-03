<div class="table-responsive">
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
            @php
                $total_debet = 0;
                $total_kredit = 0;
            @endphp
            @foreach ($mutasi as $m)
                @php
                    $debet = $m->debet_kredit == 'D' ? $m->jumlah : 0;
                    $kredit = $m->debet_kredit == 'K' ? $m->jumlah : 0;
                    $total_debet += $debet;
                    $total_kredit += $kredit;
                @endphp
                <tr>
                    <td>{{ $m->keterangan }}</td>
                    <td>{{ $m->nama_bank }}</td>
                    <td class="text-end">{{ formatAngkaDesimal($debet) }}</td>
                    <td class="text-end">{{ formatAngkaDesimal($kredit) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="text-end">Total</td>
                <td class="text-end">{{ formatAngkaDesimal($total_debet) }}</td>
                <td class="text-end">{{ formatAngkaDesimal($total_kredit) }}</td>
            </tr>
        </tbody>
    </table>
</div>
