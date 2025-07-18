<table class="table table-bordered mb-2" id="targetperbulantable">
    <thead class="table-dark">
        <tr>
            <th>Bulan</th>
            <th>Tahun</th>
            <th>Target</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_target = 0;
        @endphp
        @foreach ($detailtarget as $d)
            @php
                $total_target += $d->target_perbulan;
            @endphp
            <tr class="targetbulanan">
                <td>
                    {{ getMonthName($d->bulan) }}
                </td>
                <td>

                    {{ $d->tahun }}
                </td>
                <td class="text-end">
                    {{ formatAngka($d->target_perbulan) }}
                </td>
            </tr>
        @endforeach
    </tbody>
    <tfoot class="table-dark">
        <tr>
            <td colspan="2">TOTAL</td>
            <td class="text-end" id="gradTotaltarget"> {{ formatAngka($total_target) }}</td>
        </tr>
    </tfoot>
</table>
