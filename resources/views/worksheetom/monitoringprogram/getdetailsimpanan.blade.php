<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Tanggal</th>
            <th>Nama Program</th>
            <th>Total Reward</th>
        </tr>
    </thead>
    @php
        $grandtotal_reward = 0;
    @endphp
    <tbody>
        @foreach ($detailsimpanan as $d)
            @php
                $grandtotal_reward += $d->total_reward;
            @endphp
            <tr>
                <td>{{ formatIndo($d->tanggal) }}</td>
                <td>{{ $d->nama_program }}</td>
                <td class="text-end">{{ formatAngka($d->total_reward) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="2">Grand Total</td>
            <td class="text-end">{{ formatAngka($grandtotal_reward) }}</td>
        </tr>
    </tfoot>
</table>
