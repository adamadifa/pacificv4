<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Periode</th>
            <th>Program</th>
            <th>Total Simpanan</th>
        </tr>
    </thead>
    @php
        $grandtotal_reward = 0;
        $namabulan = config('global.list_bulan');
    @endphp
    <tbody>
        @foreach ($detailsimpanan as $d)
            @php
                $grandtotal_reward += $d->total_reward;
            @endphp
            <tr>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ !empty($d->bulan) && isset($namabulan[$d->bulan]) ? $namabulan[$d->bulan] : $d->bulan }} {{ $d->tahun }}</td>
                <td>{{ $d->kode_program == 'PR001' ? 'BB & DP' : 'AIDA' }}</td>
                <td class="text-end">{{ formatAngka($d->total_reward) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">Grand Total</td>
            <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
        </tr>
    </tfoot>
</table>
