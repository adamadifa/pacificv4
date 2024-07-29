<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th rowspan="2" class="align-middle">Cabang</th>
                <th colspan="7" class="text-center">Umur Piutang</th>
            </tr>
            <tr class="text-center">
                <th>0 - 15</th>
                <th>16 - 31</th>
                <th>32 - 45</th>
                <th>46 - 60</th>
                <th>61 - 90</th>
                <th>91 - 180</th>
                <th> > 180</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_umur_0_15 = 0;
                $total_umur_16_31 = 0;
                $total_umur_32_45 = 0;
                $total_umur_46_60 = 0;
                $total_umur_61_90 = 0;
                $total_umur_91_180 = 0;
                $total_umur_lebih_180 = 0;
            @endphp
            @foreach ($rekapaup as $d)
                @php
                    $total_umur_0_15 += $d['umur_0_15'];
                    $total_umur_16_31 += $d['umur_16_31'];
                    $total_umur_32_45 += $d['umur_32_45'];
                    $total_umur_46_60 += $d['umur_46_60'];
                    $total_umur_61_90 += $d['umur_61_90'];
                    $total_umur_91_180 += $d['umur_91_180'];
                    $total_umur_lebih_180 += $d['umur_lebih_180'];
                @endphp
                <tr>
                    <td>{{ textUpperCase($d['nama_cabang']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_0_15']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_16_31']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_32_45']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_46_60']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_61_90']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_91_180']) }}</td>
                    <td class="text-end">{{ formatAngka($d['umur_lebih_180']) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="table-dark">
            <tr>
                <td>Total</td>
                <td class="text-end">{{ formatAngka($total_umur_0_15) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_16_31) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_32_45) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_46_60) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_61_90) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_91_180) }}</td>
                <td class="text-end">{{ formatAngka($total_umur_lebih_180) }}</td>

            </tr>
        </tfoot>
    </table>
</div>
