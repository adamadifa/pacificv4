@php
    $total_reward = 0;
    $color_reward = '';
    $status = 0;
@endphp
@foreach ($peserta as $d)
    @php
        $color_reward = $d->jml_dus >= $d->qty_target ? 'bg-success text-white' : 'bg-danger text-white';
        if ($d->jml_dus >= $d->qty_target) {
            //$reward = $d->reward * $d->jml_dus;
            $bb_dep = ['PRIK004', 'PRIK001'];
            $reward_tunai = in_array($d->kode_program, $bb_dep) ? ($d->budget_rsm + $d->budget_gm) * $d->jml_tunai : $d->reward * $d->jml_tunai;
            $reward_kredit = $d->reward * $d->jml_kredit;
            $reward = $reward_tunai + $reward_kredit;
        } else {
            $reward_tunai = 0;
            $reward_kredit = 0;
            $reward = 0;
        }
        $reward = $reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $reward;
        $total_reward += $reward;
        $status = $reward == 0 ? 0 : 1;
    @endphp

    <tr class=" {{ $color_reward }}">
        <td>{{ $loop->iteration }} {{ $d->kode_program }}</td>
        <td>
            <input type="hidden" name="kode_pelanggan[{{ $loop->index }}]" value="{{ $d->kode_pelanggan }}">
            <input type="hidden" name="status[{{ $loop->index }}]" value="{{ $status }}">
            {{ $d->kode_pelanggan }}
        </td>
        <td>{{ $d->nama_pelanggan }}</td>
        <td class="text-center">
            {{ formatAngka($d->qty_target) }}
        </td>
        <td class="text-end">{{ formatAngka($d->budget_smm) }}</td>
        <td class="text-end">{{ formatAngka($d->budget_rsm) }}</td>
        <td class="text-end">{{ formatAngka($d->budget_gm) }}</td>
        <td class="text-end">
            {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
            {{ formatAngka($d->jml_dus) }} --}}

            <input type="hidden" name="qty_tunai[{{ $loop->index }}]" value="{{ $d->jml_tunai }}">
            {{ formatAngka($d->jml_tunai) }}
        </td>
        <td class="text-end">
            {{-- <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
            {{ formatAngka($d->jml_dus) }} --}}

            <input type="hidden" name="qty_kredit[{{ $loop->index }}]" value="{{ $d->jml_kredit }}">
            {{ formatAngka($d->jml_kredit) }}
        </td>
        <td class="text-end">
            <input type="hidden" name="jumlah[{{ $loop->index }}]" value="{{ $d->jml_dus }}">
            {{ formatAngka($d->jml_dus) }}
        </td>
        <td class="text-end">
            @php
                $reward_tunai = $reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $reward_tunai;
            @endphp
            <input type="hidden" name="reward_tunai[{{ $loop->index }}]" value="{{ $reward_tunai }}">

            {{ formatAngka($reward_tunai) }}
        </td>
        <td class="text-end">
            @php
                $reward_kredit = $reward > 1000000 && !in_array($d->kode_program, $bb_dep) ? 1000000 : $reward_kredit;
            @endphp
            <input type="hidden" name="reward_kredit[{{ $loop->index }}]" value="{{ $reward_kredit }}">

            {{ formatAngka($reward_kredit) }}
        </td>
        <td class="text-end">
            <input type="hidden" name="total_reward[{{ $loop->index }}]" value="{{ $reward }}">
            {{ formatAngka($reward) }}
        </td>
        <td class="">
            @if ($d->jml_dus >= $d->qty_target)
                <select name="status_pencairan[{{ $loop->index }}]" id="status_pencairan" class="form-select">
                    @if ($reward >= 100000)
                        <option value="1">Cairkan</option>
                    @endif
                    <option value="0">Simpan</option>
                </select>
            @else
                <input type="hidden" name="status_pencairan[{{ $loop->index }}]" value="0">
            @endif

        </td>
        <td>
            @if ($d->jml_dus >= $d->qty_target)
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
{{-- <tr class="table-dark">
    <td colspan="6" class="text-end">TOTAL REWARD</td>
    <td class="text-end">{{ formatAngka($total_reward) }}</td>
    <td></td>
</tr> --}}
<script>
    $(document).ready(function() {
        function hide() {
            $(".pelangganna").hide();
        }

        hide();
    });
</script>
