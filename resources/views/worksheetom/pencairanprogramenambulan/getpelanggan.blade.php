@foreach ($peserta as $d)
    @if ($d->total_jml_dus >= $d->total_qty_target)
        @php
            $bg_color = 'bg-success text-white';
            $status_reward = 1;
        @endphp
    @else
        @php
            $bg_color = 'bg-danger text-white';
            $status_reward = 0;
        @endphp
    @endif

    <tr>
        <td class="text-center {{ $bg_color }} sticky-col first-col">{{ $loop->iteration }}</td>
        <td class="{{ $bg_color }} sticky-col second-col">
            <input type="hidden" name="kode_pelanggan[{{ $loop->index }}]" value="{{ $d->kode_pelanggan }}">
            <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->total_jml_dus }}">
            <input type="hidden" name="qty_tunai[{{ $loop->index }}]" value="{{ $d->total_jml_dus_tunai }}">
            <input type="hidden" name="qty_kredit[{{ $loop->index }}]" value="{{ $d->total_jml_dus_kredit }}">
            <input type="hidden" name="reward_tunai[{{ $loop->index }}]" value="0">
            <input type="hidden" name="reward_kredit[{{ $loop->index }}]" value="0">


            {{ $d->kode_pelanggan }}
        </td>
        <td class="{{ $bg_color }} sticky-col third-col">{{ $d->nama_pelanggan }}</td>
        @php
            $total_reward = 0;
        @endphp
        @for ($i = intval(date('m', strtotime($start_date))); $i <= intval(date('m', strtotime($end_date))); $i++)
            <td class="text-center bg-info text-white">{{ $d->{"qty_target_bulan_$i"} }}</td>
            <td class="text-center {{ $d->{"jml_dus_bulan_$i"} >= $d->{"qty_target_bulan_$i"} ? 'bg-success text-white' : 'bg-danger text-white' }}">
                {{ formatAngka($d->{"jml_dus_bulan_$i"}) }}</td>
            <td class="text-end">
                @if ($status_reward == 1)
                    @php
                        $reward = $d->reward * $d->{"jml_dus_bulan_$i"};
                        $bb_dep = ['PRIK004', 'PRIK001'];
                        $reward_tunai = in_array($d->kode_program, $bb_dep)
                            ? ($d->budget_rsm + $d->budget_gm) * $d->{"jml_dus_tunai_bulan_$i"}
                            : $d->reward * $d->{"jml_dus_tunai_bulan_$i"};
                        $reward_kredit = $d->reward * $d->{"jml_dus_kredit_bulan_$i"};
                        $reward = $reward_tunai + $reward_kredit;
                        $reward = $reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $reward;

                    @endphp
                @else
                    @php
                        $reward = 0;
                    @endphp
                @endif
                @php
                    $total_reward += $reward;
                @endphp

                {{ formatAngka($reward) }}
            </td>
        @endfor
      
        <td class="text-center bg-info text-white">{{ formatAngka($d->total_qty_target) }}</td>
        <td class="text-center {{ $d->total_jml_dus >= $d->total_qty_target ? 'bg-success text-white' : 'bg-danger text-white' }}">
            {{ formatAngka($d->total_jml_dus) }}</td>
        <td class="text-end">{{ formatAngka($total_reward) }}</td>
        <td class="text-end">{{ formatAngka($d->total_reward_reguler) }}</td>
        <td class="text-end">
            @php
                $rn = $status_reward == 1 ? $total_reward - $d->total_reward_reguler : 0;
                $status = $rn == 0 ? 0 : 1;
            @endphp
            {{ formatAngka($rn) }}

            <!--Total Reward-->
            <input type="hidden" name="status[{{ $loop->index }}]" value="{{ $status }}">
            <input type="hidden" name="total_reward[{{ $loop->index }}]" value="{{ $rn }}">
        </td>
        <td class="">
            <!-- Status Pencairan-->
            @if ($d->total_jml_dus >= $d->total_qty_target)
                <select name="status_pencairan[{{ $loop->index }}]" id="status_pencairan" class="form-select">
                    <option value="1">Cairkan</option>
                    <option value="0">Simpan</option>
                </select>
            @else
                <input type="hidden" name="status_pencairan[{{ $loop->index }}]" value="0">
            @endif
        </td>
        <td>
            @if ($d->total_jml_dus >= $d->total_qty_target)
                <div class="form-check mt-3 mb-2 ">
                    <input class="form-check-input checkpelanggan" name="checkpelanggan[{{ $loop->index }}]" value="1" type="checkbox"
                        id="checkpelanggan">
                </div>
            @else
                <input class="form-check-input checkpelanggan pelangganna" name="checkpelanggan[{{ $loop->index }}]" value="1" type="checkbox"
                    id="checkpelanggan" checked>
            @endif
        </td>
    </tr>
@endforeach
<script>
    $(document).ready(function() {
        function hide() {
            $(".pelangganna").hide();
        }

        hide();
    });
</script>
